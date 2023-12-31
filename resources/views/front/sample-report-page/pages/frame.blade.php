<div id="pg3Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg3" style="-webkit-user-select: none;">
    <object data="{{ asset('assets/frontend/slide/3/frame.svg') }}" type="image/svg+xml" id="pdf3" style="-moz-transform:scale(1); z-index: 0;"></object>
</div>

<div class="text-container">
    @if($response["data"]["style"] == "center")
        <span class="intro-frame page-4-name">{{ $response["data"]["name"] }}</span>
        <span class="intro-frame page-4-title">{{ $response["data"]["title"] }}</span>
    @elseif($response["data"]["style"] == "full")
        <span class="intro-frame page-5-title-full">{{ $response["data"]["title"] }}</span>
        @if(app('request')->input('page') == 5)
            <div class="page-5-intro-index">
                <p>1. INTRODUCTION</p>
                @foreach($response["data"]["content"]["1. INTRODUCTION"] as $value)
                    <p>&emsp;{{ $value }}</p>
                @endforeach
                <p>2. RESEARCH METHODOLOGY</p>
                @foreach($response["data"]["content"]["2. RESEARCH METHODOLOGY"] as $value)
                    <p>&emsp;{{ $value }}</p>
                @endforeach
                <p>3. EXECUTIVE SUMMARY</p>
                <p>4. MARKET OVERVIEW</p>
                <p>4.1 INDUSTRY ANALYSIS - PORTERS 5 FORCE ANALYSIS</p>
                @foreach(array_slice($response["data"]["content"]["4.1 INDUSTRY ANALYSIS - PORTERS 5 FORCE ANALYSIS"], 0, 5) as $value)
                    <p>&emsp;{{ $value }}</p>
                @endforeach
            </div>
            <div class="page-5-intro-index-right">
                @foreach(array_slice($response["data"]["content"]["4.1 INDUSTRY ANALYSIS - PORTERS 5 FORCE ANALYSIS"], 5, count($response["data"]["content"]["4.1 INDUSTRY ANALYSIS - PORTERS 5 FORCE ANALYSIS"])) as $value)
                    <p>&emsp;{{ $value }}</p>
                @endforeach
                <p>5. KEY MARKET INSIGHTS</p>
                @foreach($response["data"]["content"]["5. KEY MARKET INSIGHTS"] as $value)
                    <p>&emsp;{{ $value }}</p>
                @endforeach
                <p>6. MARKET SIZE, 2021-2030</p>
            </div>
        @elseif(app('request')->input('page') == 6)
            <div class="page-5-intro-index">
                <p>6.4 By Region</p>
                <p>&emsp;6.4.1 North America</p>
                @foreach($response["data"]["content"]["6.4.1 North America"] as $value)
                    @if(is_array($value))
                        <p>&emsp;&emsp;6.4.1.1 USA</p>
                        @foreach($value as $v)
                            <p>&emsp;&emsp;&emsp;{{ $v }}</p>
                        @endforeach
                    @else
                        <p>&emsp;&emsp;{{ $value }}</p>
                    @endif
                @endforeach
                <p>6.4.2 Europe</p>
                @foreach($response["data"]["content"]["6.4.2 Europe"] as $value)
                    <p>&emsp;{{ $value }}</p>
                @endforeach
                <p>6.4.3 Asia-Pacific</p>
                @foreach($response["data"]["content"]["6.4.3 Asia-Pacific"] as $value)
                    <p>&emsp;{{ $value }}</p>
                @endforeach
            </div>
            <div class="page-5-intro-index-right">
                <p>6.4.4 Middle East & Africa (MEA)</p>
                @foreach($response["data"]["content"]["6.4.4 Middle East & Africa (MEA)"] as $value)
                    <p>&emsp;{{ $value }}</p>
                @endforeach
                <p>6.4.4 Latin America (LATAM)</p>
                @foreach($response["data"]["content"]["6.4.4 Latin America (LATAM)"] as $value)
                    <p>&emsp;{{ $value }}</p>
                @endforeach
                <p>7. COMPETITIVE INTELLIGENCE</p>
                @foreach($response["data"]["content"]["7. COMPETITIVE INTELLIGENCE"] as $value)
                    <p>&emsp;{{ $value }}</p>
                @endforeach
                <p>8. COMPANY PROFILES</p>
                @foreach($response["data"]["content"]["8. COMPANY PROFILES"] as $value)
                    <p>&emsp;{{ $value }}</p>
                @endforeach
                <p>9. NEED GAP ASSESSMENT</p>
                @foreach($response["data"]["content"]["9. NEED GAP ASSESSMENT"] as $value)
                    <p>&emsp;{{ $value }}</p>
                @endforeach
                <p>10. CONCLUSION</p>
                @foreach($response["data"]["content"]["10. CONCLUSION"] as $value)
                    <p>&emsp;{{ $value }}</p>
                @endforeach
            </div>
        @endif
    @endif
</div>