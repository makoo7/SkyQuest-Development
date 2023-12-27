@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation-white")
<div class="careers-inner">
    <div class="container">
        <p>/Careers</p>
        <h1>{!!$careers->position!!}</h1>
        @if(isset($careers->exp_range))<p>Experience- From {!!$careers->exp_range!!} of Experience</p>@endif
        @if(isset($careers->location))<p>Location- {!!$careers->location!!}</p>@endif
        @if(isset($careers->salary_range))<p>Salary- {!!$careers->salary_range!!}</p>@endif
        <h2>Job Description </h2>
        <div class="job-des-content">
            {!!$careers->description!!}
        </div>
    </div>
    <div class="apply-form">
        <div class="container">
            <h1>Apply For This Job </h1>
            <form id="frmapplyjob" name="frmapplyjob" method="post" action="{!! url('careers/jobApply') !!}" enctype="multipart/form-data">
            @csrf
                <input type="hidden" name="career_id" id="career_id" value="{!! $careers->id !!}">
                <div class="apply-form-inner">
                    <div class="inner-inp mb-sm-4 ps-0">
                        <input type="text" class="form-control rounded-0 w-100" id="email" placeholder="Business Email*" name="email" @if(auth('web')->check() && isset(Auth::user()->email)) value="{{ Auth::user()->email }}" readonly @endif>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="inner-inp mb-sm-4 pe-0">
                        <input type="text" class="form-control rounded-0 w-100" id="phone" placeholder="Phone Number*" name="phone" @if(auth('web')->check() && isset(Auth::user()->phone)) value="{{ Auth::user()->phone }}" readonly @endif>
                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="inner-inp mb-sm-4 ps-0">
                        <input type="text" class="form-control rounded-0 w-100" id="first_name" placeholder="First Name*" name="first_name" @if(auth('web')->check() && isset(Auth::user()->user_name)) value="{{ Auth::user()->user_name }}" readonly @endif>
                        @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="inner-inp mb-sm-4 pe-0">
                        <input type="text" class="form-control rounded-0 w-100" id="last_name" placeholder="Last Name*" name="last_name">
                        @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="inner-inp mb-sm-4 ps-0">
                        <input type="text" class="form-control rounded-0 w-100" id="work_experience" placeholder="Work Experience*" name="work_experience">
                        @error('work_experience')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="inner-inp mb-sm-4 pe-0">
                        <input type="text" class="form-control rounded-0 w-100" id="notice_period" placeholder="Notice Period*" name="notice_period">
                        @error('notice_period')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="inner-inp mb-sm-4 ps-0">
                        <input type="text" class="form-control rounded-0 w-100" id="current_ctc" placeholder="Current CTC*" name="current_ctc">
                        @error('current_ctc')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="inner-inp mb-sm-4 pe-0">
                        <input type="text" class="form-control rounded-0 w-100" id="expected_ctc" placeholder="Expected CTC*" name="expected_ctc">
                        @error('expected_ctc')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="inner-inp mb-sm-4 ps-0">
                        <label for="#">Select Resume*</label>
                        <input type="file" class="form-control rounded-0 w-100" id="resume" placeholder="Select Resume*" name="resume">
                        @error('resume')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="inner-inp mb-sm-4 pe-0">
                        <input type="text" class="form-control rounded-0 w-100" id="portfolio_or_web" placeholder="Portfolio/Website URL*" name="portfolio_or_web">
                        @error('portfolio_or_web')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="inner-inp mb-sm-4 ps-0">
                        {!! NoCaptcha::renderJs() !!}
                        {!! app('captcha')->display() !!}
                        <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
                        @error('g-recaptcha-response')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="w-100">
                        <span class="spinner-border spinner-border-sm me-2" style="display:none;" role="status" aria-hidden="true"></span>
                        <input type="submit" name="btnsubmit" class="btn btn-dark rounded-0 btn-lg submit-btn" value="Submit">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@section('js')
<!-- js link -->
<script src="{!! asset('assets/frontend/js/pages/jobapplication.js') !!}"></script>
@stop
@endsection