@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation")
<div class="content-body bookmark-page">
    <div class="container">
        <div class="account-header">
            <button class="btn btn-blue nav-collapse-btn"></button>
            <h3>Hello! {{ $user->user_name }}</h3>
        </div>
        <div class="account-tabs d-flex align-items-start">
            @include("front.layouts.account")
            <div class="content-view" id="myBookmarkSection">
                <div class="accordion bookmark-accordion" id="bookmarkAccordion">
                    @if(!$insightsBookmark->isEmpty())
                    <div class="accordion-item">
                        <div class="accordion-header" id="headingOne">
                            <h5 class="category-title accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne" id="insightHead" aria-expanded="true" aria-controls="collapseOne">
                                Insights
                            </h5>
                        </div>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#bookmarkAccordion">
                            @foreach($insightsBookmark as $insight)
                            <div class="bookmark-list" id="insightData" data-id="{!! $insight->entity_id !!}">
                                <a href="{!! url('insights/'.$insight->insights->slug) !!}" class="img-view">
                                    <img src="{!! $insight->insights->image_url !!}" alt="{!! $insight->insights->image_alt ?? '' !!}"/>
                                </a>
                                <div class="content">
                                    <a href="{!! url('insights/'.$insight->insights->slug) !!}" class="title">{!! $insight->insights->name !!}</a>
                                    @if(isset($insight->insights->publish_date))
                                        <div class="date">                        
                                        {!! convertUtcToIst($insight->insights->publish_date, config('constants.DISPLAY_DATE_FORMAT')) !!}
                                        </div>
                                    @endif
                                    <div class="btn-view">
                                        <a href="javascript:void(0)" onclick="removeBookmark('{!! Auth::user()->id !!}','insight','{!! $insight->entity_id !!}');" class="btn">
                                            <img id="bookmarktag" src="{!! asset('assets/frontend/images/bookmark-black.png') !!}" alt="Top market research company in India">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(!$casestudiesBookmark->isEmpty())
                        <div class="accordion-item">
                        <div class="accordion-header" id="headingTwo">
                            <h5 class="category-title accordion-button" id="casestudyHead" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Case Studies
                            </h5>
                        </div>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#bookmarkAccordion">
                            @foreach($casestudiesBookmark as $casestudy)
                            <div class="bookmark-list" id="casestudyData" data-id="{!! $casestudy->entity_id !!}">
                                <a href="{!! url('case-studies/'.$casestudy->casestudies->slug) !!}" class="img-view">
                                    <img src="{!! $casestudy->casestudies->image_url !!}" alt="{!! $casestudy->casestudies->image_alt ?? '' !!}"/>
                                </a>
                                <div class="content">
                                    <a href="{!! url('case-studies/'.$casestudy->casestudies->slug) !!}" class="title">{!! $casestudy->casestudies->name !!}</a>                                    
                                    <div class="btn-view">
                                        <a href="javascript:void(0)" onclick="removeBookmark('{!! Auth::user()->id !!}','casestudy','{!! $casestudy->entity_id !!}');" class="btn">
                                            <img id="bookmarktag" src="{!! asset('assets/frontend/images/bookmark-black.png') !!}" alt="Top market research company in India">
                                        </a>                                
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(!$reportsBookmark->isEmpty())
                        <div class="accordion-item">
                            <div class="accordion-header" id="headingThree">
                                <h5 class="category-title accordion-button" id="reportHead" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Reports
                                </h5>
                            </div>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#bookmarkAccordion">
                            @foreach($reportsBookmark as $report)
                            @if(isset($report))
                            @php
                            $report_name = $report->reports->name;

                            if(isset($report->reports->report_segments)){
                                $report_name .= " Size, Share, Growth Analysis";

                                foreach($report->reports->report_segments as $report_segment){
                                    $sub_segmentsArr = array();
                                    $report_name .= ", By ".$report_segment->name;
                                    $sub_segmentsArr = explode(",",$report_segment->value);
                                    if(count($sub_segmentsArr)>0){
                                        if(count($sub_segmentsArr)==1)
                                        $report_name .= "(".$sub_segmentsArr[0].")";
                                        if(count($sub_segmentsArr)>=2)
                                        $report_name .= "(".$sub_segmentsArr[0].",".$sub_segmentsArr[1].")";
                                    }
                                }
                                $report_name .= " - Industry Forecast ".$settings->forecast_year;
                            }
                            @endphp        
                                <div class="bookmark-list" id="reportData" data-id="{!! $report->entity_id !!}">
                                    <a href="{!! url('report/'.$report->reports->slug) !!}" class="img-view">
                                        <img src="{!! $report->reports->image_url !!}" alt="{!! $report->reports->image_alt ?? '' !!}"/>
                                    </a>
                                    <div class="content">
                                        <a href="{!! url('report/'.$report->reports->slug) !!}" class="title">{!! $report_name !!}</a>
                                        @if(isset($report->reports->publish_date))
                                            <div class="date">                        
                                            {!! convertUtcToIst($report->reports->publish_date, config('constants.DISPLAY_DATE_FORMAT')) !!}
                                            </div>
                                        @endif
                                        <div class="btn-view">
                                            <a href="javascript:void(0)" onclick="removeBookmark('{!! Auth::user()->id !!}','report','{!! $report->entity_id !!}');" class="btn">
                                            <img id="bookmarktag" src="{!! asset('assets/frontend/images/bookmark-black.png') !!}" alt="Top market research company in India">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                    </div> 
                    @endif
                </div> 
                @if($insightsBookmark->isEmpty() && $casestudiesBookmark->isEmpty() && $reportsBookmark->isEmpty())
                <h3 class="no-data-text text-center">No Bookmarks Found!</h3>
                @endif
            </div>
        </div>
    </div>
</div>
@section('js')
<!-- js link -->
<script src="{!! asset('assets/frontend/js/pages/mybookmarks.js') !!}"></script>
@stop
@endsection