@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! route('admin.reportpricing') !!}" method="POST" class="form reports-pricing-form" enctype="multipart/form-data">
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
                                        <h6>Pricing:</h6>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">License Type</p>
                                        <label class="form-control-label">Single</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="mb-0">File Type</p>
                                        <label class="form-control-label">PPT</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">Price</p>
                                        <input type="text" name="single_ppt_price" id="single_ppt_price" placeholder="Price" value="" class="form-control">
                                        @error('single_ppt_price')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">License Type</p>
                                        <label class="form-control-label">Single</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="mb-0">File Type</p>
                                        <label class="form-control-label">Word</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">Price</p>
                                        <input type="text" name="single_word_price" id="single_word_price" placeholder="Price" value="" class="form-control">
                                        @error('single_word_price')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">License Type</p>
                                        <label class="form-control-label">Single</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="mb-0">File Type</p>
                                        <label class="form-control-label">Excel</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">Price</p>
                                        <input type="text" name="single_excel_price" id="single_excel_price" placeholder="Price" value="" class="form-control">
                                        @error('single_excel_price')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">License Type</p>
                                        <label class="form-control-label">Single</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="mb-0">File Type</p>
                                        <label class="form-control-label">PowerBI</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">Price</p>
                                        <input type="text" name="single_powerBI_price" id="single_powerBI_price" placeholder="Price" value="" class="form-control">
                                        @error('single_powerBI_price')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">License Type</p>
                                        <label class="form-control-label">Multiple</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="mb-0">File Type</p>
                                        <label class="form-control-label">PPT</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">Price</p>
                                        <input type="text" name="multiple_ppt_price" id="multiple_ppt_price" placeholder="Price" value="" class="form-control">
                                        @error('multiple_ppt_price')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">License Type</p>
                                        <label class="form-control-label">Multiple</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="mb-0">File Type</p>
                                        <label class="form-control-label">Word</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">Price</p>
                                        <input type="text" name="multiple_word_price" id="multiple_word_price" placeholder="Price" value="" class="form-control">
                                        @error('multiple_word_price')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">License Type</p>
                                        <label class="form-control-label">Multiple</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="mb-0">File Type</p>
                                        <label class="form-control-label">Excel</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">Price</p>
                                        <input type="text" name="multiple_excel_price" id="multiple_excel_price" placeholder="Price" value="" class="form-control">
                                        @error('multiple_excel_price')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">License Type</p>
                                        <label class="form-control-label">Multiple</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="mb-0">File Type</p>
                                        <label class="form-control-label">PowerBI</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">Price</p>
                                        <input type="text" name="multiple_powerBI_price" id="multiple_powerBI_price" placeholder="Price" value="" class="form-control">
                                        @error('multiple_powerBI_price')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">License Type</p>
                                        <label class="form-control-label">Enterprise</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="mb-0">File Type</p>
                                        <label class="form-control-label">PPT</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">Price</p>
                                        <input type="text" name="enterprise_ppt_price" id="enterprise_ppt_price" placeholder="Price" value="" class="form-control">
                                        @error('enterprise_ppt_price')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">License Type</p>
                                        <label class="form-control-label">Enterprise</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="mb-0">File Type</p>
                                        <label class="form-control-label">Word</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">Price</p>
                                        <input type="text" name="enterprise_word_price" id="enterprise_word_price" placeholder="Price" value="" class="form-control">
                                        @error('enterprise_word_price')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">License Type</p>
                                        <label class="form-control-label">Enterprise</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="mb-0">File Type</p>
                                        <label class="form-control-label">Excel</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">Price</p>
                                        <input type="text" name="enterprise_excel_price" id="enterprise_excel_price" placeholder="Price" value="" class="form-control">
                                        @error('enterprise_excel_price')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <p class="mb-0">License Type</p>
                                        <label class="form-control-label">Enterprise</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <p class="mb-0">File Type</p>
                                        <label class="form-control-label">PowerBI</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">Price</p>
                                        <input type="text" name="enterprise_powerBI_price" id="enterprise_powerBI_price" placeholder="Price" value="" class="form-control">
                                        @error('enterprise_powerBI_price')
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
<script src="{!! asset('assets/backend/js/pages/reportpricing.js') !!}"></script>
<script src="{{ asset('assets/backend/js/select2.min.js') }}"></script>
<script>
$(document).ready(function () {
    $("#selectionTab").hide();
    $("input:radio[name=report_selection]").prop('checked', false);
});
</script>
@stop
@endsection
