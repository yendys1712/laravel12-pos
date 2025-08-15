@extends('layouts.app')

@section('content')

<div class="container mt-4">
        <h1 class="text-xl font-bold mb-4">Item History</h1>
  <div class="flex justify-between items-center mb-4">

<table class="table-auto w-full border-collapse border shadow border-gray-300">
    
    <thead>
        <tr>
            <th class="border px-2 py-1">Name Item</th>
            <th class="border px-2 py-1">Action</th>
            <th class="border px-2 py-1">User</th>
            <th class="border px-2 py-1">Quantity</th>
            <th class="border px-2 py-1">Note</th>
            <th class="border px-2 py-1">Created Date</th>
        
          
        </tr>
    </thead>
    <tbody>
        @foreach($histories as $history)
        <tr>
            <td class="border px-2 py-1">{{ $history->item_name }}</td>
            <td class="border px-2 py-1">{{ $history->action ?? 'Deleted Item' }}</td>
            <td class="border px-2 py-1">{{ $history->user_id ?? 'Cashier' }}</td>
            <td class="border px-2 py-1">{{ $history->quantity ?? '0' }}</td>
                 <td class="border px-2 py-1">{{ $history->notes ?? 'Added Manually' }}</td>
            <td class="border px-2 py-1">{{ $history->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>
{{ $histories->links() }}
</div>

@endsection
