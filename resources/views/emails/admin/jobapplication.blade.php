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
                        <p>You've got a new job application request.</p>
                        <p>Application details are as below:</p>
                        <p>Name : {!! (isset($jobapplication->name)) ? $jobapplication->name : '' !!}</p>
                        <p>Phone : {!! (isset($jobapplication->phone)) ? $jobapplication->phone : '' !!}</p>
                        <p>Email : {!! (isset($jobapplication->email)) ? $jobapplication->email : '' !!}</p>
                        <p>Work Experience : {!! (isset($jobapplication->work_experience)) ? $jobapplication->work_experience : '' !!}</p>
                        <p>Notice Period : {!! (isset($jobapplication->notice_period)) ? $jobapplication->notice_period : '' !!}</p>
                        <p>Current CTC : {!! (isset($jobapplication->current_ctc)) ? $jobapplication->current_ctc : '' !!}</p>
                        <p>Expected CTC : {!! (isset($jobapplication->expected_ctc)) ? $jobapplication->expected_ctc : '' !!}</p>
                        <p>Portfolio/Web : @if(isset($jobapplication->portfolio_or_web))<a href="{!! $jobapplication->portfolio_or_web !!}" target="_blank">{!! $jobapplication->portfolio_or_web !!}</a>@endif</p>
                        <p>Resume : @if(isset($jobapplication->resume))<a href="{!! url('careers/download/' . $jobapplication->id) !!}" target="_blank">Click here to download the Resume</a>@endif</p>
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