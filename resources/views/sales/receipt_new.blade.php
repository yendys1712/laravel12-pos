@extends('layouts.app')

@section('content')
<div id="receipt" class="p-6">
    <h1 class="text-xl font-bold mb-4">Receipt</h1>
    <p><strong>Sale ID:</strong> {{ $sale->id }}</p>
    <p><strong>Cashier:</strong> {{ $sale->user->name ?? 'N/A' }}</p>
    <p><strong>Date:</strong> {{ $sale->created_at->format('Y-m-d H:i:s') }}</p>
    <hr class="my-2">
    <table class="w-full text-sm border">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 border">Item</th>
                <th class="p-2 border">Qty</th>
                <th class="p-2 border">Price</th>
                <th class="p-2 border">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td class="p-2 border">{{ $item->name }}</td>
                <td class="p-2 border">{{ $item->pivot->quantity }}</td>
                <td class="p-2 border">₱{{ number_format($item->pivot->price, 2) }}</td>
                <td class="p-2 border">₱{{ number_format($item->pivot->price * $item->pivot->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <hr class="my-2">
    <p class="text-right text-lg font-bold">Total: ₱{{ number_format($sale->total_price, 2) }}</p>
</div>

<script>
    window.onload = function() {
        window.print();
        setTimeout(() => {
            window.location.href = "{{ route('cart.view') }}"; // Go back to cart after printing
        }, 500);
    };
</script>
@endsection
