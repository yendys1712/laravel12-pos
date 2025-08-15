@extends('layouts.app')

@section('content')


<div id="scanner-area" style="display:none;">
    <video id="scanner" width="100%" style="border: 2px solid #ccc;"></video>
</div>

<button onclick="startScanner()" class="bg-blue-600 text-white px-4 py-2 mt-4">
    📷 Start Scanner
</button>
{{-- 
<script src="https://unpkg.com/@ericblade/quagga2@v0.0.10/dist/quagga.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.min.js"></script>
<!-- Link for Version 1.2.6 -->
<script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2@1.2.6/dist/quagga.js"></script>

<script>
function startScanner() {
    document.getElementById('scanner-area').style.display = 'block';
    
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert("Your browser doesn't support camera access.");
        return;
    }

    Quagga.stop(); // Stop previous instance if any

    navigator.mediaDevices.enumerateDevices().then(devices => {
    const videoDevices = devices.filter(device => device.kind === "videoinput");

    if (videoDevices.length === 0) {
        alert("No cameras found.");
        return;
    }

    const deviceId = videoDevices[0].deviceId; // pick first available

    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#scanner'),
            constraints: {
                facingMode: "user" // try "user" if you're on laptop
                // width: { ideal: 640 },
                // height: { ideal: 480 }
            }
        },
        locator: {
            patchSize: "medium",
            halfSample: true
        },
        numOfWorkers: navigator.hardwareConcurrency || 4,
        decoder: {
            readers: ["ean_reader", "code_128_reader"]
        },
        locate: true
    }, function(err) {
        if (err) {
            console.error("Quagga init failed:", err);
            alert("Camera init error: " + err.message);
            return;
        }

        console.log("✅ Quagga initialized.");
        Quagga.start();
    });

    });

    Quagga.onDetected(function(result) {
        let barcode = result.codeResult.code;
        console.log("📦 Scanned barcode:", barcode);

        // Send to backend via AJAX
        fetch("{{ route('cart.ajax-scan') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ barcode: barcode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cartContainer').innerHTML = data.html;
            } else {
                alert("❌ " + (data.error || "Item not found"));
            }

            // Pause and restart
            Quagga.stop();
            setTimeout(() => Quagga.start(), 1500);
        });
    });
}
</script>

@endsection

