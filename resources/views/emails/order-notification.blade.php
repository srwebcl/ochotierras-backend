<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Nueva Orden</title></head>
<body style="margin:0;padding:0;background:#f4f4f0;font-family:'Helvetica Neue',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f0;padding:40px 0;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);">

      <tr>
        <td style="background:#1a1a2e;padding:32px 40px;">
          <h1 style="margin:0;color:#D4AF37;font-size:20px;font-weight:700;letter-spacing:2px;">🛒 NUEVA ORDEN — OCHO TIERRAS</h1>
        </td>
      </tr>

      <tr>
        <td style="padding:40px;">
          <table width="100%" cellpadding="0" cellspacing="0" style="background:#fff8e7;border-left:4px solid #D4AF37;padding:20px;border-radius:0 8px 8px 0;margin-bottom:32px;">
            <tr><td>
              <p style="margin:0 0 4px;font-size:22px;font-weight:700;color:#1a1a2e;">{{ $order->customer_name }}</p>
              <p style="margin:0;color:#888;font-size:13px;">{{ $order->customer_email }} · {{ $order->customer_phone }}</p>
            </td></tr>
          </table>

          <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
            <tr>
              <td style="padding:8px 0;border-bottom:1px solid #f0f0ec;color:#888;font-size:13px;width:40%;">Nº Pedido</td>
              <td style="padding:8px 0;border-bottom:1px solid #f0f0ec;color:#1a1a2e;font-size:13px;font-weight:700;">{{ $order->site_transaction_id }}</td>
            </tr>
            <tr>
              <td style="padding:8px 0;border-bottom:1px solid #f0f0ec;color:#888;font-size:13px;">Estado</td>
              <td style="padding:8px 0;border-bottom:1px solid #f0f0ec;">
                <span style="background:#D4AF37;color:#1a1a2e;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;text-transform:uppercase;">{{ $order->status }}</span>
              </td>
            </tr>
            <tr>
              <td style="padding:8px 0;border-bottom:1px solid #f0f0ec;color:#888;font-size:13px;">Dirección</td>
              <td style="padding:8px 0;border-bottom:1px solid #f0f0ec;color:#1a1a2e;font-size:13px;">{{ $order->shipping_address ?? $order->address_shipping }}</td>
            </tr>
            <tr>
              <td style="padding:8px 0;color:#888;font-size:13px;">Fecha</td>
              <td style="padding:8px 0;color:#1a1a2e;font-size:13px;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
          </table>

          <p style="margin:0 0 16px;font-size:11px;font-weight:700;color:#D4AF37;text-transform:uppercase;letter-spacing:2px;">Productos</p>
          @foreach($order->items as $item)
          <table width="100%" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #f0f0ec;margin-bottom:10px;padding-bottom:10px;">
            <tr>
              <td style="color:#333;font-size:14px;">{{ $item->product->name ?? 'Producto' }} <span style="color:#888;">x{{ $item->quantity }}</span></td>
              <td style="color:#1a1a2e;font-size:14px;font-weight:600;text-align:right;">${{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
          </table>
          @endforeach

          <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;">
            <tr>
              <td style="padding:16px 0;border-top:2px solid #1a1a2e;color:#1a1a2e;font-size:16px;font-weight:700;">TOTAL A COBRAR</td>
              <td style="padding:16px 0;border-top:2px solid #1a1a2e;color:#D4AF37;font-size:20px;font-weight:700;text-align:right;">${{ number_format($order->total_amount, 0, ',', '.') }} CLP</td>
            </tr>
          </table>
        </td>
      </tr>

      <tr>
        <td style="background:#1a1a2e;padding:20px 40px;text-align:center;">
          <p style="margin:0;color:#ffffff50;font-size:11px;">Panel de administración: <a href="https://api.ochotierras.cl/admin" style="color:#D4AF37;text-decoration:none;">api.ochotierras.cl/admin</a></p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>
