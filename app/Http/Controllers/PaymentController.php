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
            'buyer.city' => 'required|string',
            'buyer.region' => 'required|string',
            'buyer.rut' => 'required|string',
            'buyer.document_type' => 'required|in:boleta,factura',
            'coupon_code' => 'nullable|string'
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

            // Calculate actual total from products
            $calculatedTotal = 0;
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['id']);
                $calculatedTotal += $product->price * $item['quantity'];
            }

            // Apply coupon if exists
            $discountAmount = 0;
            $appliedCoupon = null;
            if (!empty($validated['coupon_code'])) {
                $coupon = \App\Models\Coupon::where('code', strtoupper($validated['coupon_code']))
                    ->where('is_active', true)
                    ->first();

                if ($coupon && (!$coupon->expires_at || !$coupon->expires_at->isPast())) {
                    $appliedCoupon = $coupon->code;
                    if ($coupon->discount_type === 'percentage') {
                        $discountAmount = $calculatedTotal * ($coupon->discount_value / 100);
                    } else {
                        $discountAmount = $coupon->discount_value;
                    }
                    $discountAmount = min($discountAmount, $calculatedTotal);
                }
            }

            $finalTotal = $calculatedTotal - $discountAmount;

            $shippingAddress = $validated['buyer']['address'] . ', ' . $validated['buyer']['city'] . ', ' . $validated['buyer']['region'];
            $orderData = [
                'customer_name'    => $validated['buyer']['name'],
                'customer_email'   => $validated['buyer']['email'],
                'customer_phone'   => $validated['buyer']['phone'],
                'status'           => 'PENDING',
                'total_amount'     => $finalTotal,
                'coupon_code'      => $appliedCoupon,
                'discount_amount'  => $discountAmount,
                'site_transaction_id' => 'ORD-' . strtoupper(uniqid()),
                'marketing_opt_in' => false,
                'customer_rut'     => $validated['buyer']['rut'],
                'document_type'    => $validated['buyer']['document_type'],
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

            // Enviar evento a Brevo
            try {
                if (env('BREVO_API_KEY')) {
                    Http::withHeaders([
                        'api-key' => env('BREVO_API_KEY'),
                        'accept' => 'application/json',
                    ])->post('https://api.brevo.com/v3/events', [
                        'event_name' => 'cart_created',
                        'contact_email' => $order->customer_email,
                        'properties' => [
                            'order_id' => $order->site_transaction_id,
                            'total' => $order->total_amount,
                            'items_count' => count($validated['items'])
                        ]
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error sending cart_created to Brevo: ' . $e->getMessage());
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
                'notificationUrl' => url('/api/payment/notify'),
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
        if (!$order) return redirect('https://ochotierras.cl/es/checkout/failure?error=order_not_found');

        if ($order->status === 'PAID') {
            return redirect('https://ochotierras.cl/es/checkout/success?order=' . $order->site_transaction_id);
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
                $this->finalizePayment($order, $paymentId);
                return redirect('https://ochotierras.cl/es/checkout/success?order=' . $order->site_transaction_id);
            } elseif ($status === 'PENDING') {
                return redirect('https://ochotierras.cl/es/checkout/pending?order=' . $order->site_transaction_id);
            } else {
                $order->update(['status' => 'FAILED']);
                return redirect('https://ochotierras.cl/es/checkout/failure?error=payment_rejected');
            }
        }

        return redirect('https://ochotierras.cl/es/checkout/failure?error=getnet_verification_failed');
    }

    /**
     * Maneja las notificaciones asíncronas (Webhooks) de Getnet.
     */
    public function handleNotification(Request $request)
    {
        Log::info('Getnet Notification Received', $request->all());

        $requestId = $request->input('requestId');
        $reference = $request->input('reference');

        if (!$requestId || !$reference) {
            return response()->json(['status' => 'error', 'message' => 'Missing data'], 400);
        }

        $order = Order::where('site_transaction_id', $reference)->first();

        if (!$order) {
            Log::error("Order not found for reference: {$reference}");
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        // Si ya está pagada, simplemente respondemos OK
        if ($order->status === 'PAID') {
            return response()->json(['status' => 'ok']);
        }

        // Verificamos el estado real contra Getnet por seguridad
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
                $this->finalizePayment($order, $paymentId);
                return response()->json(['status' => 'ok']);
            }
        }

        return response()->json(['status' => 'ok']); // Siempre respondemos 200 a la pasarela
    }

    private function finalizePayment(Order $order, $paymentId)
    {
        if ($order->status === 'PAID') return;
        
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

            // Enviar evento a Brevo (order_completed)
            try {
                if (env('BREVO_API_KEY')) {
                    Http::withHeaders([
                        'api-key' => env('BREVO_API_KEY'),
                        'accept' => 'application/json',
                    ])->post('https://api.brevo.com/v3/events', [
                        'event_name' => 'order_completed',
                        'contact_email' => $order->customer_email,
                        'properties' => [
                            'order_id' => $order->site_transaction_id,
                            'total' => $order->total_amount
                        ]
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error sending order_completed to Brevo: ' . $e->getMessage());
            }

            // Enviar emails
            try {
                if ($order->customer_email) {
                    Mail::to($order->customer_email)->send(new OrderConfirmed($order));
                }
                Mail::to('info@ochotierras.cl')
                    ->cc('rcuellar@ochotierras.cl')
                    ->send(new OrderNotification($order));
            } catch (\Exception $mailEx) {
                Log::error("Error avisando pago: " . $mailEx->getMessage());
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error finalizando pago: " . $e->getMessage());
        }
    }
}
