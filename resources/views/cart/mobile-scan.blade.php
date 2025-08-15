@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-4">
    <h2 class="text-xl font-bold text-center mb-4">📦 Scan Item Barcode</h2>

    <!-- Scanner UI -->
    <div class="border-2 border-dashed border-gray-400 rounded-lg overflow-hidden relative">
        <div id="scanner" style="width: 100%; height: 300px;"></div>
        <p class="absolute bottom-2 left-0 right-0 text-center text-sm text-gray-600">📷 Point camera at barcode</p>
    </div>

    <!-- Success Alert -->
    <div id="scanResult" class="text-center mt-4 text-green-600 font-semibold hidden">
        ✅ Item added to cart!
    </div>

    <!-- Audio Beep -->
    {{-- <audio id="beepSound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio> --}}

    <button onclick="history.back()" class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow">
        🔙 Back to Cart
    </button>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // const beep = document.getElementById('beepSound');
    const scanResult = document.getElementById('scanResult');

    function startScanner() {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner'),
                constraints: {
                    facingMode: "environment" // back camera
                },
            },
            decoder: {
                readers: ["code_128_reader", "ean_reader", "ean_8_reader", "upc_reader"]
            }
        }, function(err) {
            if (err) {
                console.error("Quagga init failed:", err);
                alert("Scanner initialization failed. Try again.");
                return;
            }
            Quagga.start();
        });

        Quagga.onDetected(async function(result) {
            const code = result.codeResult.code;
            Quagga.offDetected(); // prevent multiple triggers
            // playBeep();

            try {
                const res = await fetch("/cart/ajax-scan", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ barcode: code })
                });

                const data = await res.json();
                if (data.success) {
                    scanResult.classList.remove('hidden');
                    setTimeout(() => {
                        scanResult.classList.add('hidden');
                        Quagga.start(); // allow next scan
                    }, 1500);
                } else {
                    alert(data.message || "Item not found.");
                    Quagga.start(); // allow retry
                }
            } catch (err) {
                console.error(err);
                alert("Scan failed. Try again.");
                Quagga.start();
            }
        });
    }

    function playBeep() {
        beep.play().catch(e => console.warn('Autoplay failed:', e));
    }

    startScanner();
});
</script>
@endsection
