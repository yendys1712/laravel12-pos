<!DOCTYPE html>
<html>
<head>
    <title>Checkout Success</title>
    <script>
        window.onload = function () {
            // Open receipt popup
            window.open("{{ route('cart.receipt', $sale->id) }}", "_blank", "width=600,height=700");

            // Optional: return to cart after delay
            setTimeout(function () {
                window.location.href = "{{ view('cart') }}"; // or your cart route
            }, 3000);
        };
    </script>
</head>
<body>
    <h2 class="text-green-600 text-xl">✅ Checkout Complete!</h2>
    <p>Printing receipt...</p>
</body>
</html>