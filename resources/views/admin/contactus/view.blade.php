@extends('admin.layouts.app')
@section('content')
<section class="forms view-blog-sec">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if(!auth('admin')->user()->hasAnyRole(['Marketing Admin']))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Name</label>
                                <div class="col-sm-10 form-control-value">
                                    {!! $contactus->name !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Email</label>
                                <div class="col-sm-10">
                                    {!! $contactus->email !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Phone</label>
                                <div class="col-sm-10 form-control-value">
                                    {!! $contactus->phone !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Company Name</label>
                                <div class="col-sm-10">
                                    {!! $contactus->company_name !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Subject</label>
                                <div class="col-sm-10">
                                    {!! $contactus->subject !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Message</label>
                                <div class="col-sm-10">
                                    {!! $contactus->message !!}
                                </div>
                            </div>
                        </div>
                        @php
                            $emailParts = explode('@', $contactus->email);
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
                    <div class="line"> </div>
                    <div class="form-group row">
                        <div class="col-sm-12 text-center">
                            <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.contactus.index') !!}';">Back</button>
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
