<!DOCTYPE html>
<html lang="en" dir="ltr"> {{-- Changed language to English and direction to Left-to-Right --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->id }} - Invoice</title> {{-- Translated title --}}
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .invoice-header h1 {
            margin: 0;
            color: #444;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-info div {
            flex: 1;
        }
        .info-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left; /* Adjusted for LTR */
        }
        td {
             text-align: left; /* Adjusted for LTR */
        }
         /* Align numeric columns to the right */
        td:nth-child(3), /* Price */
        td:nth-child(4), /* Quantity */
        td:nth-child(5) { /* Item Total */
            text-align: right;
        }
         tfoot td {
            text-align: right; /* Ensure footer totals are right-aligned */
        }

        .total-row {
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 14px;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>Invoice for Order #{{ $order->id }}</h1> {{-- Translated header title --}}
        <p>Order Date: {{ $order->created_at->format('Y-m-d H:i') }}</p> {{-- Translated date text --}}
    </div>

    <div class="invoice-info">
        <div>
            <div class="info-title">Store Information:</div> {{-- Translated title --}}
            <p>Online Store Name</p> {{-- Placeholder text --}}
            <p>Address: Street, City, Country</p> {{-- Placeholder text --}}
            <p>Email: info@example.com</p> {{-- Placeholder text --}}
            <p>Phone: +123456789</p> {{-- Placeholder text --}}
        </div>

        <div style="text-align: right;"> {{-- Aligned right for LTR layout --}}
            <div class="info-title">Customer Information:</div> {{-- Translated title --}}
            <p>{{ $order->user->first_name }} {{ $order->user->last_name }}</p>
            <p>Email: {{ $order->user->email }}</p> {{-- Translated label --}}
            <p>Phone: {{ $order->user->phone_number ?? 'Not available' }}</p> {{-- Translated label and text --}}
            <p>Shipping Address: {{ $order->shipping_address }}</p> {{-- Translated label --}}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th> {{-- Translated header --}}
                <th style="text-align: right;">Price</th> {{-- Translated header and aligned right --}}
                <th style="text-align: right;">Quantity</th> {{-- Translated header and aligned right --}}
                <th style="text-align: right;">Total</th> {{-- Translated header and aligned right --}}
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td>JOD {{ number_format($item->price_at_order, 2) }}</td> {{-- Currency changed --}}
                <td>{{ $item->quantity }}</td>
                <td>JOD {{ number_format($item->price_at_order * $item->quantity, 2) }}</td> {{-- Currency changed --}}
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4">Grand Total:</td> {{-- Translated label --}}
                <td>JOD {{ number_format($order->total_amount, 2) }}</td> {{-- Currency changed --}}
            </tr>
        </tfoot>
    </table>

    <div>
        <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p> {{-- Translated label --}}
        <p><strong>Order Status:</strong> {{-- Translated label --}}
            @switch($order->status)
                @case('pending')
                    Pending {{-- Translated status --}}
                    @break
                @case('processing')
                    Processing {{-- Translated status --}}
                    @break
                @case('shipped')
                    Shipped {{-- Translated status --}}
                    @break
                @case('delivered')
                    Delivered {{-- Translated status --}}
                    @break
                @case('cancelled')
                    Cancelled {{-- Translated status --}}
                    @break
                @default
                    {{ $order->status }}
            @endswitch
        </p>
    </div>

    <div class="footer">
        <p>Thank you for shopping with us!</p> {{-- Translated footer text --}}
    </div>

    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <button onclick="window.print();" style="padding: 10px 20px; cursor: pointer;">Print Invoice</button> {{-- Translated button text --}}
        <button onclick="window.close();" style="padding: 10px 20px; margin-right: 10px; cursor: pointer;">Close</button> {{-- Translated button text --}}
    </div>
</body>
</html>