@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation")
<div class="content-body">
    <div class="container">
        <div class="account-header">
            <button class="btn btn-blue nav-collapse-btn"></button>
            <h3>Hello! {{ $user->user_name }}</h3>
        </div>
        <div class="account-tabs d-flex align-items-start">
            @include("front.layouts.account")
            <div class="content-view">
                <div class="my-profile">
                    <h5>Change Password</h5>
                    <hr class="mt-2"/>
                    <form id="frmChangePassword" name="frmChangePassword" action="{!! route('save-password') !!}" method="POST">
                        @csrf
                        <div class="details">
                            <div class="content">
                                <div class="row account-form">
                                    <div class="col-md-12">
                                        <div class="form-group pass-input-grp">
                                            <input type="hidden" name="id" id="id" value="{{ $user->id }}">
                                            <input class="form-control" type="password" name="current_password" id="current_password" placeholder="Current Password" value="" />
                                            <span toggle="#current_password" class="fa fa-eye-slash field-icon toggle-password"></span>
                                            @error('current_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group pass-input-grp">
                                            <input class="form-control" type="password" name="new_password" id="new_password" placeholder="New Password" value="" />
                                            <span toggle="#new_password" class="fa fa-eye-slash field-icon toggle-password"></span>
                                            @error('new_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group pass-input-grp">
                                            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" value="" />
                                            <span toggle="#password_confirmation" class="fa fa-eye-slash field-icon toggle-password"></span>
                                            @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-end">
                                        <div class="form-group">
                                            <button class="btn btn-black" type="submit">Save Changes</button>
                                            <a class="btn btn-black" href="{!! route('settings') !!}">Cancel</a>
                                        </div>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
<!-- js link -->
<script src="{!! asset('assets/frontend/js/pages/changepassword.js') !!}"></script>
@stop
@endsection