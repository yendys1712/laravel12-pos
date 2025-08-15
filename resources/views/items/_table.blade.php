<table id="itemTable" class="min-w-full bg-white border">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-3 py-2 border">Item</th>
            <th class="px-3 py-2 border">Barcode</th>
            <th class="px-3 py-2 border text-right">Price</th>
            <th class="px-3 py-2 border text-center">Qty</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $item)
            <tr>
                <td class="px-3 py-2 border">{{ $item->name }}</td>
                <td class="px-3 py-2 border">{{ $item->barcode }}</td>
                <td class="px-3 py-2 border text-right">₱{{ number_format($item->price, 2) }}</td>
                <td class="px-3 py-2 border text-center">{{ $item->quantity }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center py-4 text-gray-500">No items found.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $items->withQueryString()->links() }}
</div>
