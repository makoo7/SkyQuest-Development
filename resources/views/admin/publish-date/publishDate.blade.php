@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        {{-- @if(auth('admin')->user()->can('report-import')) --}}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('admin.uploadPublishDate') }}" method="POST" id="publishDatefrm" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col">
                                    @csrf
                                    <div class="d-flex">
                                        <div style="flex:1" class="mr-2">
                                            <input type="file" class="btn" id="upload_excel" name="publish_date_excel" accept=".xlsx">
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-sm-4 text-right">
                                    <button class="btn btn-primary" id="reportSubmit">Submit</button>
                                    <a href="{{ asset('assets/backend/publishDate/sampleData.xlsx') }}" class="btn btn-primary" download>Download Sample</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@section('js')
<script>
    $(document).ready(function(){
        $('#publishDatefrm').validate({
            rules:{
                publish_date_excel:{
                    required:true,
                    extension: "xlsx"
                }
            },
            messages:{
                publish_date_excel:{
                    extension:'Please upload excel file only.'
                }
            }
        });
    });
</script>
@stop
@endsection