
<h2 class="text-lg text-right font-semibold mb-2" id="total-item">🛒 Cart (Total Items: {{ $totalItems ?? collect(session('cart', []))->sum('quantity') }})</h2>

<table  class="w-full border text-sm shadow" id="itemTable">
    <thead>
        <tr class="bg-gray-200">
            <th>Item ID</th>
            <th>Name</th>
            <th>Price</th>
            <th class="text-center align-middle">Qty</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach ($cart as $index =>  $item)
            @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
            {{-- <tr class="item-row focus:bg-blue-100 cursor-pointer" tabindex="0" data-id="{{ $item['id'] }}"> --}}
             <tr class="item-row">
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['name'] }}</td>
                <td>₱{{ number_format($item['price'], 2) }}</td>
                {{-- <td>{{ $item['quantity'] }}</td> --}}
                 <td class="text-center align-middle">
                    <input type="number"
                            min="1"
                            value="{{ $item['quantity'] }}"
                            class="w-16 border rounded text-center"
                            onchange="updateCartQty({{ $item['id'] }}, this.value)">    
                </td>
                           
                <td>₱{{ number_format($subtotal, 2) }}</td>
                <td>
                    <form action="{{ route('cart.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item['id'] }}">
                        <button type="submit" class="text-red-600 hover:underline">❌ Remove</button>
                    </form>
                </td>
            </tr>
       @endforeach
    </tbody>
</table>

{{-- Total & Checkout --}}
        <div class="mt-4 text-end"> 
            <h1><span class="text-dafault fw-bold" style="font-size:30px"> Total: </span><span class="text-success fw-bold" style="font-size:35px">₱{{ number_format($total, 2) }}</span></h1>
            <form action="{{ route('cart.checkout') }}" method="POST" target="_blank" class="d-inline-block">
                @csrf
                <button type="submit" class="btn btn-lg btn-primary mt-2 border shadow">✅🧾 Checkout & Print Receipt</button>
            </form>
        </div>
    </div>   
    

