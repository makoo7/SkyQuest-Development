@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation")
<!-- hero-section start -->
<section class="banner-slider">
    <div class="container">
        {{-- <div class="slider">
            <div>
                <h3 class="hero-heading">
                    We combine our global expertise with deep local insights to help you take data-driven decisions
                </h3>
            </div>
            <div>
                <h3 class="hero-heading">
                    Enable your open innovation capabilities to align with the sustainable development goals
                </h3>
            </div>
            <div>
                <h3 class="hero-heading">
                    Accelerate your digital transformation journey
                </h3>
            </div>
        </div> --}}
        <div id="heroSlider" class="carousel slide hero-slider" data-bs-touch="true" data-bs-ride="carousel">
            <h1 class="home-title">{{ $h1 }}</h1>
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="1" aria-label="Slide 2"></button>
              <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <h3 class="hero-heading">
                    We combine our global expertise with deep local insights to help you take data-driven decisions
                </h3>
              </div>
              <div class="carousel-item">
                <h3 class="hero-heading">
                    Enable your open innovation capabilities to align with the sustainable development goals
                </h3>
              </div>
              <div class="carousel-item">
                <h3 class="hero-heading">
                    Accelerate your digital transformation journey
                </h3>
              </div>
            </div>
          </div>
        <div class="lets-talk-slider">
            <a href="{!! route('contact-us') !!}" class="btn">
                    <span>Let's Talk</span>
                    <img src="{!! asset('assets/frontend/images/right-arrow.svg') !!}" alt="right-arrow" width="32"
                        height="21" />
            </a>
        </div>
        <div class="infinite-slider-logos">
            <marquee>
                <img src="{!! asset('assets/frontend/images/logo-slider-img.webp') !!}" width="2644" height="74" alt="logo-images" style="filter: brightness(250%);max-width:inherit;" />
            </marquee>
        </div>
    </div>
</section>
<!-- hero-section end -->
<!-- client-review-section start -->
<div class="client-review-section">
    <div class="section-overlay">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="client-review-inner">
                        <span class="heading number plus">{!! $settings->satisfied_customers !!}</span><span class="symbol">+</span>
                        <p>Satisfied Customers</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="client-review-inner">
                        <span class="heading number persentage">{!! $settings->customer_retention_rate !!}</span><span class="symbol">%</span>
                        <p>Customers Retention Rate</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="client-review-inner">
                        <span class="heading number plus">{!! $settings->years_of_team_experience !!}</span><span class="symbol">+</span>
                        <p>Years of Collective Team Experience</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="client-review-inner">
                        <span class="heading number plus">{!! $settings->years_in_business !!}</span><span class="symbol">+</span>
                        <p>Years in Business</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <img src="{!! asset('assets/frontend/images/client-review-banner.webp') !!}" class="sec-bg" alt="right-arrow" loading="lazy"
                        width="100%" height="600" /> 
</div>
<!-- client-review-section end -->
<!-- about-us-section start -->
<div class="about-us-section">
    <div class="container">
        <div class="about-us-inner">
            <h4>We partner with the world's most ambitious companies to accelerate growth with breakthrough insights and
                technology.</h4>
            <p>Our team is passionate about demystifying dark data and unravel insights that can keep you one step ahead
                of competition. We connect innovation and technology to new market networks & collaborators for
                accelerating growth and aligning towards organization's sustainability goals.
                <br>
                <br>
                We work with diverse stakeholders from Fortune 1000 companies, Startups, SMEs, Development agencies &
                Government to help them access hidden data, insights and technologies.
            </p>
            <a href="{!! route('about-us') !!}">
                <button class="about-us-btn mx-auto">
                    <span>About Us</span>
                    <img src="{!! asset('assets/frontend/images/right-arrow.svg') !!}" alt="right-arrow" loading="lazy"
                        width="32" height="21" />
                </button>
            </a>
        </div>
    </div>
</div>
<!-- about-us-section end -->
<!-- services-section start -->
<div class="services-section">
    <div class="container">
        <div class="services-section-inner">
            <h4>Our Services</h4>
            <div class="services-inner-main">
                @foreach ($services as $service)
                <div class="services">
                    <div class="heading"><a
                            href="{!! url('services/'.str_replace(' ', '-', $service->name)) !!}">{!!$service->name!!}</a>
                    </div>
                    <p class="content">{!!$service->short_description!!}
                </div>
                </p>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- services-section end -->
<!-- work-with-section start -->
<div class="work-with-us">
    <div class="container">
        <div class="slider-inner">
            <h4>We Work with 12+ Sectors</h4>
            <div>
                <div class="wrapper">
                    <div class="workwith-slider">
                        @foreach ($sectorsData as $k => $sectors)
                        <div>
                            <img src="{!! str_replace('/upload/','/upload/h_250/q_80/',$sectors->image_url) !!}"
                                width="374" height="250" alt="{!! $sectors->name !!}" loading="lazy" />
                            <div class="img-content">
                                <span>/{!! str_pad($k+1, 2, '0', STR_PAD_LEFT) !!}</span>
                                <span>{!! $sectors->name !!}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- work-with-section end -->
@if($homepage->is_products)
<!-- WaterQuest-section start -->
<div class="WaterQuest">
    <div class="container">
        <div class="WaterQuest-inner">
            <div class="waterQuest-conetent-sec">
                <p>We are more than consulting</p>
                <h4>WaterQuest</h4>
                <p>WaterQuest is an echo-conscious socially responsible Department of industrial Policy & Promotion
                    (DIPP). Government of India recognized</p>
                <a href="#" style="opacity:0;pointer-event:none;">
                    <button class="waterQuest-btn">
                        <span>View All</span>
                        <img src="{!! asset('assets/frontend/images/right-arrow.svg') !!}" alt="WaterQuest-img"
                            loading="lazy" width="32" height="21" />
                    </button>
                </a>
            </div>
            <div class="waterQuest-image-sec">
                <img src="{!! asset('assets/frontend/images/waterQuest.webp') !!}" alt="waterQuest-img"
                    loading="lazy" width="688" height="583" />
            </div>
        </div>
    </div>
</div>
<!-- WaterQuest-section end -->
@endif
@if($homepage->is_case_study)
<!-- case-study-section start -->
<div class="case-study">
    <div class="container">
        <div class="case-study-inner">
            <p>/From Our Experts</p>
            <h4>Case Studies</h4>
            <div class="wrapper">
                <div class="casestudy-slider">
                    @foreach ($sel_casestudies as $k => $casestudy)
                    <div class="slides">
                        <a href="{!! url('case-studies/'.$casestudy->slug) !!}"><img
                                src="{!! str_replace('/upload/','/upload/w_364/q_80/',$casestudy->image_url) !!}"
                                width="364" height="300" alt="{!! $casestudy->image_alt !!}" loading="lazy" /></a>
                        <div class="card-body">
                            <p>{!! $casestudy->location !!}</p>
                            <a href="{!! url('case-studies/'.$casestudy->slug) !!}">{!! $casestudy->name !!}</a>
                        </div>
                    </div>
                    @endforeach
                    <div class="slides view-all-slide">
                        <a href="{!! url('case-studies') !!}" class="slider-view-all-btn">
                            View All <br /> Case Studies
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- case-study-section end -->
@endif
<!-- latest-report-section start -->
@if(!$reports->isEmpty())
<div class="latest-report-section">
    <div class="container">
        <div class="latest-report-section-inner">
            <h4>Latest Reports</h4>
            <div class="wrapper">
                <div class="latestreport-slider">
                    @foreach ($reports as $report)
                    <div class="slides">
                        <a href="{!! url('report/'.$report->slug) !!}"><img src="{!! $report->image_url !!}" width="257"
                                height="360" alt="{!! $report->image_alt !!}" loading="lazy" /></a>
                        <div class="card-body">
                            <a href="{!! url('report/'.$report->slug) !!}">{!! $report->name !!}</a>
                        </div>
                    </div>
                    @endforeach
                    <div class="slides">
                        <div class="view-all-slide">
                            <a href="{!! url('reports') !!}" class="slider-view-all-btn">
                                View All <br /> Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- latest-report-section end -->
@if($homepage->is_insights)
<!-- Insights-section start -->
<div class="insights-section">
    <div class="container">
        <div class="insights-section-inner">
            <p>/From Our Experts</p>
            <h4>Insights</h4>
            <div class="wrapper">
                <div class="insights-slider">
                    @foreach ($sel_insights as $k => $insights)
                    <div class="slides">
                        <a href="{!! url('insights/'.$insights->slug) !!}"><img
                                src="{!! str_replace('/upload/','/upload/h_332/q_80/',$insights->image_url) !!}"
                                width="544" height="300" alt="{!! $insights->image_alt !!}" loading="lazy" /></a>
                        <div class="img-content">
                            <span>/{!! str_pad($k+1, 2, '0', STR_PAD_LEFT) !!}</span>
                            <span><a href="{!! url('insights/'.$insights->slug) !!}">{!! $insights->name !!}</a></span>
                        </div>
                    </div>
                    @endforeach
                    <div class="slides view-all-slide">
                        <a href="{!! url('insights') !!}" class="slider-view-all-btn">
                            View All <br /> Insights
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Insights-section end -->
@endif
@if($homepage->is_awards)
<!-- Achievements-section start -->
<div class="Achievements-section">
    <div class="container">
        <div class="Achievements-section-inner">
            <h4>Achievements</h4>
            <p>Part of Champions of Change Initiative by Hon. PM of India 2017</p>
            <div class="wrapper">
                <div class="Achievements-slider">
                    @foreach ($sel_awards as $k => $awards)
                    <div class="slides">
                        <div class="card">
                            <img src="{!! str_replace('/upload/','/upload/w_256/q_80/',$awards->image_url) !!}"
                                width="250" height="300" alt="{!! $awards->title !!}" loading="lazy" />
                            <div class="card-content">
                                <h4>{!! $awards->title !!}</h4>
                                <p>{!! $awards->short_description !!}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Achievements-section end -->
@endif
@section('js')
<!-- js link -->
<script src="{!! asset('assets/frontend/js/pages/index.js') !!}"></script>
@stop
@endsection