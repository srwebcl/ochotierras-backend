<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public string $statusLabel;
    public string $statusEmoji;

    public function __construct(public Order $order)
    {
        $map = [
            'PENDING'   => ['⏳ Pedido recibido',     '⏳'],
            'PAID'      => ['✅ Pago confirmado',      '✅'],
            'PREPARING' => ['📦 Preparando tu pedido', '📦'],
            'SHIPPED'   => ['🚚 En camino',            '🚚'],
            'DELIVERED' => ['🎉 Entregado',            '🎉'],
            'CANCELLED' => ['❌ Pedido cancelado',     '❌'],
            'FAILED'    => ['⚠️ Pago fallido',         '⚠️'],
        ];

        $this->statusLabel = $map[$order->status][0] ?? $order->status;
        $this->statusEmoji = $map[$order->status][1] ?? '📋';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->statusEmoji} Actualización de tu pedido #{$this->order->site_transaction_id} — Ocho Tierras",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order-status-changed');
    }
}
