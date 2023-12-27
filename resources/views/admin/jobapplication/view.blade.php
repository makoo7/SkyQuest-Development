@extends('admin.layouts.app')
@section('content')
<section class="forms view-blog-sec">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Job Title</label>
                                <div class="col-sm-10 form-control-value">
                                {!! $jobapplication->career->position !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Name</label>
                                <div class="col-sm-10 form-control-value">
                                    {!! $jobapplication->name !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Email</label>
                                <div class="col-sm-10 form-control-value">
                                    {!! $jobapplication->email !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Phone</label>
                                <div class="col-sm-10 form-control-value">
                                    {!! $jobapplication->phone !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Work Experience</label>
                                <div class="col-sm-10 form-control-value">
                                    {!! $jobapplication->work_experience !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Notice Period</label>
                                <div class="col-sm-10 form-control-value">
                                    {!! $jobapplication->notice_period !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Current CTC</label>
                                <div class="col-sm-10 form-control-value">
                                    {!! $jobapplication->current_ctc !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Expected CTC</label>
                                <div class="col-sm-10 form-control-value">
                                    {!! $jobapplication->expected_ctc !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Resume</label>
                                <div class="col-sm-10 form-control-value">
                                    <a href="{!! url('admin/job-application/download/' . $jobapplication->id) !!}" target="_blank">Click here to download the Resume</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-sm-12">
                                <label class="col-sm-2 form-control-label">Portfolio/Web URL</label>
                                <div class="col-sm-10 form-control-value">
                                <a href="{!! $jobapplication->portfolio_or_web !!}" target="_blank">{!! $jobapplication->portfolio_or_web !!}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="line"> </div>
                    <div class="form-group row">
                        <div class="col-sm-12 text-center">
                            <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.job-application.index') !!}';">Back</button>
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
