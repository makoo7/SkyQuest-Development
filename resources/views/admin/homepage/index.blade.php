@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! route('admin.homepage.update') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{!! isset($homepage->id) ? $homepage->id : '' !!}">
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label mb-2">Enable/Disable page content</label>                                        
                                        <div class="custom-switch">
                                            <div class="row">
                                                <div class="col-sm-3 mb-3">
                                                    <div class="border p-2">
                                                        <input type="checkbox" class="custom-control-input" id="is_case_study" name="is_case_study" value="1" {!! isset($homepage->is_case_study) ? ($homepage->is_case_study == '1') ? 'checked' : '' : '' !!} >
                                                        <label class="custom-control-label" for="is_case_study">Case Study</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 mb-3">
                                                    <div class="border p-2">
                                                        <input type="checkbox" class="custom-control-input" id="is_feedback" name="is_feedback" value="1" {!! isset($homepage->is_feedback) ? ($homepage->is_feedback == '1') ? 'checked' : '' : '' !!} >
                                                        <label class="custom-control-label" for="is_feedback">Feedback</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 mb-3">
                                                    <div class="border p-2">
                                                        <input type="checkbox" class="custom-control-input" id="is_help" name="is_help" value="1" {!! isset($homepage->is_help) ? ($homepage->is_help == '1') ? 'checked' : '' : '' !!} >
                                                        <label class="custom-control-label" for="is_help">Help Section</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 mb-3">
                                                    <div class="border p-2">
                                                        <input type="checkbox" class="custom-control-input" id="is_insights" name="is_insights" value="1" {!! isset($homepage->is_insights) ? ($homepage->is_insights == '1') ? 'checked' : '' : '' !!} >
                                                        <label class="custom-control-label" for="is_insights">Insights</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 mb-3">
                                                    <div class="border p-2">
                                                        <input type="checkbox" class="custom-control-input" id="is_process" name="is_process" value="1" {!! isset($homepage->is_process) ? ($homepage->is_process == '1') ? 'checked' : '' : '' !!} >
                                                        <label class="custom-control-label" for="is_process">Our Process</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 mb-3">
                                                    <div class="border p-2">
                                                        <input type="checkbox" class="custom-control-input" id="is_products" name="is_products" value="1" {!! isset($homepage->is_products) ? ($homepage->is_products == '1') ? 'checked' : '' : '' !!} >
                                                        <label class="custom-control-label" for="is_products">Products</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 mb-3">
                                                    <div class="border p-2">
                                                        <input type="checkbox" class="custom-control-input" id="is_awards" name="is_awards" value="1" {!! isset($homepage->is_awards) ? ($homepage->is_awards == '1') ? 'checked' : '' : '' !!} >
                                                        <label class="custom-control-label" for="is_awards">Awards</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3">
                                        <label class="form-control-label">Client Feedback</label>
                                        <select name="clientfeedback_ids[]" id="clientfeedback_ids[]" class="form-control" multiple>
                                            @foreach ($clientfeedbacks as $clientfeedback)
                                                <option value="{{$clientfeedback->id}}" @if(in_array($clientfeedback->id, $sel_clientfeedbacks)) selected @endif>{{$clientfeedback->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-12 mb-3">
                                        <label class="form-control-label">Case Study</label>
                                        <select name="casestudy_ids[]" id="casestudy_ids[]" class="form-control" multiple>
                                            @foreach ($casestudies as $casestudy)
                                                <option value="{{$casestudy->id}}" @if(in_array($casestudy->id, $sel_casestudies)) selected @endif>{{$casestudy->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-12 mb-3">
                                        <label class="form-control-label">Insights</label>
                                        <select name="insight_ids[]" id="insight_ids[]" class="form-control" multiple>
                                            @foreach ($insights as $insight)
                                                <option value="{{$insight->id}}" @if(in_array($insight->id, $sel_insights)) selected @endif>{{$insight->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-12 mb-3">
                                        <label class="form-control-label">Awards</label>
                                        <select name="award_ids[]" id="award_ids[]" class="form-control" multiple>
                                            @foreach ($awards as $award)
                                                <option value="{{$award->id}}" @if(in_array($award->id, $sel_awards)) selected @endif>{{$award->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@section('js')
@stop
@endsection
