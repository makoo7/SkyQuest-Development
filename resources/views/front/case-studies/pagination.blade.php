<!-- case-study page start -->
<div class="case-study-page">
    <!-- case-study-slider start -->
    <div class="case-study-hero">
        <div class="container">
            <div class="case-study-hero-inner">
                <div class="wrapper">
                    <div class="case-study-hero-slider" style="position:relative;">
                        @foreach ($casestudiesData as $casestudy)
                        <a href="{!! url('case-studies/'.$casestudy->slug) !!}">
                            <div class="slides">
                                <div class="case-studyhero-imgs">
                                    <img src="{!! $casestudy->image_url !!}" alt="{!! $casestudy->image_alt !!}">
                                </div>
                                <div class="case-studyhero-containt">
                                    <span>{!!$casestudy->name!!}</span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    <br>
                    <h1>{{ $h1 }}</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- case-study-slider end -->
    <!-- card-section start -->
    <div class="card-section">
        <div class="container">
            <div class="card-section-inner">
                @foreach ($casestudiesData as $casestudy)
                <div class="cards case-studies-card">
                    <a href="{!! url('case-studies/'.$casestudy->slug) !!}">
                        <div class="card-img">
                            <img src="{!! $casestudy->image_url !!}" alt="{!! $casestudy->image_alt !!}">
                        </div>
                    </a>
                    <div class="row-text">
                        <p>@if(isset($casestudy->sectors->name)){!!$casestudy->sectors->name!!}@endif</p>
                        <p>{!!$casestudy->location!!}</p>
                        <p>@if(isset($casestudy->service->name)){!!$casestudy->service->name!!}@endif</p>
                    </div>
                    <div class="card-containt">
                        <a href="{!! url('case-studies/'.$casestudy->slug) !!}">
                            <p>{!!$casestudy->name!!}</p>
                        </a>
                    </div>
                    <div class="read-more">
                        <a href="{!! url('case-studies/'.$casestudy->slug) !!}">Read More</a>
                        @if(auth('web')->check())
                        <a href="#"
                            onclick="toggleBookmark('{!! Auth::user()->id !!}','casestudy','{!! $casestudy->id !!}');">
                            @if((isset($casestudy['casestudy_bookmark'][0]->user_id)))
                            @if(($casestudy['casestudy_bookmark'][0]->user_id == Auth::user()->id) &&
                            ($casestudy['casestudy_bookmark'][0]->entity_id == $casestudy->id))
                            <img id="bookmarktag" data-id="{!! $casestudy->id !!}"
                                src="{!! asset('assets/frontend/images/bookmark-black.png') !!}"
                                alt="Top market research company in India">
                            @else
                            <img id="bookmarktag" data-id="{!! $casestudy->id !!}"
                                src="{!! asset('assets/frontend/images/bookmark-black.png') !!}"
                                alt="Top market research company in India">
                            @endif
                            @else
                            <img id="bookmarktag" data-id="{!! $casestudy->id !!}"
                                src="{!! asset('assets/frontend/images/bookmark-white.png') !!}"
                                alt="Top market research company in India">
                            @endif
                        </a>
                        @else
                        <a href="#" onclick="alertBookmark();">
                            <img id="" src="{!! asset('assets/frontend/images/bookmark-white.png') !!}"
                                alt="Top market research company in India">
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- card-section end -->
    <!-- pagination start -->
    <div class="pagination">
        <div class="container">
            <div class="pagination-inner" id="case-studies_nav">
                {!! $casestudiesData->links() !!}
            </div>
        </div>
    </div>
    <!-- pagination start -->
</div>
<!-- case-study page end -->

<script>
// case-study-hero
$(".case-study-hero-slider").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    dots: false,
    centerMode: false,
    margin: 20,
    speed: 300,
    draggable: false,
    infinite: true,
    autoplaySpeed: 5000,
    autoplay: false,
    responsive: [{
            breakpoint: 1025,
            settings: {
                slidesToShow: 1
            }
        },
        {
            breakpoint: 991,
            settings: {
                slidesToShow: 1
            }
        },
        {
            breakpoint: 768,
            settings: {
                slidesToShow: 1
            }
        }
    ]
});