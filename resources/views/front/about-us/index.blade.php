@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation")
<!-- about-hero-section start -->
<div class="abt-hero-section">
    <div class="container">
        <div class="abt-hero-section-inner">
            <div class="wrapper">
                <div class="abt-hero-slider">
                    <div class="slides">
                        <div class="img-content">
                            <h1>Think big and move fast</h1>
                            <p>We achieve those goals by taking small steps, one at a time we are open-minded, positive, creative and seeing opportunity in the big picture</p>
                        </div>
                        <div class="slider-imgs">
                            <div class="slider-imgs-in flex-25">
                                <img alt="imgs-1" src="{!! asset('assets/frontend/images/slider-imgs-1.webp') !!}" class="imgs">
                                <img alt="imgs-2" src="{!! asset('assets/frontend/images/slider-imgs-2.webp') !!}" class="imgs align-img">
                            </div>
                        </div>
                    </div>
                    <div class="slides">
                        <div class="img-content">
                            <h1>Innovation is in our dna</h1>
                            <p>We are a team of most creative executives: associating, questioning, observing, experimenting, and networking</p>
                        </div>
                        <div class="slider-imgs">
                            <div class="slider-imgs-in flex-25">
                                <img alt="imgs-3" src="{!! asset('assets/frontend/images/slider-imgs-3.webp') !!}" class="imgs">
                                <img alt="imgs-4" src="{!! asset('assets/frontend/images/slider-imgs-4.webp') !!}" class="imgs align-img">
                            </div>
                        </div>
                    </div>
                    <div class="slides">
                        <div class="img-content">
                            <h1>Technology first </h1>
                            <p>We believe with technology and constant new trends we improve business process, while maximizing tech opportunity</p>
                        </div>
                        <div class="slider-imgs">
                            <div class="slider-imgs-in flex-25">
                                <img alt="imgs-5" src="{!! asset('assets/frontend/images/slider-imgs-5.webp') !!}" class="imgs">
                                <img alt="imgs-6" src="{!! asset('assets/frontend/images/slider-imgs-6.webp') !!}" class="imgs align-img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="infinite-slider-logos" style="overflow: hidden;">
                {{-- <marquee> --}}
                    <img src="{!! asset('assets/frontend/images/logo-slider-img.webp') !!}" alt="logo-images" width="2644" height="74" style="filter: brightness(2);">
                {{-- </marquee> --}}
            </div>
        </div>
    </div>
</div>
<!-- about-hero-section end -->
<!-- about-who-are-we-section start -->
<div class="who-are-we">
    <div class="container">
        <div class="who-are-we-inner">
            <div class="left-side">
                <p>Who Are We</p>
                <p>SkyQuest Technology Group is a global market intelligence, innovation management & commercialization organization that connects innovation to new markets, networks & collaborators for achieving sustainable development goals.</p>
                <div class="counter">
                    <div class="counter-inner">
                    <p>
                        <span class="number">{!! $settings->satisfied_customers !!}</span>+
                    </p>
                    <p>Satisfied Customers</p>
                    </div>
                    <div class="counter-inner">
                    <p>
                        <span class="number">{!! $settings->customer_retention_rate !!}</span>%
                    </p>
                    <p>Customer Retention Rate</p>
                    </div>
                    <div class="counter-inner">
                    <p>
                        <span class="number">{!! $settings->years_in_business !!}</span>
                    </p>
                    <p>Years in Business</p>
                    </div>
                    <div class="counter-inner">
                    <p>
                        <span class="number">{!! $settings->country_network !!}</span>+
                    </p>
                    <p>Country Network</p>
                    </div>
                    <div class="counter-inner">
                    <p>
                        <span class="number">{!! $settings->team_members !!}</span>+
                    </p>
                    <p>Team Members</p>
                    </div>
                    <div class="counter-inner">
                    <p>
                        <span class="number">{!! $settings->years_of_team_experience !!}</span>+
                    </p>
                    <p>Years of Collective Team Experience</p>
                    </div>
                </div>
            </div>
            <div class="right-side">
                <img src="{!! asset('assets/frontend/images/counter-img.webp') !!}" alt="counter-img">
            </div>
        </div>
    </div>
</div>
<!-- about-who-are-we-section end -->
<!-- about-who-are-we-section start -->
<div class="abt-contactus">
    <div class="container">
    <div class="abt-contactus-inner">
        <div class="address">
            <h1>We Are Located At</h1>
            <p class="heading_">USA</p>
            <p>1 Apache Way, Westford, Massachusetts 01886</p>
            <p class="heading">Hongkong</p>
            <p>Room A & B, 2nd Floor,Lee Kee Commercial Building,221-227 Queen’s Road Central</p>
            <p class="heading">India</p>
            <p>D-1001-1005, Swati Clover, Shilaj Circle, Sardar Patel Ring Rd, Thaltej, Ahmedabad, 380054</p>
        </div>
        <div class="form">
            <h1>Contact Us</h1>
            <form id="frmcontactus" name="frmcontactus" method="post" action="{!! url('saveContactUs') !!}">
            @csrf
                <div class="contct-form">
                    <div class="form-inner">
                        <input type="text" placeholder="Name*" name="name" id="name" @if(auth('web')->check() && isset(Auth::user()->user_name)) value="{{ Auth::user()->user_name }}" readonly @endif>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-inner">
                        <input type="text" placeholder="Company Name*" name="company_name" id="company_name" @if(auth('web')->check() && isset(Auth::user()->company_name)) value="{{ Auth::user()->company_name }}" readonly @endif>
                        @error('company_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-inner">
                        <input type="email" placeholder="Business Email*" name="email" id="email" @if(auth('web')->check() && isset(Auth::user()->email)) value="{{ Auth::user()->email }}" readonly @endif>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-inner">
                        <input type="text" placeholder="Phone number*" maxlength="12" name="phone" id="phone" @if(auth('web')->check() && isset(Auth::user()->phone)) value="{{ Auth::user()->phone }}" readonly @endif>
                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-inner">
                        <input type="text" placeholder="Subject*" name="subject" id="subject" value="">
                        @error('subject')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-inner">
                        <textarea name="message" placeholder="Message*" id="message"></textarea>
                        @error('message')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-inner">
                        {!! NoCaptcha::renderJs() !!}
                        {!! app('captcha')->display() !!}
                        <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
                        @error('g-recaptcha-response')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="submit-btn">
                        <span class="spinner-border spinner-border-sm me-2" style="display:none;" role="status" aria-hidden="true"></span>
                        <input type="Submit" value="Submit">
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
<!-- about-who-are-we-section end -->
@section('js')
<!-- js link -->
<script src="{!! asset('assets/frontend/js/pages/aboutus.js') !!}"></script>
@stop
@endsection