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
                        <form id="frmMyProfile" name="frmMyProfile" action="{!! route('admin.profile') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="form-control-label">User Name</label>
                                    <input name="user_name" id="user_name" type="text" class="form-control" value="{!! old('user_name') ?? $user->user_name !!}">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-control-label">Email</label>
                                    <input name="email" id="email" type="text" class="form-control" value="{!! old('email') ?? $user->email !!}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                <label class="form-control-label">Phone</label>
                                    <input name="phone" id="phone" type="text" class="form-control" maxlength="12" value="{!! old('phone') ?? $user->phone !!}">
                                </div>
                                <div class="col-sm-6">
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
                                            @if($user->image!='')
                                            <img src="{!! $user->image_url !!}" class="img-fluid rounded-circle" id="image_preview">
                                            <a href="javascript:void(0)" class="deleteMetaImg" onclick="deleteAdminAvatar({!!$user->id!!})">X</a>
                                            @else
                                            <img src="{!! asset('/assets/backend/images/default-avatar.png') !!}" class="img-fluid rounded-circle" id='image_preview'>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="line"> </div>
                            <div class="form-group row">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                    <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! URL('/admin') !!}';">Cancel</button>
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
