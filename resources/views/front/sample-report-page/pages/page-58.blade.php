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
            <div class="col-md-6">
                <p style="line-height: 1.8;">The following figure represents the market positioning of Almonds Ingredients players for the year 2023. The  market positioning of the leading manufacturers in the report name market is done considering the  following points:</p>
                <ul>
                    <li class="font-weight-bold mb-2">Product offering strength</li>
                    <ul>
                        <li class="mb-2">Breadth and Depth of product offering</li>
                        <li class="mb-2">Different features of the products</li>
                        <li class="mb-2">Product innovation</li>
                    </ul>
                    <li class="font-weight-bold mb-2">Business Strength</li>
                    <ul>
                        <li class="mb-2">Breadth of market segment served</li>
                        <li class="mb-2">Geographic footprint</li>
                        <li class="mb-2">Effectiveness of growth strategy</li>
                    </ul>
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="box-title mb-3">Prominent players in the market</h6>
                <div class="ml-4"><canvas id="prominent-player-scatter-chart"></canvas></div>
                <div class="prominent-player-strength">
                    <p class="prominent-player-strength-text">Low</p>
                    <p class="prominent-player-strength-text">Moderate</p>
                    <p class="prominent-player-strength-text">High</p>
                </div>
                <div class="prominent-player-strength vertical-text">
                    <p class="prominent-player-strength-text">Low</p>
                    <p class="prominent-player-strength-text">Moderate</p>
                    <p class="prominent-player-strength-text">High</p>
                </div>
            </div>
        </div>
    </div>
</div>