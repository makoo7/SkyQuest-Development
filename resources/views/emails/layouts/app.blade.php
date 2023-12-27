<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Password {!! Config::get('app.name') !!}</title>
</head>

<body style="margin:0; padding:0; font-size:15px; font-family:Arial, Helvetica, sans-serif; color:#000; background:#ddd;">
    <table align="center" cellspacing="0" cellpadding="0" width="600" border="0" style="padding:0; background-size:100% 100%; background-color:#e2e3e5; font-family:Arial, Helvetica, sans-serif;">
        <tr>
            <td align="center" style="padding:20px 0;width:100px;"><img src="{!! asset('assets/backend/images/logo-black.png') !!}" style="width:100px"></td>
        </tr>
        <tr>
            <td width="540px;" style="padding:0px 30px 20px;">
                @yield('content')
                <table cellspacing="0" cellpadding="0" width="100%" border="0" style="text-align:center; padding:0; border-radius:10px 10px 0 0; margin:0;font-size:12px;">
                    <tr>
                        <td align="center" style="padding:20px 0 0 0;"><p style="margin:0; font-family:Arial, Helvetica, sans-serif; color:#000">Copyright &copy; {!! date('Y') !!}. All Rights Reserved. </p></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
