<div id="pg2Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg2" style="-webkit-user-select: none;"><object data="{{ asset('assets/frontend/slide/2/2.svg') }}" type="image/svg+xml" id="pdf2" style="-moz-transform:scale(1); z-index: 0;"></object></div>
<div class="text-container">
    @if($response["data"]["style"] == "full")
    <span class="intro-frame page-5-title-full">{{ $response["data"]["title"] }}</span>
    @if($response["data"]["content"] != "")
        @if(is_array($response["data"]["content"]))
        <div class="page-9-content">
            @foreach ($response["data"]["content"] as $k => $item)
                    <span class="page-9-headings">{{ $k }}</span>
                    @foreach($item as $v)
                        @if(is_array($v))
                            @foreach($v as $val)
                            @php
                             $realstr = $val;
                             $oldstr = "{%%reportname%%}";
                             $newstr = $response["data"]["dData"];
                             $newString = str_replace($oldstr, $newstr, $realstr);
                            @endphp
                            <p>&emsp;&emsp;{{ $newString }}</p>
                            @endforeach
                        @else
                        @php
                             $realstr = $v;
                             $oldstr = "{%%reportname%%}";
                             $newstr = $response["data"]["dData"];
                             $newString = str_replace($oldstr, $newstr, $realstr);
                        @endphp
                        <p>&emsp;{{ $newString }}</p>
                        @endif
                    @endforeach
            @endforeach
        </div>
        @endif
    @endif
    @elseif($response["data"]["style"] == "")
        @if(isset($response["data"]["title"]))
        <span class="intro-frame page-5-title-full">{{ $response["data"]["title"] }}</span>
        @endif
        @if(app('request')->input('page') == 18)
            <div style="position: absolute; top:10rem;left:10rem;">
                <img src="{{ asset("assets/frontend/slide/18/18.png") }}" />
            </div>
        @elseif(app('request')->input('page') == 19)
        <div style="position: absolute; top:10rem;left:10rem;">
            <img src="{{ asset("assets/frontend/slide/19/19.png") }}" />
        </div>
        @endif
    @endif
</div>