@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @if(auth('admin')->user()->can('report-order-export'))
                    <div class="col-lg-12 addlink">
                        <a href="{!! route('admin.report-order.export') !!}"  class="btn btn-primary mb-4 admin-top">Export Data</a>
                    </div>
                    @endif
                    <div class="col-lg-12">
                        <form name="searchFrm" id="searchFrm" action="" method="POST">
                            <input type="hidden" name="sort_by" id="sort_by" value="">
                            <input type="hidden" name="sort_order" id="sort_order" value="">
                            <div class="filter-row d-flex align-items-center">
                                <div class="mb-3">
                                    <div class="form-inline">
                                        <div class="form-group">
                                            <input id="search" type="text" class="form-control" name="keyword" value="{{ Request::get('keyword') }}" placeholder="Search By Keyword">
                                        </div>
                                        <div class="form-group mx-3">
                                            <select id="payment_status" class="form-control bg-light" name="payment_status">
                                                <option value="">Select Status</option>
                                                <option value="Completed" @if(Request::get('payment_status')=='Completed') Selected @endif>Completed</option>
                                                <option value="Pending" @if(Request::get('payment_status')=='Pending') Selected @endif>Pending</option>
                                                <option value="Failed" @if(Request::get('payment_status')=='Failed') Selected @endif>Failed</option>
                                                <option value="Captured" @if(Request::get('payment_status')=='Captured') Selected @endif>Captured</option>
                                                <option value="Unpaid" @if(Request::get('payment_status')=='Unpaid') Selected @endif>Unpaid</option>
                                            </select>
                                        </div>
                                        <button id="buttonGo" type="submit" class="btn btn-primary">Go!</button>
                                        <a id="btn_clear" href="{!! route('admin.report-order.index') !!}" class="btn btn-outline-danger ml-2">Clear</a>
                                    </div>
                                </div>
                                <div class="ml-auto text-right mb-3">          
                                    <label for="per_page">Page to show:</label>                      
                                    <select name="per_page" id="per_page" class="form-control d-inline-block" style="width:auto;">
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
            <div class="card-body" id="report-order_list">
            </div>
        </div>
        <input type="hidden" name="page" id="page" value="">
    </div>
</section>
@section('js')
<script>
fetch_data('report-order',1);
</script>
<script src="{!! asset('assets/backend/js/pages/reportorder.js') !!}"></script>
@stop
@endsection
