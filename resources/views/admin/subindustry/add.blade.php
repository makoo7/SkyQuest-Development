@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! $subindustry->id ? route('admin.sub-industry.update') : route('admin.sub-industry.store') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{!! $subindustry->id !!}">
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Name</label>
                                        <input type="text" name="title" id="title" value="{!! old('title') ?? $subindustry->title !!}" class="form-control">
                                        @error('title')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">                                        
                                        <label class="form-control-label">Code</label>
                                        <input type="text" name="code" id="code" maxlength="20" value="{!! old('code') ?? $subindustry->code !!}" class="form-control">
                                        @error('code')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Industry</label>
                                        <select name="industry_id" id="industry_id" class="form-control">
                                            <option value="">Select Industry</option>
                                            @foreach ($industryData as $industry)
                                                <option value="{{$industry->id}}" @if($industry->id==$subindustry->industry_id) selected @endif>{{$industry->title}}</option>
                                            @endforeach
                                        </select>
                                        @error('industry_id')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Initial</label>
                                        <input type="text" name="initial" id="initial" value="{!! old('initial') ?? $subindustry->initial !!}" class="form-control">
                                        @error('initial')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                @if($subindustry->id)
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Slug</label>
                                        <input type="text" name="slug" id="slug" value="{!! old('slug') ?? $subindustry->slug !!}" class="form-control">
                                        @error('slug')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    @endif 
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Title</label>
                                        <input type="text" name="page_title" id="page_title" value="{!! old('page_title') ?? $subindustry->page_title !!}" class="form-control">
                                        @error('page_title')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div> 
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">H1</label>
                                        <input type="text" name="h1" id="h1" value="{!! old('h1') ?? $subindustry->h1 !!}" class="form-control">
                                        @error('h1')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>   
                                    <div class="col-sm-6">                                        
                                        <label class="form-control-label">Meta Title</label>
                                        <input type="text" name="meta_title" id="meta_title"  value="{!! old('meta_title') ?? $subindustry->meta_title !!}" class="form-control">
                                        @error('meta_title')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                   
                                </div>      
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Meta Description</label>
                                        <textarea name="meta_description" id="meta_description"   class="form-control">{!! old('meta_description') ?? $subindustry->meta_description !!}</textarea>
                                        @error('meta_description')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Meta Keyword</label>
                                        <input type="text" name="meta_keyword" id="meta_keyword"  value="{!! old('meta_keyword') ?? $subindustry->meta_keyword !!}" class="form-control">
                                        @error('meta_keyword')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>                        
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Status</label>
                                        <div class="radio-wrap">
                                            <div class="radiobtn">
                                                <input type="radio" id="is_active_yes" class="form-control checkboxinput" name="is_active" value="1" {!! $subindustry->is_active == '1' ? 'checked' : '' !!} checked>Active
                                                <h1></h1>
                                            </div>
                                            <div class="radiobtn">
                                                <input type="radio" id="is_active_no" class="form-control checkboxinput" name="is_active" value="0" {!! $subindustry->is_active == '0' ? 'checked' : '' !!} >Inactive
                                                <h1></h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.sub-industry.index') !!}';">Cancel</button>
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
<script src="{!! asset('assets/backend/js/pages/subindustry.js') !!}"></script>
@stop
@endsection
