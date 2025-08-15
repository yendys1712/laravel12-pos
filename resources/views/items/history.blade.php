@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-lg">
    <h2 class="text-xl font-bold mb-4">Item History Logs</h2>
    <table class="w-full border text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-2 py-1">Action</th>
                <th class="px-2 py-1">Item Name</th>
                <th class="px-2 py-1">User</th>
                <th class="px-2 py-1">Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($histories as $history)
            <tr>
                <td class="px-2 py-1">{{ ucfirst($history->action) }}</td>
                <td class="px-2 py-1">{{ $history->item_name }}</td>
                <td class="px-2 py-1">{{ $history->user->name ?? 'N/A' }}</td>
                <td class="px-2 py-1">{{ $history->created_at->format('Y-m-d H:i A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $histories->links() }}
    </div>
</div>
@endsection
