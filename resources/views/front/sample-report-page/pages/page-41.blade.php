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
        <div style="height: 60vh;"><canvas id="price-spectrum-chart"></canvas></div>
        <ul class="price-spectrum-text">
            <li>In the <span>Global report name</span> market, the pricing spectrum for XX is diverse, with Generally Traded XX typically found in the XX-XX range. For those who prefer premium options, High-End XX is priced in the XX-XX range, while the more budget-conscious can explore Lower-End XX, which falls within the XX-XX range. This pricing diversity reflects the range of choices available to consumers in the market name.</li>
        </ul>
    </div>
</div>