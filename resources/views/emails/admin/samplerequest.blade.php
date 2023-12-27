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
                        @if(isset($samplerequest->report->name))
                        <p>Report Name : <a href="{!! (isset($samplerequest->report->slug)) ? url('report/'.$samplerequest->report->slug) : '' !!}">{!! $samplerequest->report->name!!}</a></p>
                        @endif
                        <p>Sector : {!! (isset($samplerequest->report->sector->title)) ? $samplerequest->report->sector->title : '' !!}</p>
                        <p>Industry Group : {!! (isset($samplerequest->report->industry_group->title)) ? $samplerequest->report->industry_group->title : '' !!}</p>
                        <p>Industry : {!! (isset($samplerequest->report->industry->title)) ? $samplerequest->report->industry->title : '' !!}</p>
                        <p>Sub-Industry : {!! (isset($samplerequest->report->sub_industry->title)) ? $samplerequest->report->sub_industry->title : '' !!}</p>
                        <p>Name : {!! (isset($samplerequest->name)) ? $samplerequest->name : '' !!}</p>                        
                        <p>Phone Number : {!! (isset($samplerequest->phonecode)) ? $samplerequest->phonecode : '' !!}{!! (isset($samplerequest->phone)) ? $samplerequest->phone : '' !!}</p>
                        @if(isset($samplerequest->email))
                        <p>Email : <a href="mailto:{!! $samplerequest->email !!}">{!! $samplerequest->email !!}</a></p>
                        @endif
                        <p>Designation : {!! (isset($samplerequest->designation)) ? $samplerequest->designation : '' !!}</p>
                        <p>Company Name : {!! (isset($samplerequest->company_name)) ? $samplerequest->company_name : '' !!}</p>                        
                        <p>Country : {!! (isset($samplerequest->country->name)) ? $samplerequest->country->name : '' !!}</p>
                        <p>IP Address : {!! (isset($samplerequest->ip_address)) ? $samplerequest->ip_address : '' !!}</p>
                        <p>Linkedin Link :@if(isset($samplerequest->linkedin_link)) <a href="{!! $samplerequest->linkedin_link !!}" target="_blank">{!! $samplerequest->linkedin_link !!}</a> @endif</p>
                        <p>Description : {!! (isset($samplerequest->message)) ? $samplerequest->message : '' !!}</p>
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