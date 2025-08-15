@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4" style="color:red">📊 Sales Summary</h1>

    <form method="GET" class="flex flex-wrap gap-2 mb-4">
        <label>From:
            <input type="date" name="from" value="{{ request('from', now()->startOfMonth()->toDateString()) }}" class="border px-2 py-1 rounded shadow">
        </label>
        <label>To:
            <input type="date" name="to" value="{{ request('to', now()->toDateString()) }}" class="border px-2 py-1 rounded shadow">
        </label>
        <label>Cashier:
            <select name="cashier_id" class="border px-2 py-1 rounded shadow">
                <option value="">All</option>
                @foreach($cashiers as $cashier)
                    <option value="{{ $cashier->id }}" {{ $cashierId == $cashier->id ? 'selected' : '' }}>
                        {{ $cashier->name }}
                    </option>
                @endforeach
            </select>
        </label>
        <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded shadow">Filter</button>
    </form>

    <table id="salesSummaryTable" class="min-w-full border shadow text-sm mb-6">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">#</th>
                <th class="p-2 border">Item</th>
                <th class="p-2 border">Quantity Sold</th>
                <th class="p-2 border">Total Sales</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary as $index => $item)
                <tr>
                    <td class="p-2 border">{{ $loop->iteration }}</td>
                    <td class="p-2 border">{{ $item['name'] }}</td>
                    <td class="p-2 border">{{ $item['total_qty'] }}</td>
                    <td class="p-2 border">₱{{ number_format($item['total_sales'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 font-bold">
        Total Sales Amount: <span class="text-green-600">₱{{ number_format($totalSalesAmount, 2) }}</span>
    </div>

    <div class="mt-6">
        <h2 class="text-xl font-semibold mb-2">Top Selling Items</h2>
        {{-- <canvas id="salesChart" height="120"></canvas> --}}
         <canvas id="salesChart" width="400" class="shadow" height="200"></canvas>
    </div>
</div>
@endsection

@push('scripts')
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<script>
$(document).ready(function () {
    $('#salesSummaryTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf'],
        paging: true
    });

    // const ctx = document.getElementById('salesChart').getContext('2d');
    // const chart = new Chart(ctx, {
    //     type: 'bar', // Change to 'pie' or 'line' dynamically if needed
    //     data: {
    //         labels: {!! json_encode($chartData['labels']) !!},
    //         datasets: [{
    //             label: 'Quantity Sold',
    //             data: {!! json_encode($chartData['quantities']) !!},
    //             backgroundColor: 'rgba(54, 162, 235, 0.6)',
    //             borderColor: 'rgba(54, 162, 235, 1)',
    //             borderWidth: 1
    //         }]
    //     },
    //     options: { responsive: true }
    // });
    });
    let salesChart;

    function fetchSalesData() {
        $.get('/api/sales-data', function(data) {
            if (!salesChart) {
                // Create chart first time
                const ctx = document.getElementById('salesChart').getContext('2d');
                salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        // labels: data.dates,
                        labels: {!! json_encode($chartData['labels']) !!},
                        datasets: [{
                            label: 'Quantity Sold',
                            // data: data.totals,
                            data: {!! json_encode($chartData['quantities'])  !!},
                            borderColor: 'blue',
                            backgroundColor: 'rgba(0, 0, 255, 0.2)',
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            } else {
                // Update chart
                salesChart.data.labels = data.json_encode($chartData['labels']);
                salesChart.data.datasets[0].data = data.quantities;
                //salesChart.data.datasets[1].data = data.sales;
                salesChart.update();
            }
        });
    }

    // Fetch every 5 seconds
    fetchSalesData();
    setInterval(fetchSalesData, 5000);



</script>
@endpush
