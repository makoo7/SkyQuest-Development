@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! $admin->id ? route('admin.admin.update') : route('admin.admin.store') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{!! $admin->id !!}">
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-control-label">Role</label>
                                        <select name="role_id" id="role_id" class="form-control">
                                            <option value="">Select Role</option>
                                            @foreach ($roles as $role)
                                                <option value="{{$role->id}}" @if($admin->role_id == $role->id)selected @endif>{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-control-label">User Name</label>
                                        <input type="text" name="user_name" id="user_name" value="{!! old('user_name') ?? $admin->user_name !!}" class="form-control">
                                        @error('user_name')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-control-label">Email</label>
                                        <input type="email" name="email" id="email" value="{!! old('email') ?? $admin->email !!}" class="form-control">
                                        @error('email')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-control-label">Phone</label>
                                        <input type="text" name="phone" id="phone" maxlength="12" minlength="10" value="{!! old('phone') ?? $admin->phone !!}" class="form-control">
                                        @error('phone')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    @if(!$admin->id)
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-control-label">Password</label>
                                        <div class="pass-input-group">
                                            <input type="password" name="password" id="password" value="{!! old('password') ?? $admin->password !!}" class="form-control">
                                            <span toggle="#password" class="fa fa-eye-slash field-icon toggle-password"></span>
                                        </div>
                                        @error('password')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    @endif
                                    <div class="col-sm-12 mb-3">
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
                                            <div class="show-image imageRounded ml-4">
                                                <img src="{!! $admin->image_url !!}" class="rounded-circle" id='image_preview'>
                                                @if($admin->image)
                                                <a href="javascript:void(0)" class="deleteMetaImg" onclick="deleteAdminAvatar({!!$admin->id!!})">X</a>
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
                                                <input type="radio" id="is_active_yes" class="form-control checkboxinput" name="is_active" value="1" {!! $admin->is_active == '1' ? 'checked' : '' !!} checked>Active
                                                <h1></h1>
                                            </div>
                                            <div class="radiobtn">
                                                <input type="radio" id="is_active_no" class="form-control checkboxinput" name="is_active" value="0" {!! $admin->is_active == '0' ? 'checked' : '' !!} >Inactive
                                                <h1></h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.admin.index') !!}';">Cancel</button>
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
<script src="{!! asset('assets/backend/js/pages/admin.js') !!}"></script>
@stop
@endsection
