@extends('emails.layouts.app')
@section('content')

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" style="background:#fff; padding:25px 30px; border-radius:10px; -webkit-border-radius:10px; -o-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;">
            <table width="100%" style="margin:0;">
                <tr>
                    <td align="left" style="font-size:14px; color:#000; padding:0 0 15px; font-family:Arial, Helvetica, sans-serif;">Hi {!! (isset($newusercreate->user_name)) ? $newusercreate->user_name : '' !!}, </td>
                </tr>
                <tr>
                    <td align="left" style="font-size:13px; line-height:23px; color:#000; padding:0 0 12px; font-family:Arial, Helvetica, sans-serif;">
                        <p>Thank you for your interest in {!! config('app.name') !!}.</p>                        
                        <p>Click <a href="{!! route('home') !!}">here</a> to login in the online portal using below details.</p>
                        <p>Email: {!! (isset($newusercreate->email)) ? $newusercreate->email : '' !!}</p>
                        <p>Password: {!! (isset($newusercreate->new_password)) ? $newusercreate->new_password : '' !!}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:15px 0 5px; font-family:Arial, Helvetica, sans-serif;">
                        <p style="margin:0; font-size:13px; line-height:20px; font-family:Arial, Helvetica, sans-serif; color:#000;">Thank You, <br>{!!Config::get('app.name')!!}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@endsection