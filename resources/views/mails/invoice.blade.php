<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', 'sans-serif';
            margin: 0;
            padding: 0;
            background: #FFFFFF;
        }

        * {
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background: #151516;
        }

        .header-section {
            background: #000;
            padding: 35px 20px;
            border-bottom: 1px solid #D9D9D9;
        }

        .header-content {
            width: 100%;
        }

        .header-content table {
            width: 100%;
        }

        .logo-cell {
            width: 50%;
            text-align: left;
            vertical-align: middle;
        }

        .invoice-info-cell {
            width: 50%;
            text-align: right;
            vertical-align: middle;
        }

        .logo-img {
            max-width: 151px;
            height: auto;
        }

        .invoice-title {
            font-size: 20px;
            line-height: 20px;
            margin: 0 0 10px 0;
            font-weight: 600;
            color: #f8f8f8;
        }

        .invoice-detail {
            font-size: 15px;
            line-height: 18px;
            margin: 0 0 5px 0;
            color: #f8f8f8;
        }

        .billing-section {
            padding: 0 20px 24px 20px;
            background: #151516;
        }

        .billing-title {
            font-size: 15px;
            line-height: 20px;
            margin: 24px 0 12px 0;
            font-weight: 600;
            color: #f8f8f8;
        }

        .billing-name {
            font-size: 15px;
            line-height: 20px;
            margin: 10px 0 3px 0;
            font-weight: 400;
            color: #f8f8f8;
        }

        .billing-detail {
            font-size: 15px;
            line-height: 18px;
            margin: 0 0 3px 0;
            color: #f8f8f8;
        }

        .items-section {
            padding: 0 20px;
            background: #151516;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 0 0;
        }

        .items-header {
            background: #151516;
        }

        .items-header th {
            font-size: 14px;
            line-height: 20px;
            padding: 9px 20px;
            font-weight: 400;
            color: #ffffff;
            text-align: left;
        }

        .items-header th.center {
            text-align: center;
        }

        .items-header th.right {
            text-align: right;
        }

        .item-row {
            background: #f8f8f8;
        }

        .item-row td {
            font-size: 14px;
            line-height: 20px;
            padding: 15px 20px;
            color: #292929;
            vertical-align: top;
        }

        .item-name {
            font-size: 15px;
            font-weight: 600;
            color: #333E48;
            margin: 0 0 5px 0;
        }

        .item-variant {
            font-size: 13px;
            font-weight: 400;
            color: #333E48;
        }

        .totals-section {
            padding: 0 20px 5px 20px;
            background: #151516;
        }

        .totals-wrapper {
            background: #f8f8f8;
            padding: 30px 20px 35px 20px;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid #d8d8d8;
            padding-top: 25px;
        }

        .totals-row td {
            font-size: 14px;
            line-height: 20px;
            padding: 5px 20px 10px 0;
            color: #141312;
        }

        .totals-row.discount td {
            padding-bottom: 20px;
        }

        .totals-row.grand-total td {
            font-weight: 700;
            padding-top: 20px;
            border-top: 1px solid #d8d8d8;
        }

        .totals-row td:first-child {
            text-align: left;
            width: 50%;
        }

        .totals-row td:last-child {
            text-align: right;
            width: 50%;
        }

        .footer-section {
            background: #000;
            padding: 12px 0;
        }

        .footer-text {
            color: #ffffff;
            font-size: 14px;
            font-weight: 400;
            text-align: center;
            margin: 0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header-section">
            <table class="header-content" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="logo-cell">
                        <img src="{{ 'data:image/png;base64,' . $logoBase64 }}" class="logo-img" alt="Logo">
                    </td>
                    <td class="invoice-info-cell">
                        <h5 class="invoice-title">Invoice</h5>
                        <p class="invoice-detail">Invoice No: #{{ $order->uuid }}</p>
                        <p class="invoice-detail">Date: {{ Carbon\Carbon::parse($order->date)->format('d-m-Y') }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Billing Section -->
        <table width="100%" cellpadding="0" cellspacing="0" border="0"
            style="background: #151516; padding: 0 20px 24px 20px;">
            <tr>
                <!-- Bill To - Left -->
                <td width="50%" valign="top" style="padding: 24px 10px 0 20px;">
                    <h6 style="font-size: 15px; font-weight: 600; color: #f8f8f8; margin: 0 0 12px 0;">Bill To</h6>
                    <h6 style="font-size: 15px; font-weight: 400; color: #f8f8f8; margin: 0 0 3px 0;">
                        {{ $order->billingAddress->name }}</h6>
                    <p style="font-size: 15px; color: #f8f8f8; margin: 0 0 3px 0;">{{ $order->billingAddress->email }}</p>
                    <p style="font-size: 15px; color: #f8f8f8; margin: 0 0 3px 0;">
                        {{ $order->billingAddress->phone_number }}
                    </p>
                    <p style="font-size: 15px; color: #f8f8f8; margin: 0 0 3px 0;">
                        {{ $order->billingAddress->address_line_1 }}
                    </p>
                    <p style="font-size: 15px; color: #f8f8f8; margin: 0 0 3px 0;">
                        {{ $order->billingAddress->address_line_2 }}
                    </p>
                    <p style="font-size: 15px; color: #f8f8f8; margin: 0 0 3px 0;">
                        {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }}
                        {{ $order->billingAddress->postal_code }}
                    </p>
                </td>

                <!-- Ship To - Right -->
                <td width="50%" valign="top" style="padding: 24px 20px 0 10px;">
                    <h6 style="font-size: 15px; font-weight: 600; color: #f8f8f8; margin: 0 0 12px 0;">Ship To</h6>
                    <h6 style="font-size: 15px; font-weight: 400; color: #f8f8f8; margin: 0 0 3px 0;">
                        {{ $order->shippingAddress->name }}</h6>
                    <p style="font-size: 15px; color: #f8f8f8; margin: 0 0 3px 0;">{{ $order->shippingAddress->email }}</p>
                    <p style="font-size: 15px; color: #f8f8f8; margin: 0 0 3px 0;">
                        {{ $order->shippingAddress->phone_number }}
                    </p>
                    <p style="font-size: 15px; color: #f8f8f8; margin: 0 0 3px 0;">
                        {{ $order->shippingAddress->address_line_1 }}</p>
                    <p style="font-size: 15px; color: #f8f8f8; margin: 0 0 3px 0;">
                        {{ $order->shippingAddress->address_line_2 }}</p>
                    <p style="font-size: 15px; color: #f8f8f8; margin: 0 0 3px 0;">
                        {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}
                        {{ $order->shippingAddress->postal_code }}
                    </p>
                </td>
            </tr>
        </table>

        <!-- Items Section -->
        <div class="items-section">
            <table class="items-table" cellpadding="0" cellspacing="0">
                <thead class="items-header">
                    <tr>
                        <th style="width: 50%;">Item</th>
                        <th class="center" style="width: 10%;">Qty</th>
                        <th class="center" style="width: 20%;">Price</th>
                        <th class="right" style="width: 20%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $orderItem)
                        <tr class="item-row">
                            <td>
                                <div class="item-name">{{ $orderItem->product->name }}</div>
                                @foreach ($orderItem->productVariant->attributeValues as $attributeValue)
                                    <div class="item-variant">
                                        {{ $attributeValue->attribute->name }}: {{ $attributeValue->value }}
                                    </div>
                                @endforeach
                            </td>
                            <td class="text-center">{{ $orderItem->quantity }}</td>
                            <td class="text-center">₹{{ $orderItem->price }}</td>
                            <td class="text-right">₹{{ $orderItem->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals Section -->
        <div class="totals-section">
            <div class="totals-wrapper">
                <table class="totals-table" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr class="totals-row">
                            <td>Subtotal</td>
                            <td>₹{{ $order->sub_total }}</td>
                        </tr>
                        <tr class="totals-row discount">
                            <td>Discount</td>
                            <td>₹{{ $order->discount_amount }}</td>
                        </tr>
                        <tr class="totals-row">
                            <td>Tax</td>
                            <td>₹{{ $order->tax_amount }}</td>
                        </tr>
                        <tr class="totals-row grand-total">
                            <td>Grand Total</td>
                            <td>₹{{ $order->grand_total }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer-section">
            <p class="footer-text">Copyright © {{ date('Y') }} Desqoo All Rights Reserved.</p>
        </div>
    </div>
</body>

</html>
