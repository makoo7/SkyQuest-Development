@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @if(auth('admin')->user()->can('casestudy-add'))
                    <div class="col-lg-12 addlink">
                        <a href="{!! route('admin.casestudy.add') !!}"  class="btn btn-primary mb-4 admin-top">Add New</a>
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
                                            <input id="search" type="text" class="form-control me-2" name="keyword"
                                                value="{{ Request::get('keyword') }}" placeholder="Search By Keyword">
                                        </div>
                                        <button id="buttonGo" type="submit" class="btn btn-primary">Go!</button>
                                        <a id="btn_clear" href="{!! route('admin.casestudy.index') !!}" class="btn btn-outline-danger ml-2">Clear</a>
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
            <div class="card-body" id="casestudy_list">
            </div>
        </div>
        <input type="hidden" name="page" id="page" value="">
    </div>
</section>
@section('js')
<script>
fetch_data('casestudy',1);
</script>
<script src="{!! asset('assets/backend/js/pages/casestudy.js') !!}"></script>
@stop
@endsection
