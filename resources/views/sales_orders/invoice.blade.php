<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Invoice #{{ $sales_order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        h2, .header { text-align: center; }
    </style>
</head>
<body>
    <h2>Sales Invoice</h2>

    <p><strong>Invoice No:</strong> #{{ $sales_order->id }}</p>
    <p><strong>Customer:</strong> {{ $sales_order->customer_name }}</p>
    <p><strong>Date:</strong> {{ $sales_order->created_at->format('d M Y, h:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th style="text-align: center !important;">#</th>
                <th>Product</th>
                <th>Unit Price (₹)</th>
                <th>Quantity</th>
                <th>Subtotal (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales_order->items as $index => $item)
                <tr>
                    <td style="text-align: center !important;">{{ $index + 1 }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td style="text-align: right; !important;">{{ number_format($item->price, 2) }}</td>
                    <td style="text-align: center !important;" >{{ $item->quantity }}</td>
                    <td style="text-align: right !important;">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align: right;">Total:</th>
                <th style="text-align: right !important;">₹ {{ number_format($sales_order->total, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
