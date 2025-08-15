@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Sales Summary</h1>

    <form method="GET" class="flex gap-2 mb-4">
        <label>From:
            <input type="date" name="from" value="{{ request('from', now()->startOfMonth()->toDateString()) }}" class="border px-2 py-1 rounded">
        </label>
        <label>To:
            <input type="date" name="to" value="{{ request('to', now()->toDateString()) }}" class="border px-2 py-1 rounded">
        </label>
        <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">Filter</button>
    </form>

    <table id="salesSummaryTable" class="min-w-full border text-sm">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">#</th>
                <th class="p-2 border">Item</th>
                <th class="p-2 border">Quantity Sold</th>
                <th class="p-2 border">Total Sales</th>
                  <th class="p-2 border">Cashier</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary as $index => $item)
                <tr>
                    <td class="p-2 border">{{ $loop->iteration }}</td>
                    <td class="p-2 border">{{ $item['name'] }}</td>
                    <td class="p-2 border">{{ $item['total_qty'] }}</td>
                    <td class="p-2 border">₱{{ number_format($item['total_sales'], 2) }}</td>
                    <td class="p-2 border">{{ $item['cashier'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 font-bold">
        Total Sales Amount: <span class="text-green-600">₱{{ number_format($totalSalesAmount, 2) }}</span>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#salesSummaryTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf'],
        paging: true
    });
});
</script>
@endpush