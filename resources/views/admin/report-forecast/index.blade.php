@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! route('admin.reportforecastsettings') !!}" method="POST" class="form reports-pricing-form" enctype="multipart/form-data">
                    @csrf                    
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Reports</label>
                                        <hr>
                                        <div class="radio-wrap">
                                            <div class="radiobtn">
                                                <input type="radio" id="all_reports" class="form-control checkboxinput" name="report_selection" value="all">All
                                                <h1></h1>
                                            </div>
                                            <div class="radiobtn">
                                                <input type="radio" id="selected_reports" class="form-control checkboxinput" name="report_selection" value="selection">Select Report
                                                <h1></h1>
                                            </div>
                                        </div>
                                        @error('report_selection')
                                        <span class="error" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>     
                                <div class="form-group row" id="selectionTab">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Select Reports:</label>
                                        <select name="reports[]" class="form-control" multiple id="reports">
                                            @if($reports->count() > 0)
                                            @foreach($reports as $report)
                                            <option value="{{ $report->id }}">{{ $report->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @error('reports')
                                        <span class="error" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-2" style="margin-top: 33px;">
                                    <div class="col-sm-12">
                                        <h6>Forecast Settings:</h6>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">Historical Year</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="historical_year" id="historical_year" placeholder="Historical Year" value="" class="form-control">
                                        @error('historical_year')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">Forecast Year</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="forecast_year" id="forecast_year" placeholder="Forecast Year" value="" class="form-control">
                                        @error('forecast_year')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">Base Year</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="base_year" id="base_year" placeholder="Base Year" value="" class="form-control">
                                        @error('base_year')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">Forecast Period</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="forecast_period" id="forecast_period" placeholder="9999-9999" maxlength="9" value="" class="form-control">
                                        @error('forecast_period')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>    
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.dashboard') !!}';">Cancel</button>
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
<script src="{!! asset('assets/backend/js/pages/reportforecast.js') !!}"></script>
<script src="{{ asset('assets/backend/js/select2.min.js') }}"></script>
<script>
$(document).ready(function () {
    $("#selectionTab").hide();
    $("input:radio[name=report_selection]").prop('checked', false);
});
</script>
@stop
@endsection
