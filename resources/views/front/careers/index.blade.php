@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation")
<!-- SkyQuest Section start -->
<div class="skyquest">
    <div class="container">
    <div class="skyquest-inner">
        <h1>{{$h1}}</h1>
        <p>If innovativeness is in your schema and you have just decided to source a technology/innovation or an IP, we at SkyQuest can help you make your decision tangible.</p>
    </div>
    </div>
</div>
<!-- SkyQuest Section end -->
<!-- SkyQuest parallax-img start -->
<div class="parallax-img"></div>
<!-- SkyQuest parallax-img end -->
<!-- passion section start -->
<div class="passion">
    <div class="container">
        <div class="passion-inner">
            <h1>What is Your Passion?</h1>
            <div class="passion-features row">
                @foreach ($departments as $department)
                <div class="features-inner col-lg-4 col-sm-6">
                    <a href="{!! url('careers/'.$department->slug) !!}" class="inner">
                        <p>{!!$department->name!!}</p>
                        <span>
                            <img src="{!! asset('assets/frontend/images/passion-aerrow.svg') !!}" alt="aerrow">
                        </span>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- passion section end -->
<!-- what-do-we section start -->
<div class="what-do-we">
    <div class="container">
        <div class="whatdowe-inner">
            <p>What Do We Do?</p>
            <p>
                If you're most comfortable in an established environment that changes slowly, we're probably not the best fit.
                <br>
                <br>
                But if you want to help build and shape something, where you can make change easily, and you're up for more risk, more reward and more responsibility than your average role, then it's great you found us.
                <br>
                <br>
                Sure, we're small right now but we have ambitions to grow to about 30 people. And this means we need people like you who want to start things, create things and grow with us. Our plan is to grow team leaders from the people who go on this journey with us, which means we want you reach that level as much as you do.
            </p>
            <img src="{!! asset('assets/frontend/images/client-review-banner.webp') !!}" alt="client-review">
        </div>
    </div>
</div>
<!-- what-do-we section end -->
@section('js')
@stop
@endsection