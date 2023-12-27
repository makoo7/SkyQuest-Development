@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! route('admin.user.update') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{!! $user->id !!}">
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">User Name</label>
                                        <input type="text" name="user_name" id="user_name" value="{!! old('user_name') ?? $user->user_name !!}" class="form-control">
                                        @error('user_name')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Email</label>
                                        <input type="email" name="email" id="email" value="{!! old('email') ?? $user->email !!}" class="form-control">
                                        @error('email')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Phone</label>
                                        <input type="text" name="phone" id="phone" maxlength="12" minlength="10" value="{!! old('phone') ?? $user->phone !!}" class="form-control">
                                        @error('phone')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Company Name</label>
                                        <input type="text" name="company_name" id="company_name" value="{!! old('company_name') ?? $user->company_name !!}" class="form-control">
                                        @error('company_name')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="fileInput" class="form-control-label">Profile Pic</label>
                                        <div class="d-md-flex align-items-center">
                                            <!-- <div class="form-inline form-fileUpload">
                                                <div class="fileUpload btn btn-primary">
                                                    <span>Browse</span>
                                                    <input id="image" name="image" type="file" class="upload" accept="image/*">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <span class="uploadFile form-control"></span>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <input type="file" class="form-control-file upload" id="image" name="image" accept="image/*">
                                            </div>
                                            <div class="show-image imageRounded mt-3 mt-md-0">
                                                <img src="{!! $user->image_url !!}" class="img-fluid rounded-circle" id='image_preview'>
                                                @if($user->image)
                                                <a href="javascript:void(0)" class="deleteMetaImg" onclick="deleteUserAvatar({!!$user->id!!})">X</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Status</label>
                                        <div class="radio-wrap">
                                            <div class="radiobtn">
                                                <input type="radio" id="is_active_yes" class="form-control checkboxinput" name="is_active" value="1" {!! $user->is_active == '1' ? 'checked' : '' !!} checked>Active
                                                <h1></h1>
                                            </div>
                                            <div class="radiobtn">
                                                <input type="radio" id="is_active_no" class="form-control checkboxinput" name="is_active" value="0" {!! $user->is_active == '0' ? 'checked' : '' !!} >Inactive
                                                <h1></h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.user.index') !!}';">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@section('js')
<script src="{!! asset('assets/backend/js/pages/user.js') !!}"></script>
@stop
@endsection
