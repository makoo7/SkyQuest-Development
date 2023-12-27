@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        @if(auth('admin')->user()->can('report-import'))
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col addlink text-left col-sm-3" style="max-width: 200px;">
                        <button type="button" class="btn btn-primary" id="importBtn">Import</button>
                    </div>
                    <div class="col">
                        <form name="importFrm" id="importFrm" action="{{ route('admin.report.import') }}" class="d-none"
                            method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col">
                                    @csrf
                                    <div class="d-flex">
                                        <div style="flex:1" class="mr-2">
                                            <select name="report_type" id="report_type" class="form-control bg-light">
                                                <option value="" selected>Select Report Type</option>
                                                <option value="SD">SD Report</option>
                                                <option value="Dynamic">Dynamic Report</option>
                                                <option value="Upcoming">Upcoming Report</option>
                                            </select>
                                            <input type="file" multiple name="reports[]" id="reportsInput"
                                                class="d-none" accept=".doc, .docx, .xls, .xlsx" />
                                        </div>
                                        <div style="flex:1">
                                            <select name="report_mode" id="report_mode" class="form-control bg-light"
                                                style="display:none;">
                                                <option value="" selected>Select Report Mode</option>
                                                <option value="New">New</option>
                                                <option value="Revamp">Revamp</option>
                                                <option value="Update">Update</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="selectedReportFiles" class="text-black mt-2" style="font-size:14px;"></div>
                                </div>
                                <div class="col col-sm-2 text-right">
                                    <button class="btn btn-primary" id="reportSubmit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth('admin')->user()->can('report-import'))
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col addlink text-left col-sm-3" style="max-width: 200px;">
                        <button type="button" class="btn btn-primary" id="importgraphBtn">Import Graph</button>
                    </div>
                    <div class="col">
                        <form name="importGraphFrm" id="importGraphFrm" action="{{ route('admin.report.graphimport') }}"
                            class="d-none" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col">
                                    @csrf
                                    <div class="d-flex">
                                        <div style="flex:1">
                                            <input type="file" multiple name="excelfile[]" id="excelfile"
                                                class="form-control-file bg-light" accept=".xls, .xlsx" />
                                        </div>
                                    </div>
                                    <div id="selectedGraphFiles" class="text-black mt-2" style="font-size:14px;"></div>
                                </div>
                                <div class="col col-sm-2 text-right">
                                    <button class="btn btn-primary" id="graphSubmit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form name="searchFrm" id="searchFrm" action="" method="POST">
                            <input type="hidden" name="sort_by" id="sort_by" value="">
                            <input type="hidden" name="sort_order" id="sort_order" value="">
                            <div class="filter-row d-flex align-items-center">
                                <div class="">
                                    <div class="form-inline">
                                        <div class="form-group">
                                            <input id="search" type="text" class="form-control me-2 bg-light"
                                                name="keyword" value="{{ Request::get('keyword') }}"
                                                placeholder="Search By Keyword">
                                        </div>
                                        <div class="form-group mx-3">
                                            <select name="report_type" id="report_type" class="form-control bg-light">
                                                <option value="" selected>Select Report Type</option>
                                                <option value="SD">SD</option>
                                                <option value="Dynamic">Dynamic</option>
                                                <option value="Upcoming">Upcoming</option>
                                            </select>
                                        </div>
                                        <button id="buttonGo" type="submit" class="btn btn-primary">Go!</button>
                                        <a id="btn_clear" href="{!! route('admin.report.index') !!}"
                                            class="btn btn-outline-danger ml-2">Clear</a>
                                    </div>
                                </div>
                                <div class="ml-auto text-right">
                                    <label for="per_page">Page to show:</label>
                                    <select name="per_page" id="per_page" class="form-control d-inline-block bg-light"
                                        style="width:auto;">
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="500">500</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body" id="report_list">
            </div>
        </div>
        <input type="hidden" name="page" id="page" value="">
    </div>
</section>
<!-- import-error-modal -->
<div class="modal fade resize-modal" id="importErrorModal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="importErrorModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Report Import:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0">
                @if(session('import_error_message'))
                {!! @session('import_error_message') !!}
                @endif
            </div>
        </div>
    </div>
</div>
<!-- import-error-modal -->
@section('js')
<script>
fetch_data('report', 1);
</script>
<script src="{!! asset('assets/backend/js/pages/report.js') !!}"></script>
<script>
@if(session('import_error_message'))
$('#importErrorModal').modal('show');
@endif
</script>
@stop
@endsection