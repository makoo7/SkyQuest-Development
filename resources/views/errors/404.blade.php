@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation")
<div class="error-page">
    <div class="container">
        <div class="error-page-inner">
            <h1 class="text-center">{{ $h1 }}</h1>
            <div class="mb-2 text-center">
                <p class="mb-2">The page you are looking for might have been removed,</p>
                <p>had it's name changed or is temporarily unavailable.</p>
            </div>
        </div>
    </div>
</div>
@section('js')
@stop
@endsection