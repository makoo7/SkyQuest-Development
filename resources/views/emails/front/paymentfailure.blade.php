@extends('emails.layouts.app')
@section('content')

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" style="background:#fff; padding:25px 30px; border-radius:10px; -webkit-border-radius:10px; -o-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;">
            <table width="100%" style="margin:0;">
                <tr>
                    <td align="left" style="font-size:14px; color:#000; padding:0 0 15px; font-family:Arial, Helvetica, sans-serif;">Hello {!! (isset($paymentfailure->name)) ? $paymentfailure->name : '' !!}, </td>
                </tr>
                <tr>
                    <td align="left" style="font-size:13px; line-height:23px; color:#000; padding:0 0 12px; font-family:Arial, Helvetica, sans-serif;">
                        <p>Thank you so much for showing interest in buying our research report <a href="{!! (isset($paymentfailure->report->slug)) ? url('report/'.$paymentfailure->report->slug) : '' !!}">{!! (isset($paymentfailure->report->name)) ? $paymentfailure->report->name : '' !!}</a></p>
                        <p>Unfortunately, your payment attempt got failed. But don't worry at all...!!</p>
                        <p>We are here to help you. Please try again at <a href="{!! (isset($paymentfailure->report->slug)) ? url('buy-now/'.$paymentfailure->report->slug) : '' !!}">{!! (isset($paymentfailure->report->name)) ? $paymentfailure->report->name : '' !!}</a></p>
                        <p>If you still face any issues in payment process, our team will definitely get in touch with you.</p>
                        <p>This report has been prepared by top industry analyst using a top down and bottom-up approach and further triangulating the data.</p>
                        <p>Let us know a convenient time for a quick call to better understand and cater to your requirement in case you would require custom data points or formats.</p>
                        <p>You can also email me about specific aspects that you are looking for or contact us on +1-617-230-0741.</p>
                        <p>Looking forward to hearing from you.</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:15px 0 5px; font-family:Arial, Helvetica, sans-serif;">
                        <p style="margin:0; font-size:13px; line-height:20px; font-family:Arial, Helvetica, sans-serif; color:#000;">Regards, <br>Team SkyQuest</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@endsection