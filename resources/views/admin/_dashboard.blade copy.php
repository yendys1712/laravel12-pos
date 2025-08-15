@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>

    <h2 class="text-xl font-bold mb-4">📊 Sales Summary</h2>

       <ul>
            <li>Total Items Sold: <strong>{{ $summary['totalItemsSold'] }}</strong></li>
            <li>Total Sales: <strong>₱{{ number_format($summary['totalSales'], 2) }}</strong></li>
            <li>Top-Selling Item: <strong>{{ $summary['topItem'] }}</strong></li>
        </ul>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold">Total Items</h2>
            <p class="text-2xl font-bold text-green-600 mt-2">{{ \App\Models\Item::count() }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold">Low Stock Items</h2>
            <p class="text-2xl font-bold text-red-600 mt-2">{{ \App\Models\Item::where('quantity', '<', 5)->count() }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold">POS Link</h2>
            <a href="{{ route('items.index') }}" class="text-blue-500 hover:underline mt-2 block">Go to POS</a>
        </div>
    </div>

   <h3 class="text-lg font-semibold mt-6 mb-4">🧾 Item Sales Summary (Table Format)</h3>

<table class="w-full border text-sm shadow">
    <thead class="bg-blue-600 text-white">
        <tr>
            <th class="border px-3 py-2">#</th>
            <th class="border px-3 py-2">Item Name</th>
            <th class="border px-3 py-2 text-right">Total Qty Sold</th>
            <th class="border px-3 py-2 text-right">Total Sales</th>
            <th class="border px-3 py-2">Last Sold (Date & Time)</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($salesSummary as $index => $sale)
            <tr>
                <td class="border px-3 py-2 text-center">{{ $index + 1 }}</td>
                <td class="border px-3 py-2">{{ $sale->item->name }}</td>
                <td class="border px-3 py-2 text-right">{{ $sale->total_qty }}</td>
                <td class="border px-3 py-2 text-right">₱{{ number_format($sale->total_sales, 2) }}</td>
                <td class="border px-3 py-2">
                    {{ \Carbon\Carbon::parse($sale->last_sold_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-gray-500 py-4">No item sales recorded yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection