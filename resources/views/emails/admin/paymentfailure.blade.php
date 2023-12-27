@extends('emails.layouts.app')
@section('content')

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" style="background:#fff; padding:25px 30px; border-radius:10px; -webkit-border-radius:10px; -o-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;">
            <table width="100%" style="margin:0;">
                <tr>
                    <td align="left" style="font-size:14px; color:#000; padding:0 0 15px; font-family:Arial, Helvetica, sans-serif;">Hi Admin, </td>
                </tr>
                <tr>
                    <td align="left" style="font-size:13px; line-height:23px; color:#000; padding:0 0 12px; font-family:Arial, Helvetica, sans-serif;">
                        <p>The user has tried the payment but it's failed.</p>
                        <p>Please find the report details below:</p>
                        <p>User Name : {!! (isset($paymentfailure->name)) ? $paymentfailure->name : '' !!}</p>                        
                        <p>Report Name : {!! (isset($paymentfailure->report->name)) ? $paymentfailure->report->name : '' !!}</p>
                        <p>License Type : {!! (isset($paymentfailure->license_type)) ? $paymentfailure->license_type : '' !!} @if(isset($paymentfailure->file_type)) ({!! $paymentfailure->file_type !!})@endif</p>
                        <p>Report Price : ${!! (isset($paymentfailure->price)) ? $paymentfailure->price : '' !!}</p>

                        <p><a href="{!! url('admin/report-order/view/'.$paymentfailure->id) !!}">Click here</a> to see the order details.</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:15px 0 5px; font-family:Arial, Helvetica, sans-serif;">
                        <p style="margin:0; font-size:13px; line-height:20px; font-family:Arial, Helvetica, sans-serif; color:#000;">Thanks Your, <br>Support Team</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@endsection