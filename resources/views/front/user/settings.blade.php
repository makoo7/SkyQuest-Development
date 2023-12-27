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
                    <div class="details">
                        <div class="img-view">
                            <img src="{{ $user->image_url }}"  alt="user-img"/>
                        </div>
                        <div class="content">
                            <div class="info-list">
                                <label><span>Name:</span><span>{{ $user->user_name }}</span></label>
                                <label><span>Email:</span><span>{{ $user->email }}</span></label>
                                <label><span>Phone:</span><span>{{ $user->phone }}</span></label>
                                <label><span>Company Name:</span><span>{{ $user->company_name }}</span></label>
                            </div>
                            <hr/>
                            <div class="text-end">
                                <a class="btn btn-black" href="{!! route('profile') !!}">Edit Profile</a>
                                <a class="btn btn-black" href="{!! route('change-password') !!}">Change Password</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
<!-- js link -->
<script src="{!! asset('assets/frontend/js/pages/settings.js') !!}"></script>
@stop
@endsection