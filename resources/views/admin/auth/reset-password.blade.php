@extends('admin.layouts.app')

@section('content')
<div class="page login-page">
    <div class="container d-flex align-items-center">
        <div class="form-holder ">
            <div class="row">
                <!-- Form Panel    -->
                <div class="col-lg-6 mx-auto">
                    <div class="form d-flex align-items-center has-shadow">
                        <div class="content">
                            <div class="logo">
                                <div class="logo-inner">
                                    <img src="{!! asset('assets/backend/images/logo.webp') !!}" class="logo-img">
                                </div>
                                <h1>Reset Password</h1>
                            </div>
                            <form id="frmResetPassword" name="frmResetPassword" method="POST" action="{!! route('admin.password.update') !!}">
                                @csrf
                                <input type="hidden" name="token" value="{!! $request->route('token') !!}">
                                <div class="form-group">
                                    <input id="email" type="email" class="input-material" name="email" value="{!! old('email', $request->email) !!}" autofocus>
                                    <label for="email" class="label-material">{!! __('E-Mail Address') !!}</label>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input id="password" type="password" class="input-material" name="password"
                                    autocomplete="current-password">
                                    <span toggle="#password" class="fa fa-eye-slash field-icon toggle-password"></span>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                                    @enderror
                                    <label for="password" class="label-material">{!! __('Password') !!}</label>
                                </div>

                                <div class="form-group">
                                    <input id="password-confirm" name="password_confirmation" type="password"
                                    class="input-material" autocomplete="current-password-1">
                                    <span toggle="#password-confirm" class="fa fa-eye-slash field-icon toggle-password"></span>
                                    <label for="password-confirm" class="label-material">{!! __('Confirm Password') !!}</label>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        {!! __('Reset Password') !!}
                                    </button>
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