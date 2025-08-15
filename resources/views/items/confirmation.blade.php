@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow text-center">
   
    <h2 class="text-xl font-semibold mb-4 text-green-600">✅ Item Added Successfully</h2>

    <p><strong>Name:</strong> {{ $item->name }}</p>
    <p><strong>Price:</strong> ₱{{ number_format($item->price, 2) }}</p>
    <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
    <p><strong>Barcode:</strong> {{ $item->barcode }}</p>

    @if($item->barcode)
        <div class="mt-4">
            <h4 class="text-md font-medium mb-1">📦 Barcode:</h4>
            <div class="inline-block p-2 bg-gray-100 border rounded">
                {!! DNS1D::getBarcodeHTML($item->barcode, 'C128', 2, 50) !!}
            </div>
        </div>
    @endif

     {{-- <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item->barcode, 'C128') }}" alt="barcode" /> --}}
    {{-- <a href="{{ route('items.index') }}"
       class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
        ← Back to Item List
    </a> --}}
</div>
@endsection
