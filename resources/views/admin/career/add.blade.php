@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! $career->id ? route('admin.career.update') : route('admin.career.store') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{!! $career->id !!}">
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Position Name</label>
                                        <input type="text" name="position" id="position" value="{!! old('position') ?? $career->position !!}" class="form-control">
                                        @error('position')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">                                        
                                        <label class="form-control-label">Location</label>
                                        <input type="text" name="location" id="location" maxlength="20" value="{!! old('location') ?? $career->location !!}" class="form-control">
                                        @error('location')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">                                        
                                        <label class="form-control-label">Experience Range</label>
                                        <input type="text" name="exp_range" id="exp_range" maxlength="20" value="{!! old('exp_range') ?? $career->exp_range !!}" class="form-control">
                                        @error('exp_range')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">                                
                                        <label class="form-control-label">Salary Range</label>
                                        <input type="text" name="salary_range" id="salary_range" maxlength="50" value="{!! old('salary_range') ?? $career->salary_range !!}" class="form-control">
                                        @error('salary_range')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">No Of Position</label>
                                        <input type="text" name="no_of_position" id="no_of_position" maxlength="3" value="{!! old('no_of_position') ?? $career->no_of_position !!}" class="form-control">
                                        @error('no_of_position')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Department</label>
                                        <select name="department_id" id="department_id" class="form-control">
                                            <option value="">Select Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{$department->id}}" @if($career->department_id == $department->id)selected @endif>{{$department->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('department_id')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                @if($career->id)
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Slug</label>
                                        <input type="text" name="slug" id="slug" value="{!! old('slug') ?? $career->slug !!}" class="form-control">
                                        @error('slug')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                @endif
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Description</label>
                                        <textarea name="description" id="description" class="form-control tinymce-editor">{!! old('description') ?? $career->description !!}</textarea>
                                        @error('description')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Status</label>
                                        <div class="radio-wrap">
                                            <div class="radiobtn">
                                                <input type="radio" id="is_active_yes" class="form-control checkboxinput" name="is_active" value="1" {!! $career->is_active == '1' ? 'checked' : '' !!} checked>Active
                                                <h1></h1>
                                            </div>
                                            <div class="radiobtn">
                                                <input type="radio" id="is_active_no" class="form-control checkboxinput" name="is_active" value="0" {!! $career->is_active == '0' ? 'checked' : '' !!} >Inactive
                                                <h1></h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <a href="{!! route('admin.career.index') !!}" class="btn btn-outline-danger">Cancel</a>
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
<script src="{!! asset('assets/backend/js/pages/career.js') !!}"></script>
@stop
@endsection
