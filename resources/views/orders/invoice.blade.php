<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }} | Cafe POS</title>
    <style>body{margin:0;background:#f5f5f4;color:#292524;font-family:Arial,sans-serif}.invoice{max-width:720px;margin:32px auto;background:white;padding:40px;box-shadow:0 4px 20px #0001}.top,.line,.total{display:flex;justify-content:space-between;gap:20px}.muted{color:#78716c}.items{border-top:1px solid #e7e5e4;margin-top:28px}.line{border-bottom:1px solid #f0efed;padding:12px 0}.options{font-size:12px;color:#78716c;margin-top:4px}.total{border-top:2px solid #292524;margin-top:18px;padding-top:16px;font-size:20px;font-weight:bold}.actions{margin-top:28px;display:flex;gap:12px}.button{border:1px solid #d6d3d1;border-radius:6px;padding:10px 16px;text-decoration:none;color:#292524;background:#fff;cursor:pointer}@media print{body{background:#fff}.invoice{margin:0;box-shadow:none;padding:0}.actions{display:none}}</style>
</head>
<body>
    <main class="invoice">
        <div class="top"><div><p class="muted">CAFE POS</p><h1>Customer receipt</h1></div><div><strong>Invoice #{{ $order->id }}</strong><p class="muted">{{ $order->created_at?->format('M d, Y H:i') }}</p></div></div>
        <p class="muted">{{ ucfirst($order->order_mode) }}{{ $order->table_name ? ' · '.$order->table_name : '' }} · Payment: {{ ucfirst($order->payment_status) }}</p>
        <div class="items">
            @foreach ($order->orderItems as $item)
                @php($options = collect($item->selected_options ?? [])->map(fn ($option) => is_array($option) ? ($option['name'] ?? null) : $option)->filter()->implode(', '))
                <div class="line"><div><strong>{{ $item->quantity }} × {{ $item->product->name }}</strong>@if ($options)<div class="options">Options: {{ $options }}</div>@endif</div><strong>${{ number_format((float) $item->price * $item->quantity, 2) }}</strong></div>
            @endforeach
        </div>
        <div class="line"><span>Subtotal</span><span>${{ number_format((float) ($order->subtotal ?: $order->total_amount), 2) }}</span></div>
        @if ((float) $order->discount_amount > 0)<div class="line"><span>Discount</span><span>-${{ number_format((float) $order->discount_amount, 2) }}</span></div>@endif
        @if ((float) $order->tip_amount > 0)<div class="line"><span>Tip</span><span>${{ number_format((float) $order->tip_amount, 2) }}</span></div>@endif
        <div class="total"><span>Total</span><span>${{ number_format((float) $order->total_amount + (float) $order->tip_amount, 2) }}</span></div>
        <p class="muted">Payment: {{ $order->payments->pluck('payment_type')->map(fn ($type) => ucfirst($type))->join(', ') ?: 'Unpaid' }}</p>
        <div class="actions"><button class="button" onclick="window.print()">Print invoice</button><a class="button" href="{{ route('home') }}">Back to menu</a></div>
    </main>
</body>
</html>
