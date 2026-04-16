<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Actualización de Pedido</title></head>
<body style="margin:0;padding:0;background:#f4f4f0;font-family:'Helvetica Neue',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f0;padding:40px 0;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);">

      <tr>
        <td style="background:#1a1a2e;padding:40px;text-align:center;">
          <h1 style="margin:0;color:#D4AF37;font-size:28px;font-weight:300;letter-spacing:4px;text-transform:uppercase;">OCHO TIERRAS</h1>
          <p style="margin:8px 0 0;color:#ffffff80;font-size:12px;letter-spacing:2px;">Viña & Bodega</p>
        </td>
      </tr>

      <tr>
        <td style="background:#D4AF37;padding:32px 40px;text-align:center;">
          <p style="margin:0;font-size:48px;">{{ $statusEmoji }}</p>
          <h2 style="margin:12px 0 4px;color:#1a1a2e;font-size:22px;font-weight:700;">{{ $statusLabel }}</h2>
          <p style="margin:0;color:#1a1a2e80;font-size:13px;">Pedido #{{ $order->site_transaction_id }}</p>
        </td>
      </tr>

      <tr>
        <td style="padding:40px;">
          <p style="margin:0 0 24px;color:#333;font-size:16px;">Hola <strong>{{ $order->customer_name }}</strong>,</p>
          <p style="margin:0 0 32px;color:#555;font-size:14px;line-height:1.7;">
            @switch($order->status)
              @case('PAID') Hemos confirmado el pago de tu pedido. Estamos preparando tus vinos con sumo cuidado. @break
              @case('PREPARING') Tu pedido está siendo preparado en nuestra bodega. Te avisaremos cuando esté en camino. @break
              @case('SHIPPED') ¡Tu pedido está en camino! Puedes hacer seguimiento con el número de tracking indicado abajo. @break
              @case('DELIVERED') Tu pedido ha sido entregado. Si tienes algún problema, contáctanos de inmediato. @break
              @case('CANCELLED') Tu pedido ha sido cancelado. Si tienes dudas, contáctanos a contacto@ochotierras.cl @break
              @default Ha habido una actualización en el estado de tu pedido. @break
            @endswitch
          </p>

          <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9f9f6;border-radius:8px;margin-bottom:32px;">
            <tr><td style="padding:24px;">
              <p style="margin:0 0 16px;font-size:11px;font-weight:700;color:#D4AF37;text-transform:uppercase;letter-spacing:2px;">Tu Pedido</p>
              <table width="100%">
                <tr>
                  <td style="padding:6px 0;color:#888;font-size:13px;">Nº de Pedido</td>
                  <td style="padding:6px 0;color:#1a1a2e;font-weight:700;font-size:13px;text-align:right;">{{ $order->site_transaction_id }}</td>
                </tr>
                @if($order->tracking_number)
                <tr>
                  <td style="padding:6px 0;color:#888;font-size:13px;">Número de Tracking</td>
                  <td style="padding:6px 0;color:#1a1a2e;font-weight:700;font-size:13px;text-align:right;">{{ $order->tracking_number }} ({{ $order->courier_name }})</td>
                </tr>
                @endif
                <tr>
                  <td style="padding:6px 0;color:#888;font-size:13px;">Total</td>
                  <td style="padding:6px 0;color:#D4AF37;font-weight:700;font-size:15px;text-align:right;">${{ number_format($order->total_amount, 0, ',', '.') }} CLP</td>
                </tr>
              </table>
            </td></tr>
          </table>
        </td>
      </tr>

      <tr>
        <td style="background:#f9f9f6;padding:32px 40px;text-align:center;border-top:1px solid #eee;">
          <p style="margin:0 0 8px;color:#888;font-size:13px;">¿Tienes alguna consulta?</p>
          <a href="mailto:contacto@ochotierras.cl" style="color:#D4AF37;font-size:13px;font-weight:600;text-decoration:none;">contacto@ochotierras.cl</a>
          <p style="margin:24px 0 0;color:#bbb;font-size:11px;">© {{ date('Y') }} Viña Ocho Tierras. Todos los derechos reservados.</p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>
