<!-- Begin page background -->
<div id="pg2Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;">
</div>
<div id="pg2" style="-webkit-user-select: none;">
    <object data="{{ asset('assets/frontend/slide/2/2.svg') }}" type="image/svg+xml" id="pdf2" style="-moz-transform:scale(1); z-index: 0;"></object>
</div>
<div class="page-10-img">
    @if(app('request')->input('page') == 10)
        <img src="{{ asset('assets/frontend/slide/10/10.png') }}">
    @elseif(app('request')->input('page') == 11)
    <img src="{{ asset('assets/frontend/slide/10/11.png') }}">
    @elseif(app('request')->input('page') == 12)
    <img src="{{ asset('assets/frontend/slide/10/11.png') }}">
    @endif
</div> 
<!-- End page background -->


<!-- Begin text definitions (Positioned/styled in CSS) -->
<div class="text-container">
@if(app('request')->input('page') == 10)
<span class="intro-frame page-10-name">{{ $response["data"]["dData"] }}</span>
@elseif(app('request')->input('page') == 11)
<span class="intro-frame page-11-name">{{ $response["data"]["dData"] }}</span>
@elseif(app('request')->input('page') == 12)
<span class="intro-frame page-11-name">{{ $response["data"]["dData"] }}</span>
@endif
<span class="intro-frame page-5-title-full">{{ $response["data"]["title"] }}</span>
</div>
<!-- End text definitions -->