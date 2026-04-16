<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmed;
use App\Mail\OrderNotification;

class PaymentController extends Controller
{
    /**
     * Inicia el proceso de pago.
     */
    public function init(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total' => 'required|integer',
            'buyer' => 'required|array',
            'buyer.name' => 'required|string',
            'buyer.email' => 'required|email',
            'buyer.phone' => 'required|string',
            'buyer.address' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->find($item['id']);
                if ($product->is_pack) {
                    foreach ($product->bundleItems as $component) {
                        $requiredQty = $item['quantity'] * $component->pivot->quantity;
                        $componentStock = Product::lockForUpdate()->find($component->id);
                        if ($componentStock->stock < $requiredQty) {
                            DB::rollBack();
                            return response()->json(['error' => "Stock insuficiente en Pack para: {$componentStock->name}"], 400);
                        }
                    }
                } else {
                    if ($product->stock < $item['quantity']) {
                        DB::rollBack();
                        return response()->json(['error' => "Stock insuficiente para: {$product->name}"], 400);
                    }
                }
            }

            $shippingAddress = $validated['buyer']['address'] . ', ' . ($validated['buyer']['city'] ?? '');
            $orderData = [
                'customer_name'    => $validated['buyer']['name'],
                'customer_email'   => $validated['buyer']['email'],
                'customer_phone'   => $validated['buyer']['phone'],
                'status'           => 'PENDING',
                'total_amount'     => $validated['total'],
                'site_transaction_id' => 'ORD-' . strtoupper(uniqid()),
                'marketing_opt_in' => false,
            ];

            if (\Schema::hasColumn('orders', 'shipping_address')) $orderData['shipping_address'] = $shippingAddress;
            if (\Schema::hasColumn('orders', 'address_shipping')) $orderData['address_shipping'] = $shippingAddress;

            $order = Order::create($orderData);

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $product->price * $item['quantity'],
                ]);
            }

            DB::commit();

            $login = env('GETNET_LOGIN');
            $secretKey = env('GETNET_TRANKEY');
            $endpoint = env('GETNET_ENDPOINT');

            $nonce = random_bytes(16);
            $nonceBase64 = base64_encode($nonce);
            $seed = date('c');
            $tranKey = base64_encode(hash('sha256', $nonce . $seed . $secretKey, true));

            $auth = [
                'login' => $login,
                'tranKey' => $tranKey,
                'nonce' => $nonceBase64,
                'seed' => $seed,
            ];

            $returnUrl = url("/api/payment/return?reference={$order->site_transaction_id}");

            $payload = [
                'auth' => $auth,
                'locale' => 'es_CL',
                'buyer' => [
                    'name' => $validated['buyer']['name'],
                    'email' => $validated['buyer']['email'],
                ],
                'payment' => [
                    'reference' => $order->site_transaction_id,
                    'description' => 'Compra en Ocho Tierras',
                    'amount' => [
                        'currency' => 'CLP',
                        'total' => $order->total_amount,
                    ]
                ],
                'expiration' => date('c', strtotime('+1 hour')),
                'ipAddress' => $request->ip(),
                'returnUrl' => $returnUrl,
                'userAgent' => $request->userAgent() ?? 'Mozilla/5.0',
            ];

            $response = Http::post($endpoint . '/api/session', $payload);

            if ($response->successful()) {
                $data = $response->json();
                $order->update(['payment_id' => $data['requestId']]);
                return response()->json(['processUrl' => $data['processUrl'], 'requestId' => $data['requestId']]);
            } else {
                return response()->json(['error' => 'Error al conectar con la pasarela.'], 500);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al procesar el pedido.'], 500);
        }
    }

    public function handleReturn(Request $request)
    {
        $reference = $request->query('reference');
        $order = Order::where('site_transaction_id', $reference)->first();
        if (!$order) return redirect('https://ochotierras.vercel.app/checkout/failure?error=order_not_found');

        if ($order->status === 'PAID') {
            return redirect('https://ochotierras.vercel.app/checkout/success?order=' . $order->site_transaction_id);
        }

        $requestId = $order->payment_id;
        $login = env('GETNET_LOGIN');
        $secretKey = env('GETNET_TRANKEY');
        $endpoint = env('GETNET_ENDPOINT');

        $nonce = random_bytes(16);
        $nonceBase64 = base64_encode($nonce);
        $seed = date('c');
        $tranKey = base64_encode(hash('sha256', $nonce . $seed . $secretKey, true));

        $auth = ['login' => $login, 'tranKey' => $tranKey, 'nonce' => $nonceBase64, 'seed' => $seed];

        $response = Http::post("{$endpoint}/api/session/{$requestId}", ['auth' => $auth]);

        if ($response->successful()) {
            $data = $response->json();
            $status = $data['status']['status'] ?? null;

            if ($status === 'APPROVED') {
                $paymentId = $data['payment'][0]['authorization'] ?? $requestId;
                return $this->handleSuccess($order, $paymentId);
            } elseif ($status === 'PENDING') {
                return redirect('https://ochotierras.vercel.app/checkout/pending?order=' . $order->site_transaction_id);
            } else {
                $order->update(['status' => 'FAILED']);
                return redirect('https://ochotierras.vercel.app/checkout/failure?error=payment_rejected');
            }
        }

        return redirect('https://ochotierras.vercel.app/checkout/failure?error=getnet_verification_failed');
    }

    private function handleSuccess(Order $order, $paymentId)
    {
        if ($order->status === 'PAID') {
            return redirect('https://ochotierras.vercel.app/checkout/success?order=' . $order->site_transaction_id);
        }

        try {
            DB::beginTransaction();

            foreach ($order->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product && $product->is_pack) {
                    foreach ($product->bundleItems as $component) {
                        $qtyToDeduct = $item->quantity * $component->pivot->quantity;
                        $component->decrement('stock', $qtyToDeduct);
                    }
                } elseif ($product) {
                    $product->decrement('stock', $item->quantity);
                }
            }

            $order->update(['status' => 'PAID', 'payment_id' => $paymentId]);
            DB::commit();

            // 3. Enviar emails de notificación (Envío Seguro)
            try {
                // Email al cliente
                if ($order->customer_email) {
                    Mail::to($order->customer_email)->send(new OrderConfirmed($order));
                }
                // Email al equipo
                Mail::to('info@ochotierras.cl')
                    ->cc('contacto@ochotierras.cl')
                    ->send(new OrderNotification($order));
            } catch (\Exception $mailEx) {
                Log::error("Error enviando notificaciones de pago para orden {$order->id}: " . $mailEx->getMessage());
            }

            return redirect('https://ochotierras.vercel.app/checkout/success?order=' . $order->site_transaction_id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('https://ochotierras.vercel.app/checkout/failure?error=processing');
        }
    }
}
