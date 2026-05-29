<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>¿Olvidaste algo? — Ocho Tierras</title>
</head>
<body style="margin:0;padding:0;background:#111111;font-family:'Helvetica Neue',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#111111;padding:40px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <!-- HEADER -->
  <tr>
    <td style="background:#0d0d0d;padding:40px 48px 32px;text-align:center;border-bottom:1px solid #2a2a2a;">
      <h1 style="margin:0;color:#D4AF37;font-size:22px;font-weight:300;letter-spacing:6px;text-transform:uppercase;">OCHO TIERRAS</h1>
      <p style="margin:6px 0 0;color:#666666;font-size:10px;letter-spacing:3px;text-transform:uppercase;">Viña & Bodega · Valle del Limarí</p>
    </td>
  </tr>

  <!-- HERO -->
  <tr>
    <td style="background:linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);padding:48px 48px 40px;text-align:center;">
      <h2 style="margin:0 0 12px;color:#ffffff;font-size:28px;font-weight:300;letter-spacing:1px;">¿Dejaste tus vinos esperando?</h2>
      <p style="margin:0;color:#888888;font-size:14px;line-height:1.7;">Hola {{ $order->customer_name }}, notamos que no pudiste completar tu compra.<br>¡Tus vinos siguen reservados para ti!</p>
    </td>
  </tr>

  <!-- SEPARADOR -->
  <tr>
    <td style="background:#0d0d0d;padding:0 48px;">
      <div style="height:1px;background:linear-gradient(to right, transparent, #D4AF37, transparent);"></div>
    </td>
  </tr>

  <!-- PRODUCTOS -->
  <tr>
    <td style="background:#0d0d0d;padding:40px 48px 32px;">
      <p style="margin:0 0 16px;font-size:10px;font-weight:700;color:#D4AF37;text-transform:uppercase;letter-spacing:3px;">Tu Carrito</p>
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

  <!-- CTA -->
  <tr>
    <td style="background:#0d0d0d;padding:0 48px 48px;text-align:center;">
      <a href="https://ochotierras.cl/es/checkout" style="display:inline-block;background:#D4AF37;color:#000000;font-size:13px;font-weight:700;letter-spacing:2px;text-transform:uppercase;text-decoration:none;padding:16px 40px;border-radius:4px;">Completar mi Compra</a>
    </td>
  </tr>

  <!-- FOOTER -->
  <tr>
    <td style="background:#0a0a0a;border-top:1px solid #1e1e1e;padding:32px 48px;text-align:center;">
      <p style="margin:0;color:#333333;font-size:11px;letter-spacing:1px;">© {{ date('Y') }} Viña Ocho Tierras · Valle del Limarí, Chile</p>
    </td>
  </tr>

</table>
</td></tr>
</table>
</body>
</html>
