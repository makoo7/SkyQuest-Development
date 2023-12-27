@extends('admin.layouts.app')

@section('content')
<div class="page login-page">
    <div class="container d-flex align-items-center">
        <div class="form-holder">
            <div class="row">
                <!-- Form Panel    -->
                <div class="col-lg-6 mx-auto">
                    <div class="form d-flex align-items-center has-shadow">
                        <div class="content">
                            <div class="logo">
                                <div class="logo-inner">
                                    <img src="{!! asset('assets/backend/images/logo.webp') !!}" class="logo-img">
                                </div>
                                <h1>Admin Login</h1>
                            </div>
                            <form id="frmLogin" name="frmLogin" method="POST" action="{!! route('admin.login') !!}">
                                @csrf
                                <div class="form-group">
                                    <input id="email" type="email" class="input-material" name="email" value="{!! old('email') !!}" autocomplete="email" autofocus>
                                    <label for="email" class="label-material">{!! __('E-Mail Address') !!}</label>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="pass-input-group">
                                        <input id="password" type="password" class="input-material" name="password" autocomplete="current-password">
                                        <label for="password" class="label-material">{!! __('Password') !!}</label>
                                        <span toggle="#password" class="fa fa-eye-slash field-icon toggle-password"></span>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                    @enderror
                                    
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-lg btn-primary px-5 py-2">
                                        {!! __('Login') !!}
                                    </button>
                                </div>
                                <!-- This should be submit button but I replaced it with <a> for demo purposes-->
                            </form>

                            @if (Route::has('admin.password.request'))
                            <a class="forgot-pass" href="{!! route('admin.password.request') !!}">
                                {!! __('Forgot Your Password?') !!}
                            </a>
                            @endif
                        </div>
                        <!-- <img src="{!! asset('assets/backend/images/dron.png') !!}" class="dron-img one"/>
                        <img src="{!! asset('assets/backend/images/dron.png') !!}" class="dron-img two"/> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyrights text-center">
        <p>Powered by <a href="{!! config('constants.POWERED_BY_URL') !!}" target="_blank">{!! config('app.name')!!}</a></p>
    </div>
</div>
<!-- JavaScript files-->
@section('js')
<script src="{!! asset('assets/backend/js/pages/auth.js') !!}"></script>
@stop
@endsection
