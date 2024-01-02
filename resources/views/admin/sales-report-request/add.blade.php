@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! $email_restriction->id ? route('admin.sales-list.update') : route('admin.sales-list.store') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{!! $email_restriction->id !!}">
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                               
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Report</label>
                                        <select name="report_id" id="report_id" class="form-control" required>
                                            <option value="">--select--</option>
                                            @foreach($report as $r)
                                            <option value="{{ $r['id'] }}">{{ $r['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Start Date</label>
                                        <input type="date" class="form-control" name="start_date" id="start_date" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">End Date</label>
                                        <input type="date" class="form-control" name="end_date" id="end_date" required>
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Message</label>
                                        <textarea class="form-control" name="message" id="message" required></textarea>
                                    </div>
                                </div>
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.sales-list.index') !!}';">Cancel</button>
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
<script src="{!! asset('assets/backend/js/pages/email_restriction.js') !!}"></script>
@stop
@endsection
