@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6 bg-white shadow rounded">
    <h1 class="text-xl font-bold mb-4">Bulk Import Items</h1>
    <form action="{{ route('items.import.preview') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label class="block mb-2 font-semibold">Select File (CSV/XLSX)</label>
        <input type="file" name="file" required class="border p-2 w-full mb-4" accept=".csv,.xlsx,.xls">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Preview</button>
    </form>
</div>
@endsection
