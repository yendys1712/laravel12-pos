@extends('layouts.app')

@section('content')
@php
    $today = now()->format('Y-m-d');
@endphp

{{-- @if (isset($resultsCount))
      <script>
        Swal.fire({
            icon: 'success',
            title: 'I found a result'{{ session($resultsCount) }},
            text: '{{ session('resultsCount') }}'
        });
    </script>
@endif --}}
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
      <tbody id="item-table-body">
          
        </tbody>
    </table>
</div>
@endsection

 @push('scripts')
 <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>

<script type="text/javascript">
         
            let salesTable;

            function reloadSalesData() {
                const from = document.getElementById('from').value;
                const to = document.getElementById('to').value;
                const user_id = document.getElementById('user_id').value;
                if (!salesTable) return; // prevent error if not initialized
                const url = `{{ route('sales.sales.getData') }}?from=${from}&to=${to}&user_id=${user_id}`;
                if (salesTable) {
                         salesTable.ajax.url(url).load();
                    }
            }
           
            $(function() {
                const from = $('#from').val();
                const to = $('#to').val();
                const user_id = document.getElementById('user_id').value;
                const initialUrl = `{{ route('sales.sales.getData') }}?from=${from}&to=${to}&user_id=${user_id}`;
                salesTable = $('#itemTable').DataTable({
                        ajax: initialUrl,
                        columns: [
                            { data: 'sale_id', title: 'ID' },
                            { data: 'item_name', title: 'Item name' },
                            { data: 'quantity', title: 'Qty' },
                            { data: 'total', title: 'Total Price' },
                            { data: 'sold_at', title: 'Sold At',
                             render: function(data, type, row) {
                                        if(data == null){
                                            return 'No date and time Sold.';
                                        }else{
                                            return dayjs(data).format('MMMM D, YYYY h:mm A');
                                        }   
                                }
                            },
                            { data: 'cashier', title: 'Cashier'},
                            { data: 'action', orderable: false, searchable: false },
                        ],
                    dom: 'Bfrtip',
                        buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Export to Excel',
                            className: 'bg-green-600 text-white px-3 py-1 rounded'
                        },
                        {
                            extend: 'pdfHtml5',
                            text: 'Export to PDF',
                            className: 'bg-red-600 text-white px-3 py-1 rounded'
                        },
                        {
                            extend: 'print',
                            text: 'Print',
                            className: 'bg-blue-600 text-white px-3 py-1 rounded'
                        }
                        ],
                        createdRow: function (row, data, dataIndex) {
                            $('td', row).addClass('border px-3 py-2 hover:bg-gray-50');
                        }
                    });
            });
</script>
@endpush
