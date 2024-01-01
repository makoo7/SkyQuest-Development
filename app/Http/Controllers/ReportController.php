<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Report;
use App\Models\IndustryGroup;
use App\Models\Industry;
use App\Models\SubIndustry;
use App\Models\Sector;
use App\Models\ReportSegment;
use App\Models\ReportPricing;
use App\Models\ReportFaq;
use App\Models\ReportMetrics;
use App\Models\ReportTableofcontent;
use App\Models\ReportGraphs;
use App\Models\ClientFeedback;
use App\Models\Country;
use App\Models\CountryPhonecode;
use App\Models\ReportSampleRequest;
use App\Models\ReportInquiry;
use App\Models\ReportSubscribeNow;
use App\Models\ReportOrders;
use App\Models\User;
use App\Models\Settings;
use App\Models\pages;

use Illuminate\Http\RedirectResponse;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Razorpay\Api\Api;

use App\Mail\FreeSampleRequest;
use App\Mail\ReportInquiry as ReportInquiryMail;
use App\Mail\ReportSubscribeNow as ReportSubscribeNowMail;
use App\Mail\NewUserCreate;
use App\Mail\ReportPaymentSuccess;
use App\Mail\ReportPaymentFailure;

use App\Jobs\SendFreeSampleRequestEmail;
use App\Jobs\SendReportInquiryEmail;
use App\Jobs\SendReportSubscribeNowEmail;
use App\Jobs\SendReportPaymentSuccessEmail;
use App\Jobs\SendReportPaymentFailureEmail;
use App\Jobs\SendNewUserCreateEmail;
use App\Jobs\SendBuyNowEmail;


use Mail, Session, Auth, DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Rules\ScriptPreventRule;
use Illuminate\Support\Facades\Http;
use App\Mail\sendEmailOtp;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $title = config('metadata.reports.pageTitle');
        $meta_title = config('metadata.reports.title');
        $meta_description = config('metadata.reports.description');

        $per_page_report = config('constants.PER_PAGE_REPORT');


        $page = pages::where('slug','reports')->first();
        $h1 = ($page) ? $page->h1 : '';
        $meta_title = ($page) ? $page->meta_title : '';
        $meta_description = ($page) ? $page->meta_description : '';
        $page_title = ($page) ? $page->page_title : '';
        $meta_keyword = ($page) ? $page->meta_keyword : '';

        $reports = Report::where('is_active',1);

        if ($request->keyword!='') {
            $search = $request->keyword;

            $reports = $reports->where(function ($q) use ($search) {
                $q->where('name','like','%'.$search.'%')
                ->orwhere('country','like','%'.$search.'%')
                ->orwhere('product_id','like','%'.$search.'%')
                ->orwhere('slug','like','%'.$search.'%')
                ->orwhere('pages','like','%'.$search.'%')
                ->orwhere(function ($qqq) use ($search) {
                    $qqq->where('description','like','%'.$search.'%')->where('report_type','<>','Upcoming');
                })
                ->orwhere('market_insights','like','%'.$search.'%')
                ->orwhere('segmental_analysis','like','%'.$search.'%')
                ->orwhere('regional_insights','like','%'.$search.'%')
                ->orwhere('market_dynamics','like','%'.$search.'%')
                ->orwhere('competitive_landscape','like','%'.$search.'%')
                ->orwhere('key_market_trends','like','%'.$search.'%')
                ->orwhere('skyQuest_analysis','like','%'.$search.'%')
                ->orwhere('whats_included','like','%'.$search.'%')
                ->orWhereHas('sector', function ($qq) use ($search) {
                    $qq->where('title','like','%'.$search.'%');
                })
                ->orWhereHas('industry_group', function ($qq) use ($search) {
                    $qq->where('title','like','%'.$search.'%');
                })
                ->orWhereHas('industry', function ($qq) use ($search) {
                    $qq->where('title','like','%'.$search.'%');
                })
                ->orWhereHas('sub_industry', function ($qq) use ($search) {
                    $qq->where('title','like','%'.$search.'%');
                })
                ->orWhereHas('report_segments', function ($qq) use ($search) {
                    $qq->where('name','like','%'.$search.'%')->orwhere('value','like','%'.$search.'%');
                });
            });
        }

        if ($request->orderby!='') {
            $reports = $reports->orderBy('created_at', $request->orderby);
        } else{
            $reports = $reports->orderBy('created_at', 'desc');
        }

        // for Published and Upcoming tabs
        if($request->upcoming=='1'){
            $reports = $reports->where('report_type','Upcoming');
        } else {
            $reports = $reports->whereIN('report_type',array('SD','Dynamic'));
        }

        $reports = $reports->paginate($per_page_report);

        $sectors = Sector::where('is_active',1)->get();
        $services = Service::where('is_active',1)->get();
        $settings = Settings::first();

        if ($request->ajax()) {
            $html = '';
            if(!$reports->isEmpty()){
                foreach ($reports as $report) {
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

                    $html .= "<div class=report-items-inner>
                        <div class='report-img'><a href='". route('report.details',$report->slug) ."'><img src='".$report->image_url."' alt=''></a></div>
                        <div class='containt'>
                        <a href='". route('report.details',$report->slug) ."'>".$report_name."</a>
                        <div class='report-segment-data'>
                            <hr/>
                            <p class='grey-content'>";
                            if(isset($report->product_id)){
                            $html .= "<b>Report ID: </b>".$report->product_id." |";
                            }
                            if(isset($report->country)){
                            $html .= "<b>Region: </b>".ucfirst($report->country)." | ";
                            }
                        if($report->report_type=='Upcoming'){
                            $html .= "<b>Published Date:</b> Upcoming | ";
                        } elseif(($report->report_type!='Upcoming') && isset($report->publish_date)){
                            $html .= "<b>Published Date: </b>". convertUtcToIst($report->publish_date, config('constants.DISPLAY_REPORT_DATE')) ." | ";
                        }
                        $html .= "<b>Pages: </b>".$report->pages."
                            </p>
                            <p class='green-content'>".$report->download."+ Downloads</p>";
                        if($request->upcoming!='1'){
                            if($report->report_type=='SD'){
                                $html .= "<p class='discription'>
                                    <span>".\Illuminate\Support\Str::limit(strip_tags($report->market_insights), 150, $end=' ...')."</span>
                                </p>";
                            } else{
                                $html .= "<p class='discription'>
                                    <span>".\Illuminate\Support\Str::limit(strip_tags($report->description), 150, $end=' ...')."</span>
                                </p>";
                            }
                        }
                    $html .= "<div class='row price-btns'>
                            <p class='price col'>";
                                if($report->report_pricing()->count() > 0){
                                    $html .= "$".number_format($report->report_pricing[0]['price'],0);
                                }
                    $html .= "</p>";

                    $html .= "<a href='".url('buy-now/'.$report->slug)."' class='report-buy-btn'>Buy Now</a>
                            <a href='".url('sample-request/'.$report->slug)."' class='get-sample-btn'>Get Sample</a>";
                            if(auth('web')->check()){
                                $userId = Auth::user()->id;
                                $reporttext = 'report';
                                $func = 'toggleBookmark("'.$userId.'","'.$reporttext.'","'.$report->id.'")';
                                $html .= "<a href='javascript:void(0)' onclick='".$func."' class='btn bookmark-btn'>";
                                    if((isset($report['report_bookmark'][0]->user_id))){
                                        if(($report['report_bookmark'][0]->user_id == $userId) && ($report['report_bookmark'][0]->entity_id == $report->id)){
                                            $html .= "<img id='bookmarktag' data-id='".$report->id."' src='".asset('assets/frontend/images/bookmark-black.png')."' alt='bookmark'>";
                                        }else {
                                            $html .= "<img id='bookmarktag' data-id='".$report->id."' src='".asset('assets/frontend/images/bookmark-black.png')."' alt='bookmark'>";
                                        }
                                    }else {
                                        $html .= "<img id='bookmarktag' data-id='".$report->id."' src='".asset('assets/frontend/images/bookmark-white.png')."' alt='bookmark'>";
                                    }
                                    $html .= "</a>";
                            }else{
                                $html .= "<a href='javascript:void(0)' onclick='alertBookmark();' class='btn bookmark-btn'>
                                    <img src='".asset('assets/frontend/images/bookmark-white.png')."' alt='Top market research company in India'>
                                </a>";
                            }

                        $html .= "</div>
                        </div>
                    </div>
                </div>
                <hr class='hr-tag'>";
                }
            }

            return $html;
        }

        return view('front.reports.index',compact('title','meta_title','meta_description','services','reports','sectors','page','h1','page_title','meta_keyword'));
    }

    public function sectorReports($slug,Request $request)
    {
        $title = config('metadata.industries-slug.pageTitle');
        $meta_title = config('metadata.industries-slug.title');
        $meta_description = config('metadata.industries-slug.description');

        $reports = array();
        $sector_id = "";
        $industry_group_id = "";
        $industry_id = "";
        $sub_industry_id = "";
        $h1 = "";

        
        $per_page_report = config('constants.PER_PAGE_REPORT');
        $settings = Settings::first();

        // ceheck in sector
        $filterData = Sector::where('slug',$slug)->where('is_active',1)->first();
        $sector_id = ($filterData) ? $filterData->id : '';
        $h1 =  ($filterData) ? $filterData->h1 : '';
        $meta_title =  ($filterData) ? $filterData->meta_title : '';
        $title =  ($filterData) ? $filterData->meta_title : '';
        $meta_description = ($filterData) ? $filterData->meta_description : '';
        $page_title = ($filterData)? $filterData->page_title: '';
        $meta_keyword = ($filterData)? $filterData->meta_keyword: '';
        // ceheck in industry group
        if(!$filterData) {
            $filterData = IndustryGroup::where('slug',$slug)->where('is_active',1)->first();
            $industry_group_id = ($filterData) ? $filterData->id : '';
            $h1 =  ($filterData) ? $filterData->h1 : '';
            $meta_title =  ($filterData) ? $filterData->meta_title : '';
            $title =  ($filterData) ? $filterData->meta_title : '';
            $meta_description = ($filterData) ? $filterData->meta_description : '';
            $page_title = ($filterData)? $filterData->page_title: '';
            $meta_keyword = ($filterData)? $filterData->meta_keyword: '';
        }
        // ceheck in industry
        if(!$filterData) {
            $filterData = Industry::where('slug',$slug)->where('is_active',1)->first();
            $industry_id = ($filterData) ? $filterData->id : '';
           $h1 =  ($filterData) ? $filterData->h1 : '';
           $meta_title =  ($filterData) ? $filterData->meta_title : '';
           $title =  ($filterData) ? $filterData->meta_title : '';
           $meta_description = ($filterData) ? $filterData->meta_description : '';
           $page_title = ($filterData)? $filterData->page_title: '';
           $meta_keyword = ($filterData)? $filterData->meta_keyword: '';
        }
        // ceheck in sub-industry
        if(!$filterData) {
            $filterData = SubIndustry::where('slug',$slug)->where('is_active',1)->first();
            $sub_industry_id = ($filterData) ? $filterData->id : '';
            $h1 =  ($filterData) ? $filterData->h1 : '';
            $meta_title =  ($filterData) ? $filterData->meta_title : '';
            $title =  ($filterData) ? $filterData->meta_title : '';
            $meta_description = ($filterData) ? $filterData->meta_description : '';
            $page_title = ($filterData)? $filterData->page_title: '';
            $meta_keyword = ($filterData)? $filterData->meta_keyword: '';
        }
        
        if(!$filterData) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }

        if($filterData){
            $reports = Report::where('is_active',1);

            if ($sector_id!='') {
                $reports = $reports->where('sector_id', $sector_id);
            }
            if ($industry_group_id!='') {
                $reports = $reports->where('industry_group_id', $industry_group_id);
            }
            if ($industry_id!='') {
                $reports = $reports->where('industry_id', $industry_id);
            }
            if ($sub_industry_id!='') {
                $reports = $reports->where('sub_industry_id', $sub_industry_id);
            }
            if ($request->keyword!='') {
                $search = $request->keyword;
                $reports = $reports->where(function ($q) use ($search) {
                    $q->where('name','like','%'.$search.'%')
                    ->orwhere('country','like','%'.$search.'%')
                    ->orwhere('product_id','like','%'.$search.'%')
                    ->orwhere('slug','like','%'.$search.'%')
                    ->orwhere('pages','like','%'.$search.'%')
                    ->orwhere(function ($qqq) use ($search) {
                        $qqq->where('description','like','%'.$search.'%')->where('report_type','<>','Upcoming');
                    })
                    ->orwhere('market_insights','like','%'.$search.'%')
                    ->orwhere('segmental_analysis','like','%'.$search.'%')
                    ->orwhere('regional_insights','like','%'.$search.'%')
                    ->orwhere('market_dynamics','like','%'.$search.'%')
                    ->orwhere('competitive_landscape','like','%'.$search.'%')
                    ->orwhere('key_market_trends','like','%'.$search.'%')
                    ->orwhere('skyQuest_analysis','like','%'.$search.'%')
                    ->orwhere('whats_included','like','%'.$search.'%')
                    ->orWhereHas('sector', function ($qq) use ($search) {
                        $qq->where('title','like','%'.$search.'%');
                    })
                    ->orWhereHas('industry_group', function ($qq) use ($search) {
                        $qq->where('title','like','%'.$search.'%');
                    })
                    ->orWhereHas('industry', function ($qq) use ($search) {
                        $qq->where('title','like','%'.$search.'%');
                    })
                    ->orWhereHas('sub_industry', function ($qq) use ($search) {
                        $qq->where('title','like','%'.$search.'%');
                    })
                    ->orWhereHas('report_segments', function ($qq) use ($search) {
                        $qq->where('name','like','%'.$search.'%')->orwhere('value','like','%'.$search.'%');
                    });
                });
            }
            // for Published and Upcoming tabs
            if($request->upcoming=='1'){
                $reports = $reports->where('report_type','Upcoming');
            } else {
                $reports = $reports->whereIN('report_type',array('SD','Dynamic'));
            }

            if ($request->orderby!='') {
                $reports = $reports->orderBy('created_at', $request->orderby);
            } else {
                $reports = $reports->orderBy('created_at', 'desc');
            }

            $reportData = $reports->get();
            $reports = $reports->paginate($per_page_report);
            if ($request->ajax()) {
                $html = '';
                if(!$reports->isEmpty()){
                    foreach ($reports as $report) {
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

                        $html .= "<div class=report-items-inner>
                            <div class='report-img'><a href='". route('report.details',$report->slug) ."'><img src='".$report->image_url."' alt=''></a></div>
                            <div class='containt'>
                            <a href='". route('report.details',$report->slug) ."'>".$report_name."</a>
                            <div class='report-segment-data'>";
                        $html .= "<hr/>
                            <p class='grey-content'>";
                            if(isset($report->product_id)){
                            $html .= "<b>Report ID: </b>".$report->product_id." |";
                            }
                            if(isset($report->country)){
                            $html .= "<b>Region: </b>".ucfirst($report->country)." | ";
                            }
                        if($report->report_type=='Upcoming'){
                            $html .= "<b>Published Date:</b> Upcoming | ";
                        } elseif(($report->report_type!='Upcoming') && isset($report->publish_date)){
                            $html .= "<b>Published Date: </b>". convertUtcToIst($report->publish_date, config('constants.DISPLAY_REPORT_DATE')) ." | ";
                        }
                        $html .= "<b>Pages: </b>".$report->pages."
                            </p>
                            <p class='green-content'>".$report->download."+ Downloads</p>";
                        if($report->report_type=='SD'){
                            $html .= "<p class='discription'>
                                <span>".\Illuminate\Support\Str::limit(strip_tags($report->market_insights), 150, $end=' ...')."</span>
                            </p>";
                        } else{
                            $html .= "<p class='discription'>
                                <span>".\Illuminate\Support\Str::limit(strip_tags($report->description), 150, $end=' ...')."</span>
                            </p>";
                        }
                        $html .= "<div class='row price-btns'>
                                <p class='price col'>";
                                    if($report->report_pricing()->count() > 0){
                                        $html .= "$".number_format($report->report_pricing[0]['price'],0);
                                    }
                        $html .= "</p>
                                <a href='".url('buy-now/'.$report->slug)."' class='report-buy-btn'>Buy Now</a>
                                <a href='".url('sample-request/'.$report->slug)."' class='get-sample-btn'>Get Sample</a>";
                                if(auth('web')->check()){
                                    $userId = Auth::user()->id;
                                    $reporttext = 'report';
                                    $func = 'toggleBookmark("'.$userId.'","'.$reporttext.'","'.$report->id.'")';
                                    $html .= "<a href='javascript:void(0)' onclick='".$func."' class='btn bookmark-btn'>";
                                        if((isset($report['report_bookmark'][0]->user_id))){
                                            if(($report['report_bookmark'][0]->user_id == $userId) && ($report['report_bookmark'][0]->entity_id == $report->id)){
                                                $html .= "<img id='bookmarktag' data-id='".$report->id."' src='".asset('assets/frontend/images/bookmark-black.png')."' alt='bookmark'>";
                                            }else {
                                                $html .= "<img id='bookmarktag' data-id='".$report->id."' src='".asset('assets/frontend/images/bookmark-black.png')."' alt='bookmark'>";
                                            }
                                        }else {
                                            $html .= "<img id='bookmarktag' data-id='".$report->id."' src='".asset('assets/frontend/images/bookmark-white.png')."' alt='bookmark'>";
                                        }
                                        $html .= "</a>";
                                }else{
                                    $html .= "<a href='javascript:void(0)' onclick='alertBookmark();' class='btn bookmark-btn'>
                                        <img src='".asset('assets/frontend/images/bookmark-white.png')."' alt='Top market research company in India'>
                                    </a>";
                                }
                        $html .= "</div>
                            </div>
                        </div>
                    </div>
                    <hr class='hr-tag'>";
                    }
                }

                return $html;
            }
        }
        $sectors = Sector::where('is_active',1)->get();
        $services = Service::where('is_active',1)->get();

        return view('front.reports.index',compact('title','meta_title','h1','meta_description','services','reports','sectors','page_title','meta_keyword'));
    }

    public function details($slug)
    {
        $report = Report::where('slug',$slug)->where('is_active',1)->first();

        if(!$report) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = ($report->meta_title!='') ? $report->meta_title : $report->name;
        $meta_title = ($report->meta_title!='') ? $report->meta_title : $report->name;
        $meta_description = ($report->meta_description!='') ? $report->meta_description : $report->name;
        $schema = $report->schema;
        $page_title = "";
        $meta_keyword = "";
        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        $settings = Settings::first();

        $companiesStr = '';

        if($report->report_type=='Upcoming' && $report->competitive_landscape!=''){
            $companies = json_decode($report->competitive_landscape, true);
            if(is_array($companies)){
                $companiesStr = "<ul>";
                for($c=0;$c<count($companies);$c++){
                    $companiesStr .= "<li>".trim($companies[$c])."</li>";
                }
                $companiesStr .= "</ul>";
            }
        }

        $segments = array();
        $sub_segments = array();

        // prepare report name
        $report_name = $report->name;

        if(isset($report->report_segments)){
            $report_name .= " Size, Share, Growth Analysis";

            foreach($report->report_segments as $report_segment){
                $segments[] = $report_segment->name;
                $sub_segments[] = $report_segment->value;

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

        $largest_segment = '';
        $fastest_growth = '';
        $chart_1 = array();
        $chart_1_years = "";
        $chart_2 = array();
        $chart_3 = array();
        $chart_3_years = "";
        $chart_4 = array();
        $chart_4_years = "";
        $chart_5 = array();
        $chart_6 = array();

        if(isset($report->report_graphs) && $report->report_graphs->count() > 0){
            foreach($report->report_graphs as $report_chart){

                if($report_chart->position=='1'){ // global_market_by_region
                    $chart_1["above_name"] = $report_chart->above_name;
                    $chart_1["below_name"] = $report_chart->below_name;

                    $data_content = json_decode($report_chart->content, true);

                    //array_pop($data_content); //commented to show all records
                    $chart_1["content"] = json_encode($data_content);
                    
                    $chart_1_years_arr = [];
                    if(isset($data_content)){
                        for($i=0;$i<count($data_content);$i++){
                            if($i>0 && ($data_content[0][0]=='Year' || $data_content[0][0]=='year')){
                                $chart_1_years_arr[] = $data_content[$i][0];
                            }
                        }
                    }
                    $chart_1_years = (isset($chart_1_years_arr)) ? json_encode($chart_1_years_arr) : '';
                }
                if($report_chart->position=='2'){ // country_share
                    $chart_2["above_name"] = $report_chart->above_name;
                    $chart_2["below_name"] = $report_chart->below_name;
                    if($report_chart->content){
                        $content = json_decode($report_chart->content, true);
                        if(is_array($content)){
                            $content[0][1] = "Size";
                            $content[0][2] = ['type'=> 'string', 'role'=> 'tooltip'];
                        }
                        $conArr = [];
                        foreach($content as $key=>$con) {
                            if($key > 0) {
                                $conArr[] = array_merge($con,[2=>"xx"]);
                            } else {
                                $conArr[] = $con;
                            }
                        }
                        $chart_2["content"] = json_encode($conArr);
                    }
                }
                if($report_chart->position=='3'){ // segment_1_share
                    $chart_3["above_name"] = $report_chart->above_name;
                    $chart_3["below_name"] = $report_chart->below_name;

                    $seg_no_1 = 0;
                    $seg_no_2 = 0;
                    $seg_no_1_name = '';
                    $seg_no_2_name = '';
                    $largest_segment = '';
                    $content = json_decode($report_chart->content, true);
                    //array_pop($content);  //commented to show all records
                    //dd($content);
                    if(is_array($content)){
                        if(is_array($content[0])){
                            $seg_no_1_name = isset($content[0][1]) ? $content[0][1] : '';
                            $seg_no_2_name = isset($content[0][2]) ? $content[0][2] : '';

                            foreach($content as $k => $val){
                                if($k>0){
                                    if(array_key_exists("1", $val)){
                                        if(is_numeric($val[1])){
                                            $seg_no_1 += $val[1];
                                        }
                                    }
                                    if(array_key_exists("2", $val)){
                                        if(is_numeric($val[2])){
                                            $seg_no_2 += $val[2];
                                        }
                                    }
                                    if(array_key_exists("0", $val)){
                                        $chart_3_years_arr[] = $val[0];
                                    }
                                }
                            }
                            if($seg_no_1 > 0 && $seg_no_2 > 0){
                                $largest_segment = ($seg_no_1 > $seg_no_2) ? $seg_no_1_name : $seg_no_2_name;
                            }
                            $chart_3_years = (isset($chart_3_years_arr)) ? json_encode($chart_3_years_arr) : '';
                        }
                    }

                    $chart_3["content"] = json_encode($content);
                }
                if($report_chart->position=='4'){ // cagr
                    $chart_4["above_name"] = $report_chart->above_name;
                    $chart_4["below_name"] = $report_chart->below_name;

                    $seg_no_1 = 0;
                    $seg_no_2 = 0;
                    $seg_no_1_name = '';
                    $seg_no_2_name = '';
                    $fastest_growth = '';
                    $content = json_decode($report_chart->content, true);

                    //array_pop($content);  //commented to show all records
                    $chart_4["content"] = json_encode($content);

                    if(is_array($content)){
                        $seg_no_1_name = isset($content[0][1]) ? $content[0][1] : '';
                        $seg_no_2_name = isset($content[0][2]) ? $content[0][2] : '';

                        foreach($content as $k => $val){
                            if($k>0){
                                if(array_key_exists("1", $val)){
                                    if(is_numeric($val[1])){
                                        $seg_no_1 += $val[1];
                                    }
                                }
                                if(array_key_exists("2", $val)){
                                    if(is_numeric($val[2])){
                                        $seg_no_2 += $val[2];
                                    }
                                }
                                if(array_key_exists("0", $val)){
                                    $chart_4_years_arr[] = $val[0];
                                }
                            }
                        }
                        if($seg_no_1 > 0 && $seg_no_2 > 0){
                            $fastest_growth = ($seg_no_1 > $seg_no_2) ? $seg_no_1_name : $seg_no_2_name;
                        }
                        $chart_4_years = (isset($chart_4_years_arr)) ? json_encode($chart_4_years_arr) : '';
                    }
                }
                if($report_chart->position=='5'){ // segment_2_share
                    $chart_5["above_name"] = $report_chart->above_name;
                    $chart_5["below_name"] = $report_chart->below_name;
                    if($report_chart->content){
                        if(strpos($report_chart->content,":")){
                            $content = json_decode($report_chart->content,true);
                            $final_content = array();
                            for($c=0;$c<count($content);$c++){
                                $values = array();
                                foreach($content[$c] as $k => $val){
                                    $values[] = $val;
                                }
                                $final_content[] = array_reverse($values);
                            }
                            $conChart5 = [];
                            foreach($final_content as $key=>$con) {
                                if($key > 0) {
                                    $conChart5[] = array_merge($con,[2=>"xx"]);
                                } else {
                                    $conChart5[] = array_merge($con,[2=>array('type'=> 'string', 'role'=> 'tooltip')]);
                                }
                            }

                            $chart_5["content"] = json_encode($conChart5);
                        } else{
                            $content = json_decode($report_chart->content,true);
                            $final_content = array();
                            foreach($content as $k => $val){
                                $final_content[] = array_reverse($val);
                            }
                            $conChart5 = [];
                            foreach($final_content as $key=>$con) {
                                if($key > 0) {
                                    $conChart5[] = array_merge($con,[2=>"xx"]);
                                } else {
                                    $conChart5[] = array_merge($con,[2=>array('type'=> 'string', 'role'=> 'tooltip')]);
                                }
                            }

                            $chart_5["content"] = json_encode($conChart5);
                        }
                    }
                }

                if($report_chart->position=='6'){ // worldmap
                    $chart_6["above_name"] = $report_chart->above_name;
                    $chart_6["below_name"] = $report_chart->below_name;
                    if($report_chart->content){
                        if(strpos($report_chart->content,":")){
                            $content = json_decode($report_chart->content,true);
                            $final_content = array();
                            for($c=0;$c<count($content);$c++){
                                $values = array();
                                foreach($content[$c] as $k => $val){
                                    $values[] = $val;
                                }
                                $final_content[] = array_reverse($values);
                            }
                            $conChart6 = [];
                            foreach($final_content as $key=>$con) {
                                if($key > 0) {
                                    $conChart6[] = array_merge($con,[2=>"xx"]);
                                } else {
                                    $conChart6[] = array_merge($con,[2=>array('type'=> 'string', 'role'=> 'tooltip')]);
                                }
                            }
                            $chart_6["content"] = json_encode($conChart6);
                        } else{
                            $content = json_decode($report_chart->content,true);
                            $final_content = array();
                            foreach($content as $k => $val){
                                $final_content[] = array_reverse($val);
                            }
                            $conChart6 = [];
                            foreach($final_content as $key=>$con) {
                                if($key > 0) {
                                    $conChart6[] = array_merge($con,[2=>"xx"]);
                                } else {
                                    $conChart6[] = array_merge($con,[2=>array('type'=> 'string', 'role'=> 'tooltip')]);
                                }
                            }
                            $chart_6["content"] = json_encode($conChart6);
                        }
                    }
                }
            }
        }

        // related reports
        $report_id = (isset($report->id)) ? $report->id : '';
        $sector_id = (isset($report->sector_id)) ? $report->sector_id : '';
        $industry_group_id = (isset($report->industry_group_id)) ? $report->industry_group_id : '';
        $industry_id = (isset($report->industry_id)) ? $report->industry_id : '';
        $sub_industry_id = (isset($report->sub_industry_id)) ? $report->sub_industry_id : '';
        $related_reports = Report::where('sector_id',$sector_id)
                                ->where('industry_group_id',$industry_group_id)
                                ->where('industry_id',$industry_id)
                                ->where('sub_industry_id',$sub_industry_id)
                                ->where('id','<>',$report_id)
                                ->where('is_active',1)
                                ->inRandomOrder()
                                ->limit(4)
                                ->get();

        // feedback
        $clientfeedbacks = getClientFeedback();

        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)+<\/p>/";
        $url = array();

        // Share button
        $shareButtons = ''; 

        $sociallinks = \Share::page(route('report.details',$report->slug), $meta_title)
            ->facebook()
            ->twitter()
            ->linkedin($meta_title)
            ->getRawLinks();

        $imageURL = $report->image_url;

        $base_year_record = ReportMetrics::selectRaw('MIN(id) as mid')->select(['meta_key','meta_value'])->where('meta_key', 'like', '%Market size value in%')->where('report_id', $report_id)->first();
        $base_year_meta_key = isset($base_year_record) ? trim($base_year_record->meta_key) : '';
        $base_year_meta_value = isset($base_year_record) ? trim($base_year_record->meta_value) : '';
        return view('front.reports.details',compact('base_year_meta_key','base_year_meta_value','title','meta_title','meta_description','schema','report_name','report','services','sectors','largest_segment','fastest_growth','chart_1','chart_1_years','chart_2','chart_3','chart_3_years','chart_4','chart_4_years','chart_5','chart_6','clientfeedbacks','related_reports','reg_exUrl','url','segments','sub_segments','companiesStr','shareButtons','sociallinks','imageURL','page_title','meta_keyword'));
    }

    public function getReportData(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        $report = Report::where('id',$id)->where('is_active',1)->first();

        if(!$report) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }

        $html = "";
        switch($type){
            case 'toc':
                if(!$report->report_tableofcontent->isEmpty()){
                    if($report->report_type=='SD'){
                        foreach($report->report_tableofcontent as $report_tocdata){
                        $html .= "<div class='accordion toc-accordion' id='TocAccordionExample'>";
                        if($report_tocdata->toc!=''){
                        $html .= "<div class='accordion-item'>
                            <h2 class='accordion-header' id='headingOne'>
                                <button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#collapseOne' aria-expanded='true' aria-controls='collapseOne'>
                                    Table of Contents
                                </button>
                            </h2>
                            <div id='collapseOne' class='accordion-collapse collapse show' aria-labelledby='headingOne' data-bs-parent='#TocAccordionExample'>
                                <div class='accordion-body'>
                                    <div>".$report_tocdata->toc."</div>
                                </div>
                            </div>
                        </div>";
                        }
                        if($report_tocdata->tables!=''){
                        $html .= "<div class='accordion-item'>
                            <h2 class='accordion-header' id='headingTwo'>
                                <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseTwo' aria-expanded='false' aria-controls='collapseTwo'>
                                    List of Tables
                                </button>
                            </h2>
                            <div id='collapseTwo' class='accordion-collapse collapse' aria-labelledby='headingTwo' data-bs-parent='#TocAccordionExample'>
                                <div class='accordion-body'>
                                    <div>".$report_tocdata->tables."</div>
                                </div>
                            </div>
                        </div>";
                        }
                        if($report_tocdata->figures!=''){
                        $html .= "<div class='accordion-item'>
                            <h2 class='accordion-header' id='headingThree'>
                                <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseThree' aria-expanded='false' aria-controls='collapseThree'>
                                    List of Figures
                                </button>
                            </h2>
                            <div id='collapseThree' class='accordion-collapse collapse' aria-labelledby='headingThree' data-bs-parent='#TocAccordionExample'>
                                <div class='accordion-body'>
                                    <div>".$report_tocdata->figures."</div>
                                </div>
                            </div>
                        </div>";
                        }
                        $html .= "</div>";
                        }
                    }
                    if($report->report_type=='Dynamic'){
                        foreach($report->report_tableofcontent as $report_tocdata){
                            $html .= $report_tocdata->toc;
                        }
                    }
                }
                break;
            case 'methodology':
                if(isset($report->methodologies)){
                $html .= "<h3 class='report-title'>Methodology</h3>
                        <div>".$report->methodologies."</div>";
                }
                break;
            case 'analystsupport':
                if(isset($report->analyst_support)){
                $html .= "<h3 class='report-title'>Analyst Support</h3>
                        <div>".$report->analyst_support."</div>";
                }
                break;
        }

        return response()->json(['html' => $html, 'success' => 1]);
    }

    function getReportFileType(Request $request)
    {
        $report_id = $request->report_id;
        $license_type = $request->license_type;

        $pricing = ReportPricing::where('report_id',$report_id)->where('license_type',$license_type)->where('is_active',1)->get();

        $html = "";
        $price = "";
        if($pricing){
            foreach($pricing as $item){

                $html .= "<option value='".$item->file_type."'>".$item->file_type."</option>";
            }
            $price = number_format($pricing[0]['price'],0);
        }
        return response()->json(['html' => $html, 'success' => 1, 'price' => $price]);
    }

    function getReportPrice(Request $request)
    {
        $report_id = $request->report_id;
        $license_type = $request->license_type;
        $file_type = $request->file_type;
        $price = "";
        $pricing = ReportPricing::where('report_id',$report_id)->where('license_type',$license_type)->where('file_type',$file_type)->where('is_active',1)->pluck('price');

        $price = ($pricing) ? number_format($pricing[0],0) : '';

        return response()->json(['success' => 1, 'price' => $price]);
    }

    public function sampleRequest($slug)
    {
        $report = Report::where('slug',$slug)->where('is_active',1)->first();

        if(!$report) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = $report->name;
        $meta_title = $report->meta_title;
        $meta_description = $report->meta_description;
        $page_title = "";
        $meta_keyword = "";

        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        $countries = Country::get();
        $settings = Settings::first();

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

        // related reports
        $report_id = (isset($report->id)) ? $report->id : '';
        $sector_id = (isset($report->sector_id)) ? $report->sector_id : '';
        $industry_group_id = (isset($report->industry_group_id)) ? $report->industry_group_id : '';
        $industry_id = (isset($report->industry_id)) ? $report->industry_id : '';
        $sub_industry_id = (isset($report->sub_industry_id)) ? $report->sub_industry_id : '';
        $related_reports = Report::where('sector_id',$sector_id)
                                ->where('industry_group_id',$industry_group_id)
                                ->where('industry_id',$industry_id)
                                ->where('sub_industry_id',$sub_industry_id)
                                ->where('id','<>',$report_id)
                                ->where('is_active',1)
                                ->inRandomOrder()
                                ->limit(4)
                                ->get();

        // feedback
        $clientfeedbacks = getClientFeedback();

        return view('front.reports.samplerequest',compact('page_title','meta_keyword','title','meta_title','meta_description','report','services','sectors','clientfeedbacks','countries','related_reports','report_name'));
    }

    public function saverequestSample(Request $request)
    {
        $request->validate([
            'name' => ['required', new ScriptPreventRule()],
            'email' => 'required|email:filter',
            'phonecode' => 'required',
            'phone' => 'required',
            'company_name' => ['required', new ScriptPreventRule()],
            'designation' => ['required', new ScriptPreventRule()],
            'message' => [new ScriptPreventRule()],
            // 'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'The captcha field is required.',
            'g-recaptcha-response.captcha' => 'Invalid captcha',
        ]);
        try{
            $data = $request->except(['_token','hiddenRecaptcha']);
                    
            $phoneArr = explode(":",$data['phonecode']);
            $country_id = $phoneArr[0];
            $phonecode = $phoneArr[1];

            // update fields to users table
            if(Auth::check()){
                $user = auth('web')->user();
                if($user){
                    $user_data = array('user_name' => $data['name'],
                                    'email' => $data['email'],
                                    'phone' => $phonecode.$data['phone'],
                                    'company_name' => $data['company_name']);
                    User::where('id',$user->id)->update($user_data);
                }
            }

            $data['phonecode'] = $phonecode;
            $data['country_id'] = $country_id;
            $data['ip_address'] = \Request::getClientIp(true);// get_client_ip();
            $samplerequest = ReportSampleRequest::create($data);
            try {
                dispatch(new SendFreeSampleRequestEmail($samplerequest));
            } catch (\Exception $e) {
                Log::error('Error while sending free sample request email.'.$e->getMessage());
            }

            // call API to create ticket in freshdesk
            $report_data = Report::find($data['report_id']);
            $report_name = ($report_data) ? $report_data->name : '';

            $country = $samplerequest->country_id ? $samplerequest->country->name : '';
            $sector = ($report_data) ? $report_data->sector->title : '';
            $industry_group = ($report_data) ? $report_data->industry_group->title : '';
            $industry = ($report_data) ? $report_data->industry->title : '';
            $subindustry = ($report_data) ? $report_data->sub_industry->title : '';

            // $response = Http::withHeaders(['Authorization' => 'Token token='.config('constants.FRESHDESK_API_KEY')])->post(config('constants.TICKET_API_URL')."contacts/upsert", [
            //     'unique_identifier' => ['emails' => $data['email']],
            //     'contact' => ['first_name' => $data['name'],
            //                 'job_title' => $data['designation'],
            //                 'mobile_number' => $phonecode.$data['phone'],
            //                 'country' => $country]
            // ]);

            // $res = $response->json();
            // $contactId = ($res) ? $res['contact']['id'] : '';
                    
            // $response = Http::withHeaders(['Authorization' => 'Token token='.config('constants.FRESHDESK_API_KEY')])->post(config('constants.TICKET_API_URL')."deals", [
            //     'deal' => ['name' => $report_name,
            //                 'amount' => 1,
            //                 'contacts_added_list' => [$contactId],
            //                 'custom_field' => ['cf_description' => (isset($data['message']) && !empty(trim($data['message']))) ? $data['message'] : 'No description added',
            //                                     'cf_subject' => 'Sample Report : '.$report_name,
            //                                     'cf_company_name' => $data['company_name'],
            //                                     'cf_ip_address' => $data['ip_address'],
            //                                     'cf_sector' => $sector,
            //                                     'cf_industry_group' => $industry_group,
            //                                     'cf_industry' => $industry,
            //                                     'cf_sub_industry' => $subindustry,
            //                                     'cf_linkedin_url' => $data['linkedin_link'] ? $data['linkedin_link'] : '']]
            // ]);

            // start code from here for dynamic link for reportedemail & report_id

            return response()->json(['success' => 1, 'message' => 'Sample Report Shared Successfully!!']);
        }catch(\Exception $e){
            return response()->json(['success' => 0, 'message' => $e->getMessage()]);
        }
        
        // $notification = ['message' => 'Your free sample request has been send successfully!', 'alert-class' => 'success'];
        // return redirect()->back()->with($notification);
    }

    public function sendEmailOtp(Request $request){
        $email = $request->input('email');
        if($email){
            try{
                $digits = rand(111111, 999999);
                $data = DB::table('report_email_otp')->where('email', $email)->first();
                if($data){
                    DB::table('report_email_otp')
                    ->where('email', $email)
                    ->update(['otp' => $digits]);
                }else{
                    $data = DB::table('report_email_otp')->insert(['email' => $email, 'otp' => $digits]);
                }
                // Mail::to($email)->send(new sendEmailOtp($email,$digits));
                return response()->json(['success' => 1]);
            }catch(\Exception $ex){
                return response()->json(['success' => 0, 'error' => $ex->getMessage()]);
            }
        }
    }

    public function verifyEmailOtp(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');
        if($email && $otp)
        {
            try
            {
                $data = DB::table('report_email_otp')->where(['email' => $email, 'otp' => $otp])->first();
                if($data){
                    return response()->json(['success' => 1]);
                }else{
                    return response()->json(['success' => 0, 'error' => 'invalid otp']);
                }
            }catch(\Exception $ex){
                return response()->json(['success' => 0, 'error' => $ex->getMessage()]);
            }
        }
    }

    public function sampleReportPage(Request $request, $slug){
        $report = base64_decode($request->input('report'));
        $user = base64_decode($request->input('user'));
        $sampleId = base64_decode($request->input('sampleId'));
        $page = $request->input('page') ?? 1;
        if($slug && ($report != "") && ($user != ""))
        {
            $data = ReportSampleRequest::where(['id' => $sampleId, 
                 'report_id' => $report, 
                 'email' => $user])->first();
            if($data)
            {
                $rData = Report::find($data->report_id);
                $response = getPageResult($rData, $page);
                return view('front.sample-report-page.index', compact('response','user','report', 'sampleId', 'page', 'slug'));
            }else{
                dd("Invalid Url");    
            }
        }else{
            dd("Invalid Url");
        }
    }

    public function speakWithAnalyst($slug)
    {
        $report = Report::where('slug',$slug)->where('is_active',1)->first();

        if(!$report) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = $report->name;
        $meta_title = $report->meta_title;
        $meta_description = $report->meta_description;
        $page_title = "";
        $meta_keyword = "";

        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        $countries = Country::get();
        $settings = Settings::first();

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

        // related reports
        $report_id = (isset($report->id)) ? $report->id : '';
        $sector_id = (isset($report->sector_id)) ? $report->sector_id : '';
        $industry_group_id = (isset($report->industry_group_id)) ? $report->industry_group_id : '';
        $industry_id = (isset($report->industry_id)) ? $report->industry_id : '';
        $sub_industry_id = (isset($report->sub_industry_id)) ? $report->sub_industry_id : '';
        $related_reports = Report::where('sector_id',$sector_id)
                                ->where('industry_group_id',$industry_group_id)
                                ->where('industry_id',$industry_id)
                                ->where('sub_industry_id',$sub_industry_id)
                                ->where('id','<>',$report_id)
                                ->where('is_active',1)
                                ->inRandomOrder()
                                ->limit(4)
                                ->get();

        // feedback
        $clientfeedbacks = getClientFeedback();

        return view('front.reports.speakwithanalyst',compact('page_title','meta_keyword','title','meta_title','meta_description','report','services','sectors','clientfeedbacks','countries','related_reports','report_name'));
    }

    public function saveSpeakWithAnalyst(Request $request)
    {
        $request->validate([
            'name' => ['required', new ScriptPreventRule()],
            'email' => 'required|email:filter',
            'phonecode' => 'required',
            'phone' => 'required',
            'company_name' => ['required', new ScriptPreventRule()],
            'designation' => ['required', new ScriptPreventRule()],
            //'country_id' => 'required',
            'message' => [new ScriptPreventRule()],
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'The captcha field is required.',
            'g-recaptcha-response.captcha' => 'Invalid captcha',
        ]);

        $data = $request->except(['_token','hiddenRecaptcha','privacy_policy']);

        $phoneArr = explode(":",$data['phonecode']);
        $country_id = $phoneArr[0];
        $phonecode = $phoneArr[1];

        // update fields to users table
        if(Auth::check()){
            $user = auth('web')->user();
            if($user){
                $user_data = array('user_name' => $data['name'],
                                'email' => $data['email'],
                                'phone' => $phonecode.$data['phone'],
                                'company_name' => $data['company_name']);
                User::where('id',$user->id)->update($user_data);
            }
        }

        $data['phonecode'] = $phonecode;
        $data['country_id'] = $country_id;        
        $report_inquiry = ReportInquiry::create($data);

        try {
            // dispatch your queue job
            dispatch(new SendReportInquiryEmail($report_inquiry));
        } catch (\Exception $e) {
            Log::error('Error while sending report inquiry email.'.$e->getMessage());
        }

        // call API to create ticket in freshdesk
        $report_data = Report::find($data['report_id']);
        $report_name = ($report_data) ? $report_data->name : '';

        $country = $report_inquiry->country_id ? $report_inquiry->country->name : '';
        $sector = ($report_data) ? $report_data->sector->title : '';
        $industry_group = ($report_data) ? $report_data->industry_group->title : '';
        $industry = ($report_data) ? $report_data->industry->title : '';
        $subindustry = ($report_data) ? $report_data->sub_industry->title : '';
        $ip_address = \Request::getClientIp(true);// get_client_ip();
        
        $response = Http::withHeaders(['Authorization' => 'Token token='.config('constants.FRESHDESK_API_KEY')])->post(config('constants.TICKET_API_URL')."contacts/upsert", [
            'unique_identifier' => ['emails' => $data['email']],
            'contact' => ['first_name' => $data['name'],
                        'job_title' => $data['designation'],
                        'mobile_number' => $phonecode.$data['phone'],
                        'country' => $country]
        ]);

        $res = $response->json();
        $contactId = ($res) ? $res['contact']['id'] : '';

        $response = Http::withHeaders(['Authorization' => 'Token token='.config('constants.FRESHDESK_API_KEY')])->post(config('constants.TICKET_API_URL')."deals", [
            'deal' => ['name' => $report_name,
                        'amount' => 1,
                        'contacts_added_list' => [$contactId],
                        'custom_field' => ['cf_description' => (isset($data['message']) && !empty(trim($data['message']))) ? $data['message'] : 'No description added',
                                            'cf_subject' => 'Speak to Analyst : '.$report_name,
                                            'cf_company_name' => $data['company_name'],
                                            'cf_ip_address' => $ip_address,
                                            'cf_sector' => $sector,
                                            'cf_industry_group' => $industry_group,
                                            'cf_industry' => $industry,
                                            'cf_sub_industry' => $subindustry,
                                            'cf_linkedin_url' => $data['linkedin_link'] ? $data['linkedin_link'] : '']]
        ]);
        
        $notification = ['message' => 'Your inquiry request for this report has been send successfully!', 'alert-class' => 'success'];
        return redirect()->back()->with($notification);
    }

    public function subscribeNow($slug)
    {
        $report = Report::where('slug',$slug)->where('is_active',1)->first();

        if(!$report) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = $report->name;
        $meta_title = $report->meta_title;
        $meta_description = $report->meta_description;
        $page_title = "";
        $meta_keyword = "";

        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        $countries = Country::get();
        $settings = Settings::first();

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

        // related reports
        $report_id = (isset($report->id)) ? $report->id : '';
        $sector_id = (isset($report->sector_id)) ? $report->sector_id : '';
        $industry_group_id = (isset($report->industry_group_id)) ? $report->industry_group_id : '';
        $industry_id = (isset($report->industry_id)) ? $report->industry_id : '';
        $sub_industry_id = (isset($report->sub_industry_id)) ? $report->sub_industry_id : '';
        $related_reports = Report::where('sector_id',$sector_id)
                                ->where('industry_group_id',$industry_group_id)
                                ->where('industry_id',$industry_id)
                                ->where('sub_industry_id',$sub_industry_id)
                                ->where('id','<>',$report_id)
                                ->where('is_active',1)
                                ->inRandomOrder()
                                ->limit(4)
                                ->get();

        // feedback
        $clientfeedbacks = getClientFeedback();

        return view('front.reports.subscribenow',compact('page_title','meta_keyword','title','meta_title','meta_description','report','services','sectors','clientfeedbacks','countries','related_reports','report_name'));
    }

    public function saveSubscribeNow(Request $request)
    {
        $request->validate([
            'plan' => 'required',
            'name' => ['required', new ScriptPreventRule()],
            'email' => 'required|email:filter',
            'phonecode' => 'required',
            'phone' => 'required',
            'company_name' => ['required', new ScriptPreventRule()],
            'designation' => ['required', new ScriptPreventRule()],
            'message' => [new ScriptPreventRule()],
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'The captcha field is required.',
            'g-recaptcha-response.captcha' => 'Invalid captcha',
        ]);

        $data = $request->except(['_token','hiddenRecaptcha']);

        $phoneArr = explode(":",$data['phonecode']);
        $country_id = $phoneArr[0];
        $phonecode = $phoneArr[1];

        // update fields to users table
        if(Auth::check()){
            $user = auth('web')->user();
            if($user){
                $user_data = array('user_name' => $data['name'],
                                'email' => $data['email'],
                                'phone' => $data['phonecode'].$data['phone'],
                                'company_name' => $data['company_name']);
                User::where('id',$user->id)->update($user_data);
            }
        }

        $data['phonecode'] = $phonecode;
        $data['country_id'] = $country_id; 
        $subscribenow = ReportSubscribeNow::create($data);

        try {
            // dispatch your queue job
            dispatch(new SendReportSubscribeNowEmail($subscribenow));
        } catch (\Exception $e) {
            Log::error('Error while sending report subscription email.'.$e->getMessage());
        }

        // call API to create ticket in freshdesk
        $report_data = Report::find($data['report_id']);
        $report_name = ($report_data) ? $report_data->name : '';

        $country = $subscribenow->country_id ? $subscribenow->country->name : '';
        $sector = ($report_data) ? $report_data->sector->title : '';
        $industry_group = ($report_data) ? $report_data->industry_group->title : '';
        $industry = ($report_data) ? $report_data->industry->title : '';
        $subindustry = ($report_data) ? $report_data->sub_industry->title : '';
        $ip_address = \Request::getClientIp(true);// get_client_ip();
       
        $response = Http::withHeaders(['Authorization' => 'Token token='.config('constants.FRESHDESK_API_KEY')])->post(config('constants.TICKET_API_URL')."contacts/upsert", [
            'unique_identifier' => ['emails' => $data['email']],
            'contact' => ['first_name' => $data['name'],
                        'job_title' => $data['designation'],
                        'mobile_number' => $phonecode.$data['phone'],
                        'country' => $country]
        ]);

        $res = $response->json();
        $contactId = ($res) ? $res['contact']['id'] : '';

        $response = Http::withHeaders(['Authorization' => 'Token token='.config('constants.FRESHDESK_API_KEY')])->post(config('constants.TICKET_API_URL')."deals", [
            'deal' => ['name' => $report_name,
                        'amount' => 1,
                        'contacts_added_list' => [$contactId],
                        'custom_field' => ['cf_description' => (isset($data['message']) && !empty(trim($data['message']))) ? $data['message'] : 'No description added',
                                            'cf_subject' => 'Subscribe Now : '.$report_name,
                                            'cf_company_name' => $data['company_name'],
                                            'cf_ip_address' => $ip_address,
                                            'cf_sector' => $sector,
                                            'cf_industry_group' => $industry_group,
                                            'cf_industry' => $industry,
                                            'cf_sub_industry' => $subindustry,
                                            'cf_linkedin_url' => $data['linkedin_link'] ? $data['linkedin_link'] : '']]
        ]);

        $notification = ['message' => 'Your subscription request for this report has been send successfully!', 'alert-class' => 'success'];
        return redirect()->back()->with($notification);
    }

    public function buyNow($slug)
    {
        $report = Report::where('slug',$slug)->where('is_active',1)->first();

        if(!$report) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = $report->name;
        $meta_title = $report->meta_title;
        $meta_description = $report->meta_description;
        $services = Service::where('is_active',1)->get();
        $countries = Country::get();
        $settings = Settings::first();
        $page_title = "";
        $meta_keyword = "";

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

        return view('front.reports.buynow',compact('page_title','meta_keyword','title','meta_title','meta_description','services','report','countries','report_name'));
    }

    public function saveReportOrder(Request $request)
    {
        $request->validate([
            'name' => ['required', new ScriptPreventRule()],
            'email' => 'required|email:filter',
            'phonecode' => 'required',
            'phone' => 'required',
            'company_name' => ['required', new ScriptPreventRule()],
            //'country_id' => 'required',
            'message' => [new ScriptPreventRule()]]);

        $data = $request->except(['_token']);

        $phoneArr = explode(":",$data['phonecode']);
        $country_id = $phoneArr[0];
        $phonecode = $phoneArr[1];

        $user_id = '';
        // update fields to users table
        if(Auth::check()){
            $user = auth('web')->user();
            if($user){
                $user_id = $user->id;

                $user_data = array('user_name' => $data['name'],
                                'email' => $data['email'],
                                'phone' => $phonecode.$data['phone'],
                                'company_name' => $data['company_name']);
                User::where('id',$user->id)->update($user_data);
            }
        } else{
            $user = checkUserExist($data['email']);
            $user_id = (isset($user)) ? $user->id : '';
        }

        $report_type = '';
        if($data['report_id']!=''){
            $reportData = Report::where('id',$data['report_id'])->pluck('report_type')->toArray();
            $report_type = $reportData[0];
        }

        $pricing = ReportPricing::where('report_id',$data['report_id'])->where('license_type',$data['license_type'])->where('file_type',$data['file_type'])->where('is_active',1)->pluck('price');
        $price = ($pricing) ? $pricing[0] : str_replace(",","",trim($data['price'],"$"));

        //$price = str_replace(",","",trim($data['price'],"$"));
        $report_data = array('report_id' => $data['report_id'],
                        'report_type' => $report_type,
                        'license_type' => $data['license_type'],
                        'file_type' => $data['file_type'],
                        'payment_method' => $data['payment_methods'],
                        'payment_status' => 'Pending',
                        'price' => $price,
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'phonecode' => $phonecode,
                        'phone' => $data['phone'],
                        'company_name' => $data['company_name'],
                        'designation' => $data['designation'],
                        'country_id' => $country_id,
                        'linkedin_link' => $data['linkedin_link'],
                        'message' => $data['message']);

        if($user_id!='') $report_data['user_id'] = $user_id;

        $report_order = ReportOrders::create($report_data);

        // send buy now mail to user and admin
        try {
            dispatch(new SendBuyNowEmail($report_order));
        } catch (\Exception $e) {
            Log::error('Error while sending buy now email.'.$e->getMessage());
        }

        if(isset($report_order))
            return response()->json(['report_order_id' => $report_order->id, 'success' => 1]);
        else
            return response()->json(['report_order_id' => '', 'success' => 0]);
    }

    public function redirectForPayment(Request $request)
    {
        $request->validate([
            'name' => ['required', new ScriptPreventRule()],
            'email' => 'required|email:filter',
            'phonecode' => 'required',
            'phone' => 'required',
            'company_name' => ['required', new ScriptPreventRule()],     
            'message' => [new ScriptPreventRule()]]);

        $data = $request->except(['_token']);

        $phoneArr = explode(":",$data['phonecode']);
        $country_id = $phoneArr[0];
        $phonecode = $phoneArr[1];

        $user_id = '';
        // update fields to users table
        if(Auth::check()){
            $user = auth('web')->user();
        } else{
            $user = checkUserExist($data['email']);
        }
        $user_id = (isset($user)) ? $user->id : '';

        $report_type = '';
        $slug = '';
        $report_id = isset($data) ? $data['report_id'] : '';
        if($report_id!=''){
            $report = Report::where('id',$report_id)->where('is_active',1)->first();
            if(!$report) {
                $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
                return redirect()->back()->with($notification);
            }
            $report_type = $report->report_type;
            $slug = $report->slug;
        }

        $pricing = ReportPricing::where('report_id',$data['report_id'])->where('license_type',$data['license_type'])->where('file_type',$data['file_type'])->where('is_active',1)->pluck('price');
        $price = ($pricing) ? $pricing[0] : str_replace(",","",trim($data['price'],"$"));
        //$price = str_replace(",","",trim($data['price'],"$"));
        
        $report_data = array('user_id' => $user_id,
                        'report_id' => $data['report_id'],
                        'report_type' => $report_type,
                        'license_type' => $data['license_type'],
                        'file_type' => $data['file_type'],
                        'payment_method' => $data['payment_methods'],
                        'price' => $price,
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'phonecode' => $phonecode,
                        'phone' => $data['phone'],
                        'company_name' => $data['company_name'],
                        'designation' => $data['designation'],
                        'country_id' => $country_id,
                        'linkedin_link' => $data['linkedin_link'],
                        'message' => $data['message'],
                        'report_order_id' => $data['report_order_id']);

        
        $paymentMethod = ($data['payment_methods']!='') ? $data['payment_methods'] : '';

        switch($paymentMethod){
            case 'Stripe':
                $stripeSecretKey = getStripeKey();
                header('Content-Type: application/json');
                Stripe::setApiKey($stripeSecretKey);

                try{
                    $checkout_session = StripeSession::create([
                        'payment_method_types' => ['card'],
                        'line_items' => [
                            [
                                'price_data'  => [
                                    'product_data' => [
                                        'name' => $data['name'],
                                    ],
                                    'unit_amount'  => $price * 100,
                                    'currency'     => 'USD',
                                ],
                                'quantity'    => 1,
                                //'description' => '',
                            ],
                        ],
                        'mode' => 'payment',
                        'metadata' => $report_data,
                        'success_url' => url('stripePaymentSuccess').'?session_id={CHECKOUT_SESSION_ID}',
                        'cancel_url' => url('stripePaymentCancel').'?session_id={CHECKOUT_SESSION_ID}',
                    ]);

                    header("HTTP/1.1 303 See Other");
                    return redirect()->to($checkout_session->url);

                } catch (ApiErrorException $e) {
                    // update to report order table
                    $report_updated_data = array();
                    $report_updated_data['user_id'] = $user_id;
                    $report_updated_data['payment_status'] = 'Unpaid';

                    ReportOrders::where('id',$data['report_order_id'])->update($report_updated_data);
                    $report_order = ReportOrders::find($data['report_order_id']);

                    // send payment Failure mail to user and admin
                    try {
                        dispatch(new SendReportPaymentFailureEmail($report_order));
                    } catch (\Exception $e) {
                        Log::error('Error while sending report payment failure email.'.$e->getMessage());
                    }

                    $notification = ['message' => $e->getMessage(),'alert-class' => 'error'];
                    return redirect(route('report.buy',$slug))->withInput()->with($notification);                 
                } catch(Exception $e){
                    $notification = ['message' => $e->getMessage(),'alert-class' => 'error'];
                    return redirect(route('report.buy',$slug))->withInput()->with($notification);
                }
                break;

            case 'Razorpay':
                if(config('constants.RAZORPAY_MODE') == "test") {
                    $api = new Api(config('constants.RAZORPAY_KEY_TEST'), config('constants.RAZORPAY_SECRET_TEST'));
                } else {
                    $api = new Api(config('constants.RAZORPAY_KEY_LIVE'), config('constants.RAZORPAY_SECRET_LIVE'));
                }

                if(count($data)  && !empty($data['razorpay_payment_id'])){
                    try {
                        $payment = $api->payment->fetch($data['razorpay_payment_id']);
                        $response = $api->payment->fetch($data['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));

                        if(strtolower($response['status'])=='captured'){
                            // check user exists through email
                            if(($user_id=='') && isset($data['email'])){
                                $user = checkUserExist($data['email']);
                                if(isset($user)){
                                    $user_id = $user->id;
                                } else {
                                    // create user
                                    $password = generatePassword();
                                    $hasedPwd = \Hash::make($password);
                                    $user_data = array('user_name' => $data['name'],
                                                'email' => $data['email'],
                                                'phone' => $phonecode.$data['phone'],
                                                'company_name' => $data['company_name'],
                                                'password' => $hasedPwd);
                                    $user = User::create($user_data);

                                    if(isset($user)){
                                        $user_id = $user->id;
                                        $user_data['new_password'] = $password;
                                        dispatch(new SendNewUserCreateEmail($user_data));
                                    }
                                }
                            }
                            // update to report order table
                            $report_updated_data = array();
                            $report_updated_data['user_id'] = $user_id;
                            $report_updated_data['payment_status'] = 'Completed'; //$response['status'];
                            $report_updated_data['payment_id'] = $response['id'];

                            ReportOrders::where('id',$data['report_order_id'])->update($report_updated_data);
                            $report_order = ReportOrders::find($data['report_order_id']);

                            // send payment success mail to user and admin
                            try {
                                dispatch(new SendReportPaymentSuccessEmail($report_order));
                            } catch (\Exception $e) {
                                Log::error('Error while sending report payment success email.'.$e->getMessage());
                            }
                            $notification = ['message' => 'Your payment for this report has been done successfully!', 'alert-class' => 'success'];
                            return redirect(route('report.details',$slug))->with($notification);
                        } else {
                            // update to report order table
                            $report_updated_data = array();
                            $report_updated_data['user_id'] = $user_id;
                            $report_updated_data['payment_status'] = $response['status'];
                            $report_updated_data['payment_id'] = $response['id'];

                            ReportOrders::where('id',$data['report_order_id'])->update($report_updated_data);
                            $report_order = ReportOrders::find($data['report_order_id']);

                            // send payment Failure mail to user and admin
                            try {
                                dispatch(new SendReportPaymentFailureEmail($report_order));
                            } catch (\Exception $e) {
                                Log::error('Error while sending report payment failure email.'.$e->getMessage());
                            }
                            $notification = ['message' => 'Your payment for this report has been failed!', 'alert-class' => 'error'];
                            return redirect(route('report.buy',$slug))->withInput()->with($notification);
                        }
                    }
                    catch (\Exception $e) {
                        // update to report order table
                        $report_updated_data = array();
                        $report_updated_data['user_id'] = $user_id;
                        $report_updated_data['payment_status'] = 'Unpaid';

                        ReportOrders::where('id',$data['report_order_id'])->update($report_updated_data);
                        $report_order = ReportOrders::find($data['report_order_id']);

                        // send payment Failure mail to user and admin
                        try {
                            dispatch(new SendReportPaymentFailureEmail($report_order));
                        } catch (\Exception $e) {
                            Log::error('Error while sending report payment failure email.'.$e->getMessage());
                        }

                        $notification = ['message' => $e->getMessage(), 'alert-class' => 'error'];
                        return redirect(route('report.buy',$slug))->withInput()->with($notification);
                    }
                } else{
                    $notification = ['message' => "Something went wrong through Razorpay payment. Please try again!", 'alert-class' => 'error'];
                    return redirect(route('report.buy',$slug))->withInput()->with($notification);
                }
                break;

             case 'Paypal':

                if(count($data)  && !empty($data['orderData'])){
                    try {
                        $response = json_decode($data['orderData'], true);

                        if(count($response) && (strtolower($response['status'])=='completed')){
                            // check user exists through email
                            if(($user_id=='') && isset($data['email'])){
                                $user = checkUserExist($data['email']);
                                if(isset($user)){
                                    $user_id = $user->id;
                                } else {
                                    // create user
                                    $password = generatePassword();
                                    $hasedPwd = \Hash::make($password);
                                    $user_data = array('user_name' => $data['name'],
                                                'email' => $data['email'],
                                                'phone' => $phonecode.$data['phone'],
                                                'company_name' => $data['company_name'],
                                                'password' => $hasedPwd);
                                    $user = User::create($user_data);

                                    if(isset($user)){
                                        $user_id = $user->id;
                                        $user_data['new_password'] = $password;
                                        dispatch(new SendNewUserCreateEmail($user_data));
                                    }
                                }
                            }

                            // update to report order table
                            $report_updated_data = array();
                            $report_updated_data['user_id'] = $user_id;
                            $report_updated_data['payment_status'] = $response['status'];
                            $report_updated_data['payment_id'] = $response['id'];

                            ReportOrders::where('id',$data['report_order_id'])->update($report_updated_data);
                            $report_order = ReportOrders::find($data['report_order_id']);

                            // send payment success mail to user and admin
                            try {
                                dispatch(new SendReportPaymentSuccessEmail($report_order));
                            } catch (\Exception $e) {
                                Log::error('Error while sending report payment success email.'.$e->getMessage());
                            }
                            $notification = ['message' => 'Your payment for this report has been done successfully!', 'alert-class' => 'success'];
                            return redirect(route('report.details',$slug))->with($notification);
                        } else {
                            // update to report order table
                            $report_updated_data = array();
                            $report_updated_data['user_id'] = $user_id;
                            $report_updated_data['payment_status'] = $response['status'];
                            $report_updated_data['payment_id'] = $response['id'];

                            ReportOrders::where('id',$data['report_order_id'])->update($report_updated_data);
                            $report_order = ReportOrders::find($data['report_order_id']);

                            // send payment success mail to user and admin
                            try {
                                dispatch(new SendReportPaymentFailureEmail($report_order));
                            } catch (\Exception $e) {
                                Log::error('Error while sending report payment failure email.'.$e->getMessage());
                            }
                            $notification = ['message' => 'Your payment for this report has been failed!', 'alert-class' => 'error'];
                            return redirect(route('report.buy',$slug))->withInput()->with($notification);
                        }
                    }
                    catch (\Exception $e) {
                        // update to report order table
                        $report_updated_data = array();
                        $report_updated_data['user_id'] = $user_id;
                        $report_updated_data['payment_status'] = 'Unpaid';

                        ReportOrders::where('id',$data['report_order_id'])->update($report_updated_data);
                        $report_order = ReportOrders::find($data['report_order_id']);

                        // send payment Failure mail to user and admin
                        try {
                            dispatch(new SendReportPaymentFailureEmail($report_order));
                        } catch (\Exception $e) {
                            Log::error('Error while sending report payment failure email.'.$e->getMessage());
                        }
                        
                        $notification = ['message' => $e->getMessage(), 'alert-class' => 'error'];
                        return redirect(route('report.buy',$slug))->withInput()->with($notification);
                    }
                } else{
                    $notification = ['message' => "Something went wrong through Paypal payment. Please try again!", 'alert-class' => 'error'];
                    return redirect(route('report.buy',$slug))->withInput()->with($notification);
                }
                break;
        }
    }

    public function stripePaymentSuccess(Request $request)
    {
        $stripeSecretKey = getStripeKey();
        Stripe::setApiKey($stripeSecretKey);

        $session = StripeSession::retrieve($request->get('session_id'));

        $meta_data = $session->metadata;
        $report_id = isset($meta_data) ? $meta_data->report_id : '';

        $slug = '';
        if(isset($report_id)){
            $report = Report::where('id',$report_id)->where('is_active',1)->first();
            $slug = isset($report) ? $report->slug : '';        
        }

        if((strtolower($session->status)=='complete') && (strtolower($session->payment_status)=='paid'))
        {
            if(isset($meta_data)){
                // check user exists through email
                $user_id = (isset($meta_data->user_id) && $meta_data->user_id!='') ? $meta_data->user_id : 0;

                if(($user_id==0) && isset($meta_data->email)){
                    $user = checkUserExist($meta_data->email);
                    if(isset($user) && $user->count()>0){
                        $user_id = $user->id;
                    } else {
                        // create user
                        $password = generatePassword();
                        $hasedPwd = \Hash::make($password);
                        $user_data = array('user_name' => $meta_data->name,
                                        'email' => $meta_data->email,
                                        'phone' => $meta_data->phone,
                                        'company_name' => $meta_data->company_name,
                                        'password' => $hasedPwd);
                        $user = User::create($user_data);

                        if(isset($user)){
                            $user_id = $user->id;
                            $user_data['new_password'] = $password;
                            dispatch(new SendNewUserCreateEmail($user_data));
                        }
                    }
                }

                // update to report order table
                $report_updated_data = array();
                $report_updated_data['user_id'] = $user_id;
                $report_updated_data['payment_status'] = 'Completed'; //$session->payment_status;
                $report_updated_data['payment_id'] = $session->id;

                ReportOrders::where('id',$meta_data->report_order_id)->update($report_updated_data);
                $report_order = ReportOrders::find($meta_data->report_order_id);

                // send payment success mail to user and admin
                try {
                    dispatch(new SendReportPaymentSuccessEmail($report_order));
                } catch (\Exception $e) {
                    Log::error('Error while sending report payment success email.'.$e->getMessage());
                }
            }
            $notification = ['message' => 'Your payment for this report has been done successfully!', 'alert-class' => 'success'];
            return redirect(route('report.details',$slug))->with($notification);
        }else{
            $notification = ['message' => 'Something went wrong through stripe payment! Please try again!', 'alert-class' => 'error'];
            return redirect(route('report.buy',$slug))->withInput()->with($notification);
        }
    }

    public function stripePaymentCancel(Request $request)
    {
        $stripeSecretKey = getStripeKey();
        Stripe::setApiKey($stripeSecretKey);

        $session = StripeSession::retrieve($request->get('session_id'));

        $meta_data = $session->metadata;
        $report_id = isset($meta_data) ? $meta_data->report_id : '';

        $slug = '';
        if(isset($report_id)){
            $report = Report::where('id',$report_id)->where('is_active',1)->first();
            $slug = isset($report) ? $report->slug : '';
        }

        // update to report order table
        $report_updated_data = array();
        $report_updated_data['payment_status'] = $session->payment_status;
        $report_updated_data['payment_id'] = $session->id;
        ReportOrders::where('id',$meta_data->report_order_id)->update($report_updated_data);

        $notification = ['message' => 'Your payment for this report has been cancelled!', 'alert-class' => 'error'];
        return redirect(route('report.buy',$slug))->withInput()->with($notification);
    }
}
