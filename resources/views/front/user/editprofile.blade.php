@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation")

<!-- PAGE LOADER -->
<div class="page-loader" style="display: none;"><span class="loader-image"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span></div>
<!-- PAGE LOADER -->

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
                    <h5>Edit Profile</h5>
                    <hr class="mt-2"/>
                    <form id="frmeditProfile" name="frmeditProfile" action="{!! route('edit-profile') !!}" method="POST" class="" enctype="multipart/form-data">
                        @csrf
                        <div class="details">                                                    
                            <div class="img-view">
                                <img src="{{ $user->image_url }}"  alt="user-img" id="image_preview" />
                                @if($user->image)
                                    <a href="javascript:void(0)" class="deleteImg btn btn-danger w-100 rounded-0" onclick="deleteUserAvatar({!!$user->id!!})"><i class="fa fa-trash"></i></a>
                                @endif
                                <div class="p-image">
                                    <i class="fa fa-user-pen upload-button"></i>
                                    <input class="file-upload" type="file" name="image" id="image" accept="image/*"/>
                                </div>
                            </div>
                            <div class="content">
                                <div class="row account-form">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="hidden" name="id" id="id" value="{{ $user->id }}">
                                            <input class="form-control" type="text" name="user_name" id="user_name" placeholder="User name" value="{{ $user->user_name }}" />
                                            @error('user_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="email" id="email" placeholder="Email" value="{{ $user->email }}" />
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="phone" id="phone" placeholder="Phone" value="{{ $user->phone }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control textarea" type="text" name="company_name" id="company_name" placeholder="Company name" value="{{ $user->company_name }}" />
                                            @error('company_name')
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
<script src="{!! asset('assets/frontend/js/pages/editprofile.js') !!}"></script>
@stop
@endsection