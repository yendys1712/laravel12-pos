<div class="max-w-6xl mx-auto p-4">

    <h2 class="text-2xl font-bold mb-4">🔍 Item List (Live Search)</h2>

    <!-- 🔍 Search input -->
    <input wire:model.debounce.300ms="search" 
           type="text" 
           placeholder="Search items..." 
           class="w-full border p-2 rounded mb-4 shadow">

    <!-- 📦 Results Table -->
    <table class="w-full border text-sm shadow">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-3 py-2 border">Image</th>
                <th class="px-3 py-2 border">Name</th>
                <th class="px-3 py-2 border">Price</th>
                <th class="px-3 py-2 border">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td class="border p-2">
                        <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" style="height: 50px;" class="rounded">
                    </td>
                    <td class="border p-2">{{ $item->name }}</td>
                    <td class="border p-2">₱{{ number_format($item->price, 2) }}</td>
                    <td class="border p-2">{{ $item->quantity }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-500">No items found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>