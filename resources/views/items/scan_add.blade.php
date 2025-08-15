@extends('layouts.app')

@section('content')

<div class="max-w-lg mx-auto mt-6">
  
           <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-lg">
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
                <input type="text" id="barcode" name="barcode"  class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-gray-50" placeholder="Waiting for scan..." readonly>
            </div>
            

            <div class="flex justify-between mt-4">
                <button onclick="restartScanner()" class="text-blue-600 text-sm hover:underline">🔁 Rescan</button>
                <button onclick="Quagga.stop()">🛑 Stop Scan</button>
                <button onclick="manualEntry()" class="text-gray-600 text-sm hover:underline">✍️ Enter Manually</button>
            </div>
        </div>
    <form id="barcodeForm" action="{{ route('items.store_barcode') }}" class="mt-6 space-y-3">
        @csrf
        <div>
            <label class="block text-sm font-medium">Item Name</label>
            <input type="text" id="name" name="name" required class="w-full border px-3 py-2 rounded" placeholder="Enter item name">
        </div>

        <div>
            <label class="block text-sm font-medium">Price (₱)</label>
            <input type="number" id="price" name="price" step="0.01" required class="w-full border px-3 py-2 rounded">
        </div>

        <div>
            <label class="block text-sm font-medium">Quantity</label>
            <input type="number" id="quantity" name="quantity" value="1" required class="w-full border px-3 py-2 rounded">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">➕ Add Item</button>
        
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
        
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
                const input = document.getElementById("barcode");
                input.readOnly = false;
                input.focus();
            }

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
                            target: document.querySelector("#scanner"),
                            constraints: {
                                facingMode: "environment", // back camera
                                width: 400,
                                height: 240
                            }
                        },
                        decoder: {
                            readers: ["code_128_reader", "ean_reader", "ean_8_reader"]
                        }
                    }, function(err) {
                        if (err) {
                            console.error("Quagga init failed:", err);
                            document.getElementById('scanner').innerHTML = '<p class="text-red-500 text-sm text-center p-2">⚠️ Camera error scanner not found.</p>';
                            return;
                        }
                        Quagga.start();
                    });

                    Quagga.onDetected(data => {
                        const code = data.codeResult.code;
                        document.getElementById("barcode").value = code;

                        // Stop scanner after detection
                        Quagga.stop();
                    });
                }

                

            startScanner();

            document.getElementById('barcodeForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const data = {
                barcode: document.getElementById('barcode').value,
                name: document.getElementById('name').value,
                price: parseFloat(document.getElementById('price').value),
                quantity: parseInt(document.getElementById('quantity').value),
              
            };
           
            submitScannedItem(data);
        });

        function submitScannedItem(data) {

           fetch('{{ route("items.store_barcode") }}', {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(data)
            })
            .then(res => {
                if (!res.ok) {
                    return res.text().then(text => { throw new Error(text); });
                }
                return res.json();
            })
            .then(data => { 
                   
                    if(data.error){
                         Toastify({
                                text: "❌ Failed to add this barcode "+data.item +" barcode already exist!",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#ef4444",
                            }).showToast();
                            console.error('❌ Error:', data.error+data.item);
                    }else{
                         Toastify({
                            text: data.message + data.item.name,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#10b981",
                        }).showToast();
                        // location.reload(); // 🔄 Reload the page
                        document.getElementById('barcode').value = '';
                        document.getElementById('name').value = '';
                        document.getElementById('price').value = '';
                        document.getElementById('quantity').value = '';
                        startScanner(); // re-init scanner
                    }
                    
                   
            })
            .catch(err => {
                 console.error(err);
                Toastify({
                    text: "❌ Failed to add item."+data.message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#ef4444",
                }).showToast();
                  alert('✅ error found:'+err);
                console.error('❌ Error:', err.message);
            });
          
        }


    });

        
</script>
@endpush