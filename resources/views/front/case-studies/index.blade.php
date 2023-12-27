@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation-white")
<!-- case-study page start -->
<div class="case-studies-page" id="case-studies_list">  
    <input type="hidden" name="page" id="page" value="">
</div>
<!-- case-study page end -->
@section('js')
<!-- js link -->
<script src="{!! asset('assets/frontend/js/pages/casestudies.js') !!}"></script>
@stop
@endsection