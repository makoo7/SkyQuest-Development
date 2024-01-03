<?php

namespace App\Exports;

use App\Models\Report;
use App\Models\ReportMetrics;
use App\Models\ReportFaq;
use App\Models\ReportPricing;
use App\Models\Settings;
use App\Models\ReportTableofcontent;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use DB;

class ReportExport implements FromView, WithStyles
{

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('I')->getFont()->setBold(false);
    }

    protected $reportexport;

    public function __construct($reportexport){
        $this->reportexport = $reportexport;   
    }

    public function view(): View
    {
        $reportexportdata = $this->reportexport;       
        $fields = explode(",", $reportexportdata->fields);

        for($i=0;$i<count($fields);$i++){
            switch($fields[$i]){
                case 'title':
                    $selectedFields[] = 'Title';
                    break;
                case 'url':
                    $selectedFields[] = 'URL';
                    break;
                case 'product_code':
                    $selectedFields[] = 'Product Code';
                    break;
                case 'date':
                    $selectedFields[] = 'Date';
                    break;
                case 'length':
                    $selectedFields[] = 'Length';
                    break;
                case 'single_price':
                    $selectedFields[] = 'Price: Single User';
                    break;
                case 'site_price':
                    $selectedFields[] = 'Price: Site License';
                    break;
                case 'enterprise_price':
                    $selectedFields[] = 'Price: Enterprise License';
                    break;
                case 'toc':
                    $selectedFields[] = 'Table of Content';
                    break;
                case 'categories':
                    $selectedFields[] = 'Categories';
                    break;
                case 'countries_covered':
                    $selectedFields[] = 'Countries Covered';
                    break;
                case 'companies_mentioned':
                    $selectedFields[] = 'Companies Mentioned';
                    break;
                case 'products_mentioned':
                    $selectedFields[] = 'Products Mentioned';
                    break;
                case '2021':
                    $selectedFields[] = '2021';
                    break;
                case '2022':
                    $selectedFields[] = '2022';
                    break;
                case '2030':
                    $selectedFields[] = '2030';
                    break;
                case 'cagr':
                    $selectedFields[] = 'CAGR %';
                    break;
                case 'currency':
                    $selectedFields[] = 'Currency';
                    break;
                case 'report_type':
                    $selectedFields[] = 'Report Type';
                    break;
                case 'sector':
                    $selectedFields[] = 'Sector';
                    break;
                case 'region':
                    $selectedFields[] = 'Region';
                    break;
                case '1st_2_lines':
                    $selectedFields[] = '1st 2 lines';
                    break;
                case 'market_insights':
                    $selectedFields[] = 'market_insights';
                    break;
            }
        }

        $start_date = $reportexportdata->start_date;
        $end_date = $reportexportdata->end_date;
        
        $reports = new Report;
        if($start_date && $end_date){
            $reports = $reports->where('publish_date', '>=', $start_date);
            $reports = $reports->where('publish_date', '<=', $end_date);
        }
        if($start_date) {
            $reports = $reports->whereDate('publish_date','>=',$start_date);
        }
        if($end_date) {
            $reports = $reports->whereDate('publish_date','<=',$end_date);
        }
        
        $reports = $reports->get();

        $settings = Settings::first();
        
        $merged_collection = collect([]);
        $final_array = [];

        //$chunks = array_chunk($reports->toArray(), 500, true);

        foreach ($reports->take(5) as $k => $report)
        {        
            $market_insights = $report->market_insights;

            if(in_array('title', $fields)){
                // prepare report name
                $report_name = $report->name;

                if(isset($report->report_segments)){
                    $report_name .= " Size, Share, Growth Analysis";

                    foreach($report->report_segments as $report_segment){
                        $sub_segmentsArr = array();
                        $report_name .= ", By ".$report_segment->name;
                        $sub_segmentsArr = explode(",",$report_segment->value);
                        if(count($sub_segmentsArr)>0){
                            if(count($sub_segmentsArr)==1)
                            $report_name .= "(".$sub_segmentsArr[0].")";
                            if(count($sub_segmentsArr)>=2)
                            $report_name .= "(".$sub_segmentsArr[0].",".$sub_segmentsArr[1].")";
                        }
                    }
                    $report_name .= " - Industry Forecast ".$settings->forecast_year;
                }                
                $final_array[$k]['title'] = $report_name;
            }

            if(in_array('url', $fields)){
                $final_array[$k]['url'] = url("/report/{$report->slug}");
            }

            if(in_array('product_code', $fields)){
                $final_array[$k]['product_code'] = $report->product_id;
            }

            if(in_array('date', $fields)){
                $final_array[$k]['date'] = convertUtcToIst($report->publish_date, "Y-m-d");
            }

            if(in_array('length', $fields)){
                $final_array[$k]['length'] = $report->pages;
            }

            if(in_array('single_price', $fields)){
                $singlePricing = ReportPricing::where('report_id',$report->id)->where('license_type','Single')->where('file_type','PPT')->where('is_active',1)->pluck('price')->first();                
                $final_array[$k]['single_price'] = $singlePricing;
            }

            if(in_array('site_price', $fields)){
                $sitePricing = ReportPricing::where('report_id',$report->id)->where('license_type','Multiple')->where('file_type','PPT')->where('is_active',1)->pluck('price')->first();                                
                $final_array[$k]['site_price'] = $sitePricing;
            }

            if(in_array('enterprise_price', $fields)){
                $enterprisePricing = ReportPricing::where('report_id',$report->id)->where('license_type','Enterprise')->where('file_type','PPT')->where('is_active',1)->pluck('price')->first();                
                $final_array[$k]['enterprise_price'] = $enterprisePricing;
            }

            if(in_array('toc', $fields)){
                $toc_html = '';
                if(!$report->report_tableofcontent->isEmpty()){
                    if($report->report_type=='SD' || $report->report_type=='Dynamic'){
                        foreach($report->report_tableofcontent as $report_tocdata){
                            if($report_tocdata->toc!=''){
                                $toc_html .= $report_tocdata->toc;
                            }
                        }
                    }
                    if($report->report_type=='Upcoming'){
                        $toc_html .= '<h3 class="report-title">Table Of Content</h3>
                        <div>
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
                                </li></ul>';
                                
                                if($report->competitive_landscape!=''){
                                    $companies = json_decode($report->competitive_landscape, true);
                                }
                                if(isset($companies)){
                                    foreach($companies as $company){
                                        $toc_html .= '<li>'.$company.'
                                    <ul>
                                        <li>Exhibit Company Overview</li>
                                        <li>Exhibit Business Segment Overview</li>
                                        <li>Exhibit Financial Updates</li>
                                        <li>Exhibit Key Developments</li>
                                    </ul>
                                </li>';
                                }}
                                $toc_html .= '</ul></div>';
                    }
                }
                /* toc update code start */ 
                if($toc_html != ""){
                    $abc = $this->replaceUlWithCustomTag($toc_html);
                    $xyz = $this->replaceLiToBullet($abc);
                    // $ccc = $this->addNewlineAfterLi($xyz);
                    // dd($xyz);
                    // if($report->product_id == "SQMIG35D2005"){
                    //     dd($xyz);
                    // }
                    $toc_html = $xyz;
                }
                /* toc update code ends */
                $final_array[$k]['toc'] = $toc_html;
            }

            if(in_array('categories', $fields)){
                $final_array[$k]['categories'] = ($report->sub_industry) ? $report->sub_industry->title : '';
            }
            
            if(in_array('countries_covered', $fields)){
                $final_array[$k]['countries_covered'] = $this->replaceLiToBullet('<ul>
                <li>USA</li><li>Canada</li><li>Germany</li><li>Spain</li><li>France</li><li>UK</li><li>China</li><li>India</li><li>Japan</li><li>South Korea</li><li>Brazil</li><li>GCC Countries</li><li>South Africa</li></ul>');
            }

            if(in_array('companies_mentioned', $fields)){
                $companies = ReportMetrics::where('report_id',$report->id)->where('meta_key', 'Companies covered')->pluck('meta_value')->first();
                $final_array[$k]['companies_mentioned'] = $this->removeAhrefFromText($companies);
            }

            if(in_array('products_mentioned', $fields)){
                $segments = '';
                $i = 0;
                if(isset($report->report_segments)){
                    foreach($report->report_segments as $report_segment){
                        $sub_segmentsArr = array();
                        if($i==0){
                            $segments .= "By ".$report_segment->name;
                        }else{
                            $segments .= ", By ".$report_segment->name;
                        }
                        $sub_segmentsArr = explode(",",$report_segment->value);
                        if(count($sub_segmentsArr)>0){
                            if(count($sub_segmentsArr)==1)
                            $segments .= "(".$sub_segmentsArr[0].")";
                            if(count($sub_segmentsArr)>=2)
                            $segments .= "(".$sub_segmentsArr[0].",".$sub_segmentsArr[1].")";
                        }
                        $i++;
                    }
                }    
                $final_array[$k]['products_mentioned'] = $segments;   
            }

            if(in_array('2021', $fields)){
                $value_for_2021 = '';
                if(isset($market_insights) && $market_insights!=''){
                    $market_insights_para = getFirstPTag($market_insights);
                    if(str_contains($market_insights_para, 'in 2021')){
                        $string_part_2021 = trim(string_between_two_string($market_insights_para, 'USD', 'in 2021'));
                        $string_part_arr_2021 = explode(" ",$string_part_2021);
                        $array_keys_USD_2021 = array_keys($string_part_arr_2021, "USD");
                        if($array_keys_USD_2021){
                            $key_USD_2021 = $array_keys_USD_2021[0];
                            $value_for_2021 = $string_part_arr_2021[$key_USD_2021];
                            if (array_key_exists($key_USD_2021+1, $string_part_arr_2021)){
                                $value_for_2021 .= ' '.$string_part_arr_2021[$key_USD_2021+1];
                            }
                            if (array_key_exists($key_USD_2021+2, $string_part_arr_2021)){
                                $value_for_2021 .= ' '.$string_part_arr_2021[$key_USD_2021+2];
                            }
                        }else{
                            $value_for_2021 = 'USD '.$string_part_2021;
                        }
                    }
                }
                $final_array[$k]['value_for_2021'] = strip_tags($value_for_2021);
            }

            if(in_array('2022', $fields)){
                $value_for_2022 = '';
                if(isset($market_insights) && $market_insights!=''){
                    $market_insights_para = getFirstPTag($market_insights);
                    if(str_contains($market_insights_para, 'in 2022')){
                        $string_part_2022 = trim(string_between_two_string($market_insights_para, 'USD', 'in 2022'));
                        $string_part_arr_2022 = explode(" ",$string_part_2022);
                        $array_keys_USD_2022 = array_keys($string_part_arr_2022, "USD");
                        if($array_keys_USD_2022){
                            $key_USD_2022 = $array_keys_USD_2022[0];
                            $value_for_2022 = $string_part_arr_2022[$key_USD_2022];
                            if (array_key_exists($key_USD_2022+1, $string_part_arr_2022)){
                                $value_for_2022 .= ' '.$string_part_arr_2022[$key_USD_2022+1];
                            }
                            if (array_key_exists($key_USD_2022+2, $string_part_arr_2022)){
                                $value_for_2022 .= ' '.$string_part_arr_2022[$key_USD_2022+2];
                            }
                        }else{
                            $value_for_2022 = 'USD '.$string_part_2022;
                        }
                    }
                }
                $final_array[$k]['value_for_2022'] = strip_tags($value_for_2022);
            }

            if(in_array('2030', $fields)){
                $value_for_2030 = '';
                if(isset($market_insights) && $market_insights!=''){
                    $market_insights_para = getFirstPTag($market_insights);
                    if(str_contains($market_insights_para, 'by 2030')){
                        $string_part_2030 = trim(string_between_two_string($market_insights_para, 'USD', 'by 2030'));
                        $string_part_arr_2030 = explode(" ",$string_part_2030);
                        $array_keys_USD_2030 = array_keys($string_part_arr_2030, "USD");
                        if($array_keys_USD_2030){
                            $key_USD_2030 = $array_keys_USD_2030[count($array_keys_USD_2030)-1];
                            $value_for_2030 = $string_part_arr_2030[$key_USD_2030];
                            if (array_key_exists($key_USD_2030+1, $string_part_arr_2030)){
                                $value_for_2030 .= ' '.$string_part_arr_2030[$key_USD_2030+1];
                            }
                            if (array_key_exists($key_USD_2030+2, $string_part_arr_2030)){
                                $value_for_2030 .= ' '.$string_part_arr_2030[$key_USD_2030+2];
                            }
                        }else{
                            $value_for_2030 = 'USD '.$string_part_2030;
                        }
                    }
                }
                $final_array[$k]['value_for_2030'] = strip_tags($value_for_2030);
            }

            if(in_array('cagr', $fields)){
                $cagr = ReportMetrics::where('report_id',$report->id)->where('meta_key', 'Growth Rate')->pluck('meta_value')->first();
                $final_array[$k]['cagr'] = $cagr;
            }

            if(in_array('currency', $fields)){
                $currency = ReportMetrics::where('report_id',$report->id)->where('meta_key', 'Forecast Unit (Value)')->pluck('meta_value')->first();
                $final_array[$k]['currency'] = $currency;
            }
            
            if(in_array('report_type', $fields)){
                $report_type = ($report->publish_date) ? 'Published' : '';
                $final_array[$k]['report_type'] = $report_type;
            }

            if(in_array('sector', $fields)){
                $final_array[$k]['sector'] = $report->sector->title;
            }

            if(in_array('region', $fields)){
                $final_array[$k]['region'] = ucfirst($report->country);
            }

            if(in_array('1st_2_lines', $fields)){
                $market_insights_para = '';
                if(isset($market_insights) && $market_insights!=''){
                    $market_insights_para = getFirstPTag($market_insights);
                }
                $final_array[$k]['first_2_lines'] = $market_insights_para;
            }

            if(in_array('market_insights', $fields)){
                $final_array[$k]['market_insights'] = $market_insights;
            }

            $merged_collection->push((object)$final_array[$k]);            
        }
        return view('exports.reports', ['selectedFields' => $selectedFields, 'fields' => $fields, 'merged_collection' => $merged_collection ]);
    }
    
    public function replaceUlWithCustomTag($html) {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);

        // Load HTML with error handling
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // Save the completed HTML
        $completedHtml = $dom->saveHTML();

        // Clean up errors
        libxml_clear_errors();

        return $completedHtml;
    }

    public function replaceLiToBullet($html){
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        // $newFontFamily = 'Arial, sans-serif !important';

        // Load HTML with error handling
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // Add bullet points and $ sign to <li> tags without <b> tags
        $liElements = $dom->getElementsByTagName('li');
        $liCount = $liElements->length;

        for ($i = 0; $i < $liCount; $i++) {
            $li = $liElements->item($i);

            // Check if the <li> does not have a <b> tag
            if($li !== null){
            if (!$li->getElementsByTagName('b')->item(0)) {
                // Add bullet point
                    $bulletText = $dom->createTextNode(' 󠀩󠀩󠀩󠀩 󠀩󠀩󠀩󠀩 󠀩󠀩󠀩󠀩 •'); // Bullet point
                    $li->insertBefore($bulletText, $li->firstChild);
                // Add $ sign to the content of child nodes of child nodes
                foreach ($li->childNodes as $childNode) {
                    if ($childNode->nodeType === XML_ELEMENT_NODE) {
                        foreach ($childNode->childNodes as $grandChildNode) {
                            if ($grandChildNode->nodeType === XML_ELEMENT_NODE) {
                                $grandChildNodeValue = ltrim($grandChildNode->nodeValue);
                                $grandChildNode->nodeValue = '&#9642;' . $grandChildNodeValue;
                                $spaceText = $dom->createTextNode("\xc2\xa0\xc2\xa0\xc2\xa0"); // non-breaking space
                                $grandChildNode->insertBefore($spaceText, $grandChildNode->firstChild);
                            }
                        }
                    }
                }
            }
          }
        }

        // Save the modified HTML
        $modifiedHtml = $dom->saveHTML();

        // Clean up errors
        libxml_clear_errors();
        // $modifiedHtml = $this->replaceBullets($modifiedHtml);
        // // Remove <b> opening tag
        // $htmlWithoutOpeningTag = str_replace('<b>', '', $modifiedHtml);
        
        // // Remove <b> closing tag
        // $htmlWithoutClosingTag = str_replace('</b>', '', $htmlWithoutOpeningTag);

        return $this->replaceBullets($modifiedHtml);
    }

    function addNewlineAfterLi($html) {
        $htmlWithNewlines = str_replace('</li>', "</li><br/>", $html);
        return $htmlWithNewlines;
    }

    public function replaceBullets($html) {
        $modifiedHtml = str_replace(' &#917545;&#917545;&#917545;&#917545; &#917545;&#917545;&#917545;&#917545; &#917545;&#917545;&#917545;&#917545; &bull;&nbsp;&nbsp;&nbsp;&#9642;', ' 󠀩󠀩󠀩󠀩 󠀩 󠀩 󠀩󠀩 󠀩 󠀩 󠀩&#9642;', $html);
        return $modifiedHtml;
    }

    public function removeAhrefFromText($html){
        $pattern = '/<a\s[^>]*href=[\'"]https?:\/\/.*?[\'"][^>]*>(.*?)<\/a>/i';
        $pattern2 = '/<a\b[^>]*>(.*?)<\/a>/i';
        $data = preg_replace($pattern,'<a>$1</a>',$html);
        $result = preg_replace($pattern2, '$1', $data);
        return $this->replaceLiToBullet($result);
    }
}

