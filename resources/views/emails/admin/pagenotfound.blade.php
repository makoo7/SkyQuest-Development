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
                        <p>You've got a new page not found request.</p>
                        <p>Page not found request details are as below:</p>
                        <p>Name : {!! (isset($pagenotfound->name)) ? $pagenotfound->name : '' !!}</p>
                        <p>Phone : {!! (isset($pagenotfound->phone)) ? $pagenotfound->phone : '' !!}</p>
                        <p>Email : {!! (isset($pagenotfound->email)) ? $pagenotfound->email : '' !!}</p>
                        <p>Country : {!! (isset($pagenotfound->country->name)) ? $pagenotfound->country->name : '' !!}</p>
                        <p>Company Name : {!! (isset($pagenotfound->company_name)) ? $pagenotfound->company_name : '' !!}</p>
                        <p>Designation : {!! (isset($pagenotfound->designation)) ? $pagenotfound->designation : '' !!}</p>
                        <p>Description : {!! (isset($pagenotfound->description)) ? $pagenotfound->description : '' !!}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:15px 0 5px; font-family:Arial, Helvetica, sans-serif;">
                        <p style="margin:0; font-size:13px; line-height:20px; font-family:Arial, Helvetica, sans-serif; color:#000;">Thank You, <br>{!! Config::get('app.name') !!}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@endsection