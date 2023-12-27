@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation")
<div class="reports-hero">
    <div class="container">
        <form id="frmreportsearch" name="frmreportsearch" method="post" action="{!! route('reports') !!}">
        @csrf
            <div class="report-hero-inner">
                <div class="select-cat">
                    <div class="dropdown">
                        <button class="btn btn-secondary select-cat-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Category
                        </button>
                        @if($sectors)
                        <ul id="sectorlist" class="dropdown_menu dropdown-menu drop_right" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" data-selection="" href="{!! route('reports') !!}">Select Category</a></li>
                        @foreach($sectors as $sector)
                        <li><a class="dropdown-item" data-selection="@if(Request()->slug==$sector->slug)selected @endif" data-slug="{{$sector->slug}}" href="{!! url('industries/'.$sector->slug) !!}">{!!$sector->title!!}</a>
                            @php
                                $submenu = getSubMenuOnReport($sector->id);
                                //print_r($submenu);                                
                            @endphp
                            @if(!$submenu->isEmpty())
                                <div class="dropdown-submenu">
                                <ul id="sectorlist" class="" aria-labelledby="dropdownMenuButton">                           
                                @foreach($submenu as $item)
                                <li><a class="dropdown-item" data-selection="@if(Request()->slug==$item->slug)selected @endif" data-slug="{{$item->slug}}" href="{!! url('industries/'.$item->slug) !!}">{!!$item->title!!}</a></li>
                                @endforeach
                                </ul></div>
                            @endif
                        </li>
                        @endforeach
                        </ul>
                        @endif
                        <input type="hidden" name="hdnslug" id="hdnslug" value="{{Request()->slug}}">
                        <input type="hidden" name="hdnupcoming" id="hdnupcoming" value="{{Request()->upcoming}}">
                    </div>
                </div>
                <div class="search-bar">
                    <input type="text" id="keyword" name="keyword" placeholder="Enter Keyword">
                    <button class="searchbar-btn-reports" id="searchBtn">Search</button>
                </div>
            </div>
        </form>
        <p class="popular">
            <span>Popular Keywords:</span>
            <span>#Footwear Size Report 2021 #Automobile Report 2021 #HealthCare 2019</span>
        </p>
    </div>
</div>
<div class="reports">
    <div class="report-items">
        <div class="container">
            <div class="reports-tabs">
                <div class="tabbable-panel">
                    <div class="tabbable-line">
                        @if($h1 ?? '')
                        <h1>{{ $h1 }}</h1>
                        @endif 
                        <div class="tabs-bar">
                            <ul class="nav nav-tabs tabs-inner" id="reportTabs">
                                <li class="publish-tab">
                                    <a class="active" id="tab_1" href="#tab_default_1" data-bs-toggle="tab">Published Reports</a>
                                </li>
                                <li class="upcoming-tab">
                                    <a id="tab_2" href="#tab_default_2" data-bs-toggle="tab">Upcoming Reports</a>
                                </li>
                            </ul>
                            <select class="custom-select1 Popular sort-by-drop-down" name="orderby" id="orderby">
                                <option value="">Sort By</option>
                                <option value="asc">Oldest First</option>
                                <option value="desc">Newest First</option>
                            </select>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_default_1"></div>
                            <div class="tab-pane" id="tab_default_2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
<!-- js link -->
@if(request()->segment(1) == 'reports')
<script>
localStorage.removeItem('Tab');
</script>
@endif
<script src="{!! asset('assets/frontend/js/pages/reports.js') !!}"></script>
@stop
@endsection