@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation-white")
<div class="insights-details">
    <div class="container">
        <h1>{!! $insight->name !!}</h1>
        <div class="heading-details">
            @if(isset($insight->writer_image) && $insight->writer_image!='')
            <p class="p-0 m-0 details-inner">
                <img src="{!! $insight->writer_image_url !!}" height="60px" width="60px">
            </p>
            @endif
            @if(isset($insight->writer_name))
            <p class="p-0 m-0 details-inner">
                <span>by</span>
                <strong>{!! $insight->writer_name !!}</strong>
            </p>
            @endif
            <p class="details-date">                
                <span>
                    @if(isset($insight->publish_date))
                    {!! convertUtcToIst($insight->publish_date, config('constants.DISPLAY_DATE_FORMAT')) !!}
                    @endif
                    @if(isset($insight->read_time))
                    {!! $insight->read_time !!} min read
                    @endif
                </span>
            </p>
        </div>
        <div class="insights-details-img">
            <img src="{!! $insight->image_url !!}" title="{!! $insight->name !!}" alt="{!! $insight->image_alt ?? '' !!}">
        </div>
        <div class="fixed-social-icons">            
            <div id="social-links">
                <ul>
                    @if(isset($sociallinks))
                    <li><a href="{!! $sociallinks['facebook'] !!}" class="social-button" aria-label="Facebook" title="Facebook"><span class="fab fa-facebook-square"></span></a></li>
                    <li><a href="{!! $sociallinks['twitter'] !!}" class="social-button" aria-label="Twitter" title="Twitter"><span class="fab fa-twitter"></span></a></li>
                    <li><a href="{!! $sociallinks['linkedin'] !!}" class="social-button" aria-label="Linkedin" title="Linkedin"><span class="fab fa-linkedin"></span></a></li>
                    @endif

                    <li><a href="#" id="copyLink"><img class="book-mark-tag" src="{!! asset('assets/frontend/images/icon_link.png') !!}" alt="icon_link"/></a></li>                
                    @if(auth('web')->check())
                    <li><a href="#" onclick="toggleBookmark('{!! Auth::user()->id !!}','insight','{!! $insight->id !!}','1');">
                        @if((isset($insight['insight_bookmark'][0]->user_id)))
                            @if(($insight['insight_bookmark'][0]->user_id == Auth::user()->id) && ($insight['insight_bookmark'][0]->entity_id == $insight->id))
                                <img id="bookmarktag" class="book-mark-tag" data-id="{!! $insight->id !!}" src="{!! asset('assets/frontend/images/icon_bookmark.png') !!}" alt="Top market research company in India">
                            @else
                                <img id="bookmarktag" class="book-mark-tag" data-id="{!! $insight->id !!}" src="{!! asset('assets/frontend/images/icon_unbookmark.png') !!}" alt="Top market research company in India">
                            @endif
                        @else
                            <img id="bookmarktag" class="book-mark-tag" data-id="{!! $insight->id !!}" src="{!! asset('assets/frontend/images/icon_unbookmark.png') !!}" alt="Top market research company in India">
                        @endif
                    </a></li>
                    @else
                    <li><a href="#" onclick="alertBookmark();">
                        <img class="book-mark-tag" src="{!! asset('assets/frontend/images/icon_unbookmark.png') !!}" alt="Top market research company in India">
                    </a></li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="insights-details-content">
            <div class="content-inner">
                {!! $insight->description !!}                
            </div>
        </div>
    </div>
</div>
<div class="insights-section insights-section-details">
    <div class="insights-section-inner">
        <div class="container">
            <h1 class="mb-4 text-center">People Also Read </h1> 
            <div class="wrapper">
                <div class="insights-slider-detail">
                    @foreach ($otherinsights as $otherinsight)
                    <div class="slides">
                        <a href="{!! url('insights/'.$otherinsight->slug) !!}"><img src="{!! $otherinsight->image_url !!}" alt="{!! $otherinsight->image_alt ?? '' !!}"></a>
                        @if(isset($otherinsight->read_time))
                        <div class="seen-ago">
                            <p>{!! $otherinsight->read_time !!} MINS READ</p>
                        </div>
                        @endif
                        <div class="card-containt">
                            <a href="{!! url('insights/'.$otherinsight->slug) !!}">
                                <p class="mt-4">{!!$otherinsight->name!!}</p>
                            </a>
                        </div>
                        <div class="read-more">
                            <a href="{!! url('insights/'.$otherinsight->slug) !!}">Read More</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
<!-- js link -->
<script src="{!! asset('assets/frontend/js/pages/insights-detail.js') !!}"></script>
@stop
@endsection