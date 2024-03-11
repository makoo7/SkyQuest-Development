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
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="card industry-analysis-card h-100">
                    <div class="card-header text-left purple-background">
                        <h5 class="card-head-text mb-0">FIGURE XX: RECENT FINANCIALS</h5>
                    </div>
                    <div><canvas id="recent-financial-chart"></canvas></div>
                    <div class="chart-sample-text-box text-center">
                        <h3 class="sample-title font-weight-bold">Sample Report</h3>
                        <p class="sample-text">The numbers on this chart have been removed from this sample. All numbers are available in the full-length report</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-4 mt-md-0 col-12">
                <div class="card industry-analysis-card h-100">
                    <div class="card-header text-left purple-background">
                        <h5 class="card-head-text mb-0">FIGURE XX: BUSINESS REVENUE MIX, 2022</h5>
                    </div>
                    <div class="d-flex justify-content-center" style="height: 300px;"><canvas id="business-revenue-chart"></canvas></div>
                </div>
            </div>
            <div class="col-md-12 mt-4">
                <div class="card industry-analysis-card competitive-card">
                    <div class="card-header text-left purple-background">
                        <h5 class="card-head-text mb-0">FIGURE XX: BUSINESS REVENUE MIX, 2022</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="boxed-text boxed-text-centered">DETAILED ANALYSIS WILL BE PROVIDED WITH FULL REPORT</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>