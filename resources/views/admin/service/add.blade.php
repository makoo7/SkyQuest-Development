@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! $service->id ? route('admin.service.update') : route('admin.service.store') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{!! $service->id !!}">
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Name</label>
                                        <input type="text" name="name" id="name" value="{!! old('name') ?? $service->name !!}" class="form-control">
                                        @error('name')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">                                        
                                        <label class="form-control-label">Read Time</label>
                                        <input type="text" name="read_time" id="read_time" maxlength="20" value="{!! old('read_time') ?? $service->read_time !!}" class="form-control">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="fileInput" class="form-control-label">Image (Banner size : 1180 x 500)</label>
                                        <div class="d-md-flex align-items-center">
                                            <!-- <div class="form-inline form-fileUpload">
                                                <div class="fileUpload btn btn-primary">
                                                    <span>Browse</span>
                                                    <input id="image" name="image" type="file" class="upload" accept="image/*">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <span class="uploadFile form-control"></span>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <input type="file" class="form-control-file upload" id="image" name="image" accept="image/*">
                                            </div>
                                            <div class="show-image imageRounded mt-3 mt-md-0">
                                            <img src="{!! $service->image_url !!}" class="img-fluid rounded-circle" id='image_preview'>
                                                @if($service->image)                                                
                                                <a href="javascript:void(0)" class="deleteMetaImg" onclick="deleteServiceImage({!!$service->id!!})">X</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">                                
                                        <label class="form-control-label">Image Alt</label>
                                        <input type="text" name="image_alt" id="image_alt" value="{!! old('image_alt') ?? $service->image_alt !!}" class="form-control">                                        
                                    </div>
                                </div>
                                @if($service->id)
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Slug</label>
                                        <input type="text" name="slug" id="slug" value="{!! old('slug') ?? $service->slug !!}" class="form-control">
                                        @error('slug')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Page Title</label>
                                        <input type="text" name="page_title" id="page_title" value="{!! old('page_title') ?? $service->page_title !!}" class="form-control">
                                    </div>
                                </div>
                                @endif
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Meta Title</label>
                                        <input type="text" name="meta_title" id="meta_title" value="{!! old('meta_title') ?? $service->meta_title !!}" class="form-control">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Meta Description</label>
                                        <input type="text" name="meta_description" id="meta_description" value="{!! old('meta_description') ?? $service->meta_description !!}" class="form-control">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Description</label>
                                        <textarea name="description" id="description" class="form-control tinymce-editor">{!! old('description') ?? $service->description !!}</textarea>
                                        @error('description')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Short Description</label>                                        
                                        <textarea name="short_description" id="short_description" class="form-control">{!! old('short_description') ?? $service->short_description !!}</textarea>
                                        @error('short_description')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">How It Helps</label>
                                        <textarea name="how_it_helps" id="how_it_helps" class="form-control tinymce-editor">{!! old('how_it_helps') ?? $service->how_it_helps !!}</textarea>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Schema</label>
                                        <textarea name="schema" id="schema" class="form-control">{!! old('schema') ?? $service->schema !!}</textarea>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Status</label>
                                        <div class="radio-wrap">
                                            <div class="radiobtn">
                                                <input type="radio" id="is_active_yes" class="form-control checkboxinput" name="is_active" value="1" {!! $service->is_active == '1' ? 'checked' : '' !!} checked>Active
                                                <h1></h1>
                                            </div>
                                            <div class="radiobtn">
                                                <input type="radio" id="is_active_no" class="form-control checkboxinput" name="is_active" value="0" {!! $service->is_active == '0' ? 'checked' : '' !!} >Inactive
                                                <h1></h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.service.index') !!}';">Cancel</button>
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
<script src="{!! asset('assets/backend/js/pages/service.js') !!}"></script>
@stop
@endsection
