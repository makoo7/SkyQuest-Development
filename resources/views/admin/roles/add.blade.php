@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! $role->id ? route('admin.roles.update') : route('admin.roles.store') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{!! $role->id !!}">
                    <div class="col-lg-06">
                        <div class="card ">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-lg-4 mb-3">
                                        <div class="d-flex align-items-center">
                                            <label class="form-control-label mr-2">Name:</label>
                                            <div class="">
                                                <input type="text" name="name" id="name" value="{!! old('name') ?? $role->name !!}" @if($role->name=='Marketing Admin') readonly @endif class="form-control">
                                            </div>
                                        </div>
                                        @error('name')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="form-control-label mr-2">Permission</label>
                                        <label id="chkerror" class="error"></label>
                                        <hr class="mt-1 mb-2"/>
                                        @foreach($permission as $k => $value)
                                            <div class="row border-bottom mb-3 mx-auto">
                                                <div class="col-md-3" style="flex: 0 0 230px;">
                                                        @php
                                                        $nextModule = 0;
                                                        $module_name = $value->module_name;
                                                        $permissionRaw = Spatie\Permission\Models\Permission::where('module_name', $module_name)->get();                                                        
                                                        @endphp
                                                    <h6 class="mb-0">{!! $module_name !!}</h6>
                                                </div>
                                                <div class="col-md-8" style="flex:1; max-width:100%;">
                                                    <div class="row">
                                                        @foreach($permissionRaw as $r => $raw)
                                                        @php
                                                        $permissinValueArr = explode("-",$raw->name);
                                                        $task = (count($permissinValueArr)>1) ? $permissinValueArr[count($permissinValueArr)-1] : '';                                                            
                                                        if($task=='settings' || $task=='pricing'){
                                                            $task = '';
                                                        }
                                                        @endphp
                                                        <div class="col-md-4 mb-4">
                                                            <div class="d-flex align-items-center">
                                                                <div class="permission-check-view">
                                                                    <input class="permissionchk" data-module="{{ $raw->module_name }}" data-role="{{ $raw->name }}" type="checkbox" class="form-control" value="{{ $raw->id }}" name="permission[]" @if(in_array($raw->id,$rolePermissions)) checked @endif>
                                                                </div>
                                                                <label class="form-control-label ml-2 py-0">{!! ucfirst($task) !!}</label>
                                                            </div>
                                                        </div>  
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @error('permission')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.roles.index') !!}';">Cancel</button>
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
<script src="{!! asset('assets/backend/js/pages/roles.js') !!}"></script>
@stop
@endsection
