<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pedido Confirmado</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f0;font-family:'Helvetica Neue',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f0;padding:40px 0;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);">

      <!-- Header -->
      <tr>
        <td style="background:#1a1a2e;padding:40px;text-align:center;">
          <h1 style="margin:0;color:#D4AF37;font-size:28px;font-weight:300;letter-spacing:4px;text-transform:uppercase;">OCHO TIERRAS</h1>
          <p style="margin:8px 0 0;color:#ffffff80;font-size:12px;letter-spacing:2px;text-transform:uppercase;">Viña & Bodega</p>
        </td>
      </tr>

      <!-- Hero -->
      <tr>
        <td style="background:#D4AF37;padding:32px 40px;text-align:center;">
          <p style="margin:0;font-size:40px;">✅</p>
          <h2 style="margin:12px 0 4px;color:#1a1a2e;font-size:22px;font-weight:700;">¡Pedido Confirmado!</h2>
          <p style="margin:0;color:#1a1a2e99;font-size:14px;">Tu compra ha sido procesada exitosamente</p>
        </td>
      </tr>

      <!-- Body -->
      <tr>
        <td style="padding:40px;">
          <p style="margin:0 0 24px;color:#333;font-size:16px;">Hola <strong>{{ $order->customer_name }}</strong>,</p>
          <p style="margin:0 0 32px;color:#555;font-size:14px;line-height:1.7;">
            Hemos recibido tu pedido y está siendo procesado. Te notificaremos cuando esté en camino. ¡Gracias por elegir los vinos de Ocho Tierras!
          </p>

          <!-- Order Info -->
          <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9f9f6;border-radius:8px;margin-bottom:32px;">
            <tr>
              <td style="padding:24px;">
                <p style="margin:0 0 16px;font-size:11px;font-weight:700;color:#D4AF37;text-transform:uppercase;letter-spacing:2px;">Detalles del Pedido</p>
                <table width="100%" cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="padding:6px 0;color:#888;font-size:13px;">Nº de Pedido</td>
                    <td style="padding:6px 0;color:#1a1a2e;font-size:13px;font-weight:700;text-align:right;">{{ $order->site_transaction_id }}</td>
                  </tr>
                  <tr>
                    <td style="padding:6px 0;color:#888;font-size:13px;">Email</td>
                    <td style="padding:6px 0;color:#1a1a2e;font-size:13px;text-align:right;">{{ $order->customer_email }}</td>
                  </tr>
                  <tr>
                    <td style="padding:6px 0;color:#888;font-size:13px;">Dirección de envío</td>
                    <td style="padding:6px 0;color:#1a1a2e;font-size:13px;text-align:right;">{{ $order->shipping_address ?? $order->address_shipping }}</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          <!-- Items -->
          <p style="margin:0 0 16px;font-size:11px;font-weight:700;color:#D4AF37;text-transform:uppercase;letter-spacing:2px;">Productos</p>
          @foreach($order->items as $item)
          <table width="100%" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #f0f0ec;margin-bottom:12px;padding-bottom:12px;">
            <tr>
              <td style="color:#333;font-size:14px;">
                {{ $item->product->name ?? 'Producto' }}
                <span style="color:#888;font-size:12px;"> x{{ $item->quantity }}</span>
              </td>
              <td style="color:#1a1a2e;font-size:14px;font-weight:600;text-align:right;">
                ${{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}
              </td>
            </tr>
          </table>
          @endforeach

          <!-- Total -->
          <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;">
            <tr>
              <td style="padding:16px 0;border-top:2px solid #1a1a2e;color:#1a1a2e;font-size:16px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">Total</td>
              <td style="padding:16px 0;border-top:2px solid #1a1a2e;color:#D4AF37;font-size:18px;font-weight:700;text-align:right;">
                ${{ number_format($order->total_amount, 0, ',', '.') }} CLP
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="background:#f9f9f6;padding:32px 40px;text-align:center;border-top:1px solid #eee;">
          <p style="margin:0 0 8px;color:#888;font-size:13px;">¿Tienes alguna consulta? Contáctanos en</p>
          <a href="mailto:contacto@ochotierras.cl" style="color:#D4AF37;font-size:13px;font-weight:600;text-decoration:none;">contacto@ochotierras.cl</a>
          <p style="margin:24px 0 0;color:#bbb;font-size:11px;">© {{ date('Y') }} Viña Ocho Tierras. Todos los derechos reservados.</p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>
