@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation")
<div class="content-body">
    <div class="container">
        <div class="account-header">
            <h4>Search Result of '{{ app('request')->input('searchtxt') }}'</h4>
            <input type="hidden" name="hdnkeyword" id="hdnkeyword" value="{{ app('request')->input('searchtxt') }}">
        </div>
        @if((app('request')->input('searchtxt')!='') && ((isset($insights) && $insights->count()>0) || (isset($casestudies) && $casestudies->count()>0) || (isset($reports) && $reports->count()>0)))
        <div class="reports-tabs mt-3">        
            <ul class="nav nav-tabs tabs-inner">
                @if(isset($reportsCount))
                <li>
                    <a class="active" id="tab_1" href="#reportsTab" data-bs-toggle="tab">Reports ({!! $reportsCount !!})</a>
                </li>
                @endif
                @if(isset($insightCount))
                <li>
                    <a id="tab_2" href="#insightsTab" data-bs-toggle="tab">Insights ({!! $insightCount !!})</a>
                </li>
                @endif
                @if(isset($casestudiesCount))
                <li>
                    <a id="tab_3" href="#caseStudiesTab" data-bs-toggle="tab">Case Studies ({!! $casestudiesCount !!})</a>
                </li>
                @endif
            </ul>
            <div class="tab-content content-view mt-3" id="v-pills-tabContent">
                <!-- Reports section - starts -->
                <div class="tab-pane fade show active" id="reportsTab" role="tabpanel" tabindex="0"></div>
                <!-- Reports section - ends -->
                <!-- Insights section - starts -->
                <div class="tab-pane fade" id="insightsTab" role="tabpanel" tabindex="0"></div>
                <!-- Insights section - ends -->
                <!-- Case Study section - starts -->
                <div class="tab-pane fade" id="caseStudiesTab" role="tabpanel" tabindex="0"></div>
                <!-- Case Study section - ends -->
            </div>
        </div>
        @else
        <h4 class="no-data-text text-center">No Results Found</h4>
        @endif
    </div>
</div>
@section('js')
<!-- js link -->
<script src="{!! asset('assets/frontend/js/pages/search.js') !!}"></script>
@stop
@endsection