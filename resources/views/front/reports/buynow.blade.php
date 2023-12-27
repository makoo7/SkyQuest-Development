@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation-payment")
<div class="buy-now-container sr-form-row">
    <form class="buy-now-form-container" id="frmbuynow" name="frmbuynow" method="post" action="{!! route('redirectForPayment') !!}">
        @csrf
        <div class="description-wrapper">
            <div class="description-image">
                <a href="{!! route('report.details',$report->slug) !!}"><img src="{!! $report->image_url !!}" class="report-detail-img-2" title="{!! $report->image_alt !!}" alt="{!! $report->image_alt !!}"></a>
                <div class="discription">
                    <div>
                        <p class="report-name"><a href="{!! route('report.details',$report->slug) !!}" class="text-dark">{!! $report_name !!}</a></p>
                        <p class="report-fromat">Report format:
                            {{ implode(", ",$report->report_pricing->unique('license_type')->pluck('license_type')->toArray())}}
                        </p>
                    </div>
                    <div class="descrption-link-warrper">
                        <a href="{!! route('report.details',$report->slug) !!}">VIEW REPORT</a>
                        <a href="{!! url('reports') !!}">REMOVE</a>
                    </div>
                </div>
            </div>
            <div class="buy-now-form">
                <h6>Please register your details.</h6>
                <div class="request-sample-content">
                    <div class="row">
                        <div class="col-sm-12 mb-3">
                            <input type="hidden" id="report_id" name="report_id" value="{!! $report->id !!}">
                        </div>
                        <div class="col-sm-12 mb-3">
                            <input type="text" class="form-control" placeholder="Full name*" id="name" name="name" @if(auth('web')->check() && isset(Auth::user()->user_name)) value="{{ Auth::user()->user_name }}" readonly @endif>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-12 mb-3">
                            <input type="text" class="form-control" placeholder="Business Email*(Please avoid gmail/yahoo/hotmail IDs)" name="email" id="email" @if(auth('web')->check() && isset(Auth::user()->email)) value="{{ Auth::user()->email }}" readonly @endif>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-12 select2-view mb-3">
                            <div class="phone-row">
                                <div class="code-col">
                                    <select class="form-select" placeholder="Phone Code*" name="phonecode" id="phonecode">
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
                        <div class="col-sm-6 mb-3">
                            <input type="text" class="form-control" placeholder="Company*" name="company_name" id="company_name" @if(auth('web')->check() && isset(Auth::user()->company_name)) value="{{ Auth::user()->company_name }}" readonly @endif>
                            @error('company_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-6 mb-3">
                            <input type="text" class="form-control" placeholder="Job Title" name="designation" id="designation" value="">
                            @error('designation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-12 mb-3">
                            <input type="text" name="linkedin_link" placeholder="LinkedIn Profile Link" id="linkedin_link" class="form-control">
                        </div>
                        <div class="col-sm-12 mb-3">
                            <textarea class="form-control pt-10 pl-20" name="message" id="message" placeholder="Your Research Requirements"></textarea>
                            @error('message')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-12">
                            <div class="term-and-condition">
                                <input type="checkbox" class="mr-10" name="terms" value="true" checked="">
                                <div>I have read and agree to the<a class="ml-5" href="/privacy">&nbsp; Terms &amp; Conditions.</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- START CARDS COL -->
        <div class="price-card-container-wrraper">
            <div class="price-card-container">
                <h5>Report Type</h5>
                <div class="report-type-basic">
                    <span>Base Price</span>
                    @if(isset($report->report_pricing) && !$report->report_pricing->isEmpty())
                    <span>${!! number_format($report->report_pricing[0]['price'],0) !!}</span>
                    @endif
                </div>
                <div class="price-param-selector-container">
                    <div class="price-param-selector">
                        <div class="price-param-type-dropdown-wrapper">
                            <p class="license-type">License Type</p>
                            @if(isset($report->report_pricing) && !$report->report_pricing->isEmpty())
                            <select class="price-param-type-dropdown w-100" name="license_type" id="license_type" onchange="getFileType('{!! $report->id !!}',this.value)">
                            @foreach($report->report_pricing->unique('license_type') as $pricing)
                                <option value="{!! $pricing->license_type !!}">{!! $pricing->license_type !!}</option>
                            @endforeach
                            </select>
                            @endif
                        </div>
                        <div class="price-param-type-dropdown-wrapper">
                            <p class="license-type">File Type</p>
                            @if(isset($report->report_pricing) && !$report->report_pricing->isEmpty())
                            <select class="price-param-type-dropdown w-100" id="file_type" name="file_type">
                            @foreach($report->report_pricing->unique('file_type') as $pricing)
                                <option value="{!! $pricing->file_type !!}">{!! $pricing->file_type !!}</option>
                            @endforeach
                            </select>
                            @endif
                        </div>
                    </div>
                </div>
                @if(isset($report->report_pricing) && !$report->report_pricing->isEmpty())
                <span class="report-price" id="report_price" name="report_price">${!! number_format($report->report_pricing[0]['price'],0) !!}</span>
                <input type="hidden" name="price" id="price" value="{!! number_format($report->report_pricing[0]['price'],0) !!}">
                @endif
            </div>
            <div class="payment-methods">
                <h5>Payment Methods</h5>
                <div>
                    <input type="radio" class="mr-10" name="payment_methods" value="Razorpay" checked="" id="RazorpayOption">
                    <img src="{!! asset('assets/frontend/images/pay-razorpay.png') !!}" class="payment-method-images mr-4" alt="Razorpay">
                </div>
                <div>
                    <input type="radio" class="mr-10" name="payment_methods" value="Stripe">
                    <img src="{!! asset('assets/frontend/images/pay-stripe.png') !!}" class="payment-method-images mr-4" alt="stripe">
                </div>
                <div id="PaypalMethod">
                    <input type="radio" class="mr-10" name="payment_methods" value="Paypal" id="PaypalOption">
                    <img src="{!! asset('assets/frontend/images/pay-paypal.webp') !!}" class="payment-method-images mr-4" alt="Paypal">
                </div>
                <!-- <div>
                    <input type="radio" class="mr-10" name="payment_methods" value="AmericanExpress">
                    <img src="{!! asset('assets/frontend/images/pay-american.jpeg') !!}" class="payment-method-images mr-4" alt="AmericanExpress">
                </div> -->
                <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="">
                <input type="hidden" name="orderData" id="orderData" value="">
                <input type="hidden" name="report_order_id" id="report_order_id" value="">
                <button type="button" class="btn proceed-btn" id="proccedBtn">PROCEED</button>
            </div>
        </div>
        <!-- END CARDS COL -->
    </form>
</div>
<!-- paypal-modal -->
<div class="modal fade payment-modal" id="paypalModal" tabindex="-1" role="dialog" aria-labelledby="paypalModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="paypal-payment-form-modal">
                    <div class="card">
                        <div class="header-wrapper">
                            <img src="{!! asset('assets/frontend/images/sq-logo.webp') !!}" alt="sq-logo"/>
                            <div class="card-details">
                                <h5>SkyQuest Technology</h5>
                                <p>{!! $report->name !!}</p>
                                <h4 class="price"></h4>
                            </div>
                        </div>
                        <div>
                            <div id="paypal-button-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- paypal-modal -->
@section('js')
<script src="{!! asset('assets/frontend/js/pages/buynow.js') !!}"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@if(config('constants.PAYPAL_MODE')=='sandbox')
<script src="https://www.paypal.com/sdk/js?client-id={!! config('constants.PAYPAL_SANDBOX_CLIENT') !!}&currency=USD"></script>
@else
<script src="https://www.paypal.com/sdk/js?client-id={!! config('constants.PAYPAL_LIVE_CLIENT') !!}&currency=USD"></script>
@endif
<script>
$('#proccedBtn').on('click', function(event){
    if($("#frmbuynow").valid()){

        var selpaymentMethod = $("input[name='payment_methods']:checked").val();

        // save in form data in report order table
        $.ajax({
            type: "POST",
            data: $('#frmbuynow').serialize(),
            headers: {
                'X-CSRF-TOKEN': _token
            },
            url: baseUrl + "saveReportOrder",
            success: function (data) {
                if (data.success == "1") {
                    $("#report_order_id").val(data.report_order_id);                    
                    if(selpaymentMethod=='Stripe'){
                        $("#frmbuynow").submit();
                    }
                }
            },
        });        

        var report_id = $("#report_id").val();
        var license_type = $("#license_type").val();
        var file_type = $("#file_type").val();
        var final_report_price = getFinalReportPrice(report_id, license_type, file_type);

        var baseprice = $("#price").val();
        var totalAmount = (baseprice.replace("$","")).replace(",","");
        if(!final_report_price) final_report_price = totalAmount;
        
        if(selpaymentMethod=='Razorpay'){
            var options = {
                "key": "{!! (config('constants.RAZORPAY_MODE') == 'test') ? config('constants.RAZORPAY_KEY_TEST') : config('constants.RAZORPAY_KEY_LIVE') !!}",
                "amount": (final_report_price*100), // 2000 paise = INR 20
                "currency": "USD",
                "name": "SkyQuest",
                "description": "Payment",
                "image": "",
                "handler": function (response){
                    /*console.log('payment_id='+response.razorpay_payment_id+' order_id='+response.razorpay_order_id+' sign_id='+response.razorpay_signature);*/
                    $("#razorpay_payment_id").val(response.razorpay_payment_id);
                    $("#frmbuynow").submit();
                },
                "theme": {
                    "color": "#528FF0"
                }
            };

            var rzp1 = new Razorpay(options);

            rzp1.on('payment.failed', function (response){
                /*console.log('code='+response.error.code+'description='+response.error.description+'source='+response.error.source'step='+response.error.step+'reason='+response.error.reason+'order_id='+response.error.metadata.order_id+'payment_id='+response.error.metadata.payment_id);*/                
                $("#razorpay_payment_id").val(response.metadata.payment_id);
                $("#frmbuynow").submit();
            });

            rzp1.open();
            event.preventDefault();
        }

        if(selpaymentMethod=='Paypal'){

            $('#paypalModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            
            var report_order_id = $("#report_order_id").val();

            $(".price").html("$"+baseprice);
            $('#paypalModal').modal('show');

            function initPayPalButton(final_report_price,report_order_id) {
                // Render the PayPal button into #paypal-button-container
                paypal.Buttons({
                    // Call your server to set up the transaction
                    createOrder: function(data, actions){
                        return actions.order.create({
                            purchase_units: [
                                {
                                    amount: {
                                        value: final_report_price,
                                    },
                                    custom_id: report_order_id,
                                },
                            ],
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(orderData) {
                            $("#orderData").val(JSON.stringify(orderData, null, 2));
                            $("#frmbuynow").submit();
                        });
                    },
                    onError: function(err) {
                        console.log(err);
                    }

                }).render('#paypal-button-container');
            }

            initPayPalButton(final_report_price,report_order_id);
            event.preventDefault();
        }
    }
});
</script>
@stop
@endsection