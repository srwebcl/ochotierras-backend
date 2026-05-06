<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pedido Confirmado — Ocho Tierras</title>
</head>
<body style="margin:0;padding:0;background:#111111;font-family:'Helvetica Neue',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#111111;padding:40px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <!-- HEADER: Logo y marca -->
  <tr>
    <td style="background:#0d0d0d;padding:40px 48px 32px;text-align:center;border-bottom:1px solid #2a2a2a;">
      <!-- Logo SVG Inline -->
      <div style="margin-bottom:16px;">
        <svg width="48" height="48" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <circle cx="50" cy="50" r="48" fill="none" stroke="#D4AF37" stroke-width="2"/>
          <path d="M50 20 C35 35 25 50 50 65 C75 50 65 35 50 20Z" fill="#D4AF37" opacity="0.9"/>
          <path d="M50 35 C40 45 38 55 50 62 C62 55 60 45 50 35Z" fill="#0d0d0d"/>
          <line x1="50" y1="62" x2="50" y2="80" stroke="#D4AF37" stroke-width="2"/>
          <line x1="42" y1="72" x2="58" y2="72" stroke="#D4AF37" stroke-width="1.5"/>
        </svg>
      </div>
      <h1 style="margin:0;color:#D4AF37;font-size:22px;font-weight:300;letter-spacing:6px;text-transform:uppercase;">OCHO TIERRAS</h1>
      <p style="margin:6px 0 0;color:#666666;font-size:10px;letter-spacing:3px;text-transform:uppercase;">Viña & Bodega · Valle del Limarí</p>
    </td>
  </tr>

  <!-- HERO: Confirmación -->
  <tr>
    <td style="background:linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);padding:48px 48px 40px;text-align:center;">
      <div style="display:inline-block;background:rgba(212,175,55,0.12);border:1px solid rgba(212,175,55,0.3);border-radius:50px;padding:8px 20px;margin-bottom:24px;">
        <span style="color:#D4AF37;font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;">✓ Pago Confirmado</span>
      </div>
      <h2 style="margin:0 0 12px;color:#ffffff;font-size:28px;font-weight:300;letter-spacing:1px;">¡Gracias por tu pedido!</h2>
      <p style="margin:0;color:#888888;font-size:14px;line-height:1.7;">Tu compra ha sido procesada exitosamente.<br>Prepararemos tus vinos con el mayor cuidado.</p>
    </td>
  </tr>

  <!-- SEPARADOR DORADO -->
  <tr>
    <td style="background:#0d0d0d;padding:0 48px;">
      <div style="height:1px;background:linear-gradient(to right, transparent, #D4AF37, transparent);"></div>
    </td>
  </tr>

  <!-- CUERPO: Saludo -->
  <tr>
    <td style="background:#0d0d0d;padding:40px 48px 24px;">
      <p style="margin:0 0 8px;color:#cccccc;font-size:15px;">Hola <strong style="color:#ffffff;">{{ $order->customer_name }}</strong>,</p>
      <p style="margin:0;color:#888888;font-size:14px;line-height:1.8;">
        Hemos recibido tu pedido y está siendo procesado. Te notificaremos cuando esté en camino hacia ti.
      </p>
    </td>
  </tr>

  <!-- DETALLES DEL PEDIDO -->
  <tr>
    <td style="background:#0d0d0d;padding:0 48px 32px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#161616;border:1px solid #2a2a2a;border-radius:8px;">
        <tr>
          <td style="padding:20px 24px 12px;">
            <p style="margin:0;font-size:10px;font-weight:700;color:#D4AF37;text-transform:uppercase;letter-spacing:3px;">Detalles del Pedido</p>
          </td>
        </tr>
        <tr>
          <td style="padding:0 24px 20px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="padding:8px 0;color:#666666;font-size:12px;border-bottom:1px solid #222222;">N° de Pedido</td>
                <td style="padding:8px 0;color:#D4AF37;font-size:12px;font-weight:700;text-align:right;border-bottom:1px solid #222222;font-family:monospace;">{{ $order->site_transaction_id }}</td>
              </tr>
              <tr>
                <td style="padding:8px 0;color:#666666;font-size:12px;border-bottom:1px solid #222222;">Estado</td>
                <td style="padding:8px 0;text-align:right;border-bottom:1px solid #222222;">
                  <span style="background:rgba(34,197,94,0.15);color:#22c55e;font-size:11px;font-weight:700;padding:2px 10px;border-radius:20px;letter-spacing:1px;">PAGADO</span>
                </td>
              </tr>
              <tr>
                <td style="padding:8px 0;color:#666666;font-size:12px;border-bottom:1px solid #222222;">Correo</td>
                <td style="padding:8px 0;color:#aaaaaa;font-size:12px;text-align:right;border-bottom:1px solid #222222;">{{ $order->customer_email }}</td>
              </tr>
              @if($order->shipping_address ?? $order->address_shipping)
              <tr>
                <td style="padding:8px 0;color:#666666;font-size:12px;">Dirección de envío</td>
                <td style="padding:8px 0;color:#aaaaaa;font-size:12px;text-align:right;">{{ $order->shipping_address ?? $order->address_shipping }}</td>
              </tr>
              @endif
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- PRODUCTOS -->
  <tr>
    <td style="background:#0d0d0d;padding:0 48px 16px;">
      <p style="margin:0 0 16px;font-size:10px;font-weight:700;color:#D4AF37;text-transform:uppercase;letter-spacing:3px;">Productos</p>
      @foreach($order->items as $item)
      <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
        <tr>
          <td style="padding:12px 16px;background:#161616;border-radius:6px;color:#cccccc;font-size:13px;">
            {{ $item->product->name ?? 'Producto' }}
            <span style="color:#666666;font-size:12px;"> × {{ $item->quantity }}</span>
          </td>
          <td style="padding:12px 16px;background:#161616;border-radius:6px;color:#D4AF37;font-size:13px;font-weight:600;text-align:right;width:120px;">
            ${{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}
          </td>
        </tr>
      </table>
      @endforeach
    </td>
  </tr>

  <!-- TOTAL -->
  <tr>
    <td style="background:#0d0d0d;padding:0 48px 48px;">
      <div style="height:1px;background:linear-gradient(to right, transparent, #D4AF37, transparent);margin-bottom:20px;"></div>
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td style="color:#888888;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:2px;">Total Pagado</td>
          <td style="color:#D4AF37;font-size:22px;font-weight:700;text-align:right;">${{ number_format($order->total_amount, 0, ',', '.') }} <span style="font-size:13px;font-weight:400;">CLP</span></td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- FOOTER -->
  <tr>
    <td style="background:#0a0a0a;border-top:1px solid #1e1e1e;padding:32px 48px;text-align:center;">
      <p style="margin:0 0 8px;color:#555555;font-size:12px;">¿Tienes alguna consulta sobre tu pedido?</p>
      <a href="mailto:contacto@ochotierras.cl" style="color:#D4AF37;font-size:13px;font-weight:600;text-decoration:none;">contacto@ochotierras.cl</a>
      <div style="height:1px;background:#1e1e1e;margin:24px 0;"></div>
      <p style="margin:0;color:#333333;font-size:11px;letter-spacing:1px;">© {{ date('Y') }} Viña Ocho Tierras · Valle del Limarí, Chile</p>
    </td>
  </tr>

</table>
</td></tr>
</table>
</body>
</html>
