<div id="pg2Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg2" style="-webkit-user-select: none;"><object data="{{ asset('assets/frontend/slide/2/2.svg') }}" type="image/svg+xml" id="pdf2" style="-moz-transform:scale(1); z-index: 0;"></object></div>
<div class="text-container">
    @if($response["data"]["style"] == "full")
    <span class="intro-frame page-5-title-full">{{ $response["data"]["title"] }}</span>
    @endif
</div>
<div class="text-container page-13-content">
    {{-- <div class="row-100"> --}}
        <div class="content-50">
            <img src="{{ asset('assets/frontend/slide/10/13.png') }}" width="90%">
        </div>
        <div class="content-50 page-13-textarea">
            <ul style="list-style-type:circle">
            @foreach($response["data"]["content"] as $item)
            <li>{{ $item }}</li>    
            @endforeach
            </ul>
        </div>
    {{-- </div> --}}
</div>