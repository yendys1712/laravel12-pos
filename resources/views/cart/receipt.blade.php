<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <script>
        //   window.onload = function () {
        //     window.print();
        //     window.onafterprint = function () {
        //         // Reload cart page in parent window and close this
        //         if (window.opener) {
        //             window.opener.location.href = "/cart";
        //             window.close();
        //         }
        //     };
        // };
        // window.onload = function () {
        //     window.print();

        //     // After print, return to cart in parent and close receipt tab
        //     window.onafterprint = function () {
        //         if (window.opener) {
                    
        //             window.opener.location.href = "{{ route('cart.view') }}";
        //             window.opener.location.reload(); 
        //         }
              
        //         window.close();
        //     };

        //     // Fallback if onafterprint fails
        //     setTimeout(() => {
        //         if (window.opener) {
                
        //          //   window.opener.location.href = "{{ route('cart.view') }}";
        //             window.opener.location.reload(); 
        //         }
              
        //         window.close();
        //     }, 4000);
        // };
     </script>



</head>
<body>
    <h2>🧾 Receipt</h2>
    <p>Sale ID: {{ $sale->id }}</p>
    <p>Date: {{ $sale->created_at->format('F j, Y g:i A') }}</p>

    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->pivot->quantity }}</td>
                <td>₱{{ number_format($item->pivot->price, 2) }}</td>
                <td>₱{{ number_format(($item->pivot->price - $item->pivot->discount) * $item->pivot->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total: ₱{{ number_format($sale->total_price, 2) }}</h3>
</body>
</html>
