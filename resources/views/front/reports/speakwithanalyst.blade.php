@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation-white")
<div class="report-details-page">
    <div class="container">
        <nav class="upcoming-report-detail-breadcrumb min-height-78">
            <ol class="MuiBreadcrumbs-ol css-nhb8h9">
                <li class="MuiBreadcrumbs-li">
                    <a href="#" class="breadLinkColor" >Home</a>
                </li>
                <li class="MuiBreadcrumbs-separator">›</li>
                <li class="MuiBreadcrumbs-li">
                    <a href="{!! url('industries/'.$report->sector->slug) !!}" class="breadLinkColor">{!! $report->sector->title !!}</a>
                </li>
                <li class="MuiBreadcrumbs-separator">›</li>
                <li class="MuiBreadcrumbs-li">
                    <a href="{!! url('industries/'.$report->industry_group->slug) !!}" class="breadLinkColor">{!! $report->industry_group->title !!}</a>
                </li>
                <li class="MuiBreadcrumbs-separator">›</li>
                <li class="MuiBreadcrumbs-li">
                    <a href="{!! url('industries/'.$report->industry->slug) !!}" class="breadLinkColor">{!! $report->industry->title !!}</a>
                </li>
                <li class="MuiBreadcrumbs-separator">›</li>
                <li class="MuiBreadcrumbs-li">
                    <a href="{!! url('industries/'.$report->sub_industry->slug) !!}" class="breadLinkColor">{!! $report->sub_industry->title !!}</a>
                </li>
                <li class="MuiBreadcrumbs-separator">›</li>
                <li class="MuiBreadcrumbs-li">
                    <span>{!! $report->name !!}</span>
                </li>
            </ol>
        </nav>
        <div class="upcoming-report-container reports">
            <div class="upcoming-report-detail-container">
                <div class="report-items-inner">
                    <div class="report-img2">
                    <a href="{!! route('report.details',$report->slug) !!}"><img src="{!! $report->image_url !!}" alt="{!! $report->image_alt !!}" width="100" height="120"></a>
                    </div>
                    <div class="containt" style="flex:1">
                        <h1><a href="{!! route('report.details',$report->slug) !!}">{!! $report_name !!}</a></h1>
                        <div class="report-segment-data max-width-640">
                            <hr/>
                            <p class="grey-content">
                                @if(isset($report->product_id))
                                <b>Report ID:</b>
                                {!! $report->product_id. ' | ' !!}
                                @endif

                                @if(isset($report->country))
                                <b>Region:</b>
                                {!! ucfirst($report->country). ' | ' !!}
                                @endif

                                @if($report->report_type=='Upcoming')
                                    <b>Published Date:</b> Upcoming |
                                @elseif(($report->report_type!='Upcoming') && isset($report->publish_date))
                                    <b>Published Date:</b> {!! convertUtcToIst($report->publish_date, config('constants.DISPLAY_REPORT_DATE')) .' | ' !!}
                                @endif

                                @if(isset($report->pages))
                                <b>Pages:</b>
                                {!! $report->pages !!}
                                @endif

                                @if($report->report_type=='Upcoming')
                                | <b>Tables:</b> 55 | <b>Figures:</b> 60
                                @else
                                    @if(!$report->report_tableofcontent->isEmpty())
                                        @if(($report->report_tableofcontent[0]->tables!='') || ($report->report_tableofcontent[0]->figures!=''))
                                        |
                                        @endif

                                        @if($report->report_tableofcontent[0]->tables!='')
                                        <b>Tables:</b>
                                        {!! ($report->report_tableofcontent[0]->tables!='') ? substr_count($report->report_tableofcontent[0]->tables,"<p>") .' | ' : '' !!}
                                        @endif

                                        @if($report->report_tableofcontent[0]->figures!='')
                                        <b>Figures:</b>
                                        {!! ($report->report_tableofcontent[0]->figures!='') ? substr_count($report->report_tableofcontent[0]->figures,"<p>")  : '' !!}
                                        @endif
                                    @endif
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row sr-form-row my-5">
                    <div class="col-xl-12 col1">
                        <h6>Inquiry</h6>
                        <form class id="frmspeakwithanalyst" name="frmspeakwithanalyst" method="post" action="{!! route('speak-with-analyst') !!}">
                        @csrf
                        <div class="contct-form row">
                            <div class="col-12">
                                <input class="form-control" type="hidden" id="report_id" name="report_id" value="{!! $report->id !!}">
                            </div>
                            <div class="form-inner col-6 mb-3">
                                <input class="form-control" type="text" placeholder="First Name*" name="name" id="name" @if(auth('web')->check() && isset(Auth::user()->user_name)) value="{{ Auth::user()->user_name }}" readonly @endif>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-inner col-6 mb-3">
                                <input class="form-control" type="text" placeholder="Last Name*" name="lastname" id="lastname" @if(auth('web')->check() && isset(Auth::user()->lastname)) value="{{ Auth::user()->lastname }}" readonly @endif>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-inner col-sm-12 mb-3">
                                <input class="form-control" type="email" placeholder="Business Email*(Please avoid gmail/yahoo/hotmail IDs)" name="email" id="email" @if(auth('web')->check() && isset(Auth::user()->email)) value="{{ Auth::user()->email }}" readonly @endif>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-inner col-sm-6 mb-3 select2-view">
                                <div class="phone-row">
                                    <div class="code-col">
                                        <select class="form-control"placeholder="Phone Code*" name="phonecode" id="phonecode">
                                            <option value="">Country Code*</option>
                                            @if($countries)
                                            @foreach($countries as $country)
                                            <option value="{!! $country->id !!}:{!! $country->phonecode !!}" @if($country->id=='236' && $country->phonecode=='+1') selected @endif>{!! $country->name !!} ({!! $country->phonecode !!})</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @error('phonecode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="input-col">
                                        <input class="form-control" type="text" placeholder="Phone Number*(without country code)" maxlength="12" name="phone" id="phone" @if(auth('web')->check() && isset(Auth::user()->phone)) value="{{ Auth::user()->phone }}" readonly @endif>
                                        @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-inner col-sm-6 mb-3">
                                <input class="form-control" type="text" placeholder="Company Name*" name="company_name" id="company_name" @if(auth('web')->check() && isset(Auth::user()->company_name)) value="{{ Auth::user()->company_name }}" readonly @endif>
                                @error('company_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-inner col-sm-6 mb-3">
                                <input class="form-control" type="text" placeholder="Job Title*" name="designation" id="designation" value="">
                                @error('designation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-inner col-sm-6 mb-3">
                                <input type="text" name="linkedin_link" placeholder="LinkedIn Profile Link" id="linkedin_link" class="form-control">
                            </div>
                            <div class="form-inner col-sm-12 mb-3">
                                <textarea name="message" placeholder="Your Research Requirements" id="message" class="form-control"></textarea>
                                @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-inner col-sm-12 mb-3">
                                {!! NoCaptcha::renderJs() !!}
                                {!! app('captcha')->display() !!}
                                <input class="form-control" type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
                                @error('g-recaptcha-response')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-inner col-sm-12 mb-3">
                                <input type="checkbox" checked="checked" style="height:auto" name="privacy_policy" id="privacy_policy">&nbsp;&nbsp;&nbsp;I acknowledge that I have read the&nbsp;<a href="/privacy">Privacy Policy</a>
                            </div>
                            <div class="submit-btn col-sm-12">
                                <span class="spinner-border spinner-border-sm me-2" style="display:none;" role="status" aria-hidden="true"></span>
                                <input class="form-control" type="Submit" value="Submit">
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="col-xl-12 col2" style="padding: 30px;display: block;">
                        <div class="privacy-plcy">
                            <h6 class="mb-3">Confidentiality</h6>
                            <p class="col2-para2 mb-0">
                                We respect your privacy rights and safeguard your personal information. We prevent the disclosure of personal information to third parties.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start Right Sicky Section -->
            <div class="price-card-container-wrraper">
                <div class="price-card-container">
                    <div class="select-row">
                        <input class="form-control" type="hidden" id="report_id" name="report_id" value="{!! $report->id !!}">
                        <div class="select-col">
                            <label for="#">License Type
                                <img
                                    src="{!! asset('assets/frontend/images/icons8-eye-24.webp') !!}"
                                    title="Select license type suitable for your business requirement Single: 1 User | Multiple: 5 Users | Enterprise: Team License Pricing is variable based on license type."
                                    data-bs-toggle="tooltip" data-bs-placement="right" alt="Top market research company in USA"
                                />
                            </label>
                            @if(isset($report->report_pricing))
                            <select class="custom-select1 Popular" id="license_type" onchange="getFileType('{!! $report->id !!}',this.value)">
                            @foreach($report->report_pricing->unique('license_type') as $pricing)
                                <option value="{!! $pricing->license_type !!}">{!! $pricing->license_type !!}</option>
                            @endforeach
                            </select>
                            @endif
                        </div>
                        <div class="select-col">
                            <label for="#">File Type</label>
                            @if(isset($report->report_pricing))
                            <select class="custom-select1 Popular" id="file_type">
                            @foreach($report->report_pricing->unique('file_type') as $pricing)
                                <option value="{!! $pricing->file_type !!}">{!! $pricing->file_type !!}</option>
                            @endforeach
                            </select>
                            @endif
                        </div>
                    </div>
                    <div>Analyst Support</div>
                    <div class="report-price" id="report_price">
                    @if(isset($report->report_pricing))
                        ${!! number_format($report->report_pricing[0]['price'],0) !!}
                    @endif
                    </div>
                    <a href="{!! url('buy-now/'.$report->slug) !!}" class="btn pre-book-btn">BUY NOW</a>
                    <a href="{!! url('sample-request/'.$report->slug) !!}" class="btn get-sample-btn">GET SAMPLE</a>
                </div>
                <div class="subscribe-now">
                    <h6>Subscribe &amp; Save</h6>
                    <p>Get lifetime access to our insights</p>
                    <a href="#" class="subscribe-box-btn active">
                        <span>Basic Plan</span>
                        <strong>$5,000</strong>
                    </a>
                    <a href="#" class="subscribe-box-btn">
                        <span>Team Plan</span>
                        <strong>$10,000</strong>
                    </a>
                    <a href="{!! url('subscribe-now/'.$report->slug) !!}" class="btn btn-subscribe">SUBSCRIBE NOW</a>
                </div>
            </div>
            <!-- END Right Sicky Section -->
        </div>
    </div>
</div>

<!-- START FAQ SECTION -->
@if(!$report->report_faq->isEmpty())
@include("front.layouts.report-faq")
@endif
<!-- END FAQ SECTION -->

<!-- START RELATED REPORTS SECTION -->
@if(!$related_reports->isEmpty())
@include("front.layouts.report-related")
@endif
<!-- END RELATED REPORTS SECTION -->

<!-- START CLIENTS FEEDBACK SECTION -->
@if(!$clientfeedbacks->isEmpty())
@include("front.layouts.report-feedback")
@endif
<!-- END CLIENTS FEEDBACK SECTION -->

<section class="sticky-poroduct">
    <div class="container">
        <div class="product-view">
            <div class="content">
                <img src="{!! $report->image_url !!}" alt="{!! $report->image_alt !!}"/>
                <div id="stickyPrice">
                    <p>Product ID: {!! $report->product_id !!}</p>
                    @if(isset($report->report_pricing))
                    <h5>${!! number_format($report->report_pricing[0]['price'],0) !!}</h5>
                    @endif
                </div>
            </div>
            <div>
                <a href="{!! url('buy-now/'.$report->slug) !!}" class="btn btn-gradient">BUY NOW</a>
                <a href="{!! url('sample-request/'.$report->slug) !!}" class="btn btn-outline-orange">GET SAMPLE</a>
            </div>
        </div>
    </div>
</section>

@section('js')
<script src="{!! asset('assets/frontend/js/pages/speakwithanalyst.js') !!}"></script>
@stop
@endsection
