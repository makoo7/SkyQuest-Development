<!-- insights page start -->

<!-- insights-slider start -->
<div class="insights-hero">
    <div class="container">
        <div class="insights-hero-inner">
            <div class="wrapper">
                <div class="insights-hero-slider" style="position: relative;">
                    @foreach ($insightsData as $insights)
                    <a href="{!! url('insights/'.$insights->slug) !!}">
                        <div class="slides">
                            <div class="insightshero-imgs">
                                <img src="{!! $insights->image_url !!}" alt="{!! $insights->image_alt !!}">
                            </div>
                            <div class="insightshero-containt">
                                <span>{!!$insights->name!!}</span>
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
<!-- insights-slider end -->
<!-- card-section start -->
<div class="card-section">
    <div class="container">
        <div class="card-section-inner">
            @foreach ($insightsData as $insights)
            <div class="cards insights-card">
                <a href="{!! url('insights/'.$insights->slug) !!}">
                    <div class="card-img">
                        <img src="{!! $insights->image_url !!}" alt="{!! $insights->image_alt !!}">
                    </div>
                </a>
                <div class="card-containt">
                    <a href="{!! url('insights/'.$insights->slug) !!}">
                        <p>{!!$insights->name!!}</p>
                    </a>
                </div>
                <div class="read-more">
                    <a href="{!! url('insights/'.$insights->slug) !!}">Read More</a>
                    @if(auth('web')->check())
                    <a href="#" onclick="toggleBookmark('{!! Auth::user()->id !!}','insight','{!! $insights->id !!}');">
                        @if((isset($insights['insight_bookmark'][0]->user_id)))
                        @if(($insights['insight_bookmark'][0]->user_id == Auth::user()->id) &&
                        ($insights['insight_bookmark'][0]->entity_id == $insights->id))
                        <img id="bookmarktag" data-id="{!! $insights->id !!}"
                            src="{!! asset('assets/frontend/images/bookmark-black.png') !!}"
                            alt="Top market research company in India">
                        @else
                        <img id="bookmarktag" data-id="{!! $insights->id !!}"
                            src="{!! asset('assets/frontend/images/bookmark-white.png') !!}"
                            alt="Top market research company in India">
                        @endif
                        @else
                        <img id="bookmarktag" data-id="{!! $insights->id !!}"
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
        <div class="pagination-inner" id="insights_nav">
            {!! $insightsData->links() !!}
        </div>
    </div>
</div>
<!-- pagination end -->

<!-- insights page end -->
<script>
$(".insights-hero-slider")
    .slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        dots: false,
        centerMode: false,
        margin: 20,
        speed: 300,
        infinite: false,
        autoplaySpeed: 2000,
        autoplay: true,
        draggable: true,
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
</script>