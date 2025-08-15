<!-- resources/views/items/preview.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">

<h2 class="text-lg font-bold">Preview Items</h2>

@if(count($rows) > 0)
    <form method="POST"  action="{{ route('items.confirmitems') }}"  enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="file_path" value="{{ $path }}">
        <table class="table-auto border-collapse border border-gray-400 w-full mt-3">
            <thead>
                <tr>
                    <th class="border px-2 py-1">Name</th>
                    <th class="border px-2 py-1">Barcode</th>
                    <th class="border px-2 py-1">Price</th>
                    <th class="border px-2 py-1">Quantity</th>
                    <th class="border px-2 py-1">Created Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td class="border px-2 py-1">{{ $row['name'] ?? $row[0] }}</td>
                        <td class="border px-2 py-1">{{ $row['barcode'] ?? $row[1] }}</td>
                        <td class="border px-2 py-1">{{ $row['price'] ?? $row[2] }}</td>
                        <td class="border px-2 py-1">{{ $row['quantity'] ?? $row[3] }}</td>
                        <td class="border px-2 py-1">{{ $row[4] ?? $row[4] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('items.index') }}" class="mt-3 bg-gray-500 text-white px-4 py-2 rounded">
            Cancel
        </a>
        <button type="submit" class="mt-3 bg-green-500 text-white px-4 py-2 rounded">
            Confirm Import
        </button>
      
    </form>
     
@else
    <p>No items found in the uploaded file.</p>
@endif

</div>

<script>



</script>

@endsection
