<div id="pg2Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg2" style="-webkit-user-select: none;"><object data="{{ asset('assets/frontend/slide/2/2.svg') }}" type="image/svg+xml" id="pdf2" style="-moz-transform:scale(1); z-index: 0;"></object></div>
<div class="text-container">
    <span class="intro-frame page-5-title-full">{{ $response["data"]["title"] }}</span>
    <p class="intro-frame" style="top:10rem;left:4rem;text-align:justify;font-size:90%;">
        In our market assessment of {{ $response["data"]["name"] }} Market,  we employed a comprehensive research methodology, combining top-down and bottom-up approaches, to ensure precise market size estimations. The methodology included:
    </p>
    <ul class="intro-frame" style="list-style-type:square; top:14rem;left: 4rem;position: absolute;font-size:90%">
        <li>Identifying Key Players: We began with thorough secondary research to identify major market players, enhancing our understanding of the market landscape.</li>
        <li>Determining Market Shares: Market shares of identified players across various regions were determined through a blend of primary and secondary research methods, including analysis of their financial reports.</li>
        <li>Industry Expert Insights: In-depth interviews were conducted with industry leaders, such as CEOs, VPs, and directors, to gain valuable insights.</li>
        <li>Data Verification: All data, including percentage shares and market breakdowns, underwent rigorous cross-verification between secondary and primary sources.</li>
        <li>Parameter Analysis: A meticulous examination of all relevant market factors, validated through primary research, was undertaken.</li>
        <li>Data Consolidation: Findings from the above steps were consolidated, enriched with detailed analysis, and presented for a comprehensive dataset.
            In conclusion, our research methodology blends top-down and bottom-up approaches to ensure accuracy. It involves identifying key players, determining market shares, gathering insights from industry experts, data verification, comprehensive parameter analysis, and data consolidation, forming the basis for our report's insights.</li>
    </ul>
    <div>
        <p class="intro-frame" style="top: 28rem;left:4rem;">MARKET SIZE ESTIMATION: BOTTOM-UP APPROACH</p>
        <div>
            <div style="position: absolute; top:30rem;">
                <img src="{{ asset("assets/frontend/slide/20/2.png") }}" width="60%" alt="">
            </div>
            <div style="position: absolute; top:30rem;left:15rem;">
                <p style="font-size:80%;width:30%;padding-left:1rem;"> {{ $response["data"]["name"] }} Market  Size </p>
                <p style="font-size:80%;width:40%;padding-left:1rem;">Regional And Key Country-level Consumption Of {{ $response["data"]["name"] }} Market  Size Market </p>
                <p style="font-size:80%;width:40%;padding-left:1rem;">Summation of country-level subsegment data to arrive at regional consumption of {{ $response["data"]["name"] }} </p>
                <p style="font-size:80%;width:40%;padding-left:1rem;">Market size of {{ $response["data"]["name"] }} Market subsegments at country level</p>
            </div>
        </div>
    </div>
    <div>
        <p class="intro-frame" style="top: 28rem;left:44rem;">MARKET SIZE ESTIMATION: TOP-DOWN APPROACH</p>
        <div style="position: absolute; top:30rem;left:40rem;">
            <img src="{{ asset("assets/frontend/slide/20/1.png") }}" width="60%" alt="">
        </div>
        <div style="position: absolute; top:30rem;left:60rem;">
            <p style="font-size:80%;padding-left:1rem;"> {{ $response["data"]["name"] }} Market  Size </p>
            <p style="font-size:80%;padding-left:1rem;">Percentage split for each segment of the {{ $response["data"]["name"] }} Market </p>
            <p style="font-size:80%;padding-left:1rem;">Regional and country-wise split for each segment and subsegment of {{ $response["data"]["name"] }} Market </p>
        </div>
    </div>
</div>