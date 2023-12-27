@extends('admin.layouts.app')
@section('content')
<section class="forms view-blog-sec">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Requester's Information</h5>
                        <hr>  
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Subscription Plan</label>
                                <div class="col-sm-10 form-control-value">
                                    {!! ucfirst($report_subscription->plan) !!}
                                </div>
                            </div>
                        </div>              
                        @if(!auth('admin')->user()->hasAnyRole(['Marketing Admin']))                       
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Name</label>
                                <div class="col-sm-10 form-control-value">
                                    {!! $report_subscription->name !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Email</label>
                                <div class="col-sm-10">
                                    {!! $report_subscription->email !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Phone</label>
                                <div class="col-sm-10 form-control-value">
                                {!! $report_subscription->phonecode !!}{!! $report_subscription->phone !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Company Name</label>
                                <div class="col-sm-10">
                                    {!! $report_subscription->company_name !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($report_subscription->country->name))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Country</label>
                                <div class="col-sm-10">
                                    {!! $report_subscription->country->name !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($report_subscription->designation))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Designation</label>
                                <div class="col-sm-10">
                                    {!! $report_subscription->designation !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($report_subscription->linkedin_link))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Linkedin Link</label>
                                <div class="col-sm-10">
                                    <a href="{!! $report_subscription->linkedin_link !!}" target="_blank">{!! $report_subscription->linkedin_link !!}</a>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($report_subscription->message))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Message</label>
                                <div class="col-sm-10">
                                    {!! $report_subscription->message !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Created Date/Time</label>
                                <div class="col-sm-10">
                                {!! convertUtcToIst($report_subscription->created_at, config('constants.DISPLAY_DATE_TIME_FORMAT')) !!}
                                </div>
                            </div>
                        </div>  
                        @php
                        $emailParts = explode('@', $report_subscription->email);
                        $emailDomain = end($emailParts);
                        if (isset($emailRestrictions[$emailDomain])) 
                        {
                            $category = $emailRestrictions[$emailDomain];
                        } 
                        else 
                        {
                            $subparts = explode('.', $emailDomain); // Split the domain by .
                            $domain = end($subparts);
                            if (count($subparts) >= 2) {
                                $subdomain = $subparts[count($subparts) - 2] . '.' . $domain;
                        
                                if (isset($emailRestrictions[$subdomain])) {
                                    $category = $emailRestrictions[$subdomain];
                                } else {
                                    $category = 'Corporate';
                                }
                            } else {
                                $category = 'Corporate';
                            }
                        }
                    @endphp
                    <div class="row">
                        <div class="form-group row col-sm-12">
                            <label class="col-sm-2 form-control-label">Legal Category</label>
                            <div class="col-sm-10">
                                {!! $category !!}
                            </div>
                        </div>
                    </div>                          
                    </div>
                    <div class="card-body pt-0">
                        <h5>Report's Information</h5>            
                        <hr>    
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Report Name</label>
                                <div class="col-sm-10 form-control-value">
                                    {!! $report_subscription->report->name !!}
                                </div>
                            </div>
                        </div>
                        @if(isset($report_subscription->report->country))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Country</label>
                                <div class="col-sm-10">
                                    {!! $report_subscription->report->country !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($report_subscription->report->report_type))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Report Type</label>
                                <div class="col-sm-10">
                                    {!! $report_subscription->report->report_type !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($report_subscription->report->report_pricing))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Price</label>
                                <div class="col-sm-10 form-control-value">                                
                                    ${!! number_format($report_subscription->report->report_pricing[0]['price'],0) !!}                    
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="line"> </div>
                    <div class="form-group row">
                        <div class="col-sm-12 text-center">
                            <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.report-subscription.index') !!}';">Back</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@section('js')
@stop
@endsection
