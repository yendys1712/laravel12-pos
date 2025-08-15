@extends('layouts.app')

@section('content')

@if (session('successupdateitems'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Item Updated Successfully',
            text: '{{ session('success') }}'
        });
    </script>
@endif
@if (session('success'))
    <div class="bg-green-100 text-green-800 p-2 rounded mb-3">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="bg-red-100 text-red-800 p-2 rounded mb-3">{{ session('error') }}</div>
@endif

{{-- <form action="{{ route('pos.addByBarcode') }}" method="POST" class="flex space-x-2 mb-4">
    @csrf
    <input type="text" name="barcode" placeholder="Scan or enter barcode" autofocus
        class="border p-2 rounded w-1/2">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add</button>
</form> --}}

<div class="mb-4">
    <label class="block font-medium mb-1">Scan Barcode:</label>
    <input type="text" id="barcodeInput" name="barcode" class="border p-2 w-full mb-2" placeholder="Or scan using camera...">
    <div id="scanner" class="border w-full h-64 rounded"></div>
</div>



<!-- Show cart -->
<table class="w-full border">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-2 border">Item</th>
            <th class="p-2 border">Qty</th>
            <th class="p-2 border">Price</th>
            <th class="p-2 border">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach (session('cart', []) as $id => $item)
        <tr>
            <td class="p-2 border">{{ $item['name'] }}</td>
            <td class="p-2 border">{{ $item['quantity'] }}</td>
            <td class="p-2 border">₱{{ number_format($item['price'], 2) }}</td>
            <td class="p-2 border">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    Quagga.init({
        inputStream: {
            type: "LiveStream",
            target: document.querySelector('#scanner'),
            constraints: {
                facingMode: "environment" // back camera
            },
        },
        decoder: {
            readers: ["code_128_reader", "ean_reader", "ean_8_reader", "upc_reader"]
        }
    }, function (err) {
        if (err) {
            console.error(err);
            return;
        }
        Quagga.start();
    });

    Quagga.onDetected(function (data) {
        const code = data.codeResult.code;
        document.getElementById('barcodeInput').value = code;
        Quagga.stop(); // stop after 1 scan (you can remove this to scan continuously)
        // Optional: auto-submit or trigger search
        // document.getElementById('searchForm').submit();
    });
</script>
@endsection
