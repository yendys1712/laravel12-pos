@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto py-8">
    <h2 class="text-xl font-bold mb-4">📊 Sales Summary</h2>

<h3>📦 Total Items Sold: <strong> {{ $summary['totalItemsSold'] }} </strong></h3>
<h3>💰 Total Sales: <strong> ₱{{ number_format($summary['totalSales'], 2) }} </strong></h3>
<h3>🏆 Top Item: <strong> {{ $topItemName }} </strong></h3>


<br>
<br>
<div class="flex flex-wrap justify-between items-center mb-4 gap-2">
        <div>
            <label class="text-sm">Cashier:
                <select id="cashierSelect" class="border border-gray-300 rounded px-2 py-1 text-sm ml-1"></select>
                {{-- <canvas id="topChart" class="w-full max-w-xl"></canvas> --}}
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
                    <option value="bar">Bar</option>
                    <option value="pie">Pie</option>
                    <option value="line">Line</option>
                </select>
            </label>
        </div>
    </div>

    <canvas id="cashierItemsChart" height="100"></canvas>

        <!-- Totals -->
        <div class="flex justify-end gap-6 mt-4 text-sm text-gray-700">
            <div>🧾 Total Quantity Sold: <span id="cashierTotalSold" class="font-semibold">0</span></div>
            <div>💰 Total Sales: ₱<span id="cashierTotalAmount" class="font-semibold">0.00</span></div>
        </div>

        <!-- Breakdown Table -->
        <table id="itemTable" class="table table-hover ">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="text-left px-3 py-2 border">#</th>
                    <th class="text-left px-3 py-2 border">Item Name</th>
                    <th class="text-right px-3 py-2 border">Quantity</th>
                    <th class="text-right px-3 py-2 border">Total Sales (₱)</th>
                </tr>
            </thead>
            <tbody class="bg-white text-gray-800" id="itemDetailsBody"></tbody>
        </table>

</div> 
<script>
  
   
    document.getElementById('fromDate').addEventListener('change', updateChart);
    document.getElementById('toDate').addEventListener('change', updateChart);
    document.getElementById('cashierSelect').addEventListener('change', updateChart);
    document.getElementById('chartType').addEventListener('change', updateChart);

     fetch('/admin/cashiers/list')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('cashierSelect');
            select.innerHTML = '<option value="">All</option>';
            data.forEach(cashier => {
                const opt = document.createElement('option');
                opt.value = cashier.id;
                opt.textContent = cashier.name;
                select.appendChild(opt);
            });
        });

    function updateChart() {
        const from = document.getElementById('fromDate').value;
        const to = document.getElementById('toDate').value;
        const cashierId = document.getElementById('cashierSelect').value;
        const chartType = document.getElementById('chartType').value;

        fetch(`/admin/top-items/chart?from=${from}&to=${to}&cashier_id=${cashierId}`)
            .then(res => res.json())
            .then(renderChart); // renderChart() is your function to update the Chart.js
    }

    let chart;
    function renderChart(data) {
        const ctx = document.getElementById('topChart').getContext('2d');
        if (chart) chart.destroy();

        chart = new Chart(ctx, {
            type: document.getElementById('chartType').value,
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Sold',
                    data: data.data,
                    backgroundColor: ['#3b82f6', '#f97316', '#10b981', '#ef4444', '#8b5cf6'],
                }]
            }
        });
    }
    $(function() {
         
          const initialUrl = `{{ route('admin.data') }}`;
          salesTable = $('#itemTable').DataTable({
                ajax: initialUrl,
                columns: [
                    { data: 'name', title: '#' },
                    { data: 'quantity_sold', title: 'Item Name' },
                    { data: 'total_sales', title: 'Quantity' },
                    { data: 'last_sold_at', title: 'Total Sale',
                        render: function(data, type, row) {
                                if(data == null){
                                    return 'No date and time Sold.';
                                }else{
                                    return dayjs(data).format('MMMM D, YYYY h:mm A');
                                }   
                        }
                    },
                    { data: 'action', orderable: false, searchable: false },
                ],
               dom: 'Bfrtip',
                buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    className: 'bg-green-600 text-green px-3 py-1 rounded'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Export to PDF',
                    className: 'bg-red-600 text-red px-3 py-1 rounded'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'bg-blue-600 text-blue px-3 py-1 rounded'
                }
                ],
                createdRow: function (row, data, dataIndex) {
                    $('td', row).addClass('border px-3 py-2 hover:bg-gray-50');
                }
            });
        });



document.addEventListener('DOMContentLoaded', function () {
    fetch("{{ route('admin.top-items.chart') }}")
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.name);
            const values = data.map(item => item.total_quantity);
            const total = values.reduce((sum, val) => sum + parseInt(val), 0);

            const totalSales = data.reduce((sum, item) => sum + parseFloat(item.total_sales), 0);

            document.getElementById('totalSold').textContent = total;
            document.getElementById('totalAmount').textContent = totalSales.toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            new Chart(document.getElementById('topItemsChart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Quantity Sold',
                        data: values,
                        backgroundColor: 'rgba(37, 99, 235, 0.7)',
                        borderColor: 'rgba(37, 99, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
});

// cashier 
let cashierChart;

async function fetchCashiers() {
    const res = await fetch("{{ route('admin.cashiers.list') }}");
    const cashiers = await res.json();
    const select = document.getElementById('cashierSelect');
    select.innerHTML = '<option value="">All</option>';
    cashiers.forEach(user => {
        const opt = document.createElement('option');
        opt.value = user.id;
        opt.text = user.name;
        select.appendChild(opt);
    });
}

async function fetchCashierChart(userId = '') {
    const from = document.getElementById('fromDate').value;
    const to = document.getElementById('toDate').value;
    const chartType = document.getElementById('chartType').value;

    const url = new URL("{{ route('admin.top-items.by-cashier') }}", window.location.origin);
    if (userId) url.searchParams.append('user_id', userId);
    if (from) url.searchParams.append('from', from);
    if (to) url.searchParams.append('to', to);

    const res = await fetch(url);
    const data = await res.json();

    const labels = data.map(item => item.name);
    const quantities = data.map(item => item.total_quantity);
    const sales = data.reduce((sum, i) => sum + parseFloat(i.total_sales), 0);
    const totalQty = quantities.reduce((sum, q) => sum + parseInt(q), 0);

    document.getElementById('cashierTotalSold').textContent = totalQty;
    document.getElementById('cashierTotalAmount').textContent = sales.toLocaleString('en-PH', { minimumFractionDigits: 2 });

    if (cashierChart) cashierChart.destroy();

    let dataset;
    if (chartType === 'pie') {
        dataset = {
            data: quantities,
            backgroundColor: [
                '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                '#F472B6', '#34D399', '#60A5FA', '#E879F9', '#FBBF24'
            ],
            borderWidth: 1
        };
    } else {
        dataset = {
            label: 'Quantity Sold',
            data: quantities,
            backgroundColor: 'rgba(59, 130, 246, 0.7)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 1,
            fill: chartType === 'line',
            tension: chartType === 'line' ? 0.4 : undefined
        };
    }

    const options = {
        responsive: true,
        plugins: {
            legend: { display: true }
        }
    };

    if (chartType === 'bar' || chartType === 'line') {
        options.scales = {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        };
    }

    cashierChart = new Chart(document.getElementById('cashierItemsChart'), {
        type: chartType,
        data: { labels, datasets: [dataset] },
        options: options
    });

    // Update breakdown table
    const tbody = document.getElementById('itemDetailsBody');
    tbody.innerHTML = '';
    data.forEach((item, index) => {
        const row = document.createElement('tr');
        row.classList.add('border-t');
        row.innerHTML = `
            <td class="px-3 py-1 border text-gray-500">${index + 1}</td>
            <td class="px-3 py-1 border">${item.name}</td>
            <td class="px-3 py-1 border text-right">${item.total_quantity}</td>
            <td class="px-3 py-1 border text-right">₱${parseFloat(item.total_sales).toFixed(2)}</td>
        `;
        tbody.appendChild(row);
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    await fetchCashiers();
    await fetchCashierChart();

    document.getElementById('cashierSelect').addEventListener('change', () => {
        fetchCashierChart(document.getElementById('cashierSelect').value);
    });

    document.getElementById('fromDate').addEventListener('change', () => {
        fetchCashierChart(document.getElementById('cashierSelect').value);
    });

    document.getElementById('toDate').addEventListener('change', () => {
        fetchCashierChart(document.getElementById('cashierSelect').value);
    });

    document.getElementById('chartType').addEventListener('change', () => {
        fetchCashierChart(document.getElementById('cashierSelect').value);
    });
});

// cahsier chart here

// let cashierChart;

// async function fetchCashiers() {
//     const res = await fetch("{{ route('admin.cashiers.list') }}");
//     const cashiers = await res.json();
//     const select = document.getElementById("cashierSelect");

//     cashiers.forEach(user => {
//         const option = document.createElement("option");
//         option.value = user.id;
//         option.textContent = user.name;
//         select.appendChild(option);
//     });
// }

// async function fetchCashierChart(userId = '') {
//     const from = document.getElementById('fromDate').value;
//     const to = document.getElementById('toDate').value;
//     const chartType = document.getElementById('chartType').value;

//     const url = new URL("{{ route('admin.top-items.by-cashier') }}", window.location.origin);
//     if (userId) url.searchParams.append('user_id', userId);
//     if (from) url.searchParams.append('from', from);
//     if (to) url.searchParams.append('to', to);

//     const res = await fetch(url);
//     const data = await res.json();

//     const labels = data.map(item => item.name);
//     const quantities = data.map(item => item.total_quantity);
//     const sales = data.reduce((sum, item) => sum + parseFloat(item.total_sales), 0);
//     const totalQty = quantities.reduce((sum, q) => sum + parseInt(q), 0);

//     document.getElementById('cashierTotalSold').textContent = totalQty;
//     document.getElementById('cashierTotalAmount').textContent = sales.toLocaleString('en-PH', { minimumFractionDigits: 2 });

//     if (cashierChart) cashierChart.destroy();

//     const dataset = {
//         label: 'Quantity Sold',
//         data: quantities,
//         backgroundColor: chartType === 'pie'
//             ? ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6']
//             : 'rgba(59, 130, 246, 0.7)',
//         borderColor: chartType === 'pie'
//             ? '#fff'
//             : 'rgba(59, 130, 246, 1)',
//         borderWidth: 1,
//         fill: chartType === 'line' ? true : false,
//         tension: chartType === 'line' ? 0.4 : undefined
//     };

//     cashierChart = new Chart(document.getElementById('cashierItemsChart'), {
//         type: chartType,
//         data: {
//             labels,
//             datasets: [dataset]
//         },
//         options: {
//             responsive: true,
//             plugins: {
//                 legend: {
//                     display: true
//                 }
//             },
//             scales: chartType === 'bar' || chartType === 'line' ? {
//                 y: {
//                     beginAtZero: true,
//                     ticks: { stepSize: 1 }
//                 }
//             } : {}
//         }
//     });

        
//         document.addEventListener('DOMContentLoaded', async function () {
//             await fetchCashiers();
//             await fetchCashierChart();

//             document.getElementById('chartType').addEventListener('change', () => {
//                 fetchCashierChart(document.getElementById('cashierSelect').value);
//             });

//             document.getElementById('cashierSelect').addEventListener('change', (e) => {
//                 fetchCashierChart(e.target.value);
//             });

//             document.getElementById('fromDate').addEventListener('change', () => {
//                 fetchCashierChart(document.getElementById('cashierSelect').value);
//             });

//             document.getElementById('toDate').addEventListener('change', () => {
//                 fetchCashierChart(document.getElementById('cashierSelect').value);
//             });
//         });
//} // cahsier end chart here

</script>
@endsection 

{{-- 
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
     <div class="mb-4">
        <a href="{{ route('export.excel') }}" class="bg-green-600 text-white px-4 py-2 rounded">
            Export to Excel 
        </a>
        <a href="{{ route('export.pdf') }}" class="bg-red-600 text-white px-4 py-2 rounded ml-2">
            Export to PDF
        </a>
    </div>

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
</div> --}}
{{-- @endsection  --}}