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
                                            Thank you for subscribing to our newsletter
                                        </h1>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </thead>
            <tbody>
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
                    <td style="padding: 0px 110px 0px;background-color: #151516;">
                        <table width="400" style="margin: auto; text-align: center;">
                            <tbody>
                                <tr>
                                    <td style="padding: 0px 0 85px;">

                                        <h1
                                            style="color: #ffffff; font-size: 20px;line-height: 27px; font-weight: 600; text-align: center; padding: 0 0 10px;margin: 0;font-family: 'Open Sans', sans-serif;">
                                            Hi {{ $newsletter->email }}</h1>
                                        <p
                                            style="text-align: center; margin: auto;font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 400; color: #f4f4f4; line-height: 26px; margin-top:0px; margin-bottom: 0px;">
                                            it's greetings from desqoo!
                                        </p>
                                        <p
                                            style="text-align: center;font-family: 'Open Sans', sans-serif; margin: auto; font-size: 14px; font-weight: 400; color: #f4f4f4; line-height: 26px; margin-top:0px; margin-bottom: 0px;">
                                            You have successfully subscribed to our newsletter.
                                        </p>
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
                                    <td style="background-color: #151516; border-radius: 5px;">
                                        <table width="380" style="margin: auto;">
                                            <tbody>
                                                <tr>
                                                    <td style="padding: 0px 0 35px;">
                                                        <h1
                                                            style="display: block; color: #fff;font-family: 'Open Sans', sans-serif; text-transform: uppercase; font-size: 14px; font-weight: 500; text-align: center; padding: 0 0 15px;margin: 0;line-height: 30px;">
                                                            WE'RE HERE TO HELP</h1>
                                                        <p
                                                            style="text-align: center;font-family: 'Open Sans', sans-serif; margin: auto; width: 285px; font-size: 14px; font-weight: 400; color: #F8F8F4; line-height: 26px; margin-top:0px; margin-bottom: 0px;">
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
                        <table width="100%"
                            style="margin: auto;height: 35px;padding: 8px 0 0;background-color: #000;">
                            <tbody>
                                <tr>
                                    <td style="background-color: #000;">
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
