<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta charset="utf-8" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset("css/slide.css") }}">
<style class="shared-css" type="text/css" >
    .s1_1{font-family:DejaVuSans_mv;color:#000;}
    .page-5-title-full{
        top: 7rem;
        left: 3rem;
        font-weight: 700;
        font-size: 120%;
    }
    .page-5-intro-index{
        top: 9rem;
        position: absolute;
        line-height: 1;
        left: 3rem;
        font-size: 14px;
    }
    .page-5-intro-index-right{
        top: 11rem;
        position: absolute;
        line-height: 1;
        left: 40rem;
        font-size: 14px;
    }
    @font-face {
        font-family: DejaVuSans-Bold_n4;
        src: url({{ asset("assets/frontend/slide/fonts/DejaVuSans-Bold_n4.woff") }}) format("woff");
    }

    @font-face {
        font-family: DejaVuSans_mv;
        src: url({{ asset("assets/frontend/slide/fonts/DejaVuSans_mv.woff") }}) format("woff");
    }
</style>
</head>

<body style="margin: 0;">

{{-- Header Start --}}
    @include('front.sample-report-page.header.index')
{{-- Header Start --}}

{{-- Content --}}
    @if($response['frame'] == "intro")
        @include('front.sample-report-page.pages.intro')
    @elseif($response['frame'] == "dummy-content")
        @if(app('request')->input('page') == 2)
            @include('front.sample-report-page.pages.dummy-content-page-2')
        @elseif(app('request')->input('page') == 3)
            @include('front.sample-report-page.pages.dummy-content-page-3')
        @endif
    @elseif($response['frame'] == "frame")
        @include('front.sample-report-page.pages.frame')
    @endif
    {{-- @include('front.sample-report-page.pages.frame') --}}
    {{-- @include('front.sample-report-page.pages.content') --}}
    {{-- @include('front.sample-report-page.pages.thankyou') --}}
{{-- Content --}}

{{-- Footer --}}
    {{-- @include('front.sample-report-page.footer.index') --}}
{{-- Footer --}}

</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>
