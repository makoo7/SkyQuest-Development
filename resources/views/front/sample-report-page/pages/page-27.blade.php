<div id="pg2Overlay" style="width:100%; height:100%; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg2" class="content-background" style="-webkit-user-select: none;"></div>
<div class="text-container height-100vh">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <p class="intro-frame page-title page-5-title-full">{{ $response["data"]["title"] }}</p>
        <div>
            <img src="{{ asset("assets/frontend/slide/2/img/1.jpg") }}" alt="logo" class="img-fluid" />
        </div>
    </div>
    <div class="row content-height">
        <div class="col-md-4">
            <div style="height: 60vh;">
                <canvas id="semi-doughnut-chart"></canvas>
            </div>
        </div>
        <div class="col-md-8">
            <div class="hr-block h-100">
                <span class="box-border"></span>
                <span class="box-border"></span>
                <span class="box-border"></span>
            </div>
        </div>
    </div>
</div>