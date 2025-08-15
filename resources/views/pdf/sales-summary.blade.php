<!DOCTYPE html>
<html>
<head>
    <title>Sales Summary PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; margin-bottom: 0; }
        small { display: block; text-align: center; margin-top: 0; }
    </style>
</head>
<body>
    <h2>Item Sales Summary</h2>
    <small>{{ now()->format('F j, Y h:i A') }}</small>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Total Qty Sold</th>
                <th>Total Sales (₱)</th>
                <th>Last Sold (Date & Time)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $sale)
            <tr>
                <td>{{ $index + 1 }}</td>
                {{-- <td>{{ $sale->item->name }}</td> --}}
                <td>{{ $sale->total_qty }}</td>
                <td>₱{{ number_format($sale->total_sales, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($sale->last_sold_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>