@extends('layouts.app')

@section('content')
@if (session('success-checkout'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully check-out',
            text: '{{ session('success') }}'
        });
    </script>
@endif
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg">
    <h2 class="text-xl font-bold mb-4">🛒 Cart</h2>

    {{-- Search --}}
    <form id="searchForm" method="POST" action="{{ route('index.add') }}">
        @csrf
        <input type="text" id="itemSearch" name="term"
            class="border w-full p-2 mb-3"
            placeholder="Search item by name or barcode" autocomplete="off">
        <input type="hidden" name="id" id="id">
        <ul id="searchResults" class="bg-white border absolute z-50 w-full hidden"></ul>
    </form>

    {{-- Cart Table --}}
    @if(count($cart))
    <table class="w-full table-auto border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Item</th>
                <th class="p-2">Price</th>
                <th class="p-2">Qty</th>
                <th class="p-2">Subtotal</th>
                <th class="p-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($cart as $id => $item)
                @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                <tr>
                    <td class="p-2">{{ $item['name'] }}</td>
                    <td class="p-2">₱{{ number_format($item['price'], 2) }}</td>
                    <td class="p-2">
                        {{-- {{ $item['quantity'] }}</td> --}}
                    <form action="{{ route('index.update') }}" method="POST" class="d-flex justify-content-center">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" class="form-control form-control-sm text-center" min="1" style="width: 60px;">
                                </td>

                    <td class="p-2">₱{{ number_format($subtotal, 2) }}</td>
                    <td class="p-2">
                        <form method="POST" action="{{ route('index.remove') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $id }}">
                            <button class="text-red-600">🗑 Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-bold">
                <td colspan="3" class="text-right p-2">Total:</td>
                <td class="p-2">₱{{ number_format($total, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    {{-- Checkout Button --}}
    <form method="POST" action="{{ route('index.checkout') }}" class="mt-4">
        @csrf
        <button class="bg-green-600 text-white px-4 py-2 rounded">💵 Checkout</button>
    </form>
    @else
    <p>No items in cart yet.</p>
    @endif
</div>
{{-- JS Live Search --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('#itemSearch').on('input', function () {
        let term = $(this).val();
        if (term.length >= 2) {
            $.get('{{ route('items.search') }}', { term: term }, function (data) {
                let resultBox = $('#searchResults');
                resultBox.empty().removeClass('hidden');

                if (data.length === 0) {
                    resultBox.append('<li class="p-2 text-gray-500">No matches found</li>');
                } else {
                    data.forEach(function (item) {
                        $('#searchResults').append(`
                            <li class="p-2 hover:bg-gray-200 cursor-pointer" data-id="${item.id}">
                                ${item.name} - ₱${item.price} (${item.barcode}) - QTY: ${item.quantity}
                            </li>
                        `);
                    });
                }
            });
        } else {
            $('#searchResults').addClass('hidden');
        }
    });

    $(document).on('click', '#searchResults li', function () {
        let itemId = $(this).data('id');
        $('#id').val(itemId);
        $('#searchForm').submit();
    });

    $(document).click(function (e) {
        if (!$(e.target).closest('#searchForm').length) {
            $('#searchResults').addClass('hidden');
        }
    });
});
</script>
@endsection