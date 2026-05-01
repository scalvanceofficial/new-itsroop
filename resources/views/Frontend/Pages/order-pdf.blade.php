<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            border: 0.5px solid #000;
            padding: 8px;
        }

        .header {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .two-column {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .two-column td {
            vertical-align: top;
            padding: 5px;
        }

        .two-column td:first-child {
            width: 60%;
        }

        .two-column td:last-child {
            width: 40%;
        }

        .bordered {
            border: 0.5px solid #000;
            padding: 5px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table td {
            padding: 5px;
            border: 1px solid #000;
        }

        .items-table,
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items-table th,
        .items-table td,
        .summary-table th,
        .summary-table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .items-table th {
            text-align: left;
        }

        .summary-table td {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .align-right {
            text-align: right;
        }

        .declaration {
            margin-top: 20px;
            font-size: 10px;
            text-align: left;
        }

        .signature {
            text-align: right;
            margin-top: 20px;
        }

        .footer {
            margin-top: 10px;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">Order Detail</div>

        <!-- Consignee, Buyer, and Invoice Details -->
        <table class="two-column">
            <tr>
                <!-- Left Section: Consignee and Buyer -->
                <td>
                    <div class="bordered">
                        <strong>{{ $order->user->first_name }} {{ $order->user->last_name }}</strong><br>
                        {{ $order->address->address_line_1 }}<br>
                        {{ $order->address->address_line_2 }}<br>
                        {{ $order->address->city }}<br>
                        {{ $order->address->pincode }}<br><br>

                    </div>
                </td>
                <!-- Right Section: Invoice Details -->
                <td>
                    <table class="details-table">
                        <tr>
                            <td><strong>Order No</strong></td>
                            <td>{{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>AWB Code</strong></td>
                            <td>{{ $order->awb_code }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date</strong></td>
                            <td>{{ toIndianDate($order->created_at) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Delivery Status</strong></td>
                            <td>{{ $order->shiprocket_status }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>


        <!-- Item Details -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>SR No</th>
                    <th>Description of Goods</th>
                    <th>HSN/SAC</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Amount</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($order->products as $product)
                    <tr>
                        <td>1</td>
                        <td>{{ $product->product->name }}</td>
                        <td>8471</td>
                        <td class="align-center">{{ $product->quantity }}</td>
                        <td class="align-right">{{ toCurrency($product->price, 'pdf') }}</td>
                        <td class="align-right">{{ toCurrency($product->price, 'pdf') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="summary-table">
            <tr>
                <th colspan="5" class="align-right">Total</th>
                <th class="align-right">{{ toCurrency($order->products->sum('price'), 'pdf') }}</th>
            </tr>
        </table>

        <!-- Declaration -->
        <div class="declaration">
            We declare that this invoice shows the actual price of the goods described and that all particulars are true
            and correct.
        </div>

        <!-- Signature -->
        <div class="signature">
            for Itsroop<br>
            Authorised Signatory
        </div>

        <!-- Footer -->
        <div class="footer">
            This is a Computer Generated Invoice
        </div>
    </div>
</body>

</html>
