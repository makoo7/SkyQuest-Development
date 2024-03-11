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
        <h6 class="purple-color text-center font-weight-bold">Figure 1: <span>Report Name</span> Global Market Size in US$ Bn (2018-2030)</h6>
        <div class="row dark-grey-background py-5">
            <div class="col-md-6 text-center">
                <div style="height: 40vh;" class="d-flex justify-content-center">
                    <canvas id="global-market-largest-chart"></canvas>
                    <div class="global-mkt-chart-bottom-text">
                        <h6 class="mb-1">xx Region</h6>
                        <h6 class="mb-0">(Largest)</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-center mt-md-0 mt-5">
                <div style="height: 40vh;" class="d-flex justify-content-center">
                    <canvas id="global-market-second-largest-chart"></canvas>
                    <div class="global-mkt-chart-bottom-text">
                        <h6 class="mb-1">xx Region</h6>
                        <h6 class="mb-0">(Largest)</h6>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <h5 class="box-title mt-5 mb-2">KEY TAKEAWAYS</h5>
            <ul class="list">
                <li class="list-items data-point-list"><span class="font-weight-bold">Largest Region:</span> In 2030, <span>[Region XYZ]</span> held the distinction of being the largest market for the studied market, with a total market size of [XX%] of the global market in 2023. This trend is expected to continue through 2030, with the region maintaining its position as the largest market, projected to reach <span>[XX%]</span> of the global market by 2030.</li>
                <li class="list-items data-point-list"><span class="font-weight-bold">Second Largest Region:</span> In 2030, the second-largest market for the studied market was <span>[Second Largest Region],</span> representing <span>[XX%]</span> of the global market in 2023. This region is expected to maintain its position as the second-largest market, with a projected market size of <span>[XX%]</span> of the global market by 2030.</li>
            </ul>
        </div>
    </div>
</div>