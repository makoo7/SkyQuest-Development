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
            <img src="{{ asset('assets/frontend/slide/16/16.png') }}" width="90%">
        </div>
        <div class="content-50 page-13-textarea">
            <p style="font-size: 80%;width:100%;text-align:justify;padding-left:2rem;">This phase involves a thorough synthesis of existing publications across the  web to gather meaningful insights on the current situation of the market,  technology developments, and any other market related information. The  sources include, but are not limited to:</p>
            <ul style="list-style-type:circle">
            @foreach($response["data"]["content"] as $item)
            <li style="font-size: 80%;">{{ $item }}</li>    
            @endforeach
            </ul>
        </div>
    {{-- </div> --}}
</div>