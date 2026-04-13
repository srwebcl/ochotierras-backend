<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                'address_shipping' => $validated['buyer']['address'] . ', ' . ($validated['buyer']['city'] ?? ''),
                'status' => 'PENDING',
                'total' => $validated['total'],
                'site_transaction_id' => 'ORD-' . strtoupper(uniqid()),
                'marketing_opt_in' => false,
            ]);

            // 3. Crear Items
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
            }

            DB::commit();

            // 4. Integración Getnet (Simulada)
            $mockProcessUrl = url("/api/payment/confirm-mock?order_id={$order->id}");

            return response()->json([
                'processUrl' => $mockProcessUrl,
                'requestId' => $order->site_transaction_id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en PaymentController@init: ' . $e->getMessage());
            return response()->json(['error' => 'Error al procesar el pedido.'], 500);
        }
    }

    // Mock para probar flujo sin pasarela real configurada aun
    public function confirmMock(Request $request)
    {
        $order = Order::find($request->order_id);
        if (!$order)
            abort(404);

        // Simular respuesta exitosa del banco
        return $this->handleSuccess($order, 'MOCK-PAYMENT-ID');
    }

    /**
     * Callback de éxito (Confirmación)
     */
    private function handleSuccess(Order $order, $paymentId)
    {
        if ($order->status === 'PAID') {
            return redirect('http://localhost:3000/checkout/success?order=' . $order->site_transaction_id);
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

            return redirect('http://localhost:3000/checkout/success?order=' . $order->site_transaction_id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en confirmación de pago: ' . $e->getMessage());
            return redirect('http://localhost:3000/checkout/failure?error=processing');
        }
    }
}
