@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! route('admin.report-export.store') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-06">
                        <div class="card ">
                            <div class="card-body">
                            <h6 class="mb-0" style="text-wrap: nowrap;">Report Date:</h6>
                                <div class="row">
                                    <div class="col-xl-3 col-md-4">
                                        <label class="form-control-label mr-2">Start Date:</label>
                                        <input type="date" name="start_date" id="start_date" value="" class="form-control"> 
                                        @error('start_date')
                                            <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-md-4">
                                        <label class="form-control-label mr-2">End Date:</label>
                                        <input type="date" name="end_date" id="end_date" value="" class="form-control">
                                        @error('end_date')
                                            <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <h6 class="mb-0">Select Fields</h6>
                                        <label id="chkerror" class="error"></label>
                                        <div class="row border-bottom mb-1 pb-3 report-export-permisions">
                                            @foreach($fields as $k => $field)
                                                <div class="col-xl-3 col-md-4 col-6 Col">
                                                    <div class="form-check">
                                                        <input class="permissionchk" type="checkbox" @if(in_array($field['value'], $selected)) checked @endif class="form-control" value="{{ $field['value'] }}" name="fields[]"/>
                                                        <label class="form-control-label">{!! $field['name'] !!}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('permission')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
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
<script src="{!! asset('assets/backend/js/pages/reportexport.js') !!}"></script>
@stop
@endsection
