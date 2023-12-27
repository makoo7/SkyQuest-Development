@extends('emails.layouts.app')
@section('content')

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" style="background:#fff; padding:25px 30px; border-radius:10px; -webkit-border-radius:10px; -o-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;">
            <table width="100%" style="margin:0;">
                <tr>
                    <td align="left" style="font-size:14px; color:#000; padding:0 0 15px; font-family:Arial, Helvetica, sans-serif;">Hello {!! (isset($report_inquiry->name)) ? $report_inquiry->name : '' !!}, </td>
                </tr>
                <tr>
                    <td align="left" style="font-size:13px; line-height:23px; color:#000; padding:0 0 12px; font-family:Arial, Helvetica, sans-serif;">
                        <p>Hope you're having a fantastic day so far.</p>
                        <p>This is with reference to your interest in the <a href="{!! (isset($report_inquiry->report->slug)) ? url('report/'.$report_inquiry->report->slug) : '' !!}">{!! (isset($report_inquiry->report->name)) ? $report_inquiry->report->name : '' !!}</a></p>
                        <p>When it comes to international market coverage, we are proud to offer timely updates while keeping all regions, including even African markets covered within the same reports.</p>
                        <p>We have done some extensive, granular level research and have a strong team of analysts with wealth of experience in the field who are capable enough to answer any research queries around this topic.</p>
                        <p>Let us know a convenient time for a quick call to better understand and cater to your requirement at the earliest.</p>
                        <p>You can also email us about any custom requirement you are looking for or contact us at +1-617-230-0741.</p>
                        <p>Looking forward to hearing from you.</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:15px 0 5px; font-family:Arial, Helvetica, sans-serif;">
                        <p style="margin:0; font-size:13px; line-height:20px; font-family:Arial, Helvetica, sans-serif; color:#000;">Regards, <br>{!! Config::get('app.name') !!}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@endsection