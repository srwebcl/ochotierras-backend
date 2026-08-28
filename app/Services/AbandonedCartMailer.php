<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Recordatorio de carrito abandonado, programado directamente en Resend
 * (con su parámetro `scheduled_at`) en vez de depender de un cron propio
 * que "despierte" cada hora a revisar qué pedidos quedaron pendientes.
 *
 * Flujo: al crear el pedido se programa el envío para dentro de X horas;
 * si el pedido se paga antes, se cancela. Si Resend no responde o falla
 * al programar, no rompe el checkout — solo queda sin recordatorio y el
 * comando `orders:recover-abandoned` (vía cron) actúa como respaldo.
 */
class AbandonedCartMailer
{
    public static function schedule(Order $order, int $delayHours = 2): void
    {
        $key = config('services.resend.key');
        if (!$key || !$order->customer_email) {
            return;
        }

        try {
            $html = view('emails.abandoned-cart', [
                'order' => $order->load('items.product'),
            ])->render();

            $response = Http::withToken($key)->post('https://api.resend.com/emails', [
                'from' => config('mail.from.address', 'noreply@ochotierras.cl'),
                'to' => [$order->customer_email],
                'subject' => '¿Olvidaste algo en Ocho Tierras?',
                'html' => $html,
                'scheduled_at' => now()->addHours($delayHours)->toIso8601String(),
            ]);

            if ($response->successful() && $response->json('id')) {
                $order->update(['abandoned_email_resend_id' => $response->json('id')]);
            } else {
                Log::warning('No se pudo programar el recordatorio de carrito abandonado en Resend: ' . $response->body());
            }
        } catch (\Throwable $e) {
            Log::error('Error programando recordatorio de carrito abandonado: ' . get_class($e) . ' - ' . $e->getMessage());
        }
    }

    public static function cancel(Order $order): void
    {
        if (!$order->abandoned_email_resend_id) {
            return;
        }

        $key = config('services.resend.key');
        if (!$key) {
            return;
        }

        try {
            Http::withToken($key)->post("https://api.resend.com/emails/{$order->abandoned_email_resend_id}/cancel");
        } catch (\Throwable $e) {
            // Si ya se envió (pasó la hora programada), Resend responde con error
            // al intentar cancelarlo — no es un caso grave, solo lo dejamos en el log.
            Log::warning('No se pudo cancelar el recordatorio de carrito abandonado: ' . $e->getMessage());
        }
    }
}
