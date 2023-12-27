@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! $insight->id ? route('admin.insight.update') : route('admin.insight.store') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id" value="{!! $insight->id !!}">
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Name</label>
                                        <input type="text" name="name" id="name" value="{!! old('name') ?? $insight->name !!}" class="form-control">
                                        @error('name')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">                                        
                                        <label class="form-control-label">Read Time</label>
                                        <input type="text" name="read_time" id="read_time" maxlength="20" value="{!! old('read_time') ?? $insight->read_time !!}" class="form-control">
                                        @error('read_time')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="fileInput" class="form-control-label">Image (Banner size : 1180 X 460)</label>
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
                                            <img src="{!! $insight->image_url !!}" class="img-fluid rounded-circle" id='image_preview'>
                                            <input type="hidden" name="image_data" id="image_data" value="{!! $insight->image !!}">
                                                @if($insight->image)                                                
                                                <a href="javascript:void(0)" class="deleteMetaImg" id="deleteImg" onclick="deleteInsightImage({!!$insight->id!!})">X</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">                                
                                        <label class="form-control-label">Image Alt</label>
                                        <input type="text" name="image_alt" id="image_alt" value="{!! old('image_alt') ?? $insight->image_alt !!}" class="form-control">
                                        @error('image_alt')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Publish Date</label>
                                        <input type="text" readonly name="publish_date" id="publish_date" value="{!! old('publish_date') ?? ($insight->publish_date!='') ? date('Y-m-d', strtotime($insight->publish_date)) : '' !!}" class="form-control">
                                        @error('publish_date')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    @if($insight->id)
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Slug</label>
                                        <input type="text" name="slug" id="slug" value="{!! old('slug') ?? $insight->slug !!}" class="form-control">
                                        @error('slug')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Writer Image</label>
                                        <div class="d-md-flex align-items-center">
                                            <!-- <div class="form-inline form-fileUpload">
                                                <div class="fileUpload btn btn-primary">
                                                    <span>Browse</span>
                                                    <input id="writer_image" name="writer_image" type="file" class="upload" accept="image/*">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <span class="uploadFile form-control"></span>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <input type="file" class="form-control-file upload" id="writer_image" name="writer_image" accept="image/*">
                                            </div>
                                            <div class="show-image imageRounded mt-3 mt-md-0">
                                            <img src="{!! $insight->writer_image_url !!}" class="img-fluid rounded-circle image_preview" id='writer_image_preview'>
                                                @if($insight->writer_image)                                        
                                                <a href="javascript:void(0)" class="deleteMetaImg" id="deleteWriterImg" onclick="deleteWriterImage({!!$insight->id!!})">X</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Writer Name</label>
                                        <input type="text" name="writer_name" id="writer_name" value="{!! old('writer_name') ?? $insight->writer_name !!}" class="form-control">
                                        @error('writer_name')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Meta Title</label>
                                        <input type="text" name="meta_title" id="meta_title" value="{!! old('meta_title') ?? $insight->meta_title !!}" class="form-control">
                                        @error('meta_title')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Meta Description</label>
                                        <input type="text" name="meta_description" id="meta_description" value="{!! old('meta_description') ?? $insight->meta_description !!}" class="form-control">
                                        @error('meta_description')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Description</label>
                                        <textarea name="description" id="description" class="form-control tinymce-editor">{!! old('description') ?? $insight->description !!}</textarea>
                                        @error('description')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Short Description</label>
                                        <textarea name="short_description" id="short_description" class="form-control">{!! old('short_description') ?? $insight->short_description !!}</textarea>
                                        @error('short_description')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Schema</label>
                                        <textarea name="schema" id="schema" class="form-control">{!! old('schema') ?? $insight->schema !!}</textarea>
                                        @error('schema')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Status</label>
                                        <div class="radio-wrap">
                                            <div class="radiobtn">
                                                <input type="radio" id="is_active_yes" class="form-control checkboxinput" name="is_active" value="1" {!! $insight->is_active == '1' ? 'checked' : '' !!} checked>Active
                                                <h1></h1>
                                            </div>
                                            <div class="radiobtn">
                                                <input type="radio" id="is_active_no" class="form-control checkboxinput" name="is_active" value="0" {!! $insight->is_active == '0' ? 'checked' : '' !!} >Inactive
                                                <h1></h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <a href="{!! route('admin.insight.index') !!}" class="btn btn-outline-danger">Cancel</a>
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
<script src="{!! asset('assets/backend/js/pages/insight.js') !!}"></script>
@stop
@endsection
