@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! $pages->id ? route('admin.pages.update') : route('admin.pages.store') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{!! $pages->id !!}">
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Title</label>
                                        <input type="text" name="page_title" id="page_title" value="{!! old('page_title') ?? $pages->page_title !!}" class="form-control">
                                        @error('page_title')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Meta Keyword</label>
                                        <input type="text" name="meta_keyword" id="meta_keyword" value="{!! old('meta_keyword') ?? $pages->meta_keyword !!}" class="form-control">
                                        @error('meta_keyword')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">H1</label>
                                        <input type="text" name="h1" id="h1" value="{!! old('h1') ?? $pages->h1 !!}" class="form-control">
                                        @error('h1')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Meta Title</label>
                                        <input type="text" name="meta_title" id="meta_title" value="{!! old('meta_title') ?? $pages->meta_title !!}" class="form-control">
                                        @error('meta_title')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">                                        
                                        <label class="form-control-label">Meta Description</label>
                                        <textarea name="meta_description" id="meta_description" class="form-control">{!! old('meta_description') ?? $pages->meta_description !!}</textarea>
                                        @error('meta_description')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.pages.index') !!}';">Cancel</button>
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
<script src="{!! asset('assets/backend/js/pages/page.js') !!}"></script>
@stop
@endsection
