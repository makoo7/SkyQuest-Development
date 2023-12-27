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
                                    {!! $pagenotfound->name !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Email</label>
                                <div class="col-sm-10">
                                    {!! $pagenotfound->email !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Phone</label>
                                <div class="col-sm-10 form-control-value">
                                    {!! $pagenotfound->phone !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Company Name</label>
                                <div class="col-sm-10">
                                    {!! $pagenotfound->company_name !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Country</label>
                                <div class="col-sm-10">
                                    {!! $pagenotfound->country->name !!}
                                </div>
                            </div>
                        </div>
                        @if(isset($pagenotfound->designation))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Designation</label>
                                <div class="col-sm-10">
                                    {!! $pagenotfound->designation !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($pagenotfound->description))
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Description</label>
                                <div class="col-sm-10">
                                    {!! $pagenotfound->description !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Created Date/Time</label>
                                <div class="col-sm-10">
                                {!! convertUtcToIst($pagenotfound->created_at, config('constants.DISPLAY_DATE_TIME_FORMAT')) !!}
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="line"> </div>
                    <div class="form-group row">
                        <div class="col-sm-12 text-center">
                            <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.404-inquiry.index') !!}';">Back</button>
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
