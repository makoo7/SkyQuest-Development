@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! $casestudy->id ? route('admin.casestudy.update') : route('admin.casestudy.store') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{!! $casestudy->id !!}">
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Name</label>
                                        <input type="text" name="name" id="name" value="{!! old('name') ?? $casestudy->name !!}" class="form-control">
                                        @error('name')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">                                        
                                        <label class="form-control-label">Read Time</label>
                                        <input type="text" name="read_time" id="read_time" maxlength="20" value="{!! old('read_time') ?? $casestudy->read_time !!}" class="form-control">
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
                                            <img src="{!! $casestudy->image_url !!}" class="img-fluid rounded-circle" id='image_preview'>
                                            <input type="hidden" name="image_data" id="image_data" value="{!! $casestudy->image !!}">
                                                @if($casestudy->image)                                                
                                                <a href="javascript:void(0)" class="deleteMetaImg" onclick="deleteCaseStudyImage({!!$casestudy->id!!})">X</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">                                
                                        <label class="form-control-label">Image Alt</label>
                                        <input type="text" name="image_alt" id="image_alt" value="{!! old('image_alt') ?? $casestudy->image_alt !!}" class="form-control">
                                        @error('image_alt')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Sectors</label>
                                        <select name="sectors_id" id="sectors_id" class="form-control">
                                            @foreach ($sectors as $sector)
                                                <option value="{{$sector->id}}" @if($sector->id==$casestudy->sectors_id) selected @endif>{{$sector->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('sectors_id')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Services</label>
                                        <select name="service_id" id="service_id" class="form-control">
                                            @foreach ($services as $service)
                                                <option value="{{$service->id}}" @if($service->id==$casestudy->service_id) selected @endif>{{$service->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('service_id')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Location</label>
                                        <input type="text" name="location" id="location" value="{!! old('location') ?? $casestudy->location !!}" class="form-control">
                                        @error('location')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    @if($casestudy->id)
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Slug</label>
                                        <input type="text" name="slug" id="slug" value="{!! old('slug') ?? $casestudy->slug !!}" class="form-control">
                                        @error('slug')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Meta Title</label>
                                        <input type="text" name="meta_title" id="meta_title" value="{!! old('meta_title') ?? $casestudy->meta_title !!}" class="form-control">
                                        @error('meta_title')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Meta Description</label>
                                        <input type="text" name="meta_description" id="meta_description" value="{!! old('meta_description') ?? $casestudy->meta_description !!}" class="form-control">
                                        @error('meta_description')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Description</label>
                                        <textarea name="description" id="description" class="form-control tinymce-editor">{!! old('description') ?? $casestudy->description !!}</textarea>
                                        @error('description')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Short Description</label>
                                        <textarea name="short_description" id="short_description" class="form-control">{!! old('short_description') ?? $casestudy->short_description !!}</textarea>
                                        @error('short_description')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label">Schema</label>
                                        <textarea name="schema" id="schema" class="form-control">{!! old('schema') ?? $casestudy->schema !!}</textarea>
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
                                                <input type="radio" id="is_active_yes" class="form-control checkboxinput" name="is_active" value="1" {!! $casestudy->is_active == '1' ? 'checked' : '' !!} checked>Active
                                                <h1></h1>
                                            </div>
                                            <div class="radiobtn">
                                                <input type="radio" id="is_active_no" class="form-control checkboxinput" name="is_active" value="0" {!! $casestudy->is_active == '0' ? 'checked' : '' !!} >Inactive
                                                <h1></h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <a href="{!! route('admin.casestudy.index') !!}" class="btn btn-outline-danger">Cancel</a>
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
<script src="{!! asset('assets/backend/js/pages/casestudy.js') !!}"></script>
@stop
@endsection
