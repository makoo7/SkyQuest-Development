@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation")
<div class="insights-details parking-sys">
    <div class="container">
    <h1 class="heady">{!!$casestudy->name!!}</h1>
    <p>{!!$casestudy->short_description!!}</p>
    @if(isset($casestudy->read_time))<p>Read Time : {!!$casestudy->read_time!!}</p>@endif
    @if(isset($casestudy->location))<p>Location: {!!$casestudy->location!!}</p>@endif
    </div>
</div>
<div class="abt-services-img" style="background-image: url({!! $casestudy->image_url !!});"></div>
<div class="intro footer-show">
    <div class="container my-0">
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
                    <li><a href="#" onclick="toggleBookmark('{!! Auth::user()->id !!}','casestudy','{!! $casestudy->id !!}','1');">
                        @if((isset($casestudy['casestudy_bookmark'][0]->user_id)))
                            @if(($casestudy['casestudy_bookmark'][0]->user_id == Auth::user()->id) && ($casestudy['casestudy_bookmark'][0]->entity_id == $casestudy->id))
                                <img id="bookmarktag" class="book-mark-tag" data-id="{!! $casestudy->id !!}" src="{!! asset('assets/frontend/images/icon_bookmark.png') !!}" alt="Top market research company in India">
                            @else
                                <img id="bookmarktag" class="book-mark-tag" data-id="{!! $casestudy->id !!}" src="{!! asset('assets/frontend/images/icon_unbookmark.png') !!}" alt="Top market research company in India">
                            @endif
                        @else
                            <img id="bookmarktag" class="book-mark-tag" data-id="{!! $casestudy->id !!}" src="{!! asset('assets/frontend/images/icon_unbookmark.png') !!}" alt="Top market research company in India">
                        @endif
                    </a></li>
                    @else
                    <li><a href="#" onclick="alertBookmark();"><img class="book-mark-tag" src="{!! asset('assets/frontend/images/icon_unbookmark.png') !!}" alt="Top market research company in India"/></a></li>                
                    @endif
                </ul>
            </div>
        </div>
        <div class="insights-details-content py-0">
            <div class="content-inner">
                {!!$casestudy->description!!}
            </div>
        </div>
    </div>
</div>
<div class="feedback-form">
    <div class="container">
    <div class="feedbackform-inner">
        <h2>Feedback From Our Clients</h2>
        <div class="feedback-containt">
            <div class="containt-inner">
                <div class="feedback-slider mb-0" id="feedbackSlider" style="position: relative;">
                    @foreach ($clientfeedbacks as $clientfeedback)
                    <div class="item">
                        {!! isset($clientfeedback->feedback) ? $clientfeedback->feedback : '' !!}
                        <p>- {!! isset($clientfeedback->name) ? $clientfeedback->name : '' !!}{!! isset($clientfeedback->designation) ? ', '.$clientfeedback->designation : '' !!}{!! isset($clientfeedback->company_name) ? ', '.$clientfeedback->company_name : '' !!}.</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@section('js')
<!-- js link -->
@stop
@endsection