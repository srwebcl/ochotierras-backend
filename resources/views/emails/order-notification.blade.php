<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nueva Orden — Ocho Tierras</title>
</head>
<body style="margin:0;padding:0;background:#111111;font-family:'Helvetica Neue',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#111111;padding:40px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

  <!-- HEADER -->
  <tr>
    <td style="background:#0d0d0d;padding:32px 48px;text-align:center;border-bottom:1px solid #2a2a2a;">
      <div style="margin-bottom:14px;">
        <svg width="40" height="40" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <circle cx="50" cy="50" r="48" fill="none" stroke="#D4AF37" stroke-width="2"/>
          <path d="M50 20 C35 35 25 50 50 65 C75 50 65 35 50 20Z" fill="#D4AF37" opacity="0.9"/>
          <path d="M50 35 C40 45 38 55 50 62 C62 55 60 45 50 35Z" fill="#0d0d0d"/>
          <line x1="50" y1="62" x2="50" y2="80" stroke="#D4AF37" stroke-width="2"/>
          <line x1="42" y1="72" x2="58" y2="72" stroke="#D4AF37" stroke-width="1.5"/>
        </svg>
      </div>
      <h1 style="margin:0;color:#D4AF37;font-size:18px;font-weight:300;letter-spacing:6px;text-transform:uppercase;">OCHO TIERRAS</h1>
      <p style="margin:4px 0 0;color:#444444;font-size:10px;letter-spacing:2px;text-transform:uppercase;">Panel Interno · Nueva Orden</p>
    </td>
  </tr>

  <!-- ALERTA NUEVA VENTA -->
  <tr>
    <td style="background:linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);padding:40px 48px;text-align:center;">
      <div style="display:inline-block;background:rgba(212,175,55,0.15);border:1px solid rgba(212,175,55,0.4);border-radius:50px;padding:8px 24px;margin-bottom:16px;">
        <span style="color:#D4AF37;font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;">🛒 Nueva Venta</span>
      </div>
      <h2 style="margin:0 0 8px;color:#ffffff;font-size:26px;font-weight:300;">¡Tienes un nuevo pedido!</h2>
      <p style="margin:0;color:#555555;font-size:12px;font-family:monospace;letter-spacing:2px;">{{ $order->site_transaction_id }}</p>
    </td>
  </tr>

  <!-- SEPARADOR -->
  <tr>
    <td style="background:#0d0d0d;padding:0 48px;">
      <div style="height:1px;background:linear-gradient(to right, transparent, #D4AF37, transparent);"></div>
    </td>
  </tr>

  <!-- DATOS DEL CLIENTE -->
  <tr>
    <td style="background:#0d0d0d;padding:40px 48px 24px;">
      <p style="margin:0 0 16px;font-size:10px;font-weight:700;color:#D4AF37;text-transform:uppercase;letter-spacing:3px;">Datos del Cliente</p>
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#161616;border:1px solid #2a2a2a;border-radius:8px;">
        <tr>
          <td style="padding:20px 24px;">
            <p style="margin:0 0 4px;color:#ffffff;font-size:18px;font-weight:600;">{{ $order->customer_name }}</p>
            <p style="margin:0 0 8px;color:#888888;font-size:13px;">{{ $order->customer_email }}</p>
            @if($order->customer_phone)
            <p style="margin:0;color:#666666;font-size:12px;">📞 {{ $order->customer_phone }}</p>
            @endif
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- DETALLES INTERNOS -->
  <tr>
    <td style="background:#0d0d0d;padding:0 48px 24px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#161616;border:1px solid #2a2a2a;border-radius:8px;">
        <tr>
          <td style="padding:20px 24px 12px;">
            <p style="margin:0;font-size:10px;font-weight:700;color:#D4AF37;text-transform:uppercase;letter-spacing:3px;">Información del Pedido</p>
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
                  <span style="background:rgba(34,197,94,0.15);color:#22c55e;font-size:11px;font-weight:700;padding:2px 10px;border-radius:20px;">PAGADO</span>
                </td>
              </tr>
              <tr>
                <td style="padding:8px 0;color:#666666;font-size:12px;border-bottom:1px solid #222222;">Fecha</td>
                <td style="padding:8px 0;color:#aaaaaa;font-size:12px;text-align:right;border-bottom:1px solid #222222;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
              </tr>
              @if($order->shipping_address ?? $order->address_shipping)
              <tr>
                <td style="padding:8px 0;color:#666666;font-size:12px;">Dirección de Envío</td>
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
      <p style="margin:0 0 12px;font-size:10px;font-weight:700;color:#D4AF37;text-transform:uppercase;letter-spacing:3px;">Productos Vendidos</p>
      @foreach($order->items as $item)
      <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:8px;">
        <tr>
          <td style="padding:12px 16px;background:#161616;border-radius:6px;color:#cccccc;font-size:13px;">
            {{ $item->product->name ?? 'Producto' }}
            <span style="color:#555555;font-size:12px;"> × {{ $item->quantity }}</span>
          </td>
          <td style="padding:12px 16px;background:#161616;border-radius:6px;color:#D4AF37;font-size:13px;font-weight:600;text-align:right;width:130px;">
            ${{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}
          </td>
        </tr>
      </table>
      @endforeach
    </td>
  </tr>

  <!-- TOTAL -->
  <tr>
    <td style="background:#0d0d0d;padding:0 48px 40px;">
      <div style="height:1px;background:linear-gradient(to right, transparent, #D4AF37, transparent);margin-bottom:20px;"></div>
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td style="color:#888888;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:2px;">Total Recibido</td>
          <td style="color:#D4AF37;font-size:22px;font-weight:700;text-align:right;">${{ number_format($order->total_amount, 0, ',', '.') }} <span style="font-size:13px;font-weight:400;">CLP</span></td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- CTA ADMIN -->
  <tr>
    <td style="background:#0a0a0a;border-top:1px solid #1e1e1e;padding:32px 48px;text-align:center;">
      <a href="https://api.ochotierras.cl/admin/orders" style="display:inline-block;background:#D4AF37;color:#000000;font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;text-decoration:none;padding:12px 32px;border-radius:2px;">
        Ver Panel de Administración
      </a>
      <div style="height:1px;background:#1e1e1e;margin:24px 0;"></div>
      <p style="margin:0;color:#333333;font-size:11px;letter-spacing:1px;">© {{ date('Y') }} Viña Ocho Tierras · Panel Interno</p>
    </td>
  </tr>

</table>
</td></tr>
</table>
</body>
</html>
