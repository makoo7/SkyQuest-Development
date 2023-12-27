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
                        <p>You've got a new inquiry for the Report: {!! (isset($report_inquiry->report->name)) ? $report_inquiry->report->name : '' !!}</p>
                        <p>Details are as below:</p>
                        <p>Name : {!! (isset($report_inquiry->name)) ? $report_inquiry->name : '' !!}</p>
                        @if(isset($report_inquiry->email))
                        <p>Email : <a href="mailto:{!! $report_inquiry->email !!}">{!! $report_inquiry->email !!}</a></p>
                        @endif
                        <p>Phone : {!! (isset($report_inquiry->phonecode)) ? $report_inquiry->phonecode : '' !!}{!! (isset($report_inquiry->phone)) ? $report_inquiry->phone : '' !!}</p>
                        <p>Company Name : {!! (isset($report_inquiry->company_name)) ? $report_inquiry->company_name : '' !!}</p>
                        <p>Designation : {!! (isset($report_inquiry->designation)) ? $report_inquiry->designation : '' !!}</p>
                        <p>Country : {!! (isset($report_inquiry->country->name)) ? $report_inquiry->country->name : '' !!}</p>
                        <p>Linkedin Link :@if(isset($report_inquiry->linkedin_link)) <a href="{!! $report_inquiry->linkedin_link !!}" target="_blank">{!! $report_inquiry->linkedin_link !!}</a> @endif</p>
                        <p>Message : {!! (isset($report_inquiry->message)) ? $report_inquiry->message : '' !!}</p>
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