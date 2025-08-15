@extends('layouts.app')

@section('content')
@if (session('success-clear'))
    <script>
        Toastify({
        text: "Remove Successfully",
        duration: 3000,
        gravity: "top", 
        position: "right",
        backgroundColor: "#48bb78", // Tailwind green-500
        }).showToast();
    </script>
@endif
@if (session('success_clear_item'))
    <script>
        Toastify({
        text: "Remove Successfully",
        duration: 3000,
        gravity: "top", 
        position: "right",
        backgroundColor: "#48bb78", // Tailwind green-500
        }).showToast();
    </script>
@endif
@if (session('error_empty'))
      <script>
        Swal.fire({
            icon: 'warning',
            title: 'Cart is empty.',
            text: '{{ session('error_empty') }}'
        });
    </script>
@endif
@if (session('success_no_item'))
      <script>
        Swal.fire({
            icon: 'warning',
            title: 'No item added yet!',
            text: '{{ session('success') }}'
        });
    </script>
@endif

@if(session('reload'))
<script>
    window.onload = function () {
        window.location.reload();
    };
</script>
@endif
@if (session('success-checkout'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully check-out',
            text: '{{ session('success') }}'
        });
    </script>
@endif
@if (session('success-cart-remove'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Remove Item in the Cart',
            text: '{{ session('success-cart-remove') }}'
        });
    </script>
@endif
@if (session('successupdateitems'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Item Updated Successfully',
            text: '{{ session('success') }}'
        });
    </script>
@endif
@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}'
        });
    </script>
@endif
{{-- @if (session('sale_id'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            handleCheckout({{ session('sale_id') }});
        });
    </script>
@endif --}}

@if (session('sale_id'))
<script>
    function handleCheckout(saleId) {
        fetch('/cart/refresh', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        }).then(() => {
            window.open('/sales/receipt/' + saleId, '_blank', 'width=600,height=700');
            window.location.reload();
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        handleCheckout({{ session('sale_id') }});
    });
</script>
@endif
    
<div class="container mt-4">
    <h3 class="mb-4">🛒 Cashier Cart</h3>
     {{-- <form action="{{ route('cart.add') }}" method="POST" class="mb-4"> --}}
    {{-- @csrf --}}

    {{-- <div id="scanner" style="width: 100%; max-width: 400px; margin: auto; border: 2px dashed #ccc;"></div>
        <p class="text-center text-sm mt-2">📷 Scan a barcode using your phone's camera</p>

      --}}
        <div id="scanResult" class="text-center mt-4 text-green-600 font-semibold hidden">
            ✅ Item added to cart!
        </div>
         <label id="scanbarcode" class="block text-sm font-medium text-gray-600 mb-1"> 📷 Click Scanned Barcode:</label>
         <div id="barscan" class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-lg" style="display: none;">
            <h2 class="text-lg font-semibold text-gray-700 mb-2 text-center">📦 Scan Item Barcode</h2>

            <div class="relative border-4 border-dashed border-blue-400 rounded-lg overflow-hidden">
                <div id="scanner" class="w-full h-60 bg-gray-100 flex items-center justify-center">
                    <span class="text-gray-400 text-sm">Camera feed loading...</span>
                </div>
                <div class="absolute bottom-0 w-full bg-blue-50 text-blue-800 text-center text-xs py-1">
                    Use your phone camera to scan barcode
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-600 mb-1"> 📷 Scanned Barcode:</label>
                {{-- <input type="text" id="barcode" name="barcode"  class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-gray-50" placeholder="Waiting for scan..." readonly> --}}
                {{-- <label for="barcodeInput" class="block text-sm font-medium">🔍 Enter or Scan Barcode</label> --}}
                    <input 
                        type="text" 
                        id="barcode" 
                        name="barcode" 
                        class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="🔍 Enter or Scan Barcode"
                        autocomplete="off" readonly
                    >
            </div>
            

            <div class="flex justify-between mt-4">
                <button onclick="restartScanner()" class="text-blue-600 text-sm hover:underline">🔁 Rescan</button>
                <button onclick="Quagga.stop()">🛑 Stop Scan</button>
                <button onclick="manualEntrys()" class="text-gray-600 text-sm hover:underline">✍️ Enter Manually</button>
            </div>
        </div>
        
    <div class="row g-2">
        <div class="col-md-6">
             <input type="text" id="item-search" placeholder="Search by name or barcode"
                        class="w-full border p-2 mb-4 rounded shadow" autocomplete="off">
                      <ul id="suggestions" class="absolute z-10  max-w-3/4 bg-white border border-gray-300 rounded mt-1 shadow max-h-60 overflow-y-auto"></ul>
                      {{-- <ul id="suggestions" class="absolute bg-white border w-full z-10 hidden max-h-48 overflow-y-auto"></ul>  class="bg-white border rounded shadow mb-4 hidden" --}}
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-success w-100 order p-2 mb-4 rounded shadow">🛒 Add to Cart</button>
        </div>

          <div class="col-md-2">
                <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Clear all items from cart?')">
                    @csrf
                    <button type="submit" class="btn btn-warning w-100 order p-2 mb-4 rounded shadow">
                        🗑 Clear Cart
                    </button>
        </form>
    </div>
   </div>
    {{-- </form> --}}
            {{-- <select name="id" class="form-select" required>
                <option value="">-- Select Item --</option>
                @foreach(\App\Models\Item::all() as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->name }} (₱{{ number_format($item->price, 2) }}) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         @if ($item->quantity < 5)
                            ⚠️ LOW STOCK ({{ $item->quantity }})
                        @else
                           🔴 current.QTY: {{ $item->quantity }}
                        @endif  --}}
                        {{-- <span class="text-sm text-red-500" style=""> 🔴 current.QTY: {{ $item->quantity }}</span> --}}
                    {{-- </option>
                @endforeach
            </select>
        </div> --}}

          <!-- Search Box -->      
    
  

    <div id="cart-table">
      
        <h2 class="text-lg text-right font-semibold mb-2 " id="total-item">🛒 Cart (Total Items: {{ $totalItems ?? collect(session('cart', []))->sum('quantity') }})</h2>

        <table  class="w-full border text-sm rounded shadow" id="itemTable">
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
                    <tr  class="odd:bg-white even:bg-gray-100">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td>₱{{ number_format($item['price'], 2) }}</td>
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

        {{-- Total & Checkout target="_blank" --}}
                <div class="mt-4 text-end"> 
                    <h1><span class="text-dafault fw-bold" style="font-size:30px"> Total: </span><span class="text-success fw-bold" style="font-size:35px">₱{{ number_format($total, 2) }}</span></h1>
                    <form action="{{ route('cart.checkout') }}" method="POST"  class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-lg btn-primary mt-2 border shadow">✅🧾 Checkout & Print Receipt</button>
                    </form>
                </div>
            </div>   

  </div>  {{-- end of tables --}}
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
 <script>

            document.getElementById('scanbarcode').addEventListener('click', function () {
                    let scanner = document.getElementById('barscan');

                    if (scanner.style.display === 'none') {
                        scanner.style.display = 'block';
                    }else{
                        scanner.style.display = 'none';
                    }
            });
            $(document).ready(function () {
                $('#itemTable').DataTable({
                    scrollY: '400px',        // vertical scroll
                    //scrollX: true,           // horizontal scroll
                    scrollCollapse: true,
                    stripeClasses: ['even:bg-gray-200', 'odd:bg-white-200'],
                    paging: true,
                    fixedHeader: true,
                    responsive: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "🔍 Search items...",
                        lengthMenu: "Show _MENU_ entries",
                    }
                });
            });


            function manualEntrys() {
                    const input = document.getElementById("barcode");
                    input.readOnly = false;
                    input.focus();
            }
        
            document.addEventListener('DOMContentLoaded', () => {
            const rows = document.querySelectorAll('.item-row');
            let currentIndex = 0;

            function focusRow(index) {
                if (rows[index]) {
                    rows[index].focus();
                }
            }

            if (rows.length > 0) {
                focusRow(currentIndex);
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (currentIndex < rows.length - 1) currentIndex++;
                    focusRow(currentIndex);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (currentIndex > 0) currentIndex--;
                    focusRow(currentIndex);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    const selected = rows[currentIndex];
                   /// const itemId = selected.dataset.id;
                    const button = event.target.closest('[data-id]');
                    const itemId = button?.dataset.id;
                    const buttonname = event.target.closest('[data-name]');
                    const itemName = button?.dataset.name;
                    //const itemName = selected.dataset.name;
                    const buttonprice = event.target.closest('[data-price]');
                    const itemPrice = button?.dataset.price;
                  ///  const itemPrice = selected.dataset.price;

                    // Optional: Your AJAX/cart add function
                      const barcodeInput = document.getElementById('barcode');
                    if(barcodeInput != null){
                        handleBarcode(barcodeInput.value.trim());
                    }else{
                         addToCart(itemId, itemName, itemPrice);
                    }
                   
                }
            });

            // 👇 Optional: Click support
            rows.forEach((row, i) => {
                row.addEventListener('click', () => {
                    currentIndex = i;
                    focusRow(i);
                });
            });

            // 👇 Example addToCart function — replace with your own AJAX
                function addToCart(id, name, price) {
                    fetch(`/cart/add`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            item_id: id,
                            name: name,
                            price: price,
                            quantity: 1
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(`${name} added to cart`);
                        // Optionally reload or update cart
                    });
                }
            });
            /// ITEM ARROW END
          
            // document.getElementById('receiptContainer').style.display = 'block';
            let selectedIndex = -1;
            let suggestionsData = [];

            document.getElementById('item-search').addEventListener('input', function () {
                const query = this.value;
                if (!query) {
                    hideSuggestions();
                    return;
                }

                fetch(`/cart/search?query=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        suggestionsData = data;
                        showSuggestions(data);
                    });
            });

            document.getElementById('item-search').addEventListener('keydown', function (e) {
                const items = document.querySelectorAll('#suggestions li');

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (selectedIndex < items.length - 1) {
                        selectedIndex++;
                        updateSelection(items);
                    }
                }

                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (selectedIndex > 0) {
                        selectedIndex--;
                        updateSelection(items);
                    }
                }

                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (selectedIndex >= 0 && suggestionsData[selectedIndex]) {
                        selectItem(suggestionsData[selectedIndex].id);
                    }
                }
            });

            function showSuggestions(data) {
                const list = document.getElementById('suggestions');
                    list.innerHTML = '';
                    selectedIndex = -1;
                    suggestionsData = data;

                    data.forEach((item, index) => {
                    const li = document.createElement('li');
                    li.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer';
                    li.setAttribute('data-index', index);

                    const row = document.createElement('div');
                    row.className = 'flex justify-between items-center';

                    const info = document.createElement('div');
                    info.innerHTML = `
                        <strong>${item.name}</strong> - ₱${item.price}<br>
                        <small>Barcode: ${item.barcode} | QTY: ${item.quantity}</small> `;

                    const button = document.createElement('button');
                    button.className = 'bg-green-500 hover:bg-green-600 text-white text-sm px-2 py-1 rounded ml-4';
                    button.textContent = '➕ Add';
                    button.addEventListener('click', function (e) {
                        e.stopPropagation();
                        selectItem(item.id);
                    });

                    row.appendChild(info);
                    row.appendChild(button);
                    li.appendChild(row);

                    li.addEventListener('click', () => selectItem(item.id));
                    list.appendChild(li);
                    });

                    list.classList.remove('hidden');
                }
             
                function updateSelection(items) {
                    items.forEach((el, i) => {
                        el.classList.toggle('bg-blue-100', i === selectedIndex);
                    });
                }
                function selectItem(itemId) {
                    document.getElementById('item-search').value = '';
                    hideSuggestions();
                    addItemToCart(itemId);
                }

                function hideSuggestions() {
                    document.getElementById('suggestions').classList.add('hidden');
                    selectedIndex = -1;
                }
                function addItemToCart(itemId) {
                        fetch("{{ route('cart.ajaxAdd') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ id: itemId })
                        })
                        .then(res => res.json())
                        .then(data => {
                                document.getElementById('cart-table').innerHTML = data.cartView;
                                document.getElementById('item-search').value = ''; // optional: clear input
                        
                        });
                }
                function updateSelection(items) {
                    items.forEach((el, i) => {
                        el.classList.toggle('bg-blue-100', i === selectedIndex);
                    });
                }
            
                function removeFromCart(id) {
                    
                    fetch("{{ route('cart.remove') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ id: itemId })
                    })
                    .then(() => location.reload()); // or re-render the cart via AJAX
                }
                function removeFromCart(itemId) {
                    fetch("{{ route('cart.remove') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ id: itemId })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('cart-table').innerHTML = data.cartView;
                        }
                    });
                }
                function updateCartQty(itemId, newQty) {
                    fetch("{{ route('cart.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            id: itemId,
                            quantity: parseInt(newQty)
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (data.error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...'+data.text,
                                text: '{{ session('data.text') }}'
                            });
                        }
                        if (data.success) {
                            document.getElementById('cart-table').innerHTML = data.cartView;
                        }
                    
                    });
                }

                  
             function restartScanner() {
                            console.log('rescan barcode...');
                            document.getElementById('barcode').value = '';
                            document.getElementById('name').value = '';
                            document.getElementById('price').value = '';
                            document.getElementById('quantity').value = '';
                            Quagga.stop();
                            setTimeout(startScanner, 300);
                    }

            function manualEntry() {
                    const barcode = document.getElementById('barcodeInput').value.trim();
                    if (!barcode) return;

                    fetch(`/cart/${barcode}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.error) {
                                alert(data.error);
                            } else {
                                alert(`${data.item.name} added to cart`);
                                updateCartTable(data.cart);
                            }
                        })
                        .catch(() => alert("Something went wrong"));

                    document.getElementById('barcodeInput').value = '';
                }

                //// barcode manual input 
              document.addEventListener('DOMContentLoaded', () => {
                    const barcodeInput = document.getElementById('barcode');

                    // Trigger on Enter key
                    barcodeInput.addEventListener('keypress', function (e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            handleBarcode(barcodeInput.value.trim());
                        }
                    });

                    // Optional: Allow Quagga to populate this too
                    window.handleBarcode = function(barcode) {
                        if (!barcode) return;

                        // Example fetch to add item to cart or search
                        fetch(`/cart/ajax-scan/${barcode}`)
                            .then(res => res.json())
                            .then(data => {
                                console.log('Found item:', data);
                                // Add to cart or show item info
                            })
                            .catch(err => {
                                alert("Item not found.");
                            });

                        // Clear input and refocus
                        barcodeInput.value = '';
                        barcodeInput.focus();
                    };
                });
            //// barcode manual input 
            document.addEventListener('DOMContentLoaded', () => {
                const scannerEl = document.getElementById('scanner');
                const barcodeInput = document.getElementById('barcode');
                const nameInput = document.getElementById('name');

                let scanning = false;

          

                document.addEventListener("DOMContentLoaded", startScanner);

                function startScanner() {
                    Quagga.init({
                        inputStream: {
                            name: "Live",
                            type: "LiveStream",
                            target: document.querySelector('#scanner'),
                            constraints: {
                                facingMode: "environment", // Use back camera
                                width: 400,
                                height: 240
                            },
                        },
                        decoder: {
                            readers: ["code_128_reader", "ean_reader", "ean_8_reader"]
                            // readers: ["ean_reader", "ean_8_reader", "code_128_reader", "upc_reader", "code_39_reader"]
                        },
                    }, function (err) {
                        if (err) {
                            console.error("Quagga init failed:", err);
                            document.getElementById('scanner').innerHTML = '<p class="text-red-500 text-sm text-center p-2">⚠️ Camera error scanner not found.</p>';
                            return;
                        }
                        Quagga.start();
                    });

                    Quagga.onDetected(async function(result) {
                        const code = result.codeResult.code;
                        if (!window.barcodeScanned) {
                            window.barcodeScanned = true;
                            handleBarcode(code); // Same function as manual input
                            Quagga.stop();
                        }
                    // alert(code);
                    // Quagga.offDetected(); // prevent multiple triggers ajax-scan
                        ///playBeep();
                    if (code && !window.barcodeScanned) {
                            alert('ready to validate...'+ code);
                            window.barcodeScanned = true; // ✅ Prevent duplicate scans
                            try {
                                const res = await fetch('{{ route("cart.ajax-scan") }}', {
                                    method: "POST",
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ barcode: code })
                                });

                                const data = await res.json();
                                alert(data);
                                if (data.success) {
                                    scanResult.classList.remove('hidden');
                                    alert('item found');
                                    setTimeout(() => {
                                        scanResult.classList.add('hidden');
                                        Quagga.start(); // allow next scan
                                    }, 1500);
                                } else {
                                    alert(data.message || "Item not found.");
                                    window.barcodeScanned = false;
                                    Quagga.start(); // allow retry
                                }
                            } catch (err) {
                                console.error(err);
                                alert("Scan failed. Try again.");
                                Quagga.start();
                            }

                        }
                        
                    });
                    
                }

                function playBeep() {
                    beep.play().catch(e => console.warn('Autoplay failed:', e));
                }

                startScanner();

        });

            //     Quagga.onDetected(function (data) {
            //         const barcode = data.codeResult.code;
            //         console.log("Scanned barcode:", barcode);
            //         Quagga.stop(); // Optional: Stop after first scan

            //         // Send to backend via AJAX
            //         fetch("/cart/ajax-scan", {
            //             method: "POST",
            //             headers: {
            //                 "Content-Type": "application/json",
            //                 "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            //             },
            //             body: JSON.stringify({ barcode: barcode })
            //         })
            //         .then(res => res.json())
            //         .then(res => {
            //             console.log(res);
            //             alert("✅ Item added to cart: " + res.item.name);
            //             // Optionally reload or update cart via AJAX
            //         })
            //         .catch(err => console.error("AJAX error:", err));
            //     });
            // }

            // document.addEventListener("DOMContentLoaded", startScanner);
        
        </script>
@endpush



