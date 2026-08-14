<!DOCTYPE html>
<html>

<head>
    <title>emailer</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap');
    </style>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <div style="margin:auto; width:600px;background: #ffffff;">
        <table id="Table_01" width="600" border="0" cellpadding="0" cellspacing="0" align="center">

            <thead>
                <tr>
                    <td>
                        <table width="100%" style="margin: auto; background: #151516; height: 35px;">
                            <tbody>
                                <tr>
                                    <td>
                                        <h1
                                            style="color: #ffffff; font-size: 18px; font-weight: 400; text-align: center;margin: 0;font-family: 'Open Sans', sans-serif;">
                                            Order Confirmation</h1>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </thead>
            <tbody style="background: #000000;">
                <tr>
                    <td style="text-align:center;padding-top:25px;padding-bottom: 20px;background: #000000;">
                        <table align="center">
                            <tbody>
                                <tr>
                                    <td style="text-align: center;">
                                        <a style="display: block; margin: auto;" href="{{ route('home') }}"
                                            target="_blank" data-saferedirecturl="{{ route('home') }}">
                                            <img style="margin: auto;"
                                                src="{{ asset('frontend/images/mail-logo.png') }}" width="112"
                                                height="24">
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 0px 110px 0px; background: #151516;">
                        <table width="400" style="margin: auto; text-align: center;">
                            <tbody>
                                <tr>
                                    <td style="padding: 0px 0 45px;">
                                        <h1
                                            style="color: #fff; font-size: 20px;line-height: 27px; font-weight: 600; text-align: center; padding: 0 0 10px;margin: 0;font-family: 'Open Sans', sans-serif;">
                                            Hi {{ $order->user?->name }}</h1>
                                        <p
                                            style="text-align: center; margin: auto;font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 400; color: #F8F8F4; line-height: 26px; margin-top:0px; margin-bottom: 0px;">
                                            We're delighted to let you know that your
                                        </p>
                                        <p
                                            style="text-align: center; margin: auto;font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 400; color: #F8F8F4; line-height: 26px; margin-top:0px; margin-bottom: 0px;">
                                            desqoo order #{{ $order->uuid }} is Placed.
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0px 17px 0px;background: #151516;">
                        <table width="100%" style="margin: auto; background: #151516;">
                            <tbody>
                                <tr>
                                    <td style="background: #151516;padding: 0px;">
                                        <table width="100%" style="margin: auto; background: #151516;">
                                            <tbody>
                                                <tr>
                                                    <td style="padding: 25px 30px 25px;">
                                                        <h1
                                                            style="color: #fff; font-size: 18px;font-family: 'Open Sans', sans-serif; font-weight: 400; text-align: center; padding: 0 0 5px;margin: 0;">
                                                            View Your Order Details</h1>
                                                        <p
                                                            style="text-align: center; font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 400; color: #fff; line-height: 26px; margin-top:0px; margin-bottom: 0px;">
                                                            To see the status of your order visit
                                                            <a href="{{ route('my-account') }}"
                                                                style="text-align: center; font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 400; color: #fff; text-decoration: underline; line-height: 26px; margin-top:0px; margin-bottom: 0px;">
                                                                My Order
                                                            </a>
                                                            section in your account.
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0px 17px 0px;background: #151516;">
                        <table width="100%" style="margin: auto; background: #151516;">
                            <tbody>
                                <tr>
                                    <td style="background: #ffffff; padding: 25px 0 40px 30px;">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td style="margin: 0px; text-align: left;">
                                                        <p
                                                            style="font-size: 16px;color: #000000;font-family: 'Open Sans', sans-serif; font-weight: 700; padding-bottom: 15px; margin-bottom: 0px; margin-top: 0px; line-height: 22px; text-align: left;">
                                                            Delivery address
                                                        </p>
                                                        <p
                                                            style="font-size: 14px;font-family: 'Open Sans', sans-serif; color: #444444; font-weight: 400; margin-bottom: 0px; margin-top: 0px; line-height: 22px; text-align: left;">
                                                            Name:
                                                            <span
                                                                style="font-size: 14px;font-family: 'Open Sans', sans-serif; color: #444444; padding-bottom: 10px; font-weight: 400; margin-bottom: 0px; margin-top: 0px; line-height: 22px; text-align: left;">
                                                                {{ $order->shippingAddress->name }}
                                                            </span>
                                                        </p>
                                                        <p
                                                            style="font-size: 14px;color: #444444;font-family: 'Open Sans', sans-serif; font-weight: 400; margin-bottom: 0px; margin-top: 0px; line-height: 22px; text-align: left;">
                                                            Email:
                                                            <a href="mailto:steevdavid1@gmail.com"
                                                                style="font-size: 14px;color: #444444; text-decoration: none; padding-bottom: 10px; font-weight: 400; margin-bottom: 0px; margin-top: 0px; line-height: 22px; text-align: left;">
                                                                {{ $order->shippingAddress->email }}

                                                            </a>
                                                        </p>
                                                        <p
                                                            style="font-family: 'Open Sans', sans-serif;font-size: 14px;color: #444444; font-weight: 400; margin-bottom: 0px; margin-top: 0px; line-height: 22px; text-align: left;">
                                                            Phone Number:
                                                            <a href="tel:{{ $order->shippingAddress->formatted_phone_number }}"
                                                                style="font-family: 'Open Sans', sans-serif; font-size: 14px;color: #444444; text-decoration:
                                                                none; padding-bottom: 10px; font-weight: 400;
                                                                margin-bottom: 0px; margin-top: 0px; line-height: 22px;
                                                                text-align: left;">
                                                                {{ $order->shippingAddress->phone_number }}
                                                            </a>
                                                        </p>
                                                        <p
                                                            style="font-family: 'Open Sans', sans-serif; font-size: 14px;color: #444444; font-weight: 400; margin-bottom: 0px; margin-top: 0px; line-height: 22px; text-align: left;">
                                                            Address:
                                                            <span
                                                                style="font-family: 'Open Sans', sans-serif; font-size: 14px;color: #444444; padding-bottom: 10px; font-weight: 400; margin-bottom: 0px; margin-top: 0px; line-height: 22px; text-align: left;">
                                                                {{ $order->shippingAddress->address_line_1 }}, <br>
                                                                {{ $order->shippingAddress->address_line_2 }} <br>
                                                                {{ $order->shippingAddress->city }}, <br>
                                                                {{ $order->shippingAddress->state }}, <br>
                                                                {{ $order->shippingAddress->landmark }} <br>
                                                                {{ $order->shippingAddress->postal_code }}
                                                            </span>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px 17px 5px;background: #151516;">
                        <table width="100%" style="margin: auto; height: 30px;">
                            <tbody>
                                <tr>
                                    <td style="background: #151516;">
                                        <h1
                                            style="font-family: 'Open Sans', sans-serif; color: #ffffff; font-size: 14px; font-weight: 400; text-align: center;margin: 0;">
                                            Order Id: #{{ $order->uuid }}</h1>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>


                <tr>
                    <td style="padding: 0px 17px 0px;background: #151516;">
                        <table width="100%" style="margin: auto;">
                            <tbody>
                                <tr>
                                    <td style="background: #F8F8F4; padding: 10px 20px 20px 20px;margin: auto;">
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td style="margin: auto;">
                                                        @foreach ($order->orderItems as $orderItem)
                                                            <table
                                                                style="width: 100%; margin: auto; height: 40px; padding: 10px; background-color: #181818c4; margin-bottom: 2px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td
                                                                            style="width: 20%; margin-bottom: 0px; margin-top: 0px;">
                                                                            <img src="{{ $orderItem->productVariant->image_value ?? $orderItem->product->image_value }}"
                                                                                alt="{{ $orderItem->productVariant->image_alt_text ?? ($orderItem->product->image_alt_text ?? $orderItem->product->name) }}"
                                                                                width="67" height="67">
                                                                        </td>
                                                                        <td
                                                                            style="width: 60%; margin-bottom: 0px; margin-top: 0px;">
                                                                            <p
                                                                                style="font-size: 14px; font-family: 'Open Sans', sans-serif;color: #fff; font-weight: 600;margin: 0px; padding-bottom: 6px;">
                                                                                {{ $orderItem->product->name }}</p>
                                                                            <p
                                                                                style="font-size: 12px;font-family: 'Open Sans', sans-serif; color: #f4f4f4;font-weight: 400; margin: 0PX;">
                                                                                @foreach ($orderItem->productVariant->attributeValues as $attributeValue)
                                                                                    <div class="txt">
                                                                                        {{ $attributeValue->attribute->name }}:
                                                                                        <span>{{ $attributeValue->value }}</span>
                                                                                    </div>
                                                                                @endforeach
                                                                            </p>
                                                                            <p
                                                                                style="font-family: 'Open Sans', sans-serif;font-size: 10px; margin: 0px; color: #fff;">
                                                                                Qty: {{ $orderItem->quantity }}</p>
                                                                        </td>
                                                                        <td
                                                                            style="width: 20%; font-size: 12px;color: #000000; font-weight: 500; margin-bottom: 0px; margin-top: 0px; line-height: 22px; text-align: center;">
                                                                            <p
                                                                                style="font-family: 'Open Sans', sans-serif;font-size: 14px; color: #fff; margin: 0px;">
                                                                                ₹{{ $orderItem->total }}</p>
                                                                        </td>

                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td style="margin: auto;">
                                                        <table
                                                            style="width: 100%; margin: auto; height: 40px; padding: 0px; background-color: #F8F8F4; margin-bottom: 2px;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding: 0px;background: #F8F8F4;">
                                                                        <table width="100%"
                                                                            style="margin: auto; background: #F8F8F4;padding: 0 20px 15px;"
                                                                            border="0" cellpadding="0"
                                                                            cellspacing="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td
                                                                                        style="margin: 0px; text-align: left;">
                                                                                        <p
                                                                                            style="font-size: 14px;font-family: 'Open Sans', sans-serif; color: #000000; font-weight: 600; margin-bottom: 0px; margin-top: 0px; line-height: 22px; text-align: left;">
                                                                                            Price
                                                                                            ({{ $order->item_count }}
                                                                                            Items)
                                                                                        </p>
                                                                                    </td>
                                                                                    <td
                                                                                        style="margin: 0px; text-align: right;">
                                                                                        <p
                                                                                            style="font-family: 'Open Sans', sans-serif; font-size: 12px;color: #535353; font-weight: 400; margin-bottom: 0px; margin-top: 0px; line-height: 22px; text-align: right;">
                                                                                            ₹{{ $order->sub_total }}
                                                                                        </p>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <table width="100%"
                                                            style="padding-top: 15px; background: #F8F8F4; padding-bottom: 15px; padding: 0px 20px 10px 20px; margin: auto;"
                                                            border="0" cellpadding="0" cellspacing="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="margin: 0px; text-align: left;">
                                                                        <p
                                                                            style="font-family: 'Open Sans', sans-serif;font-size: 12px;color: #000000; font-weight: 400; margin-bottom: 0px; margin-top: 0px; line-height: 20px; text-align: left;">
                                                                            Discount
                                                                        </p>
                                                                        <p
                                                                            style="font-family: 'Open Sans', sans-serif;font-size: 11px;color: #000000; font-weight: 400; margin-bottom: 0px; margin-top: 0px; line-height: 20px; text-align: left;">
                                                                            Shipping Fee
                                                                        </p>
                                                                        <p
                                                                            style="font-family: 'Open Sans', sans-serif;font-size: 12px;color: #000000; font-weight: 400; margin-bottom: 0px; margin-top: 0px; line-height: 20px; text-align: left;">
                                                                            GST
                                                                            ({{ app('appSettings')->get('tax.percentage')->value }}%)
                                                                        </p>
                                                                    </td>
                                                                    <td style="margin: 0px; text-align: right;">
                                                                        <p
                                                                            style="font-family: 'Open Sans', sans-serif;font-size: 12px;color: #535353; font-weight: 400; margin-bottom: 0px; margin-top: 0px; line-height: 20px; text-align: right;">
                                                                            - ₹{{ $order->discount_amount }}
                                                                        </p>
                                                                        <p
                                                                            style="font-family: 'Open Sans', sans-serif;font-size: 12px;color: #535353; font-weight: 400; margin-bottom: 0px; margin-top: 0px; line-height: 20px; text-align: right;">
                                                                            ₹0.00
                                                                        </p>
                                                                        <p
                                                                            style="font-family: 'Open Sans', sans-serif;font-size: 12px;color: #535353; font-weight: 400; margin-bottom: 0px; margin-top: 0px; line-height: 20px; text-align: right;">
                                                                            ₹{{ $order->tax_amount }}
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <table width="100%" style="border-spacing:0px; ">
                                                            <tr>
                                                                <td style="padding: 0 20px; background: #F8F8F4;">
                                                                    <table width="100%" style=" margin: auto; "
                                                                        border="0" cellpadding="0"
                                                                        cellspacing="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td
                                                                                    style="padding: 15px 0; margin: 0px; text-align: left; border-top: 1px solid #EFEFEC;">
                                                                                    <p
                                                                                        style="font-family: 'Open Sans', sans-serif;font-size: 14px;color: #000000; font-weight: 500; margin-bottom: 0px; margin-top: 0px; line-height: 22px; text-align: left;">
                                                                                        Total
                                                                                    </p>
                                                                                </td>
                                                                                <td
                                                                                    style="padding: 15px 0; margin: 0px; text-align: right; border-top: 1px solid #EFEFEC;">
                                                                                    <p
                                                                                        style="font-family: 'Open Sans', sans-serif;font-size: 12px;color: #031717; font-weight: 700; margin-bottom: 0px; margin-top: 0px; line-height: 22px; text-align: right;">
                                                                                        ₹{{ $order->grand_total }}
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 0px 110px 0px;background-color: #151516;">
                        <table width="380" style="margin: auto;">
                            <tbody>
                                <tr>
                                    <td style="background: #151516; border-radius: 5px;">
                                        <table width="380" style="margin: auto;">
                                            <tbody>
                                                <tr>
                                                    <td style="padding: 0px 0 35px;">
                                                        <h1
                                                            style="display: block; color: #fff;font-family: 'Open Sans', sans-serif; text-transform: uppercase; font-size: 14px; font-weight: 500; text-align: center; padding: 0 0 15px;margin: 0;line-height: 30px;">
                                                            WE'RE HERE TO HELP</h1>
                                                        <p
                                                            style="text-align: center;font-family: 'Open Sans', sans-serif; margin: auto; width: 285px; font-size: 14px; font-weight: 400; color: #f4f4f4; line-height: 26px; margin-top:0px; margin-bottom: 0px;">
                                                            {{ $siteSettings->customer_care_info }}
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <table width="380" border="0" cellpadding="0" cellspacing="0"
                                            style="margin: auto; margin-top: 0px;">
                                            <tbody>
                                                <tr>

                                                    <td
                                                        style="margin: 0px; text-align: right; width:100%; padding: 0 0 35px;">
                                                        <table style="width: 100%;">
                                                            <tbody>
                                                                <tr>
                                                                    @if ($siteSettings->phone_number != null)
                                                                        <td style="width: 33.3%;">
                                                                            <table>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td
                                                                                            style="width: 26px; text-align: right; padding: 0px;">
                                                                                            <img src="{{ asset('frontend/images/call.png') }}"
                                                                                                width="26"
                                                                                                height="26"
                                                                                                style="object-fit: contain;">
                                                                                        </td>
                                                                                        <td
                                                                                            style="padding-left: 15px; padding-right: 0px; font-size: 13px; color: #2f2f2f; font-weight: 400; margin: 0px; text-align: left;">
                                                                                            <a href="tel:{{ $siteSettings->formatted_phone_number }}"
                                                                                                style="display: block;font-family: 'Open Sans', sans-serif; text-decoration: none; font-size: 14px; color: #F8F8F4; font-weight: 400; margin: 0px; text-align: left;">
                                                                                                Call us
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    @endif
                                                                    @if ($siteSettings->whatsapp_number != null)
                                                                        <td style="width: 33.3%;">
                                                                            <table>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td
                                                                                            style="width: 26px; text-align: right; padding: 0px;">
                                                                                            <img src="{{ asset('frontend/images/whtsup.png') }}"
                                                                                                width="26"
                                                                                                height="26"
                                                                                                style="object-fit: contain;">
                                                                                        </td>
                                                                                        <td
                                                                                            style="padding-left: 15px; padding-right: 0px; font-size: 13px; color: #2f2f2f; font-weight: 400; margin: 0px; text-align: left;">
                                                                                            <a href="https://wa.me/{{ $siteSettings->formatted_whatsapp_number }}"
                                                                                                style="display: block;font-family: 'Open Sans', sans-serif; text-decoration: none; font-size: 14px; color: #F8F8F4; font-weight: 400; margin: 0px; text-align: left;">
                                                                                                Whatsapp
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    @endif
                                                                    @if ($siteSettings->email != null)
                                                                        <td style="width: 33.3%;">
                                                                            <table>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td
                                                                                            style="width: 26px; padding: 0px;">
                                                                                            <img src="{{ asset('frontend/images/mail.png') }}"
                                                                                                width="26"
                                                                                                height="26"
                                                                                                style="object-fit: contain;">
                                                                                        </td>
                                                                                        <td
                                                                                            style="padding-left: 15px; padding-right: 0px; font-size: 13px; color: #2f2f2f; font-weight: 400; margin: 0px; text-align: right;">
                                                                                            <a href="mailto:{{ $siteSettings->email }}"
                                                                                                style="display: block;font-family: 'Open Sans', sans-serif; text-decoration: none; font-size: 14px; color: #F8F8F4; font-weight: 400; margin: 0px; text-align: right;">
                                                                                                Send Email
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>
                        <table width="100%" style="margin: auto; background: #000; height: 35px;">
                            <tbody>
                                <tr>
                                    <td style="background: #000;">
                                        <table width="100%" style="margin: auto;">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p
                                                            style="color: #ffffff; font-size: 14px;font-family: 'Open Sans', sans-serif; font-weight: 400; text-align: center; padding: 0 0 0px;margin: 0;">
                                                            Copyright © {{ date('Y') }} Desqoo All Rights Reserved.
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
