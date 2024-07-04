<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        /* CSS for styling the invoice */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        /* .invoice {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        } */

        .invoice h1 {
            text-align: center;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        .invoice-details div {
            margin-bottom: 5px;
        }

        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-items th,
        .invoice-items td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .invoice-items th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
@php
    $total = 0;
@endphp

<body>

    <h1>Invoice</h1>
    <div class="invoice-details">
        <div>Invoice Number: #{{ mt_rand(0, 99999) }}</div>
        <div>Date: April 18, 2024</div>
        <div>Customer Name: {{ $invoice['name'] }} </div>
        <div>Customer Email: {{ $invoice['email'] }}</div>
    </div>
    <table class="invoice-items">
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice['items'] as $item)
                @php
                    $total += $item->price * $item->quantity * (1 - $item->discount / 100);
                @endphp
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ $item->price }}</td>
                    <td>{{ $item->discount }}%</td>
                    <td>{{ $item->price * $item->quantity * (1 - $item->discount / 100) }}%</td>
                </tr>
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;">Total:</td>
                <td>${{ $total }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
