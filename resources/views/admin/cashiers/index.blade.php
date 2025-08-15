@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Cashiers</h2>
    <a href="{{ route('admin.cashiers.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">+ Add Cashier</a>
    <table class="w-full border text-sm">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">#</th>
                <th class="p-2 border">Name</th>
                <th class="p-2 border">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cashiers as $cashier)
            <tr>
                <td class="p-2 border">{{ $loop->iteration }}</td>
                <td class="p-2 border">{{ $cashier->name }}</td>
                <td class="p-2 border">{{ $cashier->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
