<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Homepage;
use App\Models\HomepageModule;
use App\Models\Settings;
use App\Models\ClientFeedback;
use App\Models\CaseStudy;
use App\Models\Insight;
use App\Models\Award;
use App\Models\Service;
use App\Models\Sectors;
use App\Models\Appointment;
use App\Models\ContactUs;
use App\Models\Country;
use App\Models\PageNotFoundInquiry;
use App\Models\Sector;
use App\Models\IndustryGroup;
use App\Models\Industry;
use App\Models\SubIndustry;
use App\Models\Report;
use App\Models\User;
use App\Models\pages;

use App\Mail\BookAppointment as BookAppointmentMail;
use App\Mail\ContactUs as ContactUsMail;
use App\Mail\PageNotFoundInquiry as PageNotFoundInquiryMail;

use App\Jobs\SendContactUsEmail;
use App\Jobs\SendBookAppointmentEmail;
use App\Jobs\SendPageNotFoundInquiryEmail;

use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Mail,DB;
use Illuminate\Support\Facades\Log;
use App\Rules\ScriptPreventRule;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $title = config('metadata.home.pageTitle');
        $meta_title = config('metadata.home.title');
        $meta_description = config('metadata.home.description');

        $page = pages::where('slug','home')->first();
        $h1 = ($page) ? $page->h1 : ''; 
        $meta_title = ($page) ? $page->meta_title : '';
        $meta_description = ($page) ? $page->meta_description : '';
        $page_title = ($page) ? $page->page_title : '';
        $meta_keyword = ($page) ? $page->meta_keyword : '';

        $services = Service::where('is_active',1)->get();
        $sectorsData = Sectors::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();

        $reports = Report::where('is_active',1)->whereIN('report_type',array('SD','Dynamic'))->orderBy('updated_at', 'Desc')->limit(10)->get();

        $settings = Settings::first();
        $homepage = Homepage::first();

        $home_clientfeedbacks = HomepageModule::where('item_type', 'Feedback')->pluck('item_id')->toArray();
        $sel_clientfeedbacks = ClientFeedback::whereIn('id', $home_clientfeedbacks)->get();

        $home_casestudies = HomepageModule::where('item_type', 'Case Studies')->pluck('item_id')->toArray();
        $sel_casestudies = CaseStudy::whereIn('id', $home_casestudies)->get();

        $home_insights = HomepageModule::where('item_type', 'Insignts')->pluck('item_id')->toArray();
        $sel_insights = Insight::whereIn('id', $home_insights)->get();

        $home_awards = HomepageModule::where('item_type', 'Awads')->pluck('item_id')->toArray();
        $sel_awards = Award::whereIn('id', $home_awards)->get();

        $query_token = request()->token;

        $resetForm = isset($query_token) ? '1' : '';

        return view('front.home.index',compact('title','meta_keyword','page_title','meta_title','meta_description','services','sectorsData','sectors','settings','homepage','sel_clientfeedbacks',
        'sel_casestudies','sel_insights','sel_awards','request','resetForm','reports','page','h1'));
    }

    public function searchContent(Request $request)
    {
        $per_page_record = config('constants.PER_PAGE_SEARCH');
        $settings = Settings::first();
        $output = '';
        if($request->ajax()) {
            $search = trim($request->searchtxt);
            // check in insights
            $insights = new Insight;
            $insights = $insights->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
                $q->orWhere('description', 'LIKE', "%{$search}%");
                $q->orWhere('short_description', 'LIKE', "%{$search}%");
            });
            $insights = $insights->where('is_active',1);
            $insights = $insights->paginate($per_page_record);

            // check in casestudy
            $casestudies = new CaseStudy;
            $casestudies = $casestudies->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
                $q->orWhere('description', 'LIKE', "%{$search}%");
                $q->orWhere('short_description', 'LIKE', "%{$search}%");
            });
            $casestudies = $casestudies->where('is_active',1);
            $casestudies = $casestudies->paginate($per_page_record);

            // check in reports
            $reports = new Report;
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
            $reports = $reports->where('is_active',1);
            $reports = $reports->paginate($per_page_record);

            if(isset($reports) && ($reports->count()>0)){
                $output .= "<div class='list-view'>
                                <div class='list-title'>Reports</div>
                                <div class='list-content'>";
                                foreach($reports as $report){

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

                        $output .= "<a href='".url('report/'.$report->slug)."' class='list-text'>".$report_name."</a>";
                                }
                    $output .= "</div>
                            </div>";
            }

            if(isset($insights) && ($insights->count()>0)){
                $output .= "<div class='list-view'>
                                <div class='list-title'>Insights</div>
                                <div class='list-content'>";
                                foreach($insights as $insight){
                        $output .= "<a href='".url('insights/'.$insight->slug)."' class='list-text'>".$insight->name."</a>";
                                }
                    $output .= "</div>
                            </div>";
            }

            if(isset($casestudies) && ($casestudies->count()>0)){
                $output .= "<div class='list-view'>
                                <div class='list-title'>Case Studies</div>
                                <div class='list-content'>";
                                foreach($casestudies as $casestudy){
                        $output .= "<a href='".url('case-studies/'.$casestudy->slug)."' class='list-text'>".$casestudy->name."</a>";
                                }
                    $output .= "</div>
                            </div>";
            }

            if((isset($insights) && $insights->count()>0) || (isset($casestudies) && $casestudies->count()>0) || (isset($reports) && $reports->count()>0)){
                $output .= "<div class='text-center'>
                                <button type='submit' class='btn btn-blue'>View Full Result</button>
                            </div>";
            } else{
                $output .= "<h4 class='no-data-text text-center'>No Results Found</h4>";
            }

        }
        return response()->json(['output' => $output, 'success' => 1]);
    }

    public function searchPage(Request $request)
    {
        $per_page_record = config('constants.PER_PAGE_SEARCHLIST');

        $search = trim($request->searchtxt);
        $insights = '';
        $insightCount = '';
        $casestudies = '';
        $casestudiesCount = '';
        $reports = '';
        $reportsCount = '';

        $title = "Search";
        $meta_description = "";
        $page_title="";
        $meta_keyword="";
        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();

        if(isset($search) && ($search!='')){

            // check in insights
            $insights = new Insight;
            $insights = $insights->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
                $q->orWhere('description', 'LIKE', "%{$search}%");
                $q->orWhere('short_description', 'LIKE', "%{$search}%");
            });
            $insights = $insights->where('is_active',1);
            $insightCount = $insights->count();
            $insights = $insights->paginate($per_page_record);

            // check in casestudy
            $casestudies = new CaseStudy;
            $casestudies = $casestudies->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
                $q->orWhere('description', 'LIKE', "%{$search}%");
                $q->orWhere('short_description', 'LIKE', "%{$search}%");
            });
            $casestudies = $casestudies->where('is_active',1);
            $casestudiesCount = $casestudies->count();
            $casestudies = $casestudies->paginate($per_page_record);

            // check in reports
            $reports = new Report;
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
            $reports = $reports->where('is_active',1);
            $reportsCount = $reports->count();
            $reports = $reports->paginate($per_page_record);
        }

        return view('front.home.search',compact('title','page_title','meta_keyword','meta_description','services','sectors','insights','insightCount','casestudies','casestudiesCount','reports','reportsCount'));
    }

    public function searchPageList(Request $request)
    {
        $per_page_record = config('constants.PER_PAGE_SEARCHLIST');

        $search = trim($request->searchtxt);
        $type = trim($request->type);

        $insights = '';
        $insightCount = '';
        $casestudies = '';
        $casestudiesCount = '';
        $reports = '';
        $reportsCount = '';

        $title = "Search";
        $meta_description = "";
        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        $settings = Settings::first();

        if(isset($search) && ($search!='')){

            // check in insights
            $insights = new Insight;
            $insights = $insights->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
                $q->orWhere('description', 'LIKE', "%{$search}%");
                $q->orWhere('short_description', 'LIKE', "%{$search}%");
            });
            $insights = $insights->where('is_active',1);
            $insightCount = $insights->count();
            $insights = $insights->paginate($per_page_record);

            // check in casestudy
            $casestudies = new CaseStudy;
            $casestudies = $casestudies->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
                $q->orWhere('description', 'LIKE', "%{$search}%");
                $q->orWhere('short_description', 'LIKE', "%{$search}%");
            });
            $casestudies = $casestudies->where('is_active',1);
            $casestudiesCount = $casestudies->count();
            $casestudies = $casestudies->paginate($per_page_record);

            // check in reports
            $reports = new Report;
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
            $reports = $reports->where('is_active',1);
            $reportsCount = $reports->count();
            $reports = $reports->paginate($per_page_record);

            if ($request->ajax()) {
                $html = '';

                if($type=='reportsTab'){
                    if(!$reports->isEmpty()){
                        foreach ($reports as $report) {
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

                            $html .= "<div class='sr-containt'>
                                <a href='".url('report/'.$report->slug)."' class='report-img'>
                                    <img src='".str_replace('/upload/','/upload/c_thumb,w_150,h_190,q_60,g_face/',$report->image_url)."' width='150' height='190' alt='".$report->image_alt."'>
                                </a>
                                <div class='report-segment-data'>
                                    <a href='".url('report/'.$report->slug)."' class='title'>".$report_name."</a>
                                    <p>'". \Illuminate\Support\Str::limit(strip_tags($report->market_insights), 150, $end=' ...')."'</p>";
                                if(isset($report->publish_date)){
                                $html .= "<p class='date'>".convertUtcToIst($report->publish_date, config('constants.DISPLAY_SEARCH_REPORT_DATE'))."</p>";
                                }
                                $html .= "<div><a href='".url('report/'.$report->slug)."' class='read-more-btn'>Read More <i class='fas fa-chevron-right'></i></a></div>
                                </div>
                            </div>
                            <hr class='hr-tag'>";
                        }
                    }
                }

                if($type=='insightsTab'){
                    if(!$insights->isEmpty()){
                        foreach ($insights as $insight) {
                            $html .= "<div class='sr-containt'>
                                    <a href='".url('insights/'.$insight->slug)."' class='report-img'>
                                        <img src='".str_replace('/upload/','/upload/c_thumb,w_150,h_190,q_60,g_face/',$insight->image_url)."' width='150' height='190' alt='".$insight->image_alt."'>
                                    </a>
                                    <div class='report-segment-data'>
                                        <a href='".url('insights/'.$insight->slug)."' class='title'>".$insight->name."</a>";
                                        if(isset($insight->short_description)){
                                            $html .= "<p>".\Illuminate\Support\Str::limit(strip_tags($insight->short_description), 150, $end=' ...')."</p>";
                                        }
                                        else {
                                            $html .= "<p>".\Illuminate\Support\Str::limit(strip_tags($insight->description), 150, $end=' ...')."</p>";
                                        }
                                        if(isset($insight->publish_date)){
                                        $html .= "<p class='date'>".convertUtcToIst($insight->publish_date, config('constants.DISPLAY_SEARCH_REPORT_DATE'))."</p>";
                                        }
                                        $html .= "<div><a href='".url('insights/'.$insight->slug)."' class='read-more-btn'>Read More <i class='fas fa-chevron-right'></i></a></div>
                                    </div>
                                </div>
                            <hr class='hr-tag'>";
                        }
                    }
                }

                if($type=='caseStudiesTab'){
                    if(!$casestudies->isEmpty()){
                        foreach ($casestudies as $casestudy) {
                            $html .= "<div class='sr-containt'>
                                <a href='".url('case-studies/'.$casestudy->slug)."' class='report-img'>
                                    <img src='".str_replace('/upload/','/upload/c_thumb,w_150,h_190,q_60,g_face/',$casestudy->image_url)."' width='150' height='190' alt='".$casestudy->image_alt."'>
                                </a>
                                <div class='report-segment-data'>
                                    <a href='".url('case-studies/'.$casestudy->slug)."' class='title'>".$casestudy->name."</a>";
                                    if(isset($casestudy->short_description)){
                                        $html .= "<p>".\Illuminate\Support\Str::limit(strip_tags($casestudy->short_description), 150, $end=' ...')."</p>";
                                    }else{
                                        $html .= "<p>".\Illuminate\Support\Str::limit(strip_tags($casestudy->description), 150, $end=' ...')."</p>";
                                    }
                                    $html .= "<div><a href='".url('case-studies/'.$casestudy->slug)."' class='read-more-btn'>Read More <i class='fas fa-chevron-right'></i></a></div>
                                </div>
                            </div>
                        <hr class='hr-tag'>";
                        }
                    }
                }

                return $html;
            }
        }

        return view('front.home.search',compact('title','meta_description','services','sectors','insights','insightCount','casestudies','casestudiesCount','reports','reportsCount'));
    }

    public function contactPage()
    {
        $title = config('metadata.contact-us.pageTitle');
        $meta_title = config('metadata.contact-us.title');
        $meta_description = config('metadata.contact-us.description');
       
        $page = pages::where('slug','contact-us')->first();
        $h1 = ($page) ? $page->h1 : '';
        $meta_title = ($page) ? $page->meta_title : '';
        $meta_description = ($page) ? $page->meta_description : '';
        $page_title = ($page) ? $page->page_title : '';
        $meta_keyword = ($page) ? $page->meta_keyword : '';

        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        return view('front.contact-us.index',compact('title','meta_title','meta_description','services','sectors','h1','page_title','meta_keyword'));
    }

    public function bookAppointment(Request $request)
    {
        $request->validate([
            'appointment_time' => 'required',
            'name' => ['required', new ScriptPreventRule()],
            'phone' => 'required',
            'email' => 'required|email:filter',
            'company_name' => ['required', new ScriptPreventRule()],
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'The captcha field is required.',
            'g-recaptcha-response.captcha' => 'Invalid captcha',
        ]);

        $data = $request->except(['_token','hiddenRecaptcha']);

        // update fields to users table
        if(Auth::check()){
            $user = auth('web')->user();
            if($user){
                $user_data = array('user_name' => $data['name'],
                                'email' => $data['email'],
                                'phone' => $data['phone'],
                                'company_name' => $data['company_name']);
                User::where('id',$user->id)->update($user_data);
            }
        }

        $formatDate = ($request->appointment_time!='') ? str_replace(",","",str_replace("/","-",$request->appointment_time)) : NULL;

        $d1 = date("Y-m-d", strtotime($formatDate));
        $d2 = date("Y-m-d");
        if($d1 < $d2)
        {
            throw ValidationException::withMessages([
                'appointment_time' => 'Please select valid appointment date and time',
            ]);
        }

        $data['appointment_time'] = ($formatDate!=NULL) ? date("Y-m-d H:i:s", strtotime($formatDate)) : NULL;

        $appointment = Appointment::create($data);

        try {
            // dispatch your queue job
            dispatch(new SendBookAppointmentEmail($appointment));
        } catch (\Exception $e) {
            Log::error('Error while sending book an appointment email.'.$e->getMessage());
        }
        $notification = ['message' => 'Your appointment request has been send successfully!', 'alert-class' => 'success'];
        return redirect()->route('contact-us')->with($notification);
    }

    public function aboutPage()
    {
        $title = config('metadata.about-us.pageTitle');
        $meta_title = config('metadata.about-us.title');
        $meta_description = config('metadata.about-us.description');

        $page = pages::where('slug','about-us')->first();
        $h1 = ($page) ? $page->h1 : ''; 
        $meta_title = ($page) ? $page->meta_title : '';
        $meta_description = ($page) ? $page->meta_description : '';
        $page_title = ($page) ? $page->page_title : '';
        $meta_keyword = ($page) ? $page->meta_keyword : '';

        $services = Service::where('is_active',1)->get();
        $settings = Settings::first();
        $sectors = Sector::where('is_active',1)->get();
        return view('front.about-us.index',compact('title','meta_title','meta_description','services','settings','sectors','h1','page_title','meta_keyword'));
    }

    public function saveContactUs(Request $request)
    {
        $request->validate([
            'name' => ['required', new ScriptPreventRule()],
            'phone' => 'required',
            'email' => 'required|email:filter',
            'company_name' => ['required', new ScriptPreventRule()],
            'subject' => ['required', new ScriptPreventRule()],
            'message' => ['required', new ScriptPreventRule()],
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'The captcha field is required.',
            'g-recaptcha-response.captcha' => 'Invalid captcha',
        ]);

        $data = $request->except(['_token','hiddenRecaptcha']);

        // update fields to users table
        if(Auth::check()){
            $user = auth('web')->user();
            if($user){
                $user_data = array('user_name' => $data['name'],
                                'email' => $data['email'],
                                'phone' => $data['phone'],
                                'company_name' => $data['company_name']);
                User::where('id',$user->id)->update($user_data);
            }
        }

        $contactus = ContactUs::create($data);

        try {
            // dispatch your queue job
            dispatch(new SendContactUsEmail($contactus));
        } catch (\Exception $e) {
            Log::error('Error while sending contact us email.'.$e->getMessage());
        }
        $notification = ['message' => 'Your contact us request has been send successfully!', 'alert-class' => 'success'];
        return redirect()->route('about-us')->with($notification);
    }

    public function pagenotfound()
    {
        $title = config('metadata.default.pageTitle');
        $meta_title = config('metadata.default.title');
        $meta_description = config('metadata.default.description');

        $page = pages::where('slug','404')->first();
        $h1 = ($page) ? $page->h1 : '';
        $meta_title = ($page) ? $page->meta_title : '';
        $meta_description = ($page) ? $page->meta_description : '';
        $page_title = ($page) ? $page->page_title : '';
        $meta_keyword = ($page) ? $page->meta_keyword : '';

        $services = Service::where('is_active',1)->get();
        $countries = Country::all();
        $sectors = Sector::where('is_active',1)->get();
        return view('errors.404',compact('title','meta_title','meta_description','services','countries','sectors','title','page','h1','page_title','meta_keyword'));
    }

    public function savePageNotFound(Request $request)
    {
        $request->validate([
            'name' => ['required', new ScriptPreventRule()],
            'phone' => 'required',
            'email' => 'required|email:filter',
            'country_id' => 'required',
            'company_name' => ['required', new ScriptPreventRule()],
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'The captcha field is required.',
            'g-recaptcha-response.captcha' => 'Invalid captcha',
        ]);

        if(!domain_exists($request->email)) {
            return redirect()->back()->withInput()->withErrors([
                'email' => 'Invalid email address!',
            ]);
        }
        $data = $request->except(['_token','hiddenRecaptcha']);

        // update fields to users table
        if(Auth::check()){
            $user = auth('web')->user();
            if($user){
                $user_data = array('user_name' => $data['name'],
                                'email' => $data['email'],
                                'phone' => $data['phone'],
                                'company_name' => $data['company_name']);
                User::where('id',$user->id)->update($user_data);
            }
        }

        $pagenotfound = PageNotFoundInquiry::create($data);

        try {
            // dispatch your queue job
            dispatch(new SendPageNotFoundInquiryEmail($pagenotfound));
        } catch (\Exception $e) {
            Log::error('Error while sending 404 page not found email.'.$e->getMessage());
        }
        $notification = ['message' => 'Your page not found request has been send successfully!', 'alert-class' => 'success'];
        return redirect()->back()->with($notification);
    }

    public function privacyPage()
    {
        $title = config('metadata.default.pageTitle');
        $meta_title = config('metadata.default.title');
        $meta_description = config('metadata.default.description');
        $h1 = "";
        $page = pages::where('slug','privacy')->first();
        $h1 = ($page) ? $page->h1 : '';
        $meta_title = ($page) ? $page->meta_title : '';
        $meta_description = ($page) ? $page->meta_description : '';
        $page_title = ($page) ? $page->page_title : '';
        $meta_keyword = ($page) ? $page->meta_keyword : '';
        // dd($meta_description);
        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        return view('front.cms.privacy',compact('title','meta_title','meta_description','services','sectors','page','h1','page_title','meta_keyword'));
    }

    public function cookiesPage()
    {
        $title = config('metadata.default.pageTitle');
        $meta_title = config('metadata.default.title');
        $meta_description = config('metadata.default.description');

        $page = pages::where('slug','cookies')->first();
        $h1 = ($page) ? $page->h1 : '';
        $meta_title = ($page) ? $page->meta_title : '';
        $meta_description = ($page) ? $page->meta_description : '';
        $page_title = ($page) ? $page->page_title : '';
        $meta_keyword = ($page) ? $page->meta_keyword : '';

        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        return view('front.cms.cookies',compact('title','meta_title','meta_description','services','sectors','page','h1','page_title','meta_keyword'));
    }
}
