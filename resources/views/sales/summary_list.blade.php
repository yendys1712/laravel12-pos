@extends('layouts.app')

@section('content')
<div class="p-4 bg-white rounded shadow">
    <h2 class="text-lg font-bold mb-4">Top Selling Items</h2>

    <!-- Filters -->
    <div class="flex flex-wrap justify-between items-center mb-4 gap-2">
        <div>
            <label class="text-sm">Cashier:
                <select id="cashierSelect" class="border border-gray-300 rounded px-2 py-1 text-sm ml-1">
                    <option value="">All</option>
                </select>
            </label>
        </div>
        <div class="flex gap-2">
            <label class="text-sm">From:
                <input type="date" id="fromDate" class="border border-gray-300 rounded px-2 py-1 text-sm">
            </label>
            <label class="text-sm">To:
                <input type="date" id="toDate" class="border border-gray-300 rounded px-2 py-1 text-sm">
            </label>
            <label class="text-sm">Chart Type:
                <select id="chartType" class="border border-gray-300 rounded px-2 py-1 text-sm">
                    <option value="pie">Pie</option>
                    <option value="bar">Bar</option>
                    <option value="line">Line</option>
                </select>
            </label>
            <button onclick="loadTopItemsChart()" class="bg-blue-500 text-white px-3 py-1 rounded">Apply</button>
        </div>
    </div>

    <!-- Chart -->
    <canvas id="topItemsChart" height="300"></canvas>

    <!-- List Below -->
    <h3 class="text-md font-semibold mt-6 mb-2">Item Details</h3>
    <table class="w-full border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">Item</th>
                <th class="p-2 border">Quantity Sold</th>
            </tr>
        </thead>
        <tbody id="itemList"></tbody>
    </table>
</div>
@endsection
@push('scripts')
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script>
    let chart;

    async function loadCashiers() {
        let response = await fetch("{{ route('sales.cashiers') }}");
        let cashiers = await response.json();
        let cashierSelect = document.getElementById('cashierSelect');
        cashiers.forEach(c => {
            cashierSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
        });
    }

    async function loadTopItemsChart() {
        let type = document.getElementById('chartType').value;
        let from = document.getElementById('fromDate').value;
        let to = document.getElementById('toDate').value;
        let cashier = document.getElementById('cashierSelect').value;
       // # http://127.0.0.1:8000/sales/dashboard?chartType=line&from=2025-08-02&2025-08-02&cashier_id=1
        let url = `{{ route('sales.top-items.chart') }}?chartType=${type}&from=${from}&to=${to}&cashier_id=${cashier}`;
        let response = await fetch(url);
        let data = await response.json();

        let labels = data.map(item => item.name);
        let values = data.map(item => item.total_sold);

        if (chart) chart.destroy(); // Destroy old chart

        let ctx = document.getElementById('topItemsChart').getContext('2d');
        chart = new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label: 'Top Selling Items',
                    data: values,
                    backgroundColor: [
                        '#4CAF50','#2196F3','#FFC107','#FF5722','#9C27B0',
                        '#E91E63','#00BCD4','#8BC34A','#FF9800','#673AB7'
                    ]
                }]
            }
        });

        // Populate item list
        let itemList = document.getElementById('itemList');
        itemList.innerHTML = '';
        data.forEach(item => {
            itemList.innerHTML += `<tr>
                <td class="p-2 border">${item.name}</td>
                <td class="p-2 border text-center">${item.total_sold}</td>
            </tr>`;
        });
    }

    loadCashiers();
    loadTopItemsChart();
</script>
@endpush