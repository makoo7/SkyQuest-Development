@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! $email_restriction->id ? route('admin.research-list.update') : route('admin.research-list.store') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{!! $email_restriction->id !!}">
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                               
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Message</label>
                                        <textarea class="form-control" name="message" id="message" required></textarea>
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Upload Report</label>
                                        <input type="file" name="file" id="file" accept=".doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>
                                    </div>
                                </div>
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.research-list.index') !!}';">Cancel</button>
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
