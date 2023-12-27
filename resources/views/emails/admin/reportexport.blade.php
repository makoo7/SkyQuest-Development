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
                        <p>You've got a new report export request.</p>
                        <p>Details are as below:</p>
                        <p>User Name : {!! (isset($reportExport->admin->user_name)) ? $reportExport->admin->user_name : '' !!}</p>
                        <p>Email : {!! (isset($reportExport->admin->email)) ? $reportExport->admin->email : '' !!}</p>
                        <p>Download Link : <a href="{!! url('/admin/report-export/download/'.$reportExport->uuid) !!}" target="_blank">{!! url('/admin/report-export/download/'.$reportExport->uuid) !!}</a></p>
                        @if(isset($reportExport->start_date))<p>Start Date : {!! $reportExport->start_date !!}</p>@endif
                        @if(isset($reportExport->end_date))<p>End Date : {!! $reportExport->end_date !!}</p>@endif
                        <p>Fields to download:
                            @if(isset($selectedFields))<br>
                                @foreach($selectedFields as $field)
                                {{ $field }} <br>
                                @endforeach
                            @endif
                        </p>                        
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