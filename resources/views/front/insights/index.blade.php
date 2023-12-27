@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation-white")
<!-- insights page start -->
<div class="insights-page" id="insights_list">    
    <input type="hidden" name="page" id="page" value="">
</div>
<!-- insights page end -->
@section('js')
<!-- js link -->
<script src="{!! asset('assets/frontend/js/pages/insights.js') !!}"></script>
@stop
@endsection