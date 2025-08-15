<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #ddd; padding: 8px; }
    </style>
</head>
<body onload="window.print(); window.onafterprint = () => window.close();">
<h2>🧾 Receipt #{{ $sale->id }}</h2>
    <p><strong>Sale ID:</strong> {{ $sale->id }}</p>
    <p><strong>Cashier:</strong> {{ $sale->user->name ?? 'N/A' }}</p>
    <p><strong>Date:</strong> {{ $sale->created_at->format('Y-m-d H:i:s') }}</p>
{{-- <div id="receiptContainer" style="display:none;"> --}}
<table>
    <thead>
    <tr>
        <th>Item ID</th>
        <th>Item</th>
        <th>Barcode</th>
        <th>Category</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Subtotal</th>
    </tr>
    </thead>
    <tbody>
    @php $total = 0; @endphp
    @foreach ($sale->items as $item)
        @php
            $subtotal = ($item->pivot->price - $item->pivot->discount) * $item->pivot->quantity;
            $total += $subtotal;
        @endphp
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->barcode }}</td>
            <td>{{ $item->category ?? 'N/A' }}</td>
            <td>{{ $item->pivot->quantity }}</td>
            <td>₱{{ number_format($item->pivot->price, 2) }}</td>
            <td>₱{{ number_format($subtotal, 2) }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="6"><strong>Total</strong></td>
        <td><strong>₱{{ number_format($total, 2) }}</strong></td>
    </tr>
    </tbody>
</table>
{{-- </div> --}}
</body>
</html>