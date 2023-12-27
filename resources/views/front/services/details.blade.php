@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation")
<!-- Consulting-Services start -->
<div class="Consulting-Services">
    <div class="container">
        <div class="Consulting-Services-inner">
            <h1>{!! $service->name !!}</h1>
            @if(strtolower($service->slug)=='consulting-services')
            <p>Today's market dynamics necessitate a change in HR, talent, and organisational goals. SkyQuest Human Capital services use analysis, analytics, and industry expertise to assist in the design and execution of essential initiatives ranging from business-driven HR to creative talent, leadership, and change programmes.</p>
            @else
            <p>{!! $service->short_description !!}</p>
            @endif
        </div>
        <div class="consulting-slider-infinite-img">
            <marquee>
            <img src="{!! asset('assets/frontend/images/consulting-services-slider-img.webp') !!}" alt="logo-images" style="filter: brightness(2);">
            </marquee>
        </div>
    </div>
</div>
<!-- Consulting-Services end -->
<!-- abt-services img start -->
@if($service->image!='')
<div class="abt-services-img" style="background-image:url({!! $service->image_url !!});"></div>
@endif
<!-- abt-services img end -->
<!-- abt-services start -->
<div class="abt-services">
    <div class="container">
        <div class="abtservices-inner">
            <h1>About The Service</h1>{!! $service->description !!}
        </div>
    </div>
</div>
<!-- abt-services end -->
{!! $service->how_it_helps !!}
<!-- feedback from start -->
<div class="feedback-form">
    <div class="container">
        <div class="feedbackform-inner">
            <h2>Feedback From Our Clients</h2>
            <div class="feedback-containt">
                <div class="containt-inner">
                    <div class="feedback-slider mb-0" id="feedbackSlider" style="position: relative;">
                        @foreach ($clientfeedbacks as $clientfeedback)
                        <div class="item">
                            {!! isset($clientfeedback->feedback) ? $clientfeedback->feedback : '' !!}
                            <p>- {!! isset($clientfeedback->name) ? $clientfeedback->name : '' !!}{!! isset($clientfeedback->designation) ? ', '.$clientfeedback->designation : '' !!}{!! isset($clientfeedback->company_name) ? ', '.$clientfeedback->company_name : '' !!}.</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- feedback from end -->
@section('js')
<!-- js link -->
@stop
@endsection