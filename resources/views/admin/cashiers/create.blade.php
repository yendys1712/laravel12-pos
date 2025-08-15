@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-xl font-semibold mb-4">➕ Add New Cashier</h2>

    <form action="{{ route('cashiers.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block">Name</label>
            <input type="text" name="name" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block">Email</label>
            <input type="email" name="email" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block">Password</label>
            <input type="password" name="password" class="w-full border px-3 py-2 rounded" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create Cashier</button>
    </form>
</div>
@endsection
