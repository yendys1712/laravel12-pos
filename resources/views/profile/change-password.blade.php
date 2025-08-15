@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Change Password</h2>
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-3">{{ session('success') }}</div>
    @endif
    <form action="{{ route('profile.change') }}" method="POST" class="space-y-3">
        @csrf
        <div>
            <label class="block text-sm">Current Password</label>
            <input type="password" name="current_password" class="w-full border rounded px-3 py-2">
            @error('current_password') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm">New Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2">
            @error('password') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Password</button>
    </form>
</div>
@endsection
