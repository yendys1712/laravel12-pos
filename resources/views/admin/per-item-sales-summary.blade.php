@extends('layouts.app')

@section('content')
<div class="p-4">
    <h2 class="text-xl font-bold mb-4">📦 Item Sales Summary (Grouped)</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border text-sm">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="px-3 py-2 border">#</th>
                    <th class="px-3 py-2 border">Item Name</th>
                    <th class="px-3 py-2 border">Barcode</th>
                    <th class="px-3 py-2 border text-center">Total Qty Sold</th>
                    <th class="px-3 py-2 border text-right">Unit Price</th>
                    <th class="px-3 py-2 border text-right">Total Revenue</th>
                    <th class="px-3 py-2 border text-center">Last Sold</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td class="px-3 py-2 border">{{ $item->id + 1 }}</td>
                        <td class="px-3 py-2 border">{{ $item->name }}</td>
                        <td class="px-3 py-2 border">{{ $item->barcode }}</td>
                        <td class="px-3 py-2 border text-center">{{ $item->total_quantity }}</td>
                        <td class="px-3 py-2 border text-right">₱{{ number_format($item->price, 2) }}</td>
                        <td class="px-3 py-2 border text-right">₱{{ number_format($item->total_revenue, 2) }}</td>
                        <td class="px-3 py-2 border text-center">
                            {{ \Carbon\Carbon::parse($item->last_sold)->setTimezone('Asia/Manila')->format('Y-m-d h:i A') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-3 py-4 text-center text-gray-500">No items sold yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
