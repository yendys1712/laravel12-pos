<div class="mb-4">
    <button id="toggleScanner" class="bg-blue-600 text-white px-3 py-1 rounded">
        📷 Use Camera Scanner
    </button>
</div>

<!-- Manual Input -->
<div id="manualInputContainer">
    <label for="barcodeInput" class="block text-sm font-medium">Enter Barcode</label>
    <input type="text" id="barcodeInput" class="w-full border px-3 py-2 rounded" placeholder="Type or scan barcode">
</div>

<!-- Camera Scanner (hidden by default) -->
<div id="scannerContainer" style="display: none;">
    <div id="scanner" style="width: 100%; max-width: 400px; margin: auto; border: 2px dashed #ccc;"></div>
    <p class="text-center text-sm mt-2">📷 Scan a barcode using your phone's camera</p>
</div>

<script>
document.getElementById('toggleScanner').addEventListener('click', function () {
    let scanner = document.getElementById('scannerContainer');
    let input = document.getElementById('manualInputContainer');

    if (scanner.style.display === 'none') {
        scanner.style.display = 'block';
        input.style.display = 'none';
        this.textContent = '⌨️ Use Manual Input';
        startScanner(); // Start Quagga when scanner is shown
    } else {
        scanner.style.display = 'none';
        input.style.display = 'block';
        this.textContent = '📷 Use Camera Scanner';
        stopScanner(); // Stop Quagga when switching to manual
    }
});

// Quagga init (basic)
function startScanner() {
    Quagga.init({
        inputStream: {
            type: "LiveStream",
            target: document.querySelector('#scanner'),
            constraints: { facingMode: "environment" }
        },
        decoder: { readers: ["code_128_reader", "ean_reader"] }
    }, function(err) {
        if (err) { console.error(err); return; }
        Quagga.start();
    });

    Quagga.onDetected(function(data) {
        document.getElementById('barcodeInput').value = data.codeResult.code;
        alert('Scanned: ' + data.codeResult.code);
    });
}

function stopScanner() {
    if (Quagga.running) Quagga.stop();
}
</script>