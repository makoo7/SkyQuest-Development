@extends('admin.layouts.app')

@section('content')
<!-- Dashboard Counts Section-->
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <!-- Form Elements -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="frmChangePassword" name="frmChangePassword" action="{!! route('admin.changepassword') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Current Password</label>
                                <div class="col-sm-9">
                                    <div class="pass-input-group">
                                        <input id="current_password" name="current_password" type="password" class="form-control">
                                        <span toggle="#current_password" class="fa fa-eye-slash field-icon toggle-password"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">New Password</label>
                                <div class="col-sm-9">
                                    <div class="pass-input-group">
                                        <input id="new_password" name="new_password" type="password" class="form-control">
                                        <span toggle="#new_password" class="fa fa-eye-slash field-icon toggle-password"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="form-group row">
                                <label for="fileInput" class="col-sm-3 form-control-label">Confirm Password</label>
                                <div class="col-sm-9">
                                    <div class="pass-input-group">
                                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control">
                                        <span toggle="#password_confirmation" class="fa fa-eye-slash field-icon toggle-password"></span>
                                    </div>    
                                </div>
                            </div>
                            <div class="line"> </div>
                            <div class="form-group row">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                    <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.dashboard') !!}';">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@section('js')
<script src="{!! asset('assets/backend/js/pages/auth.js') !!}"></script>
@stop
@endsection
