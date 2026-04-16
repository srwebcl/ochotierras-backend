<?php

namespace App\Observers;

use App\Mail\OrderStatusChanged;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Dispara un email al cliente cuando cambia el estado del pedido.
     * No notifica en la creación (PENDING) ni si el email no existe.
     */
    public function updated(Order $order): void
    {
        // Solo notificar si cambió el campo 'status'
        if (!$order->wasChanged('status')) {
            return;
        }

        // No notificar en estado inicial PENDING (eso lo maneja OrderConfirmed)
        $skipStatuses = ['PENDING'];
        if (in_array($order->status, $skipStatuses)) {
            return;
        }

        if (!$order->customer_email) {
            return;
        }

        try {
            Mail::to($order->customer_email)
                ->send(new OrderStatusChanged($order));
        } catch (\Exception $e) {
            Log::error("Error enviando email de cambio de estado para orden {$order->id}: " . $e->getMessage());
        }
    }
}
