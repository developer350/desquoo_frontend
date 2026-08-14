<!DOCTYPE html>
<html>

<head>
    <title>New Enquiry Notification</title>
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
                                            New Enquiry Received From {{ $enquiryType }}
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
                    <td style="padding: 25px 40px 30px;background-color: #151516;">
                        <table width="520" style="margin: auto;">
                            <tbody>
                                <tr>
                                    <td style="padding: 0 0 20px;">
                                        <h2
                                            style="color: #ffffff; font-size: 18px; font-weight: 600; text-align: center; margin: 0;font-family: 'Open Sans', sans-serif;">
                                            Enquiry Details</h2>
                                        <p
                                            style="text-align: center; margin: 10px auto 0;font-family: 'Open Sans', sans-serif; font-size: 13px; font-weight: 400; color: #f4f4f4;">
                                            Received on {{ $enquiry->created_at->format('F d, Y \a\t h:i A') }}
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table width="520" style="margin: auto; background-color: #1f1f20; border-radius: 8px;"
                            cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td style="padding: 20px;">
                                        <table width="100%" style="border-collapse: collapse;">
                                            <tbody>
                                                <tr>
                                                    <td
                                                        style="padding: 12px 15px; border-bottom: 1px solid #2a2a2b; width: 35%;">
                                                        <p
                                                            style="margin: 0; font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 600; color: #ffffff;">
                                                            Name:
                                                        </p>
                                                    </td>
                                                    <td style="padding: 12px 15px; border-bottom: 1px solid #2a2a2b;">
                                                        <p
                                                            style="margin: 0; font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 400; color: #f4f4f4;">
                                                            {{ $enquiry->name }}
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 12px 15px; border-bottom: 1px solid #2a2a2b;">
                                                        <p
                                                            style="margin: 0; font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 600; color: #ffffff;">
                                                            Email:
                                                        </p>
                                                    </td>
                                                    <td style="padding: 12px 15px; border-bottom: 1px solid #2a2a2b;">
                                                        <p
                                                            style="margin: 0; font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 400; color: #f4f4f4;">
                                                            <a href="mailto:{{ $enquiry->email }}"
                                                                style="color: #4a9eff; text-decoration: none;">{{ $enquiry->email }}</a>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 12px 15px; border-bottom: 1px solid #2a2a2b;">
                                                        <p
                                                            style="margin: 0; font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 600; color: #ffffff;">
                                                            Phone:
                                                        </p>
                                                    </td>
                                                    <td style="padding: 12px 15px; border-bottom: 1px solid #2a2a2b;">
                                                        <p
                                                            style="margin: 0; font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 400; color: #f4f4f4;">
                                                            <a href="tel:{{ $enquiry->formatted_phone_number }}"
                                                                style="color: #4a9eff; text-decoration: none;">{{ $enquiry->phone_number }}</a>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table width="520" style="margin: 25px auto 0;">
                            <tbody>
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ $enquiryType == 'Bulk Order' ? route('bulk-order-enquiries.index') : route('office-enquiries.index') }}"
                                            target="_blank"
                                            style="display: inline-block; padding: 12px 30px; background-color: #4a9eff; color: #ffffff; text-decoration: none; font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 600; border-radius: 5px;">
                                            View All Enquiries
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 25px 40px;background-color: #151516;">
                        <table width="520" style="margin: auto;">
                            <tbody>
                                <tr>
                                    <td style="text-align: center;">
                                        <p
                                            style="margin: 0; font-family: 'Open Sans', sans-serif; font-size: 13px; font-weight: 400; color: #b0b0b0; line-height: 20px;">
                                            This is an automated notification. Please respond to the customer as soon as
                                            possible.
                                        </p>
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
