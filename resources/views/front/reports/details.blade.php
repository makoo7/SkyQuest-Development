@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation")
<div class="report-details-page">
    <div class="container">
        <nav class="upcoming-report-detail-breadcrumb min-height-78">
            <ol class="MuiBreadcrumbs-ol css-nhb8h9">
                <li class="MuiBreadcrumbs-li">
                    <a href="#" class="breadLinkColor">Home</a>
                </li>
                <li class="MuiBreadcrumbs-separator">›</li>
                <li class="MuiBreadcrumbs-li">
                    <a href="{!! url('industries/'.$report->sector->slug) !!}" class="breadLinkColor">{!! $report->sector->title ?? "" !!}</a>
                </li>
                <li class="MuiBreadcrumbs-separator">›</li>
                <li class="MuiBreadcrumbs-li">
                    <a href="{!! url('industries/'.$report->industry_group->slug) !!}" class="breadLinkColor">{!! $report->industry_group->title ?? "" !!}</a>
                </li>
                <li class="MuiBreadcrumbs-separator">›</li>
                <li class="MuiBreadcrumbs-li">
                    <a href="{!! url('industries/'.$report->industry->slug) !!}" class="breadLinkColor">{!! $report->industry->title ?? "" !!}</a>
                </li>
                <li class="MuiBreadcrumbs-separator">›</li>
                <li class="MuiBreadcrumbs-li">
                    <a href="{!! url('industries/'.$report->sub_industry->slug) !!}" class="breadLinkColor">{!! $report->sub_industry->title ?? "" !!}</a>
                </li>
                <li class="MuiBreadcrumbs-separator">›</li>
                <li class="MuiBreadcrumbs-li">
                    <span>{!! $report->name !!}</span>
                </li>
            </ol>
        </nav>
        <div class="upcoming-report-container reports">
            <div class="upcoming-report-detail-container">
                <div class="report-items-inner">
                    <div class="report-img">
                        <img src="{!! $report->image_url !!}" alt="{!! $report->image_alt ?? '' !!}" width="200" height="240" loading="lazy">
                    </div>
                    <div class="containt">
                        <div class="d-sm-flex flex-sm-row-reverse align-items-center title">
                        <h1 class="me-auto" style="flex:1;">{!! isset($report_name) ? $report_name : '' !!}</h1>
                        </div>
                        <div class="report-segment-data max-width-640">
                            <hr/>
                            <p class="grey-content">
                                @if(isset($report->product_id))
                                <b>Report ID:</b>
                                {!! $report->product_id. ' | ' !!}
                                @endif

                                @if(isset($report->country))
                                <b>Region:</b>
                                {!! ucfirst($report->country). ' | ' !!}
                                @endif

                                @if($report->report_type=='Upcoming')
                                    <b>Published Date:</b> Upcoming |
                                @elseif(($report->report_type!='Upcoming') && isset($report->publish_date))
                                    <b>Published Date:</b> {!! convertUtcToIst($report->publish_date, config('constants.DISPLAY_REPORT_DATE')) .' | ' !!}
                                @endif

                                @if(isset($report->pages))
                                <b>Pages:</b>
                                {!! $report->pages !!}
                                @endif

                                @if($report->report_type=='Upcoming')
                                 | <b>Tables:</b> 55 | <b>Figures:</b> 60
                                @else
                                    @if(!$report->report_tableofcontent->isEmpty())
                                        @if(($report->report_tableofcontent[0]->tables!='') || ($report->report_tableofcontent[0]->figures!=''))
                                        |
                                        @endif

                                        @if($report->report_tableofcontent[0]->tables!='')
                                        <b>Tables:</b>
                                        {!! ($report->report_tableofcontent[0]->tables!='') ? substr_count($report->report_tableofcontent[0]->tables,"<p>") .' | ' : '' !!}
                                        @endif

                                        @if($report->report_tableofcontent[0]->figures!='')
                                        <b>Figures:</b>
                                        {!! ($report->report_tableofcontent[0]->figures!='') ? substr_count($report->report_tableofcontent[0]->figures,"<p>")  : '' !!}
                                        @endif
                                    @endif
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="fixed-social-icons">
                        <div id="social-links">
                            <ul>
                                @if(isset($sociallinks))
                                <li><a href="{!! $sociallinks['facebook'] !!}" class="social-button " id="" aria-label="Facebook" title="Facebook"><span class="fab fa-facebook-square"></span></a></li>
                                <li><a href="{!! $sociallinks['twitter'] !!}" class="social-button " id="" aria-label="Twitter" title="Twitter"><span class="fab fa-twitter"></span></a></li>
                                <li><a href="{!! $sociallinks['linkedin'] !!}" class="social-button " id="" aria-label="Linkedin" title="Linkedin"><span class="fab fa-linkedin"></span></a></li>
                                @endif
                                <li><a href="#" id="copyLink"><img class="book-mark-tag" src="{!! asset('assets/frontend/images/icon_link.png') !!}" alt="icon_link"/></a></li>
                                @if(auth('web')->check())
                                <li><a href="#" onclick="toggleBookmark('{!! Auth::user()->id !!}','report','{!! $report->id !!}','1');">
                                    @if((isset($report['report_bookmark'][0]->user_id)))
                                        @if(($report['report_bookmark'][0]->user_id == Auth::user()->id) && ($report['report_bookmark'][0]->entity_id == $report->id))
                                            <img id="bookmarktag" class="book-mark-tag" data-id="{!! $report->id !!}" src="{!! asset('assets/frontend/images/icon_bookmark.png') !!}" alt="Top market research company in India">
                                        @else
                                            <img id="bookmarktag" class="book-mark-tag" data-id="{!! $report->id !!}" src="{!! asset('assets/frontend/images/icon_unbookmark.png') !!}" alt="Top market research company in India">
                                        @endif
                                    @else
                                        <img id="bookmarktag" class="book-mark-tag" data-id="{!! $report->id !!}" src="{!! asset('assets/frontend/images/icon_unbookmark.png') !!}" alt="Top market research company in India">
                                    @endif
                                </a></li>
                                @else
                                <li><a href="#" onclick="alertBookmark();">
                                    <img class="book-mark-tag" src="{!! asset('assets/frontend/images/icon_unbookmark.png') !!}" alt="Top market research company in India">
                                </a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="reports-tabs reports-details-tabs">
                    <div class="tabbable-panel">
                        <div class="tabbable-line">
                            <div class="tabs-bar">
                                <ul class="nav nav-tabs tabs-inner">
                                    <li class="publish-tab">
                                        <a class="active" href="#tab_default_1" data-bs-toggle="tab">DESCRIPTION</a>
                                    </li>
                                    <li class="upcoming-tab">
                                        <a href="#tab_default_3" class="report-tabs" data-type="toc" data-id="tab3" data-bs-toggle="tab">TABLE OF CONTENT</a>
                                    </li>
                                    <li class="upcoming-tab">
                                        <a href="#tab_default_4" class="report-tabs" data-type="methodology" data-id="tab4" data-bs-toggle="tab">METHODOLOGY</a>
                                    </li>
                                    <li class="upcoming-tab">
                                        <a href="#tab_default_5" class="report-tabs" data-type="analystsupport" data-id="tab5" data-bs-toggle="tab">ANALYST SUPPORT</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                @if($report->report_type=='SD')
                                <div class="tab-pane active" id="tab_default_1">
                                    @if(isset($report->market_insights))
                                    <h2 class="report-title">{!! $report->name !!} Insights</h2>
                                    <div class="report-details-description">
                                        {!! $report->market_insights ?? '' !!}
                                    </div>
                                    @endif
                                    @if(!$report->report_graphs->isEmpty() && !$report->report_metrics->isEmpty())
                                    @php
                                    $forecast_period_key = array_search('Forecast period', array_column($report->report_metrics->toArray(), 'meta_key'));
                                    $forecast_period = isset($forecast_period_key)?$report->report_metrics[$forecast_period_key]['meta_value']:'';
                                    $base_year_arr = explode(" ",$base_year_meta_key);
                                    $base_year = $base_year_arr ? $base_year_arr[count($base_year_arr)-1] : '';
                                    @endphp
                                    <div class="market-snapshot-wrapper">
                                        <h4 class="market-snapshot">Market snapshot{{ $forecast_period?" - ".$forecast_period:'' }}</h4>
                                        <div class="row mb-3">
                                            @if(isset($report->report_metrics) && ($base_year_meta_value!=''))
                                            <div class="col-md-3 col-sm-6 mt-4">
                                                <div class="snap-shot-card one">
                                                    <div class="title"><p>Global Market Size</p></div>
                                                    <div class="content">
                                                        <p>{!! ($base_year_meta_value) ? $base_year_meta_value : '' !!}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if(isset($largest_segment) && !empty($largest_segment))
                                            <div class="col-md-3 col-sm-6 mt-4">
                                                <div class="snap-shot-card two">
                                                    <div class="title"><p>Largest Segment</p></div>
                                                    <div class="content">
                                                        <p>
                                                            {!! strip_tags($largest_segment) !!}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if(isset($fastest_growth) && !empty($fastest_growth))
                                            <div class="col-md-3 col-sm-6 mt-4">
                                                <div class="snap-shot-card three">
                                                    <div class="title"><p>Fastest Growth</p></div>
                                                    <div class="content">
                                                        <p>
                                                            {!! strip_tags($fastest_growth) !!}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if(isset($report->report_metrics))
                                                @php
                                                $growth_rate_key = array_search('Growth Rate', array_column($report->report_metrics->toArray(), 'meta_key'));
                                                @endphp
                                                @if(isset($growth_rate_key))
                                                    @if($report->report_metrics[$growth_rate_key]['meta_value']!='')
                                            <div class="col-md-3 col-sm-6 mt-4">
                                                <div class="snap-shot-card four">
                                                    <div class="title"><p>Growth Rate</p></div>
                                                    <div class="content">
                                                        <p>{!! strip_tags($report->report_metrics[$growth_rate_key]['meta_value'])." CAGR" !!}</p>
                                                    </div>
                                                </div>
                                            </div>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                @if(!empty($chart_1["above_name"]))<div class="graph-title">{!! strip_tags($chart_1["above_name"]) !!}</div>@endif
                                                @if(!empty($chart_1["content"]))<div id="first_chart_div" style="width:100%;height:350px;"></div>@endif
                                                @if(!empty($chart_1["below_name"]))<div class="graph-title-bottom">{!! strip_tags($chart_1["below_name"]) !!}</div>@endif
                                            </div>
                                            <div class="col-md-6 mt-4 mt-md-0">
                                                @if(!empty($chart_2["above_name"]))<div class="graph-title">{!! strip_tags($chart_2["above_name"]) !!}</div>@endif
                                                @if(!empty($chart_2["content"]))<div id="second_chart_div" style="width:100%;height:350px;"></div>@endif
                                                @if(!empty($chart_2["below_name"]))<div class="graph-title-bottom">{!! strip_tags($chart_2["below_name"]) !!}</div>@endif
                                            </div>
                                            <div class="col-md-6 mt-4">
                                                @if(!empty($chart_3["above_name"]))<div class="graph-title">{!! strip_tags($chart_3["above_name"]) !!}</div>@endif
                                                @if(!empty($chart_3["content"]))<div id="third_chart_div" style="width:100%;height:350px;"></div>@endif
                                                @if(!empty($chart_3["below_name"]))<div class="graph-title-bottom">{!! strip_tags($chart_3["below_name"]) !!}</div>@endif
                                            </div>
                                            <div class="col-md-6 mt-4">
                                                @if(!empty($chart_4["above_name"]))<div class="graph-title">{!! strip_tags($chart_4["above_name"]) !!}</div>@endif
                                                @if(!empty($chart_4["content"]))<div id="fourth_chart_div" style="width:100%;height:350px;"></div>@endif
                                                @if(!empty($chart_4["below_name"]))<div class="graph-title-bottom">{!! strip_tags($chart_4["below_name"]) !!}</div>@endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <p>
                                        To get more reports on the above market click here to
                                        <a href="{!! url('buy-now/'.$report->slug) !!}" class="paraLink">Buy The Report</a>
                                    </p>
                                    @if(isset($report->segmental_analysis))
                                    <h2 class="report-title">{!! $report->name !!} Segmental Analysis</h2>
                                    <div>
                                        {!! $report->segmental_analysis ?? '' !!}
                                    </div>
                                    @endif
                                    @if(!$report->report_graphs->isEmpty())
                                    @if(!empty($chart_5["above_name"]))<div class="text-center ms-chart-heading">{!! strip_tags($chart_5["above_name"]) !!}{{ $base_year?", ".$base_year." (%)":'' }}</div>@endif
                                    @if(!empty($chart_5["content"]))
                                    <div class="text-center">
                                        <div id="fifth_chart_div" class="fifth-chart-view"></div>
                                    </div>
                                    @endif
                                    @if(!empty($chart_5["below_name"]))<div class="text-center ms-chart-heading">{!! strip_tags($chart_5["below_name"]) !!}</div>@endif
                                    @endif
                                    <p>
                                        To get detailed analysis on other segments,
                                        <a href="{!! url('sample-request/'.$report->slug) !!}" class="paraLink">Request For Sample Report</a>
                                    </p>
                                    @if(isset($report->regional_insights))
                                    <h2 class="report-title">{!! $report->name !!} Regional Insights</h2>
                                    <div>
                                        {!! $report->regional_insights ?? '' !!}
                                    </div>
                                    @endif
                                    @if(!$report->report_graphs->isEmpty())
                                    @if(!empty($chart_6["above_name"]))<div class="text-center ms-chart-heading">{!! strip_tags($chart_6["above_name"]) !!}{{ $forecast_period?", ".$forecast_period:'' }}</div>@endif
                                    @if(!empty($chart_6["content"]))
                                    <div id="sixth_chart_div" style="height:600px;width:100%;"></div>
                                    @endif
                                    @if(!empty($chart_6) && !empty($chart_6["content"]))
                                    <ul class="graph-data">
                                        <li><span class="circle largest-circle"></span>Largest</li>
                                        <li><span class="circle fastest-circle"></span>Fastest</li>
                                    </ul>
                                    @endif
                                    @if(!empty($chart_6["below_name"]))<div class="text-center ms-chart-heading">{!! strip_tags($chart_6["below_name"]) !!}</div>@endif
                                    @endif
                                    <p class="text-center">
                                        To know more about the market opportunities by region and country, click here to
                                        <br>
                                        <a href="{!! url('buy-now/'.$report->slug) !!}" class="paraLink">Buy The Complete Report</a>
                                    </p>
                                    @if(isset($report->market_dynamics))
                                    <h2 class="report-title">{!! $report->name !!} Dynamics</h2>
                                    <div>
                                        {!! $report->market_dynamics ?? '' !!}
                                    </div>
                                    @endif
                                    <p class="text-center">
                                        <a href="{!! url('speak-with-analyst/'.$report->slug) !!}" class="paraLink">Speak to one of our ABIRAW analyst</a>
                                        for your custom requirements before the purchase of this report
                                    </p>
                                    @if(isset($report->competitive_landscape))
                                    <h2 class="report-title">{!! $report->name !!} Competitive Landscape</h2>
                                    <div>
                                        {!! $report->competitive_landscape ?? '' !!}
                                    </div>
                                    @endif
                                    @if(isset($report->key_market_trends))
                                    <h2 class="report-title">{!! str_replace('Market','Key Market',$report->name) !!} Trends</h2>
                                    <div>
                                        {!! $report->key_market_trends ?? '' !!}
                                    </div>
                                    @endif
                                    @if(isset($report->skyQuest_analysis))
                                    <h2 class="report-title">{!! $report->name !!} SkyQuest Analysis</h2>
                                    <div>
                                        {!! $report->skyQuest_analysis ?? '' !!}
                                    </div>
                                    @endif
                                    @if(isset($report->report_metrics) && !$report->report_metrics->isEmpty())
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Report Metric</th>
                                                <th>Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($report->report_metrics as $k => $report_metric)
                                            @if(isset($report_metric->meta_value) && $report_metric->meta_value!='')
                                            <tr>
                                                <td class="fw-bold">{!! $report_metric->meta_key !!}</td>
                                                <td>{!! $report_metric->meta_value !!}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                    <p>
                                        To get a free trial access to our platform which is a one stop solution for all your data requirements
                                        for quicker decision making. This platform allows you to compare markets, competitors who are prominent
                                        in the market, and mega trends that are influencing the dynamics in the market. Also, get access to
                                        detailed SkyQuest exclusive matrix.
                                    </p>
                                    <p>
                                        <a href="{!! url('buy-now/'.$report->slug) !!}" class="paraLink">Buy The Complete Report</a>
                                        to read the analyzed strategies adopted by the top vendors either to retain or gain market share
                                    </p>
                                </div>
                                @endif
                                @if($report->report_type=='Dynamic')
                                <div class="tab-pane active" id="tab_default_1">
                                    <h2 class="report-title">{!! $report->name !!}</h2>
                                    <div class="report-details-description">
                                        {!! $report->description ?? '' !!}
                                    </div>
                                    <p>
                                        To get a free trial access to our platform which is a one stop solution for all your data requirements
                                        for quicker decision making. This platform allows you to compare markets, competitors who are prominent
                                        in the market, and mega trends that are influencing the dynamics in the market. Also, get access to
                                        detailed SkyQuest exclusive matrix.
                                    </p>
                                    <p>
                                        <a href="{!! url('buy-now/'.$report->slug) !!}" class="paraLink">Buy The Complete Report</a>
                                        to read the analyzed strategies adopted by the top vendors either to retain or gain market share
                                    </p>
                                </div>
                                @endif
                                @if($report->report_type=='Upcoming')
                                <div class="tab-pane active" id="tab_default_1">
                                    <h2 class="report-title">{!! $report->name !!} Insights</h2>
                                    <h3>Market Overview:</h3>
                                    <div class="">
                                        <p>{!! $report->market_insights ?? '' !!}</p>
                                    </div>
                                    <div class="text-center ms-chart-heading">{!! $report->name !!}, Forecast & Y-O-Y Growth Rate, 2020 - 2028</div>
                                    <div class="market-snapshot-wrapper"><img src="{!! asset('assets/frontend/images/Artboard_1_2.png') !!}" alt="ForecastGrowthRate" width="800" height="450"></div>
                                    <div class="text-center">To get more reports on the above market click here to <br><a href="{!! url('sample-request/'.$report->slug) !!}" class="paraLink">Get Sample</a></div>

                                    <p>This report is being written to illustrate the market opportunity by region and by segments, indicating opportunity areas for the vendors to tap upon. To estimate the opportunity, it was very important to understand the current market scenario and the way it will grow in future.</p>
                                    <p>Production and consumption patterns are being carefully compared to forecast the market. Other factors considered to forecast the market are the growth of the adjacent market, revenue growth of the key market vendors, scenario-based analysis, and market segment growth.</p>
                                    <p>The market size was determined by estimating the market through a top-down and bottom-up approach, which was further validated with industry interviews. Considering the nature of the market we derived the {!! $report->sub_industry->title !!} by segment aggregation, the contribution of the {!! $report->sub_industry->title !!} in {!! $report->industry_group->title !!} and vendor share.</p>
                                    <p>To determine the growth of the market factors such as drivers, trends, restraints, and opportunities were identified, and the impact of these factors was analyzed to determine the market growth. To understand the market growth in detail, we have analyzed the year-on-year growth of the market. Also, historic growth rates were compared to determine growth patterns.</p>

                                    <h3>Segmentation Analysis:</h3>
                                    <p>The {!! $report->name !!} is segmented by {!! implode(", ",$segments) !!}. We are analyzing the market of these segments to identify which segment is the largest now and in the future, which segment has the highest growth rate, and the segment which offers the opportunity in the future.</p>
                                    <div class="text-center ms-chart-heading">{!! $report->name !!} Basis Point Share Analysis, 2021 Vs. 2028</div>
                                    <div class="market-snapshot-wrapper"><img src="{!! asset('assets/frontend/images/Artboard_1.png') !!}" alt="BasisPointShareAnalysis" width="800" height="450"></div>
                                    <div class="text-center">To get detailed analysis on all segments <br><a href="{!! url('buy-now/'.$report->slug) !!}" class="paraLink">BUY NOW</a></div>

                                    @if(isset($segments))
                                    <ul>
                                    @foreach($segments as $k => $segment)
                                    <li>Based on {!! $segment !!} the market is segmented as, {!! $sub_segments[$k] !!}</li>
                                    @endforeach
                                    </ul>
                                    @endif

                                    <h3>Regional Analysis:</h3>
                                    <p>{!! $report->name !!} is being analyzed by North America, Europe, Asia-Pacific (APAC), Latin America (LATAM), Middle East & Africa (MEA) regions. Key countries including the U.S., Canada, Germany, France, UK, Italy, Spain, China, India, Japan, Brazil, GCC Countries, and South Africa among others were analyzed considering various micro and macro trends.</p>
                                    <div class="text-center ms-chart-heading">{!! $report->name !!} Attractiveness Analysis, By Region 2020-2028</div>
                                    <div class="market-snapshot-wrapper"><img src="{!! asset('assets/frontend/images/Artboard_1_5.png') !!}" alt="AttractivenessAnalysis" width="800" height="450"></div>
                                    <div class="text-center">To know more about the market opportunities by region and country, click here to <br><a href="{!! url('speak-with-analyst/'.$report->slug) !!}" class="paraLink">SPEAK TO AN ANALYST</a></div>

                                    <h3>{!! $report->name !!} : Risk Analysis</h3>
                                    <p>SkyQuest's expert analysts have conducted a risk analysis to understand the impact of external extremities on {!! $report->name !!}. We analyzed how geopolitical influence, natural disasters, climate change, legal scenario, economic impact, trade & economic policies, social & ethnic concerns, and demographic changes might affect {!! $report->name !!}'s supply chain, distribution, and total revenue growth.</p>

                                    <h3>Competitive landscaping:</h3>
                                    <p>To understand the competitive landscape, we are analyzing key {!! $report->name !!} vendors in the market. To understand the competitive rivalry, we are comparing the revenue, expenses, resources, product portfolio, region coverage, market share, key initiatives, product launches, and any news related to the {!! $report->name !!}.</p>
                                    <p>To validate our hypothesis and validate our findings on the market ecosystem, we are also conducting a detailed porter's five forces analysis. Competitive Rivalry, Supplier Power, Buyer Power, Threat of Substitution, and Threat of New Entry each force is analyzed by various parameters governing those forces.</p>

                                    @if(isset($companiesStr))
                                    <h3>Key Players Covered in the Report:</h3>
                                    {!! $companiesStr !!}
                                    @endif

                                    <h3>SkyQuest's Expertise:</h3>
                                    <p>The {!! $report->name !!} is being analyzed by SkyQuest's analysts with the help of 20+ scheduled Primary interviews from both the demand and supply sides. We have already invested more than 250 hours on this report and are still refining our date to provide authenticated data to your readers and clients. Exhaustive primary and secondary research is conducted to collect information on the market, peer market, and parent market.</p>
                                    <p>Our cross-industry experts and revenue-impact consultants at SkyQuest enable our clients to convert market intelligence into actionable, quantifiable results through personalized engagement.</p>

                                    <h3>Scope Of Report</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Report Attribute</th>
                                                <th>Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="fw-bold">The base year for estimation</td>
                                                <td>2021</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Historical data</td>
                                                <td>2016 – 2022</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Forecast period</td>
                                                <td>2022 – 2028</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Report coverage</td>
                                                <td>Revenue forecast, volume forecast, company ranking, competitive landscape, growth factors, and trends, Pricing Analysis</td>
                                            </tr>
                                            @if(isset($segments))
                                            <tr>
                                                <td class="fw-bold">Segments covered</td>
                                                <td>
                                                    <ul>
                                                    @foreach($segments as $k => $segment)
                                                    <li>By {!! $segment !!} - {!! $sub_segments[$k] !!}</li>
                                                    @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td class="fw-bold">Regional scope</td>
                                                <td>North America, Europe, Asia-Pacific (APAC), Latin America (LATAM), Middle East & Africa (MEA)</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Country scope</td>
                                                <td>U.S., Canada, Germany, France, UK, Italy, Spain, China, India, Japan, Brazil, GCC Countries, South Africa</td>
                                            </tr>
                                            @if(isset($companiesStr))
                                            <tr>
                                                <td class="fw-bold">Key companies profiled</td>
                                                <td>{!! $companiesStr !!}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td class="fw-bold">Customization scope</td>
                                                <td>Free report customization (15% Free customization) with purchase. Addition or alteration to country, regional & segment scope.</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Pricing and purchase options</td>
                                                <td>Reap the benefits of customized purchase options to fit your specific research requirements.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <h3>Objectives of the Study</h3>
                                    <ul>
                                        <li>To forecast the market size, in terms of value, for various segments with respect to five main regions, namely, North America, Europe, Asia-Pacific (APAC), Latin America (LATAM), Middle East & Africa (MEA)</li>
                                        <li>To provide detailed information regarding the major factors influencing the growth of the Market (drivers, restraints, opportunities, and challenges)</li>
                                        <li>To strategically analyze the micro markets with respect to the individual growth trends, future prospects, and contribution to the total market</li>
                                        <li>To provide a detailed overview of the value chain and analyze market trends with the Porter's five forces analysis</li>
                                        <li>To analyze the opportunities in the market for various stakeholders by identifying the high-growth Segments</li>
                                        <li>To identify the key players and comprehensively analyze their market position in terms of ranking and core competencies, along with detailing the competitive landscape for the market leaders</li>
                                        <li>To analyze competitive development such as joint ventures, mergers and acquisitions, new product launches and development, and research and development in the market</li>
                                    </ul>

                                    <h3>What does this Report Deliver?</h3>
                                    <ul>
                                        <li>Market Estimation for 20+ Countries</li>
                                        <li>Historical data coverage: 2016 to 2022</li>
                                        <li>Growth projections: 2022 to 2028</li>
                                        <li>SkyQuest's premium market insights: Innovation matrix, IP analysis, Production Analysis, Value chain analysis, Technological trends, and Trade analysis</li>
                                        <li>Customization on Segments, Regions, and Company Profiles</li>
                                        <li>100+ tables, 150+ Figures, 10+ matrix</li>
                                        <li>Global and Country Market Trends</li>
                                        <li>Comprehensive Mapping of Industry Parameters</li>
                                        <li>Attractive Investment Proposition</li>
                                        <li>Competitive Strategies Adopted by Leading Market Participants</li>
                                        <li>Market drivers, restraints, opportunities, and its impact on the market</li>
                                        <li>Regulatory scenario, regional dynamics, and insights of leading countries in each region</li>
                                        <li>Segment trends analysis, opportunity, and growth</li>
                                        <li>Opportunity analysis by region and country </li>
                                        <li>Porter's five force analysis to know the market's condition</li>
                                        <li>Pricing analysis</li>
                                        <li>Parent market analysis</li>
                                        <li>Product portfolio benchmarking</li>
                                    </ul>
                                </div>
                                @endif
                                <!-- END DESCRIPTION TAB -->
                                <!-- END TABLE CONTENT TAB -->
                                <div class="tab-pane" id="tab_default_3">
                                    <h3 class="report-title">Table Of Content</h3>
                                    <div>
                                    <p>
                                        <b>Executive Summary</b></p>
                                        <p>Market overview</p>
                                        <ul>
                                            <li>Exhibit: Executive Summary – Chart on Market Overview</li>
                                            <li>Exhibit: Executive Summary – Data Table on Market Overview</li>
                                            <li>Exhibit: Executive Summary – Chart on {!! $report->name !!} Characteristics</li>
                                            <li>Exhibit: Executive Summary – Chart on Market by Geography</li>
                                            <li>Exhibit: Executive Summary – Chart on Market Segmentation</li>
                                            <li>Exhibit: Executive Summary – Chart on Incremental Growth</li>
                                            <li>Exhibit: Executive Summary – Data Table on Incremental Growth</li>
                                            <li>Exhibit: Executive Summary – Chart on Vendor Market Positioning</li>
                                        </ul>
                                        <p><b>Parent Market Analysis</b></p>
                                        <p>Market overview</p>
                                        <p>Market size</p>
                                        <ul>
                                            <li>Market Dynamics
                                                <ul>
                                                    <li>Exhibit: Impact analysis of DROC, 2021
                                                        <ul>
                                                            <li>Drivers</li>
                                                            <li>Opportunities</li>
                                                            <li>Restraints</li>
                                                            <li>Challenges</li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>SWOT Analysis</li>
                                        </ul>
                                        <p><b>KEY MARKET INSIGHTS</b></p>
                                        <ul>
                                            <li>Technology Analysis
                                                <ul>
                                                    <li>(Exhibit: Data Table: Name of technology and details)</li>
                                                </ul>
                                            </li>
                                            <li>Pricing Analysis
                                                <ul>
                                                    <li>(Exhibit: Data Table: Name of technology and pricing details)</li>
                                                </ul>
                                            </li>
                                            <li>Supply Chain Analysis
                                                <ul>
                                                    <li>(Exhibit: Detailed Supply Chain Presentation)</li>
                                                </ul>
                                            </li>
                                            <li>Value Chain Analysis
                                                <ul>
                                                    <li>(Exhibit: Detailed Value Chain Presentation)</li>
                                                </ul>
                                            </li>
                                            <li>Ecosystem Of the Market
                                                <ul>
                                                    <li>Exhibit: Parent Market Ecosystem Market Analysis</li>
                                                    <li>Exhibit: Market Characteristics of Parent Market</li>
                                                </ul>
                                            </li>
                                            <li>IP Analysis
                                                <ul>
                                                    <li>(Exhibit: Data Table: Name of product/technology, patents filed, inventor/company name, acquiring firm)</li>
                                                </ul>
                                            </li>
                                            <li>Trade Analysis
                                                <ul>
                                                    <li>(Exhibit: Data Table: Import and Export data details)</li>
                                                </ul>
                                            </li>
                                            <li>Startup Analysis
                                                <ul>
                                                    <li>(Exhibit: Data Table: Emerging startups details)</li>
                                                </ul>
                                            </li>
                                            <li>Raw Material Analysis
                                                <ul>
                                                    <li>(Exhibit: Data Table: Mapping of key raw materials)</li>
                                                </ul>
                                            </li>
                                            <li>Innovation Matrix
                                                <ul>
                                                    <li>(Exhibit: Positioning Matrix: Mapping of new and existing technologies)</li>
                                                </ul>
                                            </li>
                                            <li>Pipeline product Analysis
                                                <ul>
                                                    <li>(Exhibit: Data Table: Name of companies and pipeline products, regional mapping)</li>
                                                </ul>
                                            </li>
                                            <li>Macroeconomic Indicators</li>
                                        </ul>
                                        <p><b>COVID IMPACT</b></p>
                                        <ul>
                                            <li>Introduction</li>
                                            <li>Impact On Economy—scenario Assessment
                                                <ul>
                                                    <li>Exhibit: Data on GDP - Year-over-year growth 2016-2022 (%)</li>
                                                </ul>
                                            </li>
                                            <li>Revised Market Size
                                                <ul>
                                                    <li>Exhibit: Data Table on {!! $report->name !!} size and forecast 2021-2027 ($ million)</li>
                                                </ul>
                                            </li>
                                            <li>Impact Of COVID On Key Segments
                                                <ul>
                                                    <li>Exhibit: Data Table on Segment Market size and forecast 2021-2027 ($ million)</li>
                                                </ul>
                                            </li>
                                            <li>COVID Strategies By Company
                                                <ul>
                                                    <li>Exhibit: Analysis on key strategies adopted by companies</li>
                                                </ul>
                                            </li>
                                        </ul>
                                        <p><b>MARKET DYNAMICS & OUTLOOK</b></p>
                                        <ul>
                                            <li>Market Dynamics
                                                <ul>
                                                    <li>Exhibit: Impact analysis of DROC, 2021
                                                        <ul>
                                                            <li>Drivers</li>
                                                            <li>Opportunities</li>
                                                            <li>Restraints</li>
                                                            <li>Challenges</li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>Regulatory Landscape
                                                <ul>
                                                    <li>Exhibit: Data Table on regulation from different region</li>
                                                </ul>
                                            </li>
                                            <li>SWOT Analysis</li>
                                            <li>Porters Analysis
                                                <ul>
                                                    <li>Competitive rivalry
                                                        <ul>
                                                            <li>Exhibit: Competitive rivalry Impact of key factors, 2021</li>
                                                        </ul>
                                                    </li>
                                                    <li>Threat of substitute products
                                                        <ul>
                                                            <li>Exhibit: Threat of Substitute Products Impact of key factors, 2021</li>
                                                        </ul>
                                                    </li>
                                                    <li>Bargaining power of buyers
                                                        <ul>
                                                            <li>Exhibit: buyers bargaining power Impact of key factors, 2021</li>
                                                        </ul>
                                                    </li>
                                                    <li>Threat of new entrants
                                                        <ul>
                                                            <li>Exhibit: Threat of new entrants Impact of key factors, 2021</li>
                                                        </ul>
                                                    </li>
                                                    <li>Bargaining power of suppliers
                                                        <ul>
                                                            <li>Exhibit: Threat of suppliers bargaining power Impact of key factors, 2021</li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>Skyquest special insights on future disruptions
                                                <ul>
                                                    <li>Political Impact</li>
                                                    <li>Economic impact</li>
                                                    <li>Social Impact</li>
                                                    <li>Technical Impact</li>
                                                    <li>Environmental Impact</li>
                                                    <li>Legal Impact</li>
                                                </ul>
                                            </li>
                                        </ul>
                                        <p><b>Market Size by Region</b></p>
                                        <ul>
                                            <li>Chart on Market share by geography 2021-2027 (%)</li>
                                            <li>Data Table on Market share by geography 2021-2027(%)</li>
                                            <li><b>North America</b>
                                                <ul>
                                                    <li>Chart on Market share by country 2021-2027 (%)</li>
                                                    <li>Data Table on Market share by country 2021-2027(%)</li>
                                                    <li><b>USA</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                    <li><b>Canada</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><b>Europe</b>
                                                <ul>
                                                    <li>Chart on Market share by country 2021-2027 (%)</li>
                                                    <li>Data Table on Market share by country 2021-2027(%)</li>
                                                    <li><b>Germany</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                    <li><b>Spain</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                    <li><b>France</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                    <li><b>UK</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                    <li><b>Rest of Europe</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><b>Asia Pacific</b>
                                                <ul>
                                                    <li>Chart on Market share by country 2021-2027 (%)</li>
                                                    <li>Data Table on Market share by country 2021-2027(%)</li>
                                                    <li><b>China</b>
                                                        <ul><li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                        <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                    <li><b>India</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                    <li><b>Japan</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                    <li><b>South Korea</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                    <li><b>Rest of Asia Pacific</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><b>Latin America</b>
                                                <ul>
                                                    <li>Chart on Market share by country 2021-2027 (%)</li>
                                                    <li>Data Table on Market share by country 2021-2027(%)</li>
                                                    <li><b>Brazil</b>
                                                        <ul><li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                        <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                    <li><b>Rest of South America</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                </ul    >
                                            </li>
                                            <li><b>Middle East & Africa (MEA)</b>
                                                <ul>
                                                    <li>Chart on Market share by country 2021-2027 (%)</li>
                                                    <li>Data Table on Market share by country 2021-2027(%)</li>
                                                    <li><b>GCC Countries</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                    <li><b>South Africa</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                    <li><b>Rest of MEA</b>
                                                        <ul>
                                                            <li>Exhibit: Chart on Market share 2021-2027 (%)</li>
                                                            <li>Exhibit: Market size and forecast 2021-2027 ($ million)</li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                        <p><b>KEY COMPANY PROFILES</b></p>
                                        <ul>
                                            <li>Competitive Landscape
                                                <ul>
                                                    <li>Total number of companies covered
                                                        <ul>
                                                            <li>Exhibit: companies covered in the report, 2021</li>
                                                        </ul>
                                                    </li>
                                                    <li>Top companies market positioning
                                                        <ul>
                                                            <li>Exhibit: company positioning matrix, 2021</li>
                                                        </ul>
                                                    </li>
                                                    <li>Top companies market Share
                                                        <ul>
                                                            <li>Exhibit: Pie chart analysis on company market share, 2021(%)</li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <!-- Array of companies -->
                                            @if($report->competitive_landscape!='')
                                                @php
                                                    $companies = json_decode($report->competitive_landscape, true);
                                                @endphp
                                            @endif
                                            @if(isset($companies))
                                            @foreach($companies as $company)
                                            <li>{!! $company !!}
                                                <ul>
                                                    <li>Exhibit Company Overview</li>
                                                    <li>Exhibit Business Segment Overview</li>
                                                    <li>Exhibit Financial Updates</li>
                                                    <li>Exhibit Key Developments</li>
                                                </ul>
                                            </li>
                                            @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_default_4">
                                    <h3 class="report-title">Methodology</h3>
                                    <div>
                                        <p>For the {!! $report->name !!}, our research methodology involved a mixture of primary and secondary data sources. Key steps involved in the research process are listed below:</p>
                                        <p>1. <b>Information Procurement:</b> This stage involved the procurement of Market data or related information via primary and secondary sources. The various secondary sources used included various company websites, annual reports, trade databases, and paid databases such as Hoover's, Bloomberg Business, Factiva, and Avention. Our team did 45 primary interactions Globally which included several stakeholders such as manufacturers, customers, key opinion leaders, etc. Overall, information procurement was one of the most extensive stages in our research process.</p>
                                        <p>2. <b>Information Analysis:</b> This step involved triangulation of data through bottom-up and top-down approaches to estimate and validate the total size and future estimate of the {!! $report->name !!}.</p>
                                        <p>3. <b>Report Formulation:</b> The final step entailed the placement of data points in appropriate Market spaces in an attempt to deduce viable conclusions.</p>
                                        <p>4. <b>Validation & Publishing:</b> Validation is the most important step in the process. Validation & re-validation via an intricately designed process helped us finalize data points to be used for final calculations. The final Market estimates and forecasts were then aligned and sent to our panel of industry experts for validation of data. Once the validation was done the report was sent to our Quality Assurance team to ensure adherence to style guides, consistency & design.</p>
                                    </div>
                                </div>
                                <!-- END METHODOLOGY TAB -->
                                <div class="tab-pane" id="tab_default_5">
                                    <h3 class="report-title">Analyst Support</h3>
                                    <div>
                                        <p><b>Customization Options</b></p>
                                        <p>With the given market data, our dedicated team of analysts can offer you the following customization options are available for the {!! $report->name !!}:</p>
                                        <p><b>Product Analysis:</b> Product matrix, which offers a detailed comparison of the product portfolio of companies.</p>
                                        <p><b>Regional Analysis:</b> Further analysis of the {!! $report->name !!} for additional countries.</p>
                                        <p><b>Competitive Analysis:</b> Detailed analysis and profiling of additional Market players & comparative analysis of competitive products.</p>
                                        <p><b>Go to Market Strategy:</b> Find the high-growth channels to invest your marketing efforts and increase your customer base.</p>
                                        <p><b>Innovation Mapping:</b> Identify racial solutions and innovation, connected to deep ecosystems of innovators, start-ups, academics, and strategic partners.</p>
                                        <p><b>Category Intelligence:</b> Customized intelligence that is relevant to their supply Markets will enable them to make smarter sourcing decisions and improve their category management.</p>
                                        <p><b>Public Company Transcript Analysis:</b> To improve the investment performance by generating new alpha and making better-informed decisions.</p>
                                        <p><b>Social Media Listening:</b> To analyze the conversations and trends happening not just around your brand, but around your industry as a whole, and use those insights to make better Marketing decisions.</p>
                                    </div>
                                </div>
                                <!-- END ANALYST SUPPORT TAB -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start Right Sicky Section -->
            <div class="price-card-container-wrraper">
                <div class="price-card-container">
                    <div class="select-row">
                        <input type="hidden" id="report_id" name="report_id" value="{!! $report->id !!}">
                        <div class="select-col">
                            <label for="#">License Type
                                <img
                                    src="{!! asset('assets/frontend/images/icons8-eye-24.webp') !!}"
                                    title="Select license type suitable for your business requirement Single: 1 User | Multiple: 5 Users | Enterprise: Team License Pricing is variable based on license type."
                                    data-bs-toggle="tooltip" data-bs-placement="right" alt="Top market research company in USA"
                                />
                            </label>
                            @if(isset($report->report_pricing))
                            <select class="custom-select1 Popular" id="license_type" onchange="getFileType('{!! $report->id !!}',this.value)">
                            @foreach($report->report_pricing->unique('license_type') as $pricing)
                                <option value="{!! $pricing->license_type !!}">{!! $pricing->license_type !!}</option>
                            @endforeach
                            </select>
                            @endif
                        </div>
                        <div class="select-col">
                            <label for="#">File Type</label>
                            @if(isset($report->report_pricing))
                            <select class="custom-select1 Popular" id="file_type">
                            @foreach($report->report_pricing->unique('file_type') as $pricing)
                                <option value="{!! $pricing->file_type !!}">{!! $pricing->file_type !!}</option>
                            @endforeach
                            </select>
                            @endif
                        </div>
                    </div>
                    <div class="report-price" id="report_price">
                    @if(isset($report->report_pricing) && !$report->report_pricing->isEmpty())
                        ${!! number_format($report->report_pricing[0]['price'],0) !!}
                    @endif
                    </div>
                    <a href="{!! url('buy-now/'.$report->slug) !!}" class="btn pre-book-btn">BUY NOW</a>
                    <a href="{!! url('sample-request/'.$report->slug) !!}" class="btn get-sample-btn">GET SAMPLE</a>
                </div>
                <div class="speak-to-analyst">
                    <h6>Want to customize this report?</h6>
                    <p>Our industry expert will work with you to provide you with customized data in a short amount of time.</p>
                    <a class="btn" href="{!! url('speak-with-analyst/'.$report->slug) !!}">SPEAK TO AN ANALYST</a>
                </div>
                <div class="subscribe-now">
                    <h6>Subscribe &amp; Save</h6>
                    <p>Get lifetime access to our reports</p>
                    <a href="#" class="subscribe-box-btn active">
                        <span>Basic Plan</span>
                        <strong>$5,000</strong>
                    </a>
                    <a href="#" class="subscribe-box-btn">
                        <span>Team Plan</span>
                        <strong>$10,000</strong>
                    </a>
                    <a href="{!! url('subscribe-now/'.$report->slug) !!}" class="btn btn-subscribe">SUBSCRIBE NOW</a>
                </div>
            </div>
            <!-- END Right Sicky Section -->
        </div>
    </div>
</div>

<!-- START FAQ SECTION -->
@if(!$report->report_faq->isEmpty())
@include("front.layouts.report-faq")
@endif
<!-- END FAQ SECTION -->

<!-- START SPEAK OUR ANALYST SECTION -->
<div class="speak-with-our-analyst-sec">
    <div class="container">
        <h3 class="speak-with-our-analyst-heading">Speak With Our Analyst</h3>
        <p>
            Want to customize this report? This report can be personalized according to your needs.
            Our analysts and industry experts will work directly with you to understand your requirements
            and provide you with customized data in a short amount of time. We offer $1000 worth of FREE
            customization at the time of purchase.
        </p>
        <div class="btn-view text-center">
            <a class="outline-arrow-btn" href="{!! url('speak-with-analyst/'.$report->slug) !!}">
                <span>Click here</span>
                <img src="{!! asset('assets/frontend/images/right-arrow.svg') !!}" alt="right-arrow" width="32" height="21">
            </a>
        </div>
        <marquee><img src="{!! asset('assets/frontend/images/logo-slider-img.webp') !!}" alt="logo-images" style="max-width:inherit;" width="2644" height="74"></marquee>
    </div>
</div>
<!-- END SPEAK OUR ANALYST SECTION -->

<!-- START RELATED REPORTS SECTION -->
@if(!$related_reports->isEmpty())
@include("front.layouts.report-related")
@endif
<!-- END RELATED REPORTS SECTION -->

<!-- START CLIENTS FEEDBACK SECTION -->
@if(!$clientfeedbacks->isEmpty())
@include("front.layouts.report-feedback")
@endif
<!-- END CLIENTS FEEDBACK SECTION -->

<section class="sticky-poroduct">
    <div class="container">
        <div class="product-view">
            <div class="content">
                <img src="{!! $report->image_url !!}" alt="{!! $report->image_alt !!}" width="50" height="60" />
                <div id="stickyPrice">
                    <p>Product ID: {!! $report->product_id !!}</p>
                    @if(isset($report->report_pricing) && !$report->report_pricing->isEmpty())
                    <h5>${!! number_format($report->report_pricing[0]['price'],0) !!}</h5>
                    @endif
                </div>
            </div>
            <div>
                <a href="{!! url('buy-now/'.$report->slug) !!}" class="btn btn-gradient">BUY NOW</a>
                <a href="{!! url('sample-request/'.$report->slug) !!}" class="btn btn-outline-orange">GET SAMPLE</a>
            </div>
        </div>
    </div>
</section>

@section('js')
<!-- js link -->
@if(($report->report_type=='SD') || ($report->report_type=='Dynamic'))
<script>
$(".report-tabs").on('click', function () {
    var report_id = $("#report_id").val();
    var tabId = $(this).attr('href');
    var type = $(this).attr('data-type');

    $.ajax({
        type: "POST",
        data: { id: report_id, type: type },
        headers: {
            'X-CSRF-TOKEN': _token
        },
        url: baseUrl + "reports/getReportData",
        success: function (data) {
            if (data.success == "1") {
                $(tabId).html(data.html);
            }
        },
        error: function (data, e) {
            toastr.error("Something went wrong. Please try again later.");
        }
    });
})
</script>
@endif

@if(($report->report_type=='SD') || ($report->report_type=='Dynamic'))
<script type="text/javascript" src="{!! asset('assets/frontend/js/charts-loader.js') !!}"></script>
<script>
@if(!$report->report_graphs->isEmpty())
    google.charts.load('current', {'packages':['corechart','geochart']});
@endif

const colors = [
    "#DD392A",
    "#ED9720",
    "#46961A",
    "#9A1B99",
    "#4C9AC6",
    "#DD4477",
    "#8CBF40",
    "#B82E2E",
    "#3567CC",
];

var executed_part1 = false;
var executed_part2 = false;
var executed_part3 = false;

$(window).scroll(function () {
    var product = $(window).scrollTop();

    if (product >= 500 && !executed_part1){
        // to load chart 1
        var chart1 = '{!! (!empty($chart_1["content"])) ? $chart_1["content"] : "" !!}';
        var chart_1_years = '{!! $chart_1_years !!}';

        if(chart1!=''){
            google.charts.setOnLoadCallback(drawVisualization);
        }
        function drawVisualization() {
            // Some raw data (not necessarily accurate)
            var data = google.visualization.arrayToDataTable($.parseJSON(chart1));
            var options = {
                title : '',
                vAxis: {title: 'Market Size ($)'},
                hAxis: {title: 'Year',format: '', ticks: $.parseJSON(chart_1_years) },
                seriesType: 'bars',
                chartArea: {
                    top: 50,
                    bottom: 50,
                    // right: 0,
                    // left: 50,
                    width: "100%",
                    height: "100%"
                },
                legend: { position: "bottom" },
                isStacked: true,
                tooltip: { trigger: "none", isHtml: true },
                async: true,
            };
            var chart = new google.visualization.ComboChart(document.getElementById('first_chart_div'));
            chart.draw(data, options);
        }

        // to load chart 2
        var chart2 = '{!! (!empty($chart_2["content"])) ? $chart_2["content"] : "" !!}';

        if(chart2!=''){
            google.charts.setOnLoadCallback(drawChart);
        }
        function drawChart() {
            var data2 = google.visualization.arrayToDataTable($.parseJSON(chart2));
            var options = {
                title: '',
                pieHole: 0.4,
                tooltip: {
                    isHtml: true,
                    trigger: "visible",
                },
                pieSliceText: "none",
                chartArea: {
                    top: 50,
                    bottom: 50,
                    // right: 0,
                    // left: 50,
                    width: "100%",
                    height: "100%"
                },
                legend: {
                    position: "bottom",
                    maxLines: 5,
                    alignment: "center",
                    textStyle: { fontSize: 12 },
                },
                async: true,
            };
            var chart = new google.visualization.PieChart(document.getElementById('second_chart_div'));
            chart.draw(data2, options);
        }

        // to load chart 3
        var chart3 = '{!! (!empty($chart_3["content"])) ? $chart_3["content"] : "" !!}';
        var chart_3_years = '{!! (!empty($chart_3_years)) ? $chart_3_years : "" !!}';

        if(chart3!=''){
            google.charts.setOnLoadCallback(drawVisualization3);
        }
        function drawVisualization3() {
            // Some raw data (not necessarily accurate)
            var data3 = google.visualization.arrayToDataTable($.parseJSON(chart3));
            var options = {
                title : '',
                vAxis: {title: 'Market Size ($)'},
                hAxis: {title: 'Year',format: '', ticks: $.parseJSON(chart_3_years) },
                seriesType: 'bars',
                chartArea: {
                    top: 50,
                    bottom: 50,
                    // right: 0,
                    // left: 50,
                    width: "100%",
                    height: "100%"
                },
                legend: { position: "bottom" },
                isStacked: true,
                tooltip: { trigger: "none", isHtml: true },
                async: true,
            };
            var chart = new google.visualization.ComboChart(document.getElementById('third_chart_div'));
            chart.draw(data3, options);
        }

        // to load chart 4
        var chart4 = '{!! (!empty($chart_4["content"])) ? $chart_4["content"] : "" !!}';
        var chart_4_years = '{!! (!empty($chart_4_years)) ? $chart_4_years : "" !!}';

        if(chart4!=''){
            google.charts.setOnLoadCallback(drawChart4);
        }
        function drawChart4() {
            // Some raw data (not necessarily accurate)
            var data4 = google.visualization.arrayToDataTable($.parseJSON(chart4));
            var options = {
                hAxis: {title: "Year",format: '', ticks: $.parseJSON(chart_4_years) },
                vAxis: {title: "Growth Rate (%)"},
                series: {1: { curveType: "function" }},
                chartArea: {
                    top: 50,
                    bottom: 50,
                    // right: 0,
                    // left: 50,
                    width: "100%",
                    height: "100%"
                },
                legend: { position: "bottom" },
                tooltip: { trigger: "none", isHtml: true },
                async: true,
            };
            var chart = new google.visualization.LineChart(document.getElementById('fourth_chart_div'));
            chart.draw(data4, options);
        }

        executed_part1 = true;
    }

    if (product >= 2600 && !executed_part2){
        // to load chart 5
        var chart5 = '{!! (!empty($chart_5["content"])) ? $chart_5["content"] : "" !!}';

        if(chart5!=''){
            google.charts.setOnLoadCallback(drawChart5);
        }
        function drawChart5() {
            // Some raw data (not necessarily accurate)
            var data5 = google.visualization.arrayToDataTable($.parseJSON(chart5));
            var options = {
                title: "",
                pieHole: 0.4,
                tooltip: {
                    isHtml: true,
                    trigger: "focus",
                },
                pieSliceText: "none",
                chartArea: {
                    top: 50,
                    bottom: 10,
                    // right: 0,
                    // left: 50,
                    width: "100%",
                    height: "100%",
                },
                legend: {
                    position: "top",
                    maxLines: 5,
                    alignment: "center",
                    textStyle: { fontSize: 12 },
                },
                async: true,
            };
            var chart = new google.visualization.PieChart(document.getElementById('fifth_chart_div'));
            chart.draw(data5, options);
        }
        executed_part2 = true;
    }

    if (product >= 3500 && !executed_part3){
        // to load chart 6
        var chart6 = '{!! (!empty($chart_6["content"])) ? $chart_6["content"] : "" !!}';

        if(chart6!=''){
            google.charts.setOnLoadCallback(drawRegionsMap);
        }
        function drawRegionsMap() {
            var data6 = google.visualization.arrayToDataTable($.parseJSON(chart6));
            var options = {
                legend: "none",
                sizeAxis: { minValue: 1, maxSize: 12 },
                colorAxis: { colors },
                tooltip: {
                    isHtml: true,
                    trigger: "visible",
                    p: { html: true },
                },
                async: true,
            };
            var chart = new google.visualization.GeoChart(document.getElementById('sixth_chart_div'));
            chart.draw(data6, options);
        }
        executed_part3 = true;
    }
});
</script>
@endif

@stop
@endsection
