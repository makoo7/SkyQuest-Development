<div id="pg2Overlay" style="width:100%; height:100%; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg2" class="content-background" style="-webkit-user-select: none;"></div>
<div class="text-container height-100vh">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <p class="intro-frame page-title page-5-title-full">{{ $response["data"]["title"] }}</p>
        <div>
            <img src="{{ asset("assets/frontend/slide/2/img/1.jpg") }}" alt="logo" class="img-fluid" />
        </div>
    </div>
    <div class="content-height">
        <h5 class="purple-color text-center font-weight-bold">Figure 2: <span>Report Name Market Size</span> by Region in US$ Bn (2018-2030)</h5>
        <div style="height: 60vh;"><canvas id="region-market-size-bar-chart"></canvas></div>
        <div class="grey-background">
            <h5 class="font-weight-bold text-uppercase mb-4">KEY TAKEAWAYS</h5>
            <ul class="list blur-element">
                <li class="list-items data-point-list">According to SkyQuest Analysis, the global market is expected to exhibit substantial growth from 2018 to 2030, with a Compound Annual Growth Rate (CAGR) of <span>XX%</span> during this period. Starting from a historical base of <span>US$XX billion</span> in 2018, the market is estimated to reach <span>US$XX</span> billion in 2023 as the current year estimate, and is projected to further expand to <span>US$XX</span> billion by 2030, reflecting a promising long-term outlook and lucrative opportunities for investors and businesses.</li>
            </ul>
        </div>
    </div>
</div>