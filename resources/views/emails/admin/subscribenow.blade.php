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
                        <p>You've got a new subscribe request for the Report: {!! (isset($subscribenow->report->name)) ? $subscribenow->report->name : '' !!}</p>
                        <p>Details are as below:</p>
                        <p>Plan : {!! (isset($subscribenow->plan)) ? $subscribenow->plan : '' !!}</p>
                        <p>Name : {!! (isset($subscribenow->name)) ? $subscribenow->name : '' !!}</p>
                        @if(isset($subscribenow->email))
                        <p>Email : <a href="mailto:{!! $subscribenow->email !!}">{!! $subscribenow->email !!}</a></p>
                        @endif
                        <p>Phone : {!! (isset($subscribenow->phonecode)) ? $subscribenow->phonecode : '' !!}{!! (isset($subscribenow->phone)) ? $subscribenow->phone : '' !!}</p>
                        <p>Company Name : {!! (isset($subscribenow->company_name)) ? $subscribenow->company_name : '' !!}</p>
                        <p>Designation : {!! (isset($subscribenow->designation)) ? $subscribenow->designation : '' !!}</p>
                        <p>Country : {!! (isset($subscribenow->country->name)) ? $subscribenow->country->name : '' !!}</p>
                        <p>Linkedin Link :@if(isset($subscribenow->linkedin_link)) <a href="{!! $subscribenow->linkedin_link !!}" target="_blank">{!! $subscribenow->linkedin_link !!}</a> @endif</p>
                        <p>Message : {!! (isset($subscribenow->message)) ? $subscribenow->message : '' !!}</p>
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