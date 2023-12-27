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
                                <h1>Forgot Password</h1>
                            </div>
                            @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {!! session('status') !!}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif
                            <form id="frmForgotPassword" name="frmForgotPassword" method="POST" action="{!! route('admin.password.email') !!}">
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
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">
                                        {!! __('Send Password Reset Link') !!}
                                    </button>
                                </div>
                                <div class="form-group text-center">
                                    <!-- <button type="button" class="btn mt-3 mt-sm-0" onclick="location.href='{!! route('admin.login') !!}';">
                                        {!! __('Back to Login') !!}
                                    </button> -->
                                    <a class="forgot-pass" href="{!! route('admin.login') !!}">
                                        {!! __('Back to Login') !!}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyrights text-center">
        <p>Powered by <a href="{!! config('constants.POWERED_BY_URL') !!}" target="_blank">{!! config('app.name')!!}</a></p>
    </div>
</div>
@section('js')
<script src="{!! asset('assets/backend/js/pages/auth.js') !!}"></script>
@stop
@endsection