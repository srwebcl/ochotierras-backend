<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Inicia el proceso de pago.
     * 1. Valida Stock.
     * 2. Crea la Orden en BD.
     * 3. Retorna URL de pago (Getnet/Webpay).
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

            // 1. Validar Stock Estricto (Recursivo para Packs)
            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->find($item['id']);

                if ($product->is_pack) {
                    // Validar componentes del pack
                    foreach ($product->bundleItems as $component) {
                        $requiredQty = $item['quantity'] * $component->pivot->quantity;
                        $componentStock = Product::lockForUpdate()->find($component->id); // Re-lock component

                        if ($componentStock->stock < $requiredQty) {
                            DB::rollBack();
                            return response()->json([
                                'error' => "Stock insuficiente en Pack para: {$componentStock->name}. Requerido: {$requiredQty}"
                            ], 400);
                        }
                    }
                } else {
                    // Producto normal
                    if ($product->stock < $item['quantity']) {
                        DB::rollBack();
                        return response()->json([
                            'error' => "Stock insuficiente para: {$product->name}. Quedan: {$product->stock}"
                        ], 400);
                    }
                }
            }

            // 2. Crear Orden
            $order = Order::create([
                'customer_name' => $validated['buyer']['name'],
                'customer_email' => $validated['buyer']['email'],
                'customer_phone' => $validated['buyer']['phone'],
                'shipping_address' => $validated['buyer']['address'] . ', ' . ($validated['buyer']['city'] ?? ''),
                'status' => 'PENDING',
                'total_amount' => $validated['total'],
                'site_transaction_id' => 'ORD-' . strtoupper(uniqid()),
                'marketing_opt_in' => false,
            ]);

            // 3. Crear Items
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

            // 4. Integración Getnet
            $login = env('GETNET_LOGIN', '7ffbb7bf1f7361b1200b2e8d74e1d76f');
            $secretKey = env('GETNET_TRANKEY', 'SnZP3D63n3I9dH9O');
            $endpoint = env('GETNET_ENDPOINT', 'https://checkout.test.getnet.cl');

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
                
                // Store the Getnet session ID temporarily in payment_id or a safe field to easily find it later
                $order->update(['payment_id' => $data['requestId']]);

                return response()->json([
                    'processUrl' => $data['processUrl'],
                    'requestId' => $data['requestId'],
                ]);
            } else {
                Log::error('Error Getnet: ' . $response->body());
                return response()->json(['error' => 'Error al conectar con la pasarela de pagos.'], 500);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en PaymentController@init: ' . $e->getMessage());
            return response()->json(['error' => 'Error al procesar el pedido.'], 500);
        }
    }

    public function confirmMock(Request $request)
    {
        // Se mantiene para compatibilidad en pruebas directas
        $order = Order::find($request->order_id);
        if (!$order) abort(404);

        return $this->handleSuccess($order, 'MOCK-PAYMENT-ID');
    }

    /**
     * Retorno desde Getnet
     */
    public function handleReturn(Request $request)
    {
        $reference = $request->query('reference');
        if (!$reference) {
            return redirect('https://ochotierras.vercel.app/checkout/failure?error=missing_reference');
        }

        $order = Order::where('site_transaction_id', $reference)->first();
        if (!$order) {
            return redirect('https://ochotierras.vercel.app/checkout/failure?error=order_not_found');
        }

        if ($order->status === 'PAID') {
            return redirect('https://ochotierras.vercel.app/checkout/success?order=' . $order->site_transaction_id);
        }

        $requestId = $order->payment_id; // Guardado en el init
        if (!$requestId) {
            return redirect('https://ochotierras.vercel.app/checkout/failure?error=missing_request_id');
        }

        // Consultar a Getnet el estado de la transacción
        $login = env('GETNET_LOGIN', '7ffbb7bf1f7361b1200b2e8d74e1d76f');
        $secretKey = env('GETNET_TRANKEY', 'SnZP3D63n3I9dH9O');
        $endpoint = env('GETNET_ENDPOINT', 'https://checkout.test.getnet.cl');

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

        $response = Http::post("{$endpoint}/api/session/{$requestId}", [
            'auth' => $auth
        ]);

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

    /**
     * Callback de éxito (Confirmación)
     */
    private function handleSuccess(Order $order, $paymentId)
    {
        if ($order->status === 'PAID') {
            return redirect('https://ochotierras.vercel.app/checkout/success?order=' . $order->site_transaction_id);
        }

        try {
            DB::beginTransaction();

            // 1. Descontar Stock (Recursivo para Packs)
            foreach ($order->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);

                if ($product && $product->is_pack) {
                    // Descontar componentes
                    foreach ($product->bundleItems as $component) {
                        $qtyToDeduct = $item->quantity * $component->pivot->quantity;
                        $component->decrement('stock', $qtyToDeduct);
                    }
                    // Nota: No descontamos stock del pack en sí, ya que es virtual. 
                    // Opcionalmente podríamos tener un stock "caché" en el pack, pero la fuente de verdad son los hijos.
                } elseif ($product) {
                    $product->decrement('stock', $item->quantity);
                }
            }

            // 2. Actualizar Orden
            $order->update([
                'status' => 'PAID',
                'payment_id' => $paymentId
            ]);

            DB::commit();

            return redirect('https://ochotierras.vercel.app/checkout/success?order=' . $order->site_transaction_id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en confirmación de pago: ' . $e->getMessage());
            return redirect('https://ochotierras.vercel.app/checkout/failure?error=processing');
        }
    }
}
