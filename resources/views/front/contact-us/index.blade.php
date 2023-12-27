@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation-white")
<!-- get-in-touch start -->
<div class="get-in-touch">
    <div class="container">
        <div class="getin-touch-inner">
            <h1 class="mb-lg-4">Get in touch</h1>
            <a href="mailto:info@skyquestt.com">
                <img src="{!! asset('assets/frontend/images/email-text-underline.svg') !!}" width="1078" height="109"
                    alt="emai-text-img" style="height:auto;" />
            </a>
        </div>
    </div>
</div>
<!-- get-in-touch end -->
<!-- contctus start -->
<div class="contactus">
    <div class="container">
        <div class="contactus-inner">
            <h2>Contact Us</h2>
            <p>Our experts understand and guide your project with tailor made solutions just for you. You can connect
                with us via various medium.</p>
            <div class="social-media-icn">
                <div class="social-icn">
                    <div class="social-inner">
                        <a href="tel:9898 090 605">
                            <img src="{!! asset('assets/frontend/images/contact-call.svg') !!}" alt="contact-call">
                        </a>
                    </div>
                </div>
                <div class="social-icn">
                    <div class="social-inner">
                        <a href="https://wa.me/9898090605">
                            <img src="{!! asset('assets/frontend/images/contact-whatsapp.svg') !!}"
                                alt="contact-whatsapp">
                        </a>
                    </div>
                </div>
                <div class="social-icn">
                    <div class="social-inner">
                        <a href="mailto:info@skyquestt.com">
                            <img src="{!! asset('assets/frontend/images/contact-email.webp') !!}" alt="contact-email">
                        </a>
                    </div>
                </div>
                <div class="social-icn">
                    <div class="social-inner">
                        <a href="https://www.linkedin.com/company/skyquest-technology-consulting-private-limited/"
                            target="_blank">
                            <img src="{!! asset('assets/frontend/images/contact-linkedin.webp') !!}"
                                alt="contact-linkedin">
                        </a>
                    </div>
                </div>
                <div class="social-icn">
                    <div class="social-inner">
                        <a href="https://www.facebook.com/STI4SDG/" target="_blank">
                            <img src="{!! asset('assets/frontend/images/contact-facebook.webp') !!}"
                                alt="contact-facebook">
                        </a>
                    </div>
                </div>
            </div>
            <button class="contact-booking-btn" data-bs-toggle="modal" data-bs-target="#book-appointment">Book An
                Appointment</button>
        </div>
    </div>
</div>
<!-- contactus end -->
<!-- located-at start -->
<div class="located">
    <div class="container">
        <div class="located-inner">
            <h2>We Are Located At</h2>
            <div class="address">
                <div class="ad-inner">
                    <p>USA</p>
                    <p>1 Apache Way, Westford, Massachusetts 01886</p>
                </div>
                <div class="ad-inner">
                    <p>Hongkong</p>
                    <p>Room A & B, 2nd Floor,Lee Kee Commercial Building,</p>
                    <p>221-227 Queen's Road Central</p>
                </div>
                <div class="ad-inner">
                    <p>India</p>
                    <p>D-1001-1005, Swati Clover, Shilaj Circle, Sardar Patel Ring Rd,</p>
                    <p>Thaltej, Ahmedabad, 380054</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- located-at end -->
<!-- contact us form modal -->
<div class="modal fade contact-booking con-resize-modal" id="book-appointment" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Book an Appointment</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmbookappointment" name="frmbookappointment" method="post"
                    action="{!! route('book-appointment') !!}">
                    @csrf
                    <input type="text" class="modal-input w-100 mt-20" readonly style="user-select:none"
                        placeholder="Appointment Time*" name="appointment_time" id="appointment_time" value="">
                    @error('appointment_time')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <input type="text" class="modal-input w-100 mt-20" placeholder="Name*" name="name" id="name"
                        minlength="3" @if(auth('web')->check() && isset(Auth::user()->user_name))
                    value="{{ Auth::user()->user_name }}" readonly @endif>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <input type="text" class="modal-input w-100 mt-20 " placeholder="Phone Number*" name="phone"
                        id="phone" minlength="8" maxlength="12" @if(auth('web')->check() && isset(Auth::user()->phone))
                    value="{{ Auth::user()->phone }}" readonly @endif>
                    @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <input type="email" class="modal-input w-100 mt-20 " placeholder="Business Email*" name="email" id="email"
                        @if(auth('web')->check() && isset(Auth::user()->email)) value="{{ Auth::user()->email }}"
                    readonly @endif>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <input type="text" class="modal-input w-100 mt-20 mb-20" placeholder="Company Name*"
                        name="company_name" id="company_name" minlength="3" @if(auth('web')->check() &&
                    isset(Auth::user()->company_name)) value="{{ Auth::user()->company_name }}" readonly @endif>
                    @error('company_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <div class="pass-input-grp mt-20">
                        {!! NoCaptcha::renderJs() !!}
                        {!! app('captcha')->display() !!}
                        <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha"
                            id="hiddenRecaptcha">
                        @error('g-recaptcha-response')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <a href="#"><button class="modal-login-btn" type="submit" name="contactfrm">
                            <span class="spinner-border spinner-border-sm me-2" style="display:none;" role="status"
                                aria-hidden="true"></span>
                            Submit
                        </button></a>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- contact us form modal -->
@section('js')
<!-- js link -->
<script src="{!! asset('assets/frontend/js/pages/bookappointment.js') !!}"></script>
@if((request()->routeIs('contact-us')) && (request('contactfrm')) && ($errors->has('appointment_time') || $errors->has('name') || $errors->has('email') || $errors->has('company_name')))
<script>
$(function() {
    $('#book-appointment').modal('show');
});
</script>
@endif
@stop
@endsection