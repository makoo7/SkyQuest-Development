<div id="pg2Overlay" style="width:100%; height:100%; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg2" class="content-background" style="-webkit-user-select: none;"></div>
<div class="text-container">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <p class="intro-frame page-title page-5-title-full">{{ $response["data"]["title"] }}</p>
        <div>
            <img src="{{ asset("assets/frontend/slide/2/img/1.jpg") }}" alt="logo" class="img-fluid" />
        </div>
    </div>
    <div class="row content-height">
        <div class="col-md-6 text-center">
            <div class="grey-background">
                <h3 class="box-title">GLOBAL MARKET SIZE BY SEGMENT 1</h3>
                <p class="chart-title-sub-text">in USD Million</p>
                <div>
                    <canvas id="global-market-seg1"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-center">
            <div class="grey-background">
                <h3 class="box-title">GLOBAL MARKET SIZE BY GEOGRAPHY</h3>
                <p class="chart-title-sub-text">in USD Million</p>
                <div>
                    <canvas id="global-market-geography"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-center mt-4">
            <div class="grey-background">
                <h3 class="box-title">GLOBAL MARKET SIZE BY SEGMENT 2</h3>
                <p class="chart-title-sub-text">in USD Million</p>
                <div>
                    <canvas id="global-market-seg2"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-center mt-4">
            <div class="grey-background">
                <h3 class="box-title">GLOBAL MARKET SIZE BY SEGMENT 3</h3>
                <p class="chart-title-sub-text">in USD Million</p>
                <div>
                    <canvas id="global-market-seg3"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>