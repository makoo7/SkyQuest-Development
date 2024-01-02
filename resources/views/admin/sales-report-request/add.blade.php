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
                                    {{-- <div class="col-sm-6">
                                        <label class="form-control-label">Email Domain</label>
                                        <input type="text" name="email_domain" id="email_domain" value="{!! old('email_domain') ?? $email_restriction->email_domain !!}" class="form-control">
                                        @error('email_domain')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Email Category</label>
                                        <select name="email_category" id="email_category" class="form-control">
                                            <option value="Generic" @if($email_restriction->email_category == "Generic") selected @endif>Generic</option>
                                            <option value="Edu" @if($email_restriction->email_category == "Edu") selected @endif>Edu</option>
                                        </select>
                                        @error('email_category')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div> --}}
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
