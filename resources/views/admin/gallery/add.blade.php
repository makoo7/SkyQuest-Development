@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! $gallery->id ? route('admin.gallery.update') : route('admin.gallery.store') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{!! $gallery->id !!}">
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Name</label>
                                        <input type="text" name="name" id="name" value="{!! old('name') ?? $gallery->name !!}" class="form-control">
                                        @error('name')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="fileInput" class="form-control-label">Image</label>
                                        <div class="d-md-flex align-items-center">
                                            <div class="form-group">
                                                <input type="file" class="form-control-file upload" id="image" name="image" accept="image/*">
                                            </div>
                                            <div class="show-image imageRounded mt-3 mt-md-0">
                                            <img src="{!! $gallery->image_url !!}" class="img-fluid rounded-circle" id='image_preview'>
                                            <input type="hidden" name="image_data" id="image_data" value="{!! $gallery->image !!}">
                                                @if($gallery->image)                                                
                                                <!-- <a href="javascript:void(0)" class="deleteMetaImg" onclick="deleteImage({!!$gallery->id!!})">X</a> -->
                                                @endif
                                            </div>
                                            @error('image')
                                            <span class="error" role="alert">{!! $message !!}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.gallery.index') !!}';">Cancel</button>
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
<script src="{!! asset('assets/backend/js/pages/gallery.js') !!}"></script>
@stop
@endsection
