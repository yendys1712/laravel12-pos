@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Edit Profile</h2>
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-3">{{ session('success') }}</div>
    @endif
    <form action="{{ route('profile.update') }}" method="POST" class="space-y-3">
        @csrf
        <div>
            <label class="block text-sm">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded px-3 py-2">
            @error('name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2">
            @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Profile</button>
    </form>
</div>
@endsection
