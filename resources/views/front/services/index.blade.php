@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation-white")
<!-- services page start -->
<div class="services-page">
    <div class="services-hero">
        <div class="container">
            <div class="services-hero-inner">
                <img src="{!! asset('assets/frontend/images/services-hero-img.webp') !!}" alt="services-hro-img">
                <div class="services-containt">
                    <h1>{{ $h1 }}</h1>
                    <p>We have worked with multiple clients through their growth and impact journey by providing our expertise in innovation, insights, and technology</p>
                </div>
            </div>
        </div>
    </div>
    <!-- services cards start -->
    <div class="services-card">
        <div class="container">
            <div class="servicescard-inner">
                @foreach ($services as $service)
                <div class="cards">
                    <a href="{!! url('services/'.$service->slug) !!}">
                    <img src="{!! str_replace('/upload/','/upload/q_80/',$service->image_url) !!}" alt="{!!$service->image_alt!!}">
                    </a>
                    <a href="{!! url('services/'.$service->slug) !!}">
                    <p>{!!$service->name!!}</p>
                    </a>
                    <a href="{!! url('services/'.$service->slug) !!}">
                    <p>{!!\Illuminate\Support\Str::limit($service->short_description, 75, $end=' ...')!!}</p>
                    </a>
                    @if(strlen($service->short_description)>75)
                    <a href="{!! url('services/'.$service->slug) !!}">
                    <p>Read More</p>
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- services cards end -->
</div>
<!-- services page end -->
@section('js')
<!-- js link -->
@stop
@endsection