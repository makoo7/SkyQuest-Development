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
                                    {!! $report_order->name !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Email</label>
                                <div class="col-sm-10">
                                    {!! $report_order->email !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Phone</label>
                                <div class="col-sm-10 form-control-value">
                                {!! $report_order->phonecode !!}{!! $report_order->phone !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Company Name</label>
                                <div class="col-sm-10">
                                    {!! $report_order->company_name !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($report_order->country->name))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Country</label>
                                <div class="col-sm-10">
                                    {!! $report_order->country->name !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($report_order->designation))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Designation</label>
                                <div class="col-sm-10">
                                    {!! $report_order->designation !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($report_order->linkedin_link))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Linkedin Link</label>
                                <div class="col-sm-10">
                                    <a href="{!! $report_order->linkedin_link !!}" targe="_blank">{!! $report_order->linkedin_link !!}</a>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($report_order->message))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Message</label>
                                <div class="col-sm-10">
                                    {!! $report_order->message !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Created Date/Time</label>
                                <div class="col-sm-10">
                                {!! convertUtcToIst($report_order->created_at, config('constants.DISPLAY_DATE_TIME_FORMAT')) !!}
                                </div>
                            </div>
                        </div>
                        @php
                            $emailParts = explode('@', $report_order->email);
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
                                    {!! $report_order->report->name !!}
                                </div>
                            </div>
                        </div>
                        @if(isset($report_order->report->country))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Report Country</label>
                                <div class="col-sm-10">
                                    {!! $report_order->report->country !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Report Type</label>
                                <div class="col-sm-10 form-control-value">                                
                                    {!! $report_order->report_type !!}                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">License Type</label>
                                <div class="col-sm-10 form-control-value">                                
                                {!! $report_order->license_type !!}                    
                                </div>
                            </div>
                        </div>   
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">File Type</label>
                                <div class="col-sm-10 form-control-value">                                
                                {!! $report_order->file_type !!}                    
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Payment Mode</label>
                                <div class="col-sm-10 form-control-value">                                
                                {!! $report_order->payment_method !!}                    
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Payment Status</label>
                                <div class="col-sm-10 form-control-value">                                
                                {!! $report_order->payment_status !!}                    
                                </div>
                            </div>
                        </div>                        
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Price</label>
                                <div class="col-sm-10 form-control-value">                                
                                    ${!! number_format($report_order->price,0) !!}                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="line"> </div>
                    <div class="form-group row">
                        <div class="col-sm-12 text-center">
                            <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.report-order.index') !!}';">Back</button>
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
