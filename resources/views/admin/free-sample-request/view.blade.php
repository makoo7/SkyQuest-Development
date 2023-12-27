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
                        @if(!auth('admin')->user()->hasAnyRole(['Marketing Admin']))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Name</label>
                                <div class="col-sm-10 form-control-value">
                                    {!! $samplerequest->name !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Email</label>
                                <div class="col-sm-10">
                                    {!! $samplerequest->email !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Phone</label>
                                <div class="col-sm-10 form-control-value">
                                {!! $samplerequest->phonecode !!}{!! $samplerequest->phone !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Company Name</label>
                                <div class="col-sm-10">
                                    {!! $samplerequest->company_name !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($samplerequest->country->name))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Country</label>
                                <div class="col-sm-10">
                                    {!! $samplerequest->country->name !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($samplerequest->designation))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Designation</label>
                                <div class="col-sm-10">
                                    {!! $samplerequest->designation !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($samplerequest->linkedin_link))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Linkedin Link</label>
                                <div class="col-sm-10">
                                    <a href="{!! $samplerequest->linkedin_link !!}" targe="_blank">{!! $samplerequest->linkedin_link !!}</a>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($samplerequest->message))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Message</label>
                                <div class="col-sm-10">
                                    {!! $samplerequest->message !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Created Date/Time</label>
                                <div class="col-sm-10">
                                {!! convertUtcToIst($samplerequest->created_at, config('constants.DISPLAY_DATE_TIME_FORMAT')) !!}
                                </div>
                            </div>
                        </div>
                        @php
                            $emailParts = explode('@', $samplerequest->email);
                            $emailDomain = end($emailParts);
                            if (isset($emailRestrictions[$emailDomain])) {
                                $category = $emailRestrictions[$emailDomain];
                            } else {
                                // $domains = 'aac.if.ac.uk';
                                $subparts = explode('.', $emailDomain); // Split the domain by .
                                $domain = end($subparts);
                                if (count($subparts) >= 2) {
                                    $subdomain = $subparts[count($subparts) - 2] . '.' . $domain;
                                    // dd($subdomain);
                            
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
                                    {!! $samplerequest->report->name !!}
                                </div>
                            </div>
                        </div>
                        @if(isset($samplerequest->report->country))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Country</label>
                                <div class="col-sm-10">
                                    {!! $samplerequest->report->country !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($samplerequest->report->report_type))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Report Type</label>
                                <div class="col-sm-10">
                                    {!! $samplerequest->report->report_type !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($samplerequest->report->report_pricing))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Price</label>
                                <div class="col-sm-10 form-control-value">                                
                                    ${!! number_format($samplerequest->report->report_pricing[0]['price'],0) !!}                    
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="line"> </div>
                    <div class="form-group row">
                        <div class="col-sm-12 text-center">
                            <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.free-sample-request.index') !!}';">Back</button>
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
