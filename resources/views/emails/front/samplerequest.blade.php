@extends('emails.layouts.app')
@section('content')

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" style="background:#fff; padding:25px 30px; border-radius:10px; -webkit-border-radius:10px; -o-border-radius:10px; -moz-border-radius:10px; -ms-border-radius:10px;">
            <table width="100%" style="margin:0;">
                <tr>
                    <td align="left" style="font-size:14px; color:#000; padding:0 0 15px; font-family:Arial, Helvetica, sans-serif;">Hello {!! (isset($samplerequest->name)) ? $samplerequest->name : '' !!}, </td>
                </tr>
                <tr>
                    <td align="left" style="font-size:13px; line-height:23px; color:#000; padding:0 0 12px; font-family:Arial, Helvetica, sans-serif;">                        
                        <p>SkyQuest Technology Group is a technology and data-driven market research company. We work with you to understand your goals and objectives. We then help you develop a data-driven program that will transform your business and help you achieve your desired results.</p>
                        <p>This is concerning to your interest in the <a href="{!! (isset($samplerequest->report->slug)) ? url('report/'.$samplerequest->report->slug) : '' !!}">{!! (isset($samplerequest->report->name)) ? $samplerequest->report->name : '' !!}</a></p>
                        @if($samplerequest->report->free_sample_report_link!='')
                        <p><a href="{!! $samplerequest->report->free_sample_report_link !!}">Click here</a> to see the sample report.</p>
                        @endif
                        <p>Please find below coverage of the report.</p>
                        <table width="100%" border="1" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>Parameter</td>
                                <td>Our Offerings</td>
                                <td>Description</td>
                            </tr>
                            <tr>
                                <td><b>Section 1</b></td>
                                <td>
                                    <b><ul>
                                        <li>Research Definition and Scope</li>
                                        <li>Research Objectives</li>
                                        <li>Assumptions</li>
                                    </ul></b>
                                </td>
                                <td>Research definition and scope is a summary covering the segments and sub-segments of the Global B Market. This section also briefs about the overall research methodology and assumptions taken into consideration to estimate the Global B market.</td>
                            </tr>
                            <tr>
                                <td><b>Section 2</b></td>
                                <td>
                                    <ul>
                                        <li><b>Market Overview</b>
                                            <ol>
                                                <li>Global B Market Forecast</li>     
                                                <li>Market Scenario</li>
                                                <li>Market Opportunity Map</li>
                                            </ol>
                                        </li>                                        
                                    </ul>
                                </td>
                                <td>This section consists of the Global B market overview along with market size estimates in terms of value & volume (US$ Million & KT). It also cover market scenario and opportunity assessment analysis to provide comprehensive market synopsis.</td>
                            </tr>
                            <tr>
                                <td><b>Section 3</b></td>
                                <td>
                                    <ul>
                                        <li><b>Market Dynamics</b>
                                            <ol>
                                                <li>Drivers</li>     
                                                <li>Restraints</li>
                                                <li>Impact Analysis</li>
                                                <li>Market Opportunity</li>   
                                                <li>Market Trends</li>
                                                <li>PEST Analysis</li>
                                                <li>PORTER'S Analysis</li>     
                                                <li>Regulatory Scenario</li>
                                                <li>Market Attractiveness Analysis</li>
                                                <li>Market Opportunity Assessment</li>  
                                            </ol>
                                        </li>                                        
                                    </ul>
                                </td>
                                <td>This section includes drivers that will provide an idea about the positive outlook of the market. Apart from this, the report would provide restraints to give a glimpse into the market challenges as per the present scenario, PEST analysis, PORTER's five forces, and market trends for a significant player. The report will provide information about key market trends, mergers and acquisitions scenarios, and regulations imposed to help you understand the market at a granular level. Moreover, we have also provided market attractiveness analysis and market opportunity assessment to make better strategic decisions and standout in the market.</td>
                            </tr>
                            <tr>
                                <td><b>Section 4</b></td>
                                <td>
                                    <ul>
                                        <li><b>Global B Market: Impact of Coronavirus (Covid-19) Pandemic</b>
                                            <ol>
                                                <li>Overview</li>     
                                                <li>Factors Affecting Global B Market COVID-19</li>
                                                <li>Impact Analysis</li>
                                            </ol>
                                        </li>                                        
                                    </ul>
                                </td>
                                <td>This section includes the Impact of the Coronavirus (Covid-19) Pandemic on Global B market.</td>
                            </tr>
                            <tr>
                                <td><b>Section 5, 6, 7, & 8</b></td>
                                <td>
                                    <ul>
                                        <li><b>Segment Analysis</b></li>                                        
                                    </ul>
                                </td>
                                <td>
                                    We will provide in-depth qualitative & quantitative analysis for the material, product type, manufacturing method, and end-use industry based on value (in US$ Mn) and volume (KT).
                                    We will also provide the market sizing and forecast for the material, product type, manufacturing method, and end-use industry category. In addition, the report would comprise critical insights affecting the growth/decline of the market in different economies.
                                    In short, this segment will give a holistic overview of the market along with extensive information related to market, size of the market and opportunities within the Global B Market.
                                </td>
                            </tr>
                            <tr>
                                <td><b>Section 9</b></td>
                                <td>
                                    <ul>
                                        <li><b>Regional Analysis</b>
                                            <ol>
                                                <li>North America</li>     
                                                <li>Latin America</li>
                                                <li>Europe</li>
                                                <li>Asia Pacific</li>     
                                                <li>Middle East</li>
                                                <li>Africa</li>
                                            </ol>
                                        </li>                                        
                                    </ul>
                                </td>
                                <td>This section includes in-depth qualitative & quantitative analysis for over 20 countries spread across 6 key regions by value (US Mn) and volume (KT).</td>
                            </tr>
                            <tr>
                                <td><b>Section 10</b></td>
                                <td>
                                    <ul>
                                        <li><b>Company Overview</b>
                                            <ul>
                                                <li>Ownership, Founders, Establishment date, and Headquarters</li>     
                                                <li>Employee Count</li>
                                                <li>Business Overview</li>
                                                <li>Product Portfolio</li>
                                            </ul>
                                        </li>
                                        <li><b>Key Developments</b></li>
                                        <li><b>Marketing & Promotional Activities</b></li>
                                        <li><b>Strategies & USP</b></li>
                                    </ul>
                                </td>
                                <td>This section will offer a detailed company profiling of all the players enlisted in the report. It starts with the market share and analysis to help identify the presence & strength of a company. It also includes in-depth insights into companies operating strategies, company overview, ownership, founders, establishment date, headquarters, employee count, business overview, product portfolio, key developments, geographical presence, marketing & promotional activities, revenue, and strategies.</td>
                            </tr>
                        </table>
                        <p>The market study is structured to offer a macro-level overview of trends, sectoral growth prospects, regional attractiveness, and an overview of key companies operating within this market space. We have done a granular level research to give you the best possible outcome. Our research team comprises expert level analysts with immense experience in the field with strong potential to handle any research queries around this topic.</p>
                        <p>We are actively tracking the COVID-19 pandemic, particularly regarding its market impact across various sectors. Therefore, we have included a detailed section sighting the impact areas within the final deliverable.</p>
                        <p>A detailed sample covering all the report aspect mentioned above will be shared within 24 hours. This document will give you a clearer picture and feel of the final report.</p>
                        <p>Meanwhile, it would be wonderful if you can find some time to talk with our research analyst over the phone call at your convenient time to understand your requirement and report scope. We would also like to know more if you are looking for custom requirements in the report.</p>
                        <p>We will be more than happy to know if you are looking for any specific requirement in the report. You can drop us an email or contact us at +1-617-230-0741.</p>
                        <p>We are looking forward to hearing from you.</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:15px 0 5px; font-family:Arial, Helvetica, sans-serif;">
                        <p style="margin:0; font-size:13px; line-height:20px; font-family:Arial, Helvetica, sans-serif; color:#000;">Regards, <br>{!! Config::get('app.name') !!}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@endsection