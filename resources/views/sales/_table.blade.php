@php
    $today = now()->format('Y-m-d');
@endphp
<div class="max-w-4xl mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Sales History</h1>
        <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">🧾 Sales Report</h2>

        <form class="flex flex-wrap items-center gap-2 justify-end">
            <label for="from" class="text-sm">From:</label>
            <input type="date" id="from" value="{{ $today }}" class="border rounded px-2 py-1">

            <label for="to" class="text-sm">To:</label>
            <input type="date" id="to" value="{{ $today }}" class="border rounded px-2 py-1">

            <label for="user_id" class="text-sm">Cashier:</label>
            <select id="user_id" class="border rounded px-2 py-1">
                <option value="">All</option>
                @foreach (\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>

            <button type="button" onclick="reloadSalesData()" class="bg-blue-600 text-white px-3 py-1 rounded">
                Filter
            </button>
      
        </form>
    </div>

    <!-- Sales Table -->
    <table id="itemTable" class="">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Item Name</th>
                <th class="p-2 border">Qty</th>
                <th class="p-2 border">Total Price</th>
                <th class="p-2 border">Sold At</th>
                <th class="p-2 border">Cashier</th>
                <th class="p-2 border">Action</th>
          
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
              <tr>
                <td class="px-3 py-2 border">{{ $sale->id }}</td>
                <td class="px-3 py-2 border">{{ $sale->item_name }}</td>
                <td class="px-3 py-2 border text-center">{{ $sale->quantity }}</td>
                <td class="px-3 py-2 border text-right">₱{{ number_format($sale->total_price, 2) }}</td>
                <td class="p-2 border">{{ $sale->sold_at }}</td>
                <td class="p-2 border">{{ $sale->cashier }}</td>
            </tr>
            @empty
                <tr><td colspan="4" class="text-center py-4 text-gray-500">No sales found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>