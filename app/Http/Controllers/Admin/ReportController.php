<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use App\Models\UsersBookmark;
use App\Models\ReportSampleRequest;
use App\Models\ReportInquiry;
use App\Models\ReportSubscribeNow;
use App\Models\ReportOrders;
use Illuminate\Support\Facades\Storage;
use App\Imports\ReportGraphsImport;
use App\Imports\PublishImport;
use App\Imports\UpcomingReportImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Jobs\UpdateAllReportPricing;
use App\Jobs\UpdateAllReportMetrics;
use File,DB;
use Carbon\Carbon;

use App\Models\CSIGraphs;
use App\Models\CSIReports;
use App\Models\CSISectormodel;
use App\Models\CSIIndustrygroupmodel;
use App\Models\CSIIndustrymodel;
use App\Models\CSISubindustrymodel;
use App\Models\AuthenticationBasemodel;
use App\Models\CSIUpcomingreports;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:report-list|report-add|report-edit|report-delete|report-import,admin', ['only' => ['index']]);
        $this->middleware('permission:report-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:report-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:report-delete,admin', ['only' => ['destroy']]);
        $this->middleware('permission:report-import,admin', ['only' => ['import']]);
        $this->middleware('permission:report-pricing,admin', ['only' => ['reportPricing', 'updateReportPricing']]);
        $this->middleware('permission:report-forecast-settings,admin', ['only' => ['reportForecastSettings','updateReportForecastSettings']]);
    }

    public function index()
	{
		$title = 'Reports';
		return view('admin.report.index', compact('title'));
	}

    public function ajax(Request $request)
	{
        $user = auth('admin')->user();
        
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        
        $reports = new Report;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $reports = $reports->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
                $q->orWhere('product_id', 'LIKE', "%{$search}%");
                $q->orWhere('report_type', 'LIKE', "%{$search}%");
                $q->orWhere('publish_date', 'LIKE', "%{$search}%");
                $q->orWhere('publish_date', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
            });
        }
        if($request->report_type){
            $reports = $reports->where(function ($q) use ($request) {
                $q->where('report_type', $request->report_type);
            });
        }
        if (!is_null($request->is_active)) {
            $reports = $reports->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $reports = $reports->orderBy($request->sort_by, $request->sort_order);
        } else {
            $reports = $reports->orderBy('id', 'Desc');
        }
        $reportCount = $reports->count();
        $reports = $reports->paginate($per_page_record); 
        return view('admin.report.pagination', compact('reports', 'request','reportCount'));	
	}

    public function edit($id)
	{
		$report = Report::find($id);
        if(!$report) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Report Details';
        $sectors = Sector::where('is_active','1')->get();
        $industryGroups = IndustryGroup::where('is_active','1')->get();
        $industries = Industry::where('is_active','1')->get();
        $subIndustries = SubIndustry::where('is_active','1')->get();
        $reportPricing = ReportPricing::where('report_id',$id)->get();
        $reportSegments = ReportSegment::where('report_id',$id)->get();
        $reportFaqs = ReportFaq::where('report_id',$id)->get();
        return view('admin.report.add',compact('title','report','sectors','industryGroups','industries','subIndustries','reportPricing','reportSegments','reportFaqs'));
	}

    public function create()
    {
        $title = 'Create Report Details';
        $sectors = Sector::where('is_active','1')->get();
        $industryGroups = IndustryGroup::where('is_active','1')->get();
        $industries = Industry::where('is_active','1')->get();
        $subIndustries = SubIndustry::where('is_active','1')->get();
        // $reportPricing = ReportPricing::where('report_id',$id)->get();
        // $reportSegments = ReportSegment::where('report_id',$id)->get();
        // $reportFaqs = ReportFaq::where('report_id',$id)->get();
        return view('admin.report.add_new',compact('title','sectors','industryGroups','industries','subIndustries'));   
    }

    public function store(Request $request)
    {
        dd($request->all());
    }

    public function getIndustryData(Request $request)
    {
        $entity_id = $request->entity_id;
        $type = $request->type;
        $selected = $request->selected;

        $html = '';
        if(isset($entity_id) && isset($type)){
            switch($type){
                case 'industry_group':
                    $industryGroups = IndustryGroup::where('sector_id',$entity_id)->where('is_active',1)->get();
                    if(!$industryGroups->isEmpty()){
                        $html .= "<option value=''>Select Industry Group</option>";
                        foreach ($industryGroups as $industryGroup){       
                            if(isset($selected) && ($industryGroup->id==$selected)){
                                $html .= "<option value='".$industryGroup->id."' selected>".$industryGroup->title."</option>";
                            } else{
                                $html .= "<option value='".$industryGroup->id."'>".$industryGroup->title."</option>";
                            }
                        }
                    }
                    break;

                case 'industry':
                    $industries = Industry::where('industry_group_id',$entity_id)->where('is_active',1)->get();
                    if(!$industries->isEmpty()){
                        $html .= "<option value=''>Select Industry</option>";
                        foreach ($industries as $industry){                
                            if(isset($selected) && $industry->id==$selected){
                                $html .= "<option value='".$industry->id."' selected>".$industry->title."</option>";
                            } else{
                                $html .= "<option value='".$industry->id."'>".$industry->title."</option>";
                            }
                        }
                    }
                    break;

                case 'sub_industry':
                    $sub_industries = SubIndustry::where('industry_id',$entity_id)->where('is_active',1)->get();
                    if(!$sub_industries->isEmpty()){
                        $html .= "<option value=''>Select Sub Industry</option>";
                        foreach ($sub_industries as $sub_industry){          
                            if(isset($selected) && $sub_industry->id==$selected){      
                                $html .= "<option value='".$sub_industry->id."' selected>".$sub_industry->title."</option>";
                            } else{
                                $html .= "<option value='".$sub_industry->id."'>".$sub_industry->title."</option>";
                            }
                        }
                    }
                    break;
            }
            
        }
        return $html;
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:reports,id',
            'name' => 'required',
            'slug' => 'required',
            'image_alt' => 'required',
            's_c' => 'required',
            'download' => 'required',
            'pages' => 'required',
            'meta_title' => 'required',
            'meta_description' => 'required',
            'sector_id' => 'required',
            'industry_group_id' => 'required',
            'industry_id' => 'required',
            'sub_industry_id' => 'required',
            'license_type' => 'required',
            'file_type' => 'required',
            'price' => 'required',
            'description' => 'required_if:report_type,Dynamic',
            'toc' => 'required_if:report_type,Dynamic',
            'whats_included' => 'required_if:report_type,SD,Dynamic',
            'methodologies' => 'required_if:report_type,SD,Dynamic',
            'analyst_support' => 'required_if:report_type,SD,Dynamic',
            'market_insights' => 'required_if:report_type,SD,Upcoming',
            'segmental_analysis' => 'required_if:report_type,SD',
            'regional_insights' => 'required_if:report_type,SD',
            'market_dynamics' => 'required_if:report_type,SD',
            'competitive_landscape' => 'required_if:report_type,SD',
            'key_market_trends' => 'required_if:report_type,SD',
            'skyQuest_analysis' => 'required_if:report_type,SD',
        ]);

        $data = $request->except(['_token','_method','toc','license_type','file_type','price','report_pricing_id','segment_name','sub_segment_name','image','report_segment_id','report_segment_counter','faq_question','faq_answer','report_faq_id']);
        
        $report = Report::find($request->id);
        
        // check slug already exists 
        if($report->slug != $data['slug']){
            $reportSlug = checkReportSlug($data['slug'], '', $report->id);           
            
            if(isset($reportSlug) && ($reportSlug->count() > 0)){
                $notification = ['message' => 'Report slug already exists.','alert-class' => 'error'];
		        return redirect()->route('admin.report.index')->with($notification);
            }
        }

        switch($report->s_c)
        {
            case 'syndicate':
            case 'Syndicate':
                $s_c =  'Syndicate';
                break;
            
            case 'MI':
            case 'Market Intelligence':
                $s_c =  'Market Intelligence';
                break;     

            case 'Competitor Intelligence':
            case 'CI':
                $s_c = 'Competitor Intelligence';
                break;

            case 'Supplier Intelligence':
            case 'SI':
                $s_c = 'Supplier Intelligence';
                break;

            case 'Open Innovation':
            case 'OI':
                $s_c = 'Open Innovation';
                break;
        }
        
        if(($report->country!=$data['country']) || ($report->s_c!=$data['s_c']) || ($report->sector_id!=$data['sector_id']) || ($report->industry_group_id!=$data['industry_group_id']) || ($report->industry_id!=$data['industry_id']) || ($report->sub_industry_id!=$data['sub_industry_id'])){
            // generate product_id or SKU
            $country_code = '';
            $sector_code = '';
            $sub_industry_initial = '';
            $report_count = '';
            
            $report_country = $data['country'];
            $report_sector_id = $data['sector_id'];
            $report_sub_industry_id = $data['sub_industry_id'];
            $sc = $data['s_c'];

            $country_code = ($report_country!='') ? substr($report_country,0,1) : '';
            if($report_sector_id!=''){
                $sectorData = Sector::where('id',$report_sector_id)->pluck('code');            
                $sector_code = $sectorData[0];
            }  
            if($report_sub_industry_id!=''){
                $SubIndustryData = SubIndustry::where('id',$report_sub_industry_id)->pluck('initial');
                $sub_industry_initial = $SubIndustryData[0];
            }
            $combination = $sector_code.$sub_industry_initial;
            $report_count = $this->getReportCount($report_sub_industry_id,$report->report_type,$combination,$report->id);
    
            $sc_code = 'MI';

            if($sc!=''){
                switch($sc){
                    case 'Market intelligence':
                        $sc_code = 'MI';
                        break;

                    case 'Competitor Intelligence':
                        $sc_code = 'CI';
                        break;
                    
                    case 'Supplier Intelligence':
                        $sc_code = 'SI';
                        break;

                    case 'Open Innovation':
                        $sc_code = 'OI';
                        break;
                }
            }

            if($report->report_type=='Upcoming'){
                $companyCode = "UC";
            }else{
                $companyCode = "SQ";
            }

            $sku = $companyCode.$sc_code.$country_code.$sector_code.$sub_industry_initial.$report_count;
            
            $isSKUExist = checkReportSKU($sku,$report->id);
            if(isset($isSKUExist) && ($isSKUExist->count() > 0)){
                $notification = ['message' => $sku.' already exists!','alert-class' => 'error'];
		        return redirect()->route('admin.report.index')->with($notification);
            } else{
                $data['product_id'] = $sku;
            }
        }
        // image upload
        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.REPORT_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
                // remove older image if any
                if($report->image) {
                    cloudinary()->destroy($report->image_id);
                }
            } catch (\Exception $e) {

            }
        }
        //pricing
        if($request->has('report_pricing_id')){
            for($i=0;$i<count($request->report_pricing_id);$i++){
                $reportPriceId = $request->report_pricing_id[$i];
                $licenseType = $request->license_type[$i];
                $fileType = $request->file_type[$i];
                $price = $request->price[$i];
                $reportPrice = ReportPricing::find($reportPriceId);
                if($reportPrice){
                    $reportPrice->license_type = $licenseType;
                    $reportPrice->file_type = $fileType;
                    $reportPrice->price = $price;
                    $reportPrice->save();
                }
            }
        }
        //segment
        if($request->has('segment_name')){
            for($i=0;$i<count($request->segment_name);$i++){
                $segmentName = $request->segment_name[$i];
                $reportSegmentId = $request->report_segment_id[$i];
                $reportSegmentCounter = $request->report_segment_counter[$i];
                $reportSegment = ReportSegment::find($reportSegmentId);
                if(isset($segmentName)){
                    if($reportSegment){
                        $sgementValue = '';
                        if($request->has('sub_segment_name') && isset($request->sub_segment_name[$reportSegmentId])){
                            $sub_segment_name_update = array_filter($request->sub_segment_name[$reportSegmentId]);
                            $sgementValue = implode(', ',$sub_segment_name_update);                        
                        }
                        $reportSegment->value = $sgementValue;
                        $reportSegment->name = $segmentName;
                        $reportSegment->save();                    
                    } else {
                        $reportSegment = new ReportSegment();
                        $reportSegment->name = $segmentName;
                        $reportSegment->report_id = $request->id;
                        $sgementValue = '';
                        if($request->has('sub_segment_name') && isset($request->sub_segment_name[$reportSegmentCounter])){
                            $sub_segment_name_new = array_filter($request->sub_segment_name[$reportSegmentCounter]);
                            $sgementValue = implode(', ',$sub_segment_name_new);
                        }
                        $reportSegment->value = $sgementValue;
                        $reportSegment->save();
                    }
                }
            }
        }
        //faqs
        if($request->has('faq_question')){
            for($i=0;$i<count($request->faq_question);$i++){
                $faqQue = isset($request->faq_question[$i]) ? $request->faq_question[$i] : '';
                $faqAns = isset($request->faq_answer[$i]) ? $request->faq_answer[$i] : '';
                $reportFaqId = isset($request->report_faq_id[$i]) ? $request->report_faq_id[$i] : '';
                $reportFaq = ReportFaq::find($reportFaqId);
                
                if($reportFaq){
                    $reportFaq->faq_question = $faqQue;
                    $reportFaq->faq_answer = $faqAns;
                    $reportFaq->save();
                } else {
                    $reportFaq = new ReportFaq();
                    $reportFaq->report_id = $request->id;
                    $reportFaq->faq_question = $faqQue;
                    $reportFaq->faq_answer = $faqAns;
                    $reportFaq->is_auto = '0';
                    $reportFaq->save();
                }
            }
        }
        Report::where('id',$request->id)->update($data);

        // automation for SD type - starts
        if($report->report_type=='SD'){

            $report_id = $request->id;

            $segmentArr = array();
            $segments = ReportSegment::where('report_id',$report_id)->get();
            $segmentArr = $segments->toArray();
                
            $report_name = $data['name'];
            $market_insights = $data['market_insights'];
            $competitive_landscape = $data['competitive_landscape'];
            $market_dynamics = $data['market_dynamics'];
            $key_market_trends = $data['key_market_trends'];
            $regional_insights = $data['regional_insights'];

            
            // insert into metrics table
            $metrics = array();            
            if(isset($market_insights) && $market_insights!=''){
                $market_insights_para = $this->getFirstPTag($market_insights);
                $contentArr = explode(" ",$market_insights_para);
                if(is_array($contentArr) && count($contentArr) >0) {
                    if(strpos($market_insights_para, "in")) {          
                        $startyear_key = array_search("in",$contentArr);
                        $metrics['startyear'] = ($startyear_key!='') ? rtrim($contentArr[$startyear_key+1],",") : '';
                    }

                    if(strpos($market_insights_para, "CAGR")) {                                               
                        $growth_rate = trim($this->string_between_two_string($market_insights_para, 'CAGR of', '%'));
                        $metrics['growth_rate'] = $growth_rate."%";
                    }

                    if(strpos($market_insights_para, "period")) {
                        $metrics['forecast_period'] = rtrim(ltrim(strip_tags(trim($this->string_between_two_string($market_insights_para, 'period', '.'))),"("),")");
                    }
                }

                if(strpos($market_insights_para, "by")) {
                    $endyear_pos = strpos($market_insights_para,"by");
                    $metrics['endyear']  = substr($market_insights_para, $endyear_pos+3, 4);
                }

                if(strpos($market_insights_para, "valued")) {
                    $startyear_size = trim($this->string_between_two_string($market_insights_para, 'valued', 'in'));
                    $startyear_arr = explode(" ",$startyear_size);                        
                    $startyear_key = array_search("USD",$startyear_arr);
                    $startyear_size = '';
                    if($startyear_key){
                        if(isset($startyear_arr[$startyear_key]))
                            $startyear_size = $startyear_arr[$startyear_key];
                        if(isset($startyear_arr[$startyear_key+1]))
                            $startyear_size .= ' '.$startyear_arr[$startyear_key+1];
                        if(isset($startyear_arr[$startyear_key+2]))
                            $startyear_size .= ' '.$startyear_arr[$startyear_key+2];
                    }
                    $metrics['startyear_size'] = $startyear_size;
                }

                if((strpos($market_insights_para, "USD")!='') && (strpos($market_insights_para, "by")!='')) {
                    $end_year_size = $this->string_between_two_string($market_insights_para, 'USD', 'by');                            
                    $end_year_arr = explode(" ",$end_year_size);
                    //$end_year_key = array_search("USD",$end_year_arr);
                    $end_year_array_keys = array_keys($end_year_arr, "USD");
                    $end_year_key = $end_year_array_keys[1];
                    $endyear_size = '';
                    if(isset($end_year_arr[$end_year_key+1]) && (isset($end_year_arr[$end_year_key+2]))){
                        $endyear_size .= $end_year_arr[$end_year_key].' '.$end_year_arr[$end_year_key+1].' '.$end_year_arr[$end_year_key+2];
                    }
                    $metrics['endyear_size'] = $endyear_size;
                    $forecast_unit = '';
                    if(isset($end_year_arr[$end_year_key+2])){
                        $forecast_unit = $end_year_arr[$end_year_key].' '.ucfirst($end_year_arr[$end_year_key+2]);
                    }
                    $metrics['forecast_unit'] = $forecast_unit;
                }
            }
            
            if(isset($competitive_landscape) && $competitive_landscape!=''){                 
                $string = $this->string_between_two_string($competitive_landscape,'Player','Recent Development');
                
                $start1 = strpos($string,'<ul>');
                $end1 = strrpos($string, '</ul>', $start1);
                $sub_str = substr($string, $start1, $end1-$start1);                
                $metrics['companies_covered'] = $sub_str;
            }
            
            if(count($metrics)>0){

                // delete old metrics data for this report
                ReportMetrics::where('report_id',$report_id)->delete();

                if(isset($metrics['startyear'])) {
                    $metricsArr = array();
                    $metricsArr['report_id'] = $report_id;
                    $metricsArr['meta_key'] = 'Market size value in '.$metrics['startyear'];
                    $metricsArr['meta_value'] = isset($metrics['startyear_size']) ? $metrics['startyear_size'] : '';            
                    ReportMetrics::create($metricsArr);
                }

                if(isset($metrics['endyear'])) {
                    $metricsArr = array();
                    $metricsArr['report_id'] = $report_id;
                    $metricsArr['meta_key'] = 'Market size value in '.$metrics['endyear'];
                    $metricsArr['meta_value'] = isset($metrics['endyear_size']) ? $metrics['endyear_size'] : '';                    
                    ReportMetrics::create($metricsArr);
                }

                if(isset($metrics['growth_rate'])) {
                    $metricsArr = array();
                    $metricsArr['report_id'] = $report_id;
                    $metricsArr['meta_key'] = 'Growth Rate';
                    $metricsArr['meta_value'] = $metrics['growth_rate'];                    
                    ReportMetrics::create($metricsArr);
                }

                if(isset($metrics['startyear'])) {
                    $metricsArr = array();
                    $metricsArr['report_id'] = $report_id;
                    $metricsArr['meta_key'] = 'Base year';
                    $metricsArr['meta_value'] = $metrics['startyear'];                    
                    ReportMetrics::create($metricsArr);
                }

                if(isset($metrics['forecast_period'])) {
                    $metricsArr = array();
                    $metricsArr['report_id'] = $report_id;
                    $metricsArr['meta_key'] = 'Forecast period';
                    $metricsArr['meta_value'] = $metrics['forecast_period'];                    
                    ReportMetrics::create($metricsArr);
                }

                if(isset($metrics['forecast_unit'])) {
                    $metricsArr = array();
                    $metricsArr['report_id'] = $report_id;
                    $metricsArr['meta_key'] = 'Forecast Unit (Value)';
                    $metricsArr['meta_value'] = $metrics['forecast_unit'];                    
                    ReportMetrics::create($metricsArr);
                }
                            
                if(isset($segmentArr) && (count($segmentArr) > 0)) {
                    $segmentDataArr = '<ul>';
                    for($k=0;$k<count($segmentArr);$k++){
                        $segmentDataArr .= '<li>'.$segmentArr[$k]['name'];
                        $segmentDataArr .= '<ul>';
                        $segmentDataArr .= '<li>'.$segmentArr[$k]['value'].'</li>';
                        $segmentDataArr .= '</ul>';
                        $segmentDataArr .= '</li>';
                    }
                    $segmentDataArr .= '</ul>';

                    $metricsArr = array();
                    $metricsArr['report_id'] = $report_id;
                    $metricsArr['meta_key'] = 'Segments covered';
                    $metricsArr['meta_value'] = $segmentDataArr;              
                    ReportMetrics::create($metricsArr);
                }

                // for Regions covered
                $regions_covered = "North America (US, Canada), Europe (Germany, France, United Kingdom, Italy, Spain, Rest of Europe), Asia Pacific (China, India, Japan, Rest of Asia-Pacific), Latin America (Brazil, Rest of Latin America), Middle East & Africa (South Africa, GCC Countries, Rest of MEA)";
                $metricsArr = array();
                $metricsArr['report_id'] = $report_id;
                $metricsArr['meta_key'] = 'Regions covered';
                $metricsArr['meta_value'] = $regions_covered;
                ReportMetrics::create($metricsArr);

                // for Companies covered
                if(isset($metrics['companies_covered'])) {
                    $metricsArr = array();
                    $metricsArr['report_id'] = $report_id;
                    $metricsArr['meta_key'] = 'Companies covered';
                    $metricsArr['meta_value'] = $metrics['companies_covered'];                    
                    ReportMetrics::create($metricsArr);
                }

                // for Customization scope            
                $customization_scope = '<p>Free report customization with purchase. Customization includes:-
                <ul><li>Segments by type, application, etc</li><li>Company profile</li><li>Market dynamics & outlook</li><li>Region</li></ul></p>';            
                $metricsArr = array();
                $metricsArr['report_id'] = $report_id;
                $metricsArr['meta_key'] = 'Customization scope';
                $metricsArr['meta_value'] = $customization_scope;                    
                ReportMetrics::create($metricsArr);                
            }

            // insert into report_faq
            $faq = array();
            // Faq Q1 - first para of Market Insights
            $faq_answer1 = '';
            if(isset($market_insights)){                
                $faq_answer1 = $this->getFirstPTag($market_insights);
            }
            $faq[0]['faq_question'] = "What is the global market size of ";
            $faq[0]['faq_answer'] = $faq_answer1;

            // Faq Q2 - first para and top player company of Competitive Landscape                 
            $faq_answer2 = '';
            if(isset($competitive_landscape)){ 
                $faq_answer2 = $this->getFirstPTag($competitive_landscape);
                //$faq_answer2 .= $this->getTopPlayerCompanies($competitive_landscape,'0');
                if(isset($metrics['companies_covered'])){
                    $metrics_companies_covered = $metrics['companies_covered'];
                
                    $doc = new \DOMDocument();
                    $doc->loadHTML($metrics_companies_covered);
                    $liList = $doc->getElementsByTagName('li');                
                    $liValues = array();
                    foreach ($liList as $li) {
                        $liValues[] = $li->nodeValue;
                    }
                    $faq_answer2 .= " '".implode("', '",$liValues)."'";
                }
            }
            $faq[1]['faq_question'] = "Who are the key vendors in the ";
            $faq[1]['faq_answer'] = $faq_answer2;

            // Faq Q3 - text from first list point of Market Dynamics
            $faq_answer3 = '';
            if(isset($market_dynamics)){ 
                if(strpos($market_dynamics, "<li>")!=''){
                    $faq_answer3 = $this->getFirstLiTag($market_dynamics);
                }
            }
            $faq[2]['faq_question'] = "What is the key driver of ";
            $faq[2]['faq_answer'] = $faq_answer3;

            // Faq Q4 - first list of Key Market Trends
            $faq_answer4 = '';
            if(isset($key_market_trends)){
                if(strpos($key_market_trends, "<li>")!=''){
                    $faq_answer4 = $this->getFirstLiTag($key_market_trends);
                }
            }
            $faq[3]['faq_question'] = "What is the key market trend for ";
            $faq[3]['faq_answer'] = $faq_answer4;

            // Faq Q5 - first para of Regional Insights - Done
            $faq_answer5 = '';
            if(isset($regional_insights)){
                $faq_answer5 = $this->getFirstPTag($regional_insights);
            }
            //$faq_answer5 = $regional_insights;
            $faq[4]['faq_question'] = "Which region accounted for the largest share in ";
            $faq[4]['faq_answer'] = $faq_answer5;

            // delete older faq data
            ReportFaq::where('report_id',$report_id)->where('is_auto','1')->delete();

            for($k=0;$k<count($faq);$k++){
                if($k==0){
                    $updated_name = str_replace('Global',"",$report_name);
                    $que = $faq[$k]['faq_question'].$updated_name."?";
                } else {
                    $que = $faq[$k]['faq_question'].$report_name."?";
                }

                $faqData = array();
                $faqData['report_id'] = $report_id;
                $faqData['faq_question'] = $que;
                $faqData['faq_answer'] = strip_tags($faq[$k]['faq_answer']);
                $faqData['is_auto'] = '1';
                ReportFaq::create($faqData);
            }

            // insert into report_tableofcontent
            // for TOC field - starts
            // static text
            //$static_toc = "<h3>".$report_name." Table of Contents</h3>";
            $static_toc = "<ul><li><b>Executive Summary</b>";
            $static_toc .= "<ul><li>Market Overview</li><li>Wheel of Fortune</li></ul></li>";        
            $static_toc .= "<li><b>Research Methodology</b>";
            $static_toc .= "<ul><li>Information Procurement</li><li>Secondary & Primary Data Sources</li><li>Market Size Estimation</li><li>Market Assumptions & Limitations</li></ul></li>";
            $static_toc .= "<li><b>Parent Market Analysis</b>";
            $static_toc .= "<ul><li>Market Overview</li><li>Market Size</li><li>Market Dynamics<ul><li>Drivers</li><li>Opportunities</li><li>Restraints</li><li>Challenges</li></ul></li></ul></li>";
            $static_toc .= "<li><b>Key Market Insights</b>";
            $static_toc .= "<ul><li>Technology Analysis</li><li>Pricing Analysis</li><li>Supply Chain Analysis</li><li>Value Chain Analysis</li><li>Ecosystem of the Market</li><li>IP Analysis</li><li>Trade Analysis</li><li>Startup Analysis</li><li>Raw Material Analysis</li><li>Innovation Matrix</li><li>Pipeline Product Analysis</li><li>Macroeconomic Indicators</li><li>Top Investment Analysis</li><li>Key Success Factor</li><li>Degree of Competition</li></ul></li>";
            $static_toc .= "<li><b>Market Dynamics & Outlook</b>";
            $static_toc .= "<ul><li>Market Dynamics<ul><li>Drivers</li><li>Opportunities</li><li>Restraints</li><li>Challenges</li></ul></li>";
            $static_toc .= "<li>Regulatory Landscape</li><li>Porters Analysis<ul><li>Competitive rivalry</li><li>Threat of Substitute Products</li><li>Bargaining Power of Buyers</li><li>Threat of New Entrants</li><li>Bargaining Power of Suppliers</li></ul></li>";
            $static_toc .= "<li>Skyquest Special Insights on Future Disruptions<ul><li>Political Impact</li><li>Economic Impact</li><li>Social Impact</li><li>Technical Impact</li><li>Environmental Impact</li><li>Legal Impact</li></ul></li></ul></li>";
            
            // for all segments
            if(isset($segmentArr) && (count($segmentArr) > 0)) {
                for($k=0;$k<count($segmentArr);$k++){
                    $sub_segmentData = array();
                    $sub_segment_str = $segmentArr[$k]['value'];
                    $sub_segmentData = explode(",",$sub_segment_str);

                    $static_toc .= "<li><b>".$report_name." by ".$segmentArr[$k]['name']."</b>";
                    if(count($sub_segmentData)>0)
                    {
                        $static_toc .= "<ul><li>Market Overview</li>";
                    }
                    for($s=0;$s<count($sub_segmentData);$s++)
                    {
                        $static_toc .= "<li>".trim($sub_segmentData[$s])."</li>";
                        if($s==(count($sub_segmentData)-1))
                        {
                            $static_toc .= "</ul>";
                        }
                    }
                    $static_toc .= "</li>";
                }
            }

            // static text by Region
            $static_toc .= "<li><b>".$report_name." Size by Region</b>";
            $static_toc .= "<ul><li>Market Overview</li><li>North America<ul><li>USA</li><li>Canada</li></ul></li><li>Europe<ul><li>Germany</li><li>Spain</li><li>France</li><li>UK</li><li>Rest of Europe</li></ul></li><li>Asia Pacific<ul><li>China</li><li>India</li><li>Japan</li><li>South Korea</li><li>Rest of Asia-Pacific</li></ul></li><li>Latin America<ul><li>Brazil</li><li>Rest of Latin America</li></ul></li><li>Middle East & Africa (MEA)<ul><li>GCC Countries</li><li>South Africa</li><li>Rest of MEA</li></ul></li></ul></li>";
            
            // static text for Competitive Landscape
            $static_toc .= "<li><b>Competitive Landscape</b>";
            $static_toc .= "<ul><li>Top 5 Player Comparison</li><li>Market Positioning of Key Players, 2021</li><li>Strategies Adopted by Key Market Players</li><li>Top Winning Strategies<ul><li>By Development</li><li>By Company</li><li>By Year</li></ul></li><li>Recent Activities in the Market</li><li>Key Companies Market Share (%), 2021</li></ul></li>";

            // for Key Company Profiles
            $static_toc .= "<li><b>Key Company Profiles</b>";
            if(isset($metrics['companies_covered']) && ($metrics['companies_covered']!='')) {
                
                $metrics_companies_covered = $metrics['companies_covered'];
                
                $doc = new \DOMDocument();
                $doc->loadHTML($metrics_companies_covered);
                $liList = $doc->getElementsByTagName('li');
                $liValues = array();
                foreach ($liList as $li) {                    
                    $liValues[] = $li->nodeValue;
                }
                
                $static_toc .= "<ul>";
                $static_text_company_profiles = "<ul><li>Company Overview</li><li>Business Segment Overview</li><li>Financial Updates</li><li>Key Developments</li></ul>";
                if(count($liValues)>0){
                    for($c=0;$c<count($liValues);$c++)
                    {
                        if(trim($liValues[$c])!=''){
                            $company_name = trim($liValues[$c]);
                            $static_toc .= "<li>".$company_name.$static_text_company_profiles."</li>";
                        }
                    }
                }
                $static_toc .= "</ul>";
            }
            
            $static_toc .= "</li>";
            // for TOC field - ends

            // for tables field - starts
            // static text
            //$static_tables = "<h3>List of Tables</h3>";
            $static_tables = "<p>Table 1. ".$report_name." Size, 2021-2028 (USD Million)</p>";
            $static_tables .= "<p>Table 2. ".$report_name." Regulatory Landscape</p>";
            $static_tables .= "<p>Table 3. ".$report_name." IP Analysis</p>";

            // for all segments
            $table_no = 4;
            for($k=0;$k<count($segmentArr);$k++){
                $sub_segmentData = array();
                $sub_segment_str = $segmentArr[$k]['value'];
                $sub_segmentData = explode(",",$sub_segment_str);

                $static_tables .= "<p>Table ".$table_no++.". ".$report_name." Research And Analysis By ".$segmentArr[$k]['name'].", 2021-2028 (USD Million)</p>";            
                for($s=0;$s<count($sub_segmentData);$s++)
                {
                    $static_tables .= "<p>Table ".$table_no++.". ".$report_name." For ".trim($sub_segmentData[$s]).", 2021-2028 (USD Million)</p>";
                }
            }

            $static_tables .= "<p>Table ".$table_no++.". ".$report_name." Research And Analysis By Region, 2021-2028 (USD Million)</p>";

            // for all segments by country
            $countries = array("North America",array("US","Canada"),"European",array("UK","Germany","France","Italy","Spain","Rest Of Europe"),"Asia-Pacific",array("China","India","Japan","South Korea","Rest Of Asia-Pacific"),"Latin America",array("Brazil","Rest Of Latin America"),"Middle East And Africa",array("GCC Countries","South Africa","Rest Of Middle East And Africa"));
            for($c=0;$c<count($countries);$c++){
                if(!is_array($countries[$c]))
                {
                    $static_tables .= "<p>Table ".$table_no++.". ".$countries[$c]." ".$report_name." Research And Analysis By Country, 2021-2028 (USD Million)</p>";
                    for($k=0;$k<count($segmentArr);$k++){
                        $static_tables .= "<p>Table ".$table_no++.". ".$countries[$c]." ".$report_name." Research And Analysis By ".$segmentArr[$k]['name'].", 2021-2028 (USD Million)</p>";
                    }
                } else {
                    for($s=0;$s<count($countries[$c]);$s++){
                        for($k=0;$k<count($segmentArr);$k++){
                            $static_tables .= "<p>Table ".$table_no++.". ".$countries[$c][$s]." ".$report_name." Research And Analysis By ".$segmentArr[$k]['name'].", 2021-2028 (USD Million)</p>";
                        }
                    }
                }            
            }
            // for tables field - ends

            // for figures field - starts
            // static text
            //$static_figures = "<h3>List of Figures</h3>";
            $static_figures = "<p>Figure 1. ".$report_name." Size, 2021-2028 (USD Million)</p>";
            $static_figures .= "<p>Figure 2. ".$report_name." Wheel Of Fortune</p>";
            $static_figures .= "<p>Figure 3. ".$report_name." Parent Market Analysis</p>";
            $static_figures .= "<p>Figure 4. ".$report_name." Technology Analysis</p>";
            $static_figures .= "<p>Figure 5. ".$report_name." Pricing Analysis</p>";
            $static_figures .= "<p>Figure 6. ".$report_name." Supply Chain Analysis</p>";
            $static_figures .= "<p>Figure 7. ".$report_name." Value Chain Analysis</p>";
            $static_figures .= "<p>Figure 8. ".$report_name." Ecosystem Of The Market</p>";
            $static_figures .= "<p>Figure 9. ".$report_name." Trade Analysis</p>";
            $static_figures .= "<p>Figure 10. ".$report_name." Startup Analysis</p>";
            $static_figures .= "<p>Figure 11. ".$report_name." Raw Material Analysis</p>";
            $static_figures .= "<p>Figure 12. ".$report_name." Innovation Matrix</p>";
            $static_figures .= "<p>Figure 13. ".$report_name." Pipeline Product Analysis</p>";
            $static_figures .= "<p>Figure 14. ".$report_name." Macroeconomic Indicators</p>";
            $static_figures .= "<p>Figure 15. ".$report_name." Top Investment Analysis</p>";
            $static_figures .= "<p>Figure 16. ".$report_name." Key Success Factor</p>";
            $static_figures .= "<p>Figure 17. ".$report_name." Degree Of Competition</p>";
            $static_figures .= "<p>Figure 18. ".$report_name." Porter And Its Impact Analysis</p>";
            $static_figures .= "<p>Figure 19. ".$report_name." Skyquest Special Insights On Future Disruptions</p>";
            
            // for all segments
            $figure_no = 20;
            $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Share By Segment, 2021 Vs 2028 (%)</p>";
            for($k=0;$k<count($segmentArr);$k++){
                $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Share By ".$segmentArr[$k]['name'].", 2021 Vs 2028 (%)</p>";
            }

            // by country
            $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Share By Region, 2021 Vs 2028 (%)</p>";
            
            $countries = array("North America",array("US","Canada"),"Europe",array("UK","Germany","France","Italy","Spain","Rest Of Europe"),"Asia-Pacific",array("China","India","Japan","South Korea","Rest Of Asia-Pacific"),"Latin America",array("Brazil","Rest Of Latin America"),"Middle East And Africa",array("GCC","South Africa","Rest Of Middle East And Africa"));
            for($c=0;$c<count($countries);$c++){
                if(!is_array($countries[$c])){
                    $static_figures .= "<p>Figure ".$figure_no++.". ".$countries[$c]." ".$report_name." Share By Country, 2021-2028 (USD Million)</p>";
                } else {
                    for($s=0;$s<count($countries[$c]);$s++){
                        $static_figures .= "<p>Figure ".$figure_no++.". ".$countries[$c][$s]." ".$report_name." Size, 2021-2028 (USD Million)</p>";
                    }
                }            
            }

            // static text
            $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Top 5 Player Comparison</p>";
            $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Positioning Of Key Players, 2021</p>";
            $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Strategies Adopted By Key Market Players</p>";
            $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Top Winning Strategies</p>";
            $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." By Development</p>";
            $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." By Company</p>";
            $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." By Year</p>";
            $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Recent Activities In The Market</p>";
            $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Key Companies Market Share (%), 2021</p>";
            
            // by company
            if(isset($metrics['companies_covered']) && ($metrics['companies_covered']!='')) {
                
                $metrics_companies_covered = $metrics['companies_covered'];
                
                $doc = new \DOMDocument();
                $doc->loadHTML($metrics_companies_covered);
                $liList = $doc->getElementsByTagName('li');                
                $liValues = array();
                foreach ($liList as $li) {
                    $liValues[] = $li->nodeValue;
                }
                
                if(count($liValues)>0){
                    for($c=0;$c<count($liValues);$c++)
                    {
                        if(trim($liValues[$c])!=''){
                            $company_name = trim($liValues[$c]);
                            $static_figures .= "<p>Figure ".$figure_no++.". ".$company_name." Company Overview & Information</p>";
                        }
                    }
                }
            }
            // for figures field - ends

            // delete older tableofcontent data
            ReportTableofcontent::where('report_id',$report_id)->delete();

            // save to report_tableofcontent
            $tocData = array();
            $tocData['report_id'] = $report_id;
            $tocData['toc'] = $static_toc;
            $tocData['tables'] = $static_tables;
            $tocData['figures'] = $static_figures;
            ReportTableofcontent::create($tocData);

        }
        // automation for SD type - ends

        if($report->report_type=='Dynamic'){
            $toc_data = array();
            $toc_data['toc'] = $request->toc;
            ReportTableofcontent::where('report_id',$request->id)->update($toc_data);
        }

        $notification = ['message' => 'Report updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.report.index')->with($notification);
    }

    public function deleteSegment(Request $request){
        $report_segment = ReportSegment::find($request->id);
		if($report_segment) {        
            $report_segment->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }

    public function deleteFaq(Request $request){
        $report_faq = ReportFaq::find($request->id);
		if($report_faq) {        
            $report_faq->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }
    
    public function status(Request $request)
    {
        $status = 0;
        $report = Report::find($request->id);
        if($report) {
            if($report->is_active) {
                $report->is_active = 0;
                $status = 0;
            } else {
                $report->is_active = 1;
                $status = 1;
            }
            $report->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function destroy(Request $request)
    {
        $report = Report::find($request->id);
		if($report) {
            // delete older image if any       
            /* if(isset($report->image)) {
                cloudinary()->destroy($report->image);
            } */
            $report->delete();

            // delete report from sample request
            ReportSampleRequest::where('report_id',$report->id)->delete();

            // delete report from inquiry request
            ReportInquiry::where('report_id',$report->id)->delete();

            // delete report from subscribe request
            ReportSubscribeNow::where('report_id',$report->id)->delete();

            // delete report from report order
            ReportOrders::where('report_id',$report->id)->delete();

            // delete report from user bookmark
            UsersBookmark::where('entity_type','report')->where('entity_id',$report->id)->delete();

            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }

    // schema generation for all reports
    public function schemaGeneration()
    {
        ini_set('max_execution_time', -1);
        
        $reportcount = Report::count();
        $no_of_records_per_page = 200;
        
        $total_pages = ceil($reportcount / $no_of_records_per_page);

        for($pageno=1;$pageno<=$total_pages;$pageno++){
            $offset = ($pageno-1) * $no_of_records_per_page;
            $reports = DB::select("SELECT * FROM `reports` LIMIT $offset, $no_of_records_per_page");
            
            if(count($reports) > 0){
                foreach ($reports as $report) {                                
                    $schema = '<script type="application/ld+json">
                    {
                        "@context": "https://schema.org/",
                        "@type": "Website",
                        "name": "'.$report->meta_title.'",
                        "description": "'.$report->meta_description.'"
                    }
                    </script>
                    <script type="application/ld+json">
                    {
                        "@context": "https://schema.org",
                        "@type": "BreadcrumbList",
                        "itemListElement": [
                            {
                                "@type": "ListItem",
                                "position": 1,
                                "item": {
                                    "@id": "'.url('/').'",
                                    "name": "Home"
                                }
                            },';  
                            $sector = DB::select("SELECT `slug`,`title` FROM `sector` where `id` = $report->sector_id");                                                  
                            if(isset($sector)){
                            $schema .= '
                            {
                                "@type": "ListItem",
                                "position": 2,
                                "item": {
                                    "@id": "'.url('industries/'.$sector[0]->slug).'",
                                    "name": "'.$sector[0]->title.'"
                                }
                            },';
                            }
                            $industry_group = DB::select("SELECT `slug`,`title` FROM `industry_group` where `id` = $report->industry_group_id");                                                  
                            if(isset($industry_group)){
                            $schema .= '
                            {
                                "@type": "ListItem",
                                "position": 3,
                                "item": {
                                    "@id": "'.url('industries/'.$industry_group[0]->slug).'",
                                    "name": "'.$industry_group[0]->title.'"
                                }
                            },';
                            }
                            $industry = DB::select("SELECT `slug`,`title` FROM `industry` where `id` = $report->industry_id");                                                  
                            if(isset($industry)){
                            $schema .= '
                            {
                                "@type": "ListItem",
                                "position": 4,
                                "item": {
                                    "@id": "'.url('industries/'.$industry[0]->slug).'",
                                    "name": "'.$industry[0]->title.'"
                                }
                            },';
                            }
                            $sub_industry = DB::select("SELECT `slug`,`title` FROM `sub_industry` where `id` = $report->sub_industry_id");                                                  
                            if(isset($sub_industry)){
                            $schema .= '
                            {
                                "@type": "ListItem",
                                "position": 5,
                                "item": {
                                    "@id": "'.url('industries/'.$sub_industry[0]->slug).'",
                                    "name": "'.$sub_industry[0]->title.'"
                                }
                            }';
                            }
            $schema .= ']
                    }
                    </script>';
                    $report_faqs = DB::select("SELECT `faq_question`,`faq_answer` FROM `report_faq` where `report_id` = $report->id");                                                  
                    if(isset($report_faqs) && (count($report_faqs) >0)){
            $schema .= '<script type="application/ld+json">{
                        "@context": "https://schema.org",
                        "@type": "FAQPage",
                        "mainEntity":[';
                        foreach($report_faqs as $k => $faq_item){
                            $ans = $faq_item->faq_answer."<a href='". url('report/'.$report->slug) ."'>Read More</a>";
                            $schema .= '{
                                            "@type": "Question",
                                            "name": "'.$faq_item->faq_question.'", 
                                            "acceptedAnswer": {
                                                "@type": "Answer", 
                                                "text": "'.$ans.'"
                                            }
                                        }';
                            if($k!=(count($report_faqs)-1)){
                                $schema .= ',';
                            }
                        }                    
                    $schema .= ']
                    }
                    </script>';
                    }
                    $data = array();
                    $data['schema'] = $schema;
                    Report::where('id',$report->id)->update($data);
                }                 
            }
        }
    }

    public function importFiles(Request $request)
	{
        $report_mode = $request->report_mode;
        $report_type = $request->report_type;
        
        $errorString = "";
        $extensionError = 0;
		if($request->files->count() > 0){
            foreach($request->file('reports') as $key => $file) {
                $uploaded_filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                
                if($report_type!='Upcoming' && $extension!=''){
                    if($extension!='docx'){
                        $extensionError = 1;
                        $errorString .= '<b><span class="text-danger">For '.$uploaded_filename.':- please input valid file.</span></b>';
                    }
                }else if($report_type=='Upcoming' && $extension!=''){
                    if($extension!='xlsx'){
                        $extensionError = 1;
                        $errorString .= '<b><span class="text-danger">For '.$uploaded_filename.':- please input valid file.</span></b>';
                    }
                }
                
                if($extensionError==0){
                    if($report_mode=='New'){
                        if($report_type=='Upcoming'){
                            $import_status = $this->saveUpcomingReports($file, $report_mode);
                            if($import_status!=''){
                                $import_status = rtrim($import_status, ", ");
                                $errorString .= '<br><b>Reports import from file '.$uploaded_filename.':-</b>'.$import_status;
                            }else{
                                $errorString .= '<br><b><span class="text-success">'.$uploaded_filename.' report imported successfully.</span></b>';
                            }
                        } else {
                            $import_status = $this->savetoDB($file,$report_type);
                            if($import_status!=''){
                                $import_status = rtrim($import_status, ", ");
                                $errorString .= '<br><b><span class="text-danger">Errors in file '.$uploaded_filename.':-</span></b><br>'.$import_status; 
                            }else{
                                $errorString .= '<br><b><span class="text-success">'.$uploaded_filename.' report imported successfully.</span></b>';
                            }
                        }
                    } else if($report_mode=='Revamp'){
                        if($report_type=='Upcoming'){
                            $import_status = $this->saveUpdatedUpcomingReports($file, $report_mode);
                            if($import_status!=''){
                                $import_status = rtrim($import_status, ", ");
                                $errorString .= '<br><b>Reports import from file '.$uploaded_filename.':-</b><br>'.$import_status;
                            }else{
                                $errorString .= '<br><b><span class="text-success">'.$uploaded_filename.' report imported successfully.</span></b>';
                            }                     
                        } else {
                            $import_status = $this->revamptoDB($file,'Revamp',$report_type);
                            if(isset($import_status) && !is_array($import_status)){
                                $import_status = rtrim($import_status, ", ");
                                $errorString .= '<br><b><span class="text-danger">Errors in file '.$uploaded_filename.':-</span></b><br>'.$import_status;
                            }        
                            if(isset($import_status) && is_array($import_status) && $import_status[0]=='no-sku'){
                                $errorString .= 'Please add SKU details to the file to revamp the report.';
                            }
                            else if(isset($import_status) && is_array($import_status) && $import_status[0]=='sku-notfound'){
                                $errorString .= 'SKU does not exists in the database.';
                            }
                            else if(isset($import_status) && is_array($import_status) && $import_status[0]=='error'){                            
                                $errorString .= '<br><b><span class="text-danger">Errors in file '.$uploaded_filename.':-</span></b><br>'.$import_status[1];
                            }else{
                                $errorString .= '<br><b><span class="text-success">'.$uploaded_filename.' report imported successfully.</span></b>';
                            }
                        }
                    } else if($report_mode=='Update'){
                        if($report_type=='Upcoming'){
                            $import_status = $this->saveUpdatedUpcomingReports($file, $report_mode);
                            if($import_status!=''){
                                $import_status = rtrim($import_status, ", ");
                                $errorString .= '<br><b>Reports import from file '.$uploaded_filename.':-</b><br>'.$import_status;
                            }else{
                                $errorString .= '<br><b><span class="text-success">'.$uploaded_filename.' report imported successfully.</span></b>';
                            }                 
                        } else {
                            $import_status = $this->revamptoDB($file,'Update',$report_type);
                            if(isset($import_status) && !is_array($import_status)){
                                $import_status = rtrim($import_status, ", ");
                                $errorString .= '<br><b><span class="text-danger">Errors in file '.$uploaded_filename.':-</span></b><br>'.$import_status;
                            }        
                            if(isset($import_status) && is_array($import_status) && $import_status[0]=='no-sku'){
                                $errorString .= 'Please add SKU details to the file to update the report.';
                            }
                            else if(isset($import_status) && is_array($import_status) && $import_status[0]=='sku-notfound'){
                                $errorString .= 'SKU does not exists in the database.';
                            }
                            else if(isset($import_status) && is_array($import_status) && $import_status[0]=='error'){
                                $errorString .= '<br><b><span class="text-danger">Errors in file '.$uploaded_filename.':-</span></b><br>'.$import_status[1];
                            }else{
                                $errorString .= '<br><b><span class="text-success">'.$uploaded_filename.' report imported successfully.</span></b>';
                            }
                        }
                    }        
                }        
                $key++;
            }

            if(isset($errorString) && $errorString!=''){                
                $notification = ['import_error_message' => $errorString,'alert-class' => 'error'];
                return redirect()->route('admin.report.index')->with($notification);
            }

            $notification = ['import_success_message' => 'Report imported successfully','alert-class' => 'success'];
		} else {
            $notification = ['import_error_message' => '<span class="text-danger">Please select atleast one file to import.</span>','alert-class' => 'error'];
		}
		return redirect()->route('admin.report.index')->with($notification);
	}

    public function getReportCount($id,$report_type,$combination,$reportId='')
    {
        if(($report_type=='SD') || ($report_type=='Dynamic')){
            $reports = Report::selectRaw('SUBSTRING_INDEX(product_id, "'.$combination.'", -1) as vfield, product_id, id')
            ->where('sub_industry_id',$id)
            ->where(function($c) use ($combination) {
                $c->where('product_id','like','%'.$combination.'%');
            })
            ->where(function($query) {
                $query->where('report_type','SD')->orWhere('report_type','Dynamic');
            })->orderBy('vfield', 'desc')->get();
                            
        } else{
            $reports = Report::selectRaw('SUBSTRING_INDEX(product_id, "'.$combination.'", -1) as vfield, product_id, id')
            ->where('sub_industry_id',$id)
            ->where(function($c) use ($combination) {
                $c->where('product_id','like','%'.$combination.'%');
            })
            ->where('report_type',$report_type)
            ->orderBy('vfield', 'desc')->get();
        }
        
        $nextNo = '2001';
        if(count($reports)>0){          
            $lastReportId = $reports[0]['product_id'];
            if($lastReportId!=''){
                $lastNo = substr($lastReportId,-4);
                if(isset($reportId) && ($reportId==$reports[0]['id'])){
                    $nextNo = (int)$lastNo;
                }else{
                    $nextNo = (int)$lastNo+1;
                }
            }
        }
        return $nextNo;
    }

    public function savetoDB($file,$report_type)
    {
        // Read contents
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($file);
        
        $sections = $phpWord->getSections();
        
        $error_str = "";
        $mainArray = array();
        $titleArray = array();
        $i = 0;
        
        if(count($sections)==0){
            return "Unable to read a file.";
        }

        foreach ($sections as $section) {
            $elements = $section->getElements();
            
            foreach ($elements as $element) {
                switch(get_class($element)) {
                    case 'PhpOffice\PhpWord\Element\Title':
                        if(get_class($element)==='PhpOffice\PhpWord\Element\TextBreak'){
                        }
                        else if (get_class($element) === 'PhpOffice\PhpWord\Element\TextRun') {
                            $title_text = "";
                            $textRunElements = $element->getElements();
                            foreach ($textRunElements as $textRunElement) {
                                if(get_class($textRunElement)==='PhpOffice\PhpWord\Element\TextBreak'){
                                }else{
                                    $title_text .= html_entity_decode($textRunElement->getText());
                                }
                            }
                            $mainArray[$i]['title'] = trim($title_text);
                            $titleArray[] = trim($title_text);
                            $i++;
                        }
                        else{
                            if (is_object($element->getText())) {
                                $title_text = "";
                                $text_runElement = $element->getText();
                                $textRunElements = $text_runElement->getElements();
                                foreach ($textRunElements as $textRunElement) {
                                    if(get_class($textRunElement)==='PhpOffice\PhpWord\Element\TextBreak'){
                                    }else{
                                        $title_text .= html_entity_decode($textRunElement->getText());                                    
                                    }
                                }
                                $mainArray[$i]['title'] = trim($title_text);
                                $titleArray[] = trim($title_text);
                                $i++;
                            } else {
                                $mainArray[$i]['title'] = html_entity_decode(trim($element->getText()));
                                $titleArray[] = html_entity_decode(trim($element->getText()));
                                $i++;
                            }
                        }
                        break;

                    case 'PhpOffice\PhpWord\Element\Text':
                        $format = $element->getFontStyle();
                        if($format->isBold()){
                            $mainArray[$i-1]['data'][] = html_entity_decode("<b>".$element->getText()."</b>");
                        } else {
                            $mainArray[$i-1]['data'][] = html_entity_decode($element->getText());
                        }
                        break;

                    case 'PhpOffice\PhpWord\Element\TextRun':
                        $textRunElements = $element->getElements();                        
                        $textDataArr = "";
                        foreach ($textRunElements as $textRunElement) {
                            if(get_class($textRunElement)==='PhpOffice\PhpWord\Element\TextBreak'){
                            }
                            else if (get_class($textRunElement) === 'PhpOffice\PhpWord\Element\Link') {
                                $format = $textRunElement->getFontStyle();
                                $source = $textRunElement->getSource();
                                if($source!=''){
                                    $textDataArr .= "<a href='".$source."' target='_blank'>";
                                }
                                if($format->isBold()){
                                    $textDataArr .= html_entity_decode("<b>".$textRunElement->getText()."</b>");
                                } else {
                                    $textDataArr .= html_entity_decode($textRunElement->getText());
                                }
                                if($source!=''){
                                    $textDataArr .= "</a>";
                                }
                            } else if (get_class($textRunElement) === 'PhpOffice\PhpWord\Element\Text') {
                                $format = $textRunElement->getFontStyle();
                                if($format->isBold()){
                                    $textDataArr .= html_entity_decode("<b>".$textRunElement->getText()."</b>");
                                } else{
                                    $textDataArr .= html_entity_decode($textRunElement->getText());    
                                }
                            }
                        }
                        $mainArray[$i-1]['data'][] = $textDataArr;
                        break;

                    case 'PhpOffice\PhpWord\Element\TextBreak':
                        break;

                    case 'PhpOffice\PhpWord\Element\ListItemRun':
                        $textRunElements = $element->getElements();
                        $textDataArr = "";
                        foreach ($textRunElements as $textRunElement) {
                            if(get_class($textRunElement)==='PhpOffice\PhpWord\Element\TextBreak'){
                            }else{
                                $source = '';
                                $format = $textRunElement->getFontStyle();                            
                                if(get_class($textRunElement)==='PhpOffice\PhpWord\Element\Link'){
                                    $source = $textRunElement->getSource();
                                }
                                if($source!=''){
                                    $textDataArr .= "<a href='".$source."' target='_blank'>";
                                }                            
                                if($format->isBold()){
                                    $textDataArr .= html_entity_decode("<b>".$textRunElement->getText()."</b>");
                                } else{
                                    $textDataArr .= html_entity_decode($textRunElement->getText());
                                }   
                                if($source!=''){
                                    $textDataArr .= "</a>";
                                }    
                            }
                        }
                        $mainArray[$i-1]['data'][]['list'] = $textDataArr;
                        break;

                    case 'PhpOffice\PhpWord\Element\Link':
                        $textRunElements = $element->getElements();
                        $textDataArr = "";
                        foreach ($textRunElements as $textRunElement) {
                            if(get_class($textRunElement)==='PhpOffice\PhpWord\Element\TextBreak'){
                            }else{
                                $format = $textRunElement->getFontStyle();
                                $source = $textRunElement->getSource();
                                if($source!=''){
                                    $textDataArr .= "<a href='".$source."' target='_blank'>";
                                }
                                if($format->isBold()){
                                    $textDataArr .= html_entity_decode("<b>".$textRunElement->getText()."</b>");
                                } else{
                                    $textDataArr .= html_entity_decode($textRunElement->getText());
                                }  
                                if($source!=''){
                                    $textDataArr .= "</a>";
                                }   
                            } 
                        }
                        $mainArray[$i-1]['data'][] = $textDataArr;
                        break;                    
                }                
            }
        }
        
        // for SD report type
        if($report_type=='SD'){
            // check title for all mandatory fields
            //$mandatory_fields = array("Report Name","Sector","Industry Group","Industry","Sub-Industry","Country","Report Slug","Report Type","Market Insights","Regional Insights","Market Dynamics","Competitive Landscape","Key Market Trends");
            $mandatory_fields = array("Report Name","Segments","Image","Sector","Industry Group","Industry","Sub-Industry","Country","Download","Image Alt","Report Slug","Meta Title","Meta Description","Pages","Report Type","Methodologies","Analyst Support","Market Insights","Segmental Analysis","Regional Insights","Market Dynamics","Competitive Landscape","Key Market Trends","SkyQuest Analysis");
            for($i=0;$i<count($mandatory_fields);$i++){
                if(!in_array($mandatory_fields[$i],$titleArray)){
                    $error_str .= $mandatory_fields[$i]. " does not exist., ";
                }
            }

            // if uploaded file not in pre defined format
            if($error_str!=''){
                return $error_str;
            }
            //dd($mainArray);
            // store in DB
            $segmentArr = array();
            $sub_segmentArr = array();
            $pricingArr = array();           
            $metrics = array();
            $faq = array();
            $dbData = array();
            $f = 0;        
            foreach($mainArray as $a => $inner1) {
                if($inner1['title']=='Report Name'){
                    $title = 'name';
                    if(isset($inner1['data'][0])) {
                        $content = "";       
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Report Name does not exist., ";
                        }
                    }else{
                        $error_str .= "Report Name does not exist., ";
                    }
                }
                else if($inner1['title']=='Image'){
                    $title = 'image';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Image does not exist., ";
                        }
                    }else{
                        $error_str .= "Image does not exist., ";
                    }
                }
                else if($inner1['title']=='Country'){
                    $title = 'country';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        $report_country = $content;
                        if(!$content){
                            $error_str .= "Country does not exist., ";
                        }
                    }else{
                        $error_str .= "Country does not exist., ";
                    }
                }
                else if($inner1['title']=='Download'){
                    $title = 'download';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){           
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Download does not exist., ";
                        }
                    }else{
                        $error_str .= "Download does not exist., ";
                    }
                }
                else if($inner1['title']=='Image Alt'){
                    $title = 'image_alt';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){           
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Image Alt does not exist., ";
                        }
                    }else{
                        $error_str .= "Image Alt does not exist., ";
                    }
                }
                else if($inner1['title']=='Report Slug'){
                    $title = 'slug';                   
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){           
                            $content .= $inner2;
                        }
                        if(strtolower($content)=='null'){
                            $error_str .= "Report Slug can not be NULL., ";
                        } elseif(trim($content)==''){
                            $error_str .= "Report Slug does not exist., ";
                        } else{
                            // check slug already exists 
                            $reportSlug = checkReportSlug($content);
                            if(isset($reportSlug) && ($reportSlug->count() > 0)){
                                $error_str .= "Slug : ".$content." already exists., ";
                            }
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Report Slug does not exist., ";
                        }
                    }else{
                        $error_str .= "Report Slug does not exist., ";
                    }
                }
                else if($inner1['title']=='Meta Title'){
                    $title = 'meta_title';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){           
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Meta Title does not exist., ";
                        }
                    }else{
                        $error_str .= "Meta Title does not exist., ";
                    }
                }
                else if($inner1['title']=='Meta Description'){
                    $title = 'meta_description';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){           
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Meta Description does not exist., ";
                        }
                    }else{
                        $error_str .= "Meta Description does not exist., ";
                    }
                }
                else if($inner1['title']=='Pages'){
                    $title = 'pages';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){           
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Pages does not exist., ";
                        }
                    }else{
                        $error_str .= "Pages does not exist., ";
                    }
                }
                else if($inner1['title']=='Methodologies'){
                    $title = 'methodologies';
                    if(!empty($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){ 
                            if(isset($inner2)){
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";
                                } else {
                                    $content .= "<ul>";
                                        foreach($inner2 as $d => $inner3) {
                                            $content .= "<li>".$inner3."</li>";
                                        }
                                    $content .= "</ul>";
                                }
                            }
                        }
                        $dbData[$title] = $content;
                        if(empty(trim($content))){
                            $error_str .= "Methodologies does not exist., ";
                        }
                    }else{
                        $error_str .= "Methodologies does not exist., ";
                    }
                }
                else if($inner1['title']=='Analyst Support'){
                    $title = 'analyst_support';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){
                            if(!is_array($inner2)) {
                                $content .= "<p>".$inner2."</p>";                        
                            } else {
                                $content .= "<ul>";
                                    foreach($inner2 as $d => $inner3) {
                                        $content .= "<li>".$inner3."</li>";
                                    }
                                $content .= "</ul>";
                            }
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Analyst Support does not exist., ";
                        }
                    }else{
                        $error_str .= "Analyst Support does not exist., ";
                    }
                }
                else if($inner1['title']=='Market Insights'){
                    $title = 'market_insights';                                
                    if(isset($inner1['data'][0])) {
                        $content = "";
                        $faq_answer = "";
                        foreach($inner1['data'] as $b => $inner2){        
                            if(!is_array($inner2)) {
                                $content .= "<p>".$inner2."</p>";
                                // get metrics data
                                if($b==0)
                                {   
                                    $inner2 = strip_tags($inner2);
                                    $contentArr = explode(" ",$inner2);
                                    if(is_array($contentArr) && count($contentArr) >0) {
                                        if(str_contains($inner2, "in")) {          
                                            $startyear_key = array_search("in",$contentArr);
                                            $metrics['startyear'] = ($startyear_key!='') ? rtrim($contentArr[$startyear_key+1],",") : '';
                                            if(!$metrics['startyear']) {
                                                $error_str .= "Can not fetch start year of Market size value for Report Metric., ";
                                            }
                                        }else {
                                            $error_str .= "Can not fetch start year of Market size value for Report Metric., ";
                                        }

                                        if(str_contains($inner2, "CAGR")) {
                                            $growth_rate = trim($this->string_between_two_string($inner2, 'CAGR of', '%'));
                                            $metrics['growth_rate'] = ($growth_rate!='') ? $growth_rate."%" : '';
                                            if(!$metrics['growth_rate']) {
                                                $error_str .= "Can not fetch growth rate for Report Metric., ";
                                            }
                                        }else {
                                            $error_str .= "Can not fetch growth rate for Report Metric., ";
                                        }

                                        if(str_contains($inner2, "period")) {
                                            $metrics['forecast_period'] = rtrim(ltrim(strip_tags(trim($this->string_between_two_string($inner2, 'period', '.')))));
                                            if(!$metrics['forecast_period']) {
                                                $error_str .= "Can not fetch forecast period for Report Metric., ";
                                            }
                                        }else {
                                            $error_str .= "Can not fetch forecast period for Report Metric., ";
                                        }
                                    }

                                    if(str_contains($inner2, "by")) {
                                        $endyear_pos = strpos($inner2,"by");
                                        $metrics['endyear']  = ($endyear_pos!='') ? substr($inner2, $endyear_pos+3, 4) : '';
                                        if(!$metrics['endyear']) {
                                            $error_str .= "Can not fetch end year of Market size value for Report Metric., ";
                                        }
                                    }else {
                                        $error_str .= "Can not fetch end year of Market size value for Report Metric., ";
                                    }

                                    if(str_contains($inner2, "valued")) {
                                        $startyear_size = trim($this->string_between_two_string($inner2, 'valued', 'in'));
                                        $startyear_arr = explode(" ",$startyear_size);
                                        $startyear_key = array_search("USD",$startyear_arr);
                                        $startyear_size = '';
                                        if($startyear_key){
                                            if(isset($startyear_arr[$startyear_key])){
                                                $startyear_size = $startyear_arr[$startyear_key];
                                                $metrics['forecast_unit'] = $startyear_arr[$startyear_key];
                                            }
                                            if(isset($startyear_arr[$startyear_key+1]))
                                                $startyear_size .= ' '.$startyear_arr[$startyear_key+1];
                                            if(isset($startyear_arr[$startyear_key+2])){
                                                $startyear_size .= ' '.$startyear_arr[$startyear_key+2];
                                                $metrics['forecast_unit'] .= ' '.ucfirst($startyear_arr[$startyear_key+2]);
                                            }
                                        }
                                        $metrics['startyear_size'] = $startyear_size;
                                        if(!$startyear_size) {
                                            $error_str .= "Can not fetch Market size value of start year for Report Metric., ";
                                        }
                                    }else {
                                        $error_str .= "Can not fetch Market size value of start year for Report Metric., ";
                                    }

                                    if(str_contains($inner2, "by")) {
                                        $end_year_size = $this->string_between_two_string($inner2, 'USD', 'by');
                                        $end_year_arr = explode(" ",$end_year_size);
                                        //$end_year_key = array_search("USD",$end_year_arr);
                                        $end_year_array_keys = array_keys($end_year_arr, "USD");
                                        $end_year_key = $end_year_array_keys[1];

                                        if(isset($end_year_arr[$end_year_key])){
                                            $metrics['endyear_size'] = $end_year_arr[$end_year_key];
                                            //$metrics['forecast_unit'] = $end_year_arr[$end_year_key];
                                        }

                                        if(isset($end_year_arr[$end_year_key+1])){
                                            $metrics['endyear_size'] .= ' '.$end_year_arr[$end_year_key+1];
                                        }

                                        if(isset($end_year_arr[$end_year_key+2])){
                                            $metrics['endyear_size'] .= ' '.$end_year_arr[$end_year_key+2];
                                            //$metrics['forecast_unit'] .= ' '.ucfirst($end_year_arr[$end_year_key+2]);
                                        }
                                        
                                        if(!$metrics['endyear_size']) {
                                            $error_str .= "Can not fetch Market size value of end year for Report Metric., ";
                                        }

                                        if(!$metrics['forecast_unit']) {
                                            $error_str .= "Can not fetch forecast unit for Report Metric., ";
                                        }                                        
                                    }else {
                                        $error_str .= "Can not fetch Market size value of end year for Report Metric., ";
                                        $error_str .= "Can not fetch forecast unit for Report Metric., ";
                                    }                                                    
                                    
                                    // get faq data 
                                    $faq_answer .= $inner2;
                                }
                            } else {
                                $content .= "<ul>";
                                    foreach($inner2 as $d => $inner3) {
                                        $content .= "<li>".$inner3."</li>";
                                    }
                                $content .= "</ul>";
                            }
                        }
                        $dbData[$title] = $content;

                        if(!$content){
                            $error_str .= "Market Insights does not exist., ";
                        }

                        // get Faq Q1
                        $faq[0]['faq_question'] = "";
                        $faq[0]['faq_answer'] = "";
                        if($faq_answer!=''){
                            $faq[0]['faq_question'] = "What is the global market size of ";
                            $faq[0]['faq_answer'] = $faq_answer;
                        }
                    }else{
                        $error_str .= "Market Insights does not exist., ";
                    }
                }
                else if($inner1['title']=='Segmental Analysis'){
                    $title = 'segmental_analysis';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){  
                            if(!is_array($inner2)) {
                                $content .= "<p>".$inner2."</p>";                        
                            } else {
                                $content .= "<ul>";
                                    foreach($inner2 as $d => $inner3) {
                                        $content .= "<li>".$inner3."</li>";
                                    }
                                $content .= "</ul>";
                            }
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Segmental Analysis does not exist., ";
                        }
                    }else{
                        $error_str .= "Segmental Analysis does not exist., ";
                    }
                }
                else if($inner1['title']=='Regional Insights'){
                    $title = 'regional_insights';
                    
                    if(isset($inner1['data'][0])) {
                        $content = "";   
                        $faq_answer = "";
                        $list_counter = 0;
                        foreach($inner1['data'] as $b => $inner2){    
                            if(!is_array($inner2)) {
                                $content .= "<p>".$inner2."</p>";
                                
                                // get Faq Q5
                                if($list_counter==0)
                                {
                                    $faq_answer = $inner2;
                                    $list_counter++;
                                }
                            } else {
                                $content .= "<ul>";
                                    foreach($inner2 as $d => $inner3) {
                                        $content .= "<li>".$inner3."</li>";
                                    }
                                $content .= "</ul>";
                            }
                        }
                        $dbData[$title] = $content;

                        if(!$content){
                            $error_str .= "Regional Insights does not exist., ";
                        }

                        // get Faq Q5
                        $faq[4]['faq_question'] = "";
                        $faq[4]['faq_answer'] = "";
                        if($faq_answer!=''){
                            $faq[4]['faq_question'] = "Which region accounted for the largest share in ";
                            $faq[4]['faq_answer'] = $faq_answer;
                        }
                    }else{
                        $error_str .= "Regional Insights does not exist., ";
                    }
                }
                else if($inner1['title']=='Market Dynamics'){
                    $title = 'market_dynamics';
                    if(isset($inner1['data'][0])) {
                        $content = "";       
                        $faq_answer = "";
                        $list_counter = 0;
                        foreach($inner1['data'] as $b => $inner2){    
                            if(!is_array($inner2)) {
                                $content .= "<p>".$inner2."</p>";                        
                            } else {                                        
                                $content .= "<ul>";
                                    foreach($inner2 as $d => $inner3) {
                                        $content .= "<li>".$inner3."</li>";

                                        // get Faq Q3
                                        if($list_counter==0)
                                        {
                                            $faq_answer = $inner3;
                                            $list_counter++;
                                        }
                                    }
                                $content .= "</ul>";
                            }
                        }
                        $dbData[$title] = $content;

                        if(!$content){
                            $error_str .= "Market Dynamics does not exist., ";
                        }

                        // get Faq Q3
                        $faq[2]['faq_question'] = "";
                        $faq[2]['faq_answer'] = "";
                        if($faq_answer!=''){
                            $faq[2]['faq_question'] = "What is the key driver of ";
                            $faq[2]['faq_answer'] = $faq_answer;
                        }
                    }else{
                        $error_str .= "Market Dynamics does not exist., ";
                    }             
                }
                else if($inner1['title']=='Competitive Landscape'){
                    $title = 'competitive_landscape';
                    if(isset($inner1['data'][0])) {
                        $content = "";
                        $faq_answer = "";
                        $prev_str = "";
                        
                        $list_key = 0;
                        $metrics['companies_covered'] = array();
                        foreach($inner1['data'] as $b => $inner2){   
                            if(!is_array($inner2)) {
                                $content .= "<p>".$inner2."</p>";
                                $prev_str = $inner2;
                                // get faq data 
                                if($b==0){                                        
                                    $faq_answer .= $inner2 . " ";
                                }
                            } else {                                         
                                $str_to_compare = $prev_str;
                                if((str_contains($str_to_compare, "Top")) || (str_contains($str_to_compare, "Player")))
                                {
                                    $list_key = 1;
                                }

                                $endstr_to_compare = $prev_str;
                                if(str_contains($endstr_to_compare, "Recent Development"))
                                {
                                    $list_key = 0;
                                }
                                
                                $content .= "<ul>";                                        
                                foreach($inner2 as $d => $inner3) {                     
                                    $content .= "<li>".$inner3."</li>";
                                    if($list_key){
                                        $metrics['companies_covered'][] = "<li>".$inner3."</li>";
                                        $faq_answer .= "'".$inner3."', ";
                                    }
                                }
                                $content .= "</ul>";
                            }
                        }
                        if(!$metrics['companies_covered']) {
                            $error_str .= "Can not fetch companies covered for Report Metric., ";
                        }
                        $dbData[$title] = $content;

                        if(!$content){
                            $error_str .= "Competitive Landscape does not exist., ";
                        }

                        // get Faq Q2
                        $faq[1]['faq_question'] = "";
                        $faq[1]['faq_answer'] = "";
                        if($faq_answer!=''){
                            $faq[1]['faq_question'] = "Who are the key vendors in the ";
                            $faq[1]['faq_answer'] = rtrim($faq_answer,", ");
                        }
                    }else{
                        $error_str .= "Competitive Landscape does not exist., ";
                    }
                }
                else if($inner1['title']=='Key Market Trends'){
                    $title = 'key_market_trends';
                    if(isset($inner1['data'][0])) {
                        $content = "";   
                        $faq_answer = "";
                        $list_counter = 0;
                        foreach($inner1['data'] as $b => $inner2){
                            if(!is_array($inner2)) {
                                $content .= "<p>".$inner2."</p>";
                            } else {
                                $content .= "<ul>";
                                    foreach($inner2 as $d => $inner3) {
                                        $content .= "<li>".$inner3."</li>";
                                        
                                        // get Faq Q4
                                        if($list_counter==0)
                                        {
                                            $faq_answer = $inner3;
                                            $list_counter++;
                                        }
                                    }
                                $content .= "</ul>";
                            }
                        }
                        $dbData[$title] = $content;

                        if(!$content){
                            $error_str .= "Key Market Trends does not exist., ";
                        }

                        // get Faq Q4
                        $faq[3]['faq_question'] = "";
                        $faq[3]['faq_answer'] = "";
                        if($faq_answer!=''){
                            $faq[3]['faq_question'] = "What is the key market trend for ";
                            $faq[3]['faq_answer'] = $faq_answer;
                        }
                    }else{
                        $error_str .= "Key Market Trends does not exist., ";
                    }
                }
                else if($inner1['title']=='SkyQuest Analysis'){
                    $title = 'skyQuest_analysis';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){
                            if(!is_array($inner2)) {
                                $content .= "<p>".$inner2."</p>";                        
                            } else {
                                $content .= "<ul>";
                                    foreach($inner2 as $d => $inner3) {
                                        $content .= "<li>".$inner3."</li>";
                                    }
                                $content .= "</ul>";
                            }
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "SkyQuest Analysis does not exist., ";
                        }
                    }else{
                        $error_str .= "SkyQuest Analysis does not exist., ";
                    }
                }
                else if($inner1['title']=='What\'s Included'){
                    $title = 'whats_included';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){
                            if(!is_array($inner2)) {
                                $content .= "<p>".$inner2."</p>";                        
                            } else {
                                $content .= "<ul>";
                                    foreach($inner2 as $d => $inner3) {
                                        $content .= "<li>".$inner3."</li>";
                                    }
                                $content .= "</ul>";
                            }
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "What's Included does not exist., ";
                        }
                    }else{
                        $error_str .= "What's Included does not exist., ";
                    }
                }
                else if($inner1['title']=='Sector'){
                    $title = 'sector_id';
                    if(isset($inner1['data'][0])) {
                        $content = "";              
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        if(!$content){
                            $error_str .= "Sector does not exist., ";
                        }
                        $sectorData = Sector::where('title',$content)->first();
                        if(!(isset($sectorData)) && $content!=''){
                            $error_str .= "Sector : ".$content." does not exist in our records., ";
                        }                    
                        $report_sector_id = (isset($sectorData)) ? $sectorData->id : '';
                        $dbData[$title] = (isset($sectorData)) ? $sectorData->id : 0;
                    }else{
                        $error_str .= "Sector does not exist., ";
                    }
                }
                else if($inner1['title']=='Industry Group'){
                    $title = 'industry_group_id';
                    if(isset($inner1['data'][0])) {
                        $content = "";              
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        if(!$content){
                            $error_str .= "Industry Group does not exist., ";
                        }
                        $IndustryGroupData = IndustryGroup::where('title',$content)->first();
                        if(!(isset($IndustryGroupData)) && $content!=''){
                            $error_str .= "Industry Group : ".$content." does not exist in our records., ";
                        }
                        if($report_sector_id && $content!=''){
                            $IndustryGroupData = IndustryGroup::where('title',$content)->where('sector_id',$report_sector_id)->first();
                            if(!(isset($IndustryGroupData))){
                                $error_str .= "Industry Group : ".$content." does not map with given sector., ";
                            }
                        }
                        $report_industry_group_id = (isset($IndustryGroupData)) ? $IndustryGroupData->id : '';
                        $dbData[$title] = (isset($IndustryGroupData)) ? $IndustryGroupData->id : 0;
                    }else{
                        $error_str .= "Industry Group does not exist., ";
                    }
                }
                else if($inner1['title']=='Industry'){
                    $title = 'industry_id';
                    if(isset($inner1['data'][0])) {
                        $content = "";              
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        if(!$content){
                            $error_str .= "Industry does not exist., ";
                        }
                        $IndustryData = Industry::where('title',$content)->first();
                        if(!(isset($IndustryData)) && $content!=''){
                            $error_str .= "Industry : ".$content." does not exist in our records., ";
                        }
                        if($report_industry_group_id && $content!=''){
                            $IndustryData = Industry::where('title',$content)->where('industry_group_id',$report_industry_group_id)->first();
                            if(!(isset($IndustryData))){
                                $error_str .= "Industry : ".$content." does not map with given industry group., ";
                            }
                        }
                        $report_industry_id = (isset($IndustryData)) ? $IndustryData->id : '';
                        $dbData[$title] = (isset($IndustryData)) ? $IndustryData->id : 0;
                    }else{
                        $error_str .= "Industry does not exist., ";
                    }
                }
                else if($inner1['title']=='Sub-Industry'){
                    $title = 'sub_industry_id';
                    if(isset($inner1['data'][0])) {
                        $content = "";              
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        if(!$content){
                            $error_str .= "Sub-Industry does not exist., ";
                        }
                        $SubIndustryData = SubIndustry::where('title',$content)->first();
                        if(!(isset($SubIndustryData)) && $content!=''){
                            $error_str .= "Sub-Industry : ".$content." does not exist in our records., ";
                        }
                        if($report_industry_id && $content!=''){
                            $SubIndustryData = SubIndustry::where('title',$content)->where('industry_id',$report_industry_id)->first();
                            if(!(isset($SubIndustryData))){
                                $error_str .= "Sub-Industry : ".$content." does not map with given industry., ";
                            }
                        }
                        $report_sub_industry_id = (isset($SubIndustryData)) ? $SubIndustryData->id : '';
                        $dbData[$title] = (isset($SubIndustryData)) ? $SubIndustryData->id : 0;
                    }else{
                        $error_str .= "Sub-Industry does not exist., ";
                    }
                }
                else if(strtolower($inner1['title'])=='segment'){
                    if(isset($inner1['data'][0])) {
                        $content = "";              
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        $segmentArr[] = $content;
                        if(!$content){
                            $error_str .= "Segment does not exist., ";
                        }
                    }else{
                        $error_str .= "Segment does not exist., ";
                    }
                }
                else if((strtolower($inner1['title'])=='sub-segments') || (strtolower($inner1['title'])=='sub-segment')){
                    if(isset($inner1['data'][0])) {
                        $content = "";              
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        $sub_segmentArr[] = $content;
                        if(!$content){
                            $error_str .= "Sub-segment does not exist., ";
                        }
                    }else{
                        $error_str .= "Sub-segment does not exist., ";
                    }
                }
                else if($inner1['title']=='Report Type'){
                    if(isset($inner1['data'][0])) {
                        $content = array();
                        foreach($inner1['data'] as $b => $inner2){
                            if($inner2!=''){
                                $content[] = $inner2;
                            }
                        }
                        $pricingArr = $content;
                        if(!$content){
                            $error_str .= "Report type does not exist., ";
                        }
                    }else{
                        $error_str .= "Report Type does not exist., ";
                    }
                }
            }        
            
            // generate product_id or SKU
            $country_code = '';
            $sector_code = '';
            $sub_industry_initial = '';
            $report_count = '';

            $country_code = ($report_country!='') ? substr($report_country,0,1) : '';
            if($report_sector_id!=''){
                $sectorData = Sector::where('id',$report_sector_id)->pluck('code');            
                $sector_code = $sectorData[0];
            }  
            if($report_sub_industry_id!=''){
                $SubIndustryData = SubIndustry::where('id',$report_sub_industry_id)->pluck('initial');
                $sub_industry_initial = $SubIndustryData[0];
            }
            $combination = $sector_code.$sub_industry_initial;
            $report_count = $this->getReportCount($report_sub_industry_id,$report_type,$combination);

            $sku = "SQMI".$country_code.$sector_code.$sub_industry_initial.$report_count;
            
            $isSKUExist = checkReportSKU($sku);
            if(isset($isSKUExist) && ($isSKUExist->count() > 0)){
                $error_str .= "SKU : ".$sku." already exists., ";
            }

            // price check
            for($k=0;$k<count($pricingArr);$k++){
                switch($k){
                    case '2':
                    case '5':
                    case '8':
                    case '11':
                    case '14':
                    case '17':
                    case '20':
                    case '23':
                    case '26':
                    case '29':
                    case '32':
                    case '35':
                        if(!is_numeric($pricingArr[$k])){
                            $error_str .= "Price : ".$pricingArr[$k]." is invalid., ";
                        }
                        break;
                }             
            }

            if($error_str!=''){
                return $error_str;
            }
        
            $dbData['publish_date'] = date("Y-m-d");
            $dbData['report_type'] = 'SD';
            $dbData['product_id'] = $sku;

            $reportData = Report::create($dbData);

            if($reportData->id){
                //upload image from url            
                /*if($dbData['image'] != '' && $dbData['image'] != 'Null'){
                    $contents = file_get_contents($dbData['image']);
                    $imageName = substr($dbData['image'], strrpos($dbData['image'], '/') + 1);
                    Storage::put($imageName, $contents);
                    $path = storage_path() .'/app/'. $imageName;
                    $folder = config('cloudinary.upload_preset') . config('constants.REPORT_PATH');
                    try {
                        $uploadedImg = cloudinary()->upload($path,['folder' => $folder])->getSecurePath();
                        $reportData->image = $uploadedImg;
                        $reportData->save();
                        Storage::delete($imageName);
                    } catch (\Exception $e) {
        
                    }
                }*/
                // insert into report_segments
                $report_id = $reportData->id;
                $report_name = $reportData->name;
                for($k=0;$k<count($segmentArr);$k++){
                    if(isset($segmentArr[$k]) && isset($sub_segmentArr[$k])){
                        $segmentData = array();
                        $segmentData['report_id'] = $reportData->id;
                        $segmentData['name'] = $segmentArr[$k];
                        $segmentData['value'] = $sub_segmentArr[$k];
                        ReportSegment::create($segmentData);
                    }
                }
                // insert into report_pricing
                for($k=0;$k<count($pricingArr);$k++){
                    $pricingData = array();
                    $pricingData['report_id'] = $reportData->id;
                    $pricingData['license_type'] = $pricingArr[$k];
                    $pricingData['file_type'] = $pricingArr[++$k];
                    $pricingData['price'] = $pricingArr[++$k];
                    ReportPricing::create($pricingData);
                }

                // insert into report_metrics
                if(count($metrics)>0){
                    if(isset($metrics['startyear']) && isset($metrics['startyear_size'])) {
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Market size value in '.$metrics['startyear'];
                        $metricsArr['meta_value'] = $metrics['startyear_size'];            
                        ReportMetrics::create($metricsArr);
                    }

                    if(isset($metrics['endyear']) && isset($metrics['endyear_size'])) {
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Market size value in '.$metrics['endyear'];
                        $metricsArr['meta_value'] = $metrics['endyear_size'];                    
                        ReportMetrics::create($metricsArr);
                    }

                    if(isset($metrics['growth_rate'])) {
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Growth Rate';
                        $metricsArr['meta_value'] = $metrics['growth_rate'];                    
                        ReportMetrics::create($metricsArr);
                    }

                    if(isset($metrics['startyear'])) {
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Base year';
                        $metricsArr['meta_value'] = $metrics['startyear'];                    
                        ReportMetrics::create($metricsArr);
                    }

                    if(isset($metrics['forecast_period'])) {
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Forecast period';
                        $metricsArr['meta_value'] = $metrics['forecast_period'];                    
                        ReportMetrics::create($metricsArr);
                    }

                    if(isset($metrics['forecast_unit'])) {
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Forecast Unit (Value)';
                        $metricsArr['meta_value'] = $metrics['forecast_unit'];                    
                        ReportMetrics::create($metricsArr);
                    }
                                
                    if(isset($segmentArr) && (count($segmentArr) > 0)) {
                        $segmentDataArr = '<ul>';
                        for($k=0;$k<count($segmentArr);$k++){
                            if(isset($segmentArr[$k]) && isset($sub_segmentArr[$k])){
                                $segmentDataArr .= '<li>'.$segmentArr[$k];
                                $segmentDataArr .= '<ul>';
                                $segmentDataArr .= '<li>'.$sub_segmentArr[$k].'</li>';
                                $segmentDataArr .= '</ul>';
                                $segmentDataArr .= '</li>';
                            }
                        }
                        $segmentDataArr .= '</ul>';

                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Segments covered';
                        $metricsArr['meta_value'] = $segmentDataArr;              
                        ReportMetrics::create($metricsArr);
                    }

                    // for Regions covered
                    $regions_covered = "North America (US, Canada), Europe (Germany, France, United Kingdom, Italy, Spain, Rest of Europe), Asia Pacific (China, India, Japan, Rest of Asia-Pacific), Latin America (Brazil, Rest of Latin America), Middle East & Africa (South Africa, GCC Countries, Rest of MEA)";
                    $metricsArr = array();
                    $metricsArr['report_id'] = $report_id;
                    $metricsArr['meta_key'] = 'Regions covered';
                    $metricsArr['meta_value'] = $regions_covered;
                    ReportMetrics::create($metricsArr);

                    // for Companies covered
                    if(isset($metrics['companies_covered']) && count($metrics['companies_covered'])>0) {
                        $companies_covered = "<ul>".implode(" ",$metrics['companies_covered'])."</ul>";
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Companies covered';
                        $metricsArr['meta_value'] = $companies_covered;                    
                        ReportMetrics::create($metricsArr);
                    }

                    // for Customization scope            
                    $customization_scope = '<p>Free report customization with purchase. Customization includes:-
                    <ul><li>Segments by type, application, etc</li><li>Company profile</li><li>Market dynamics & outlook</li><li>Region</li></ul></p>';            
                    $metricsArr = array();
                    $metricsArr['report_id'] = $report_id;
                    $metricsArr['meta_key'] = 'Customization scope';
                    $metricsArr['meta_value'] = $customization_scope;                    
                    ReportMetrics::create($metricsArr);
                    
                }

                // insert into report_faq
                for($k=0;$k<count($faq);$k++){
                    if(($faq[$k]['faq_question']!='') && ($faq[$k]['faq_answer']!='')){
                        if($k==0){
                            $updated_name = str_replace('Global',"",$report_name);
                            $que = $faq[$k]['faq_question'].$updated_name."?";
                        } else {
                            $que = $faq[$k]['faq_question'].$report_name."?";
                        }

                        $faqData = array();
                        $faqData['report_id'] = $report_id;
                        $faqData['faq_question'] = $que;
                        $faqData['faq_answer'] = $faq[$k]['faq_answer'];
                        $faqData['is_auto'] = '1';
                        ReportFaq::create($faqData);
                    }
                }

                // insert into report_tableofcontent
                // for TOC field - starts
                // static text
                $static_toc = "<ul><li><b>Executive Summary</b>";
                $static_toc .= "<ul><li>Market Overview</li><li>Wheel of Fortune</li></ul></li>";        
                $static_toc .= "<li><b>Research Methodology</b>";
                $static_toc .= "<ul><li>Information Procurement</li><li>Secondary & Primary Data Sources</li><li>Market Size Estimation</li><li>Market Assumptions & Limitations</li></ul></li>";
                $static_toc .= "<li><b>Parent Market Analysis</b>";
                $static_toc .= "<ul><li>Market Overview</li><li>Market Size</li><li>Market Dynamics<ul><li>Drivers</li><li>Opportunities</li><li>Restraints</li><li>Challenges</li></ul></li></ul></li>";
                $static_toc .= "<li><b>Key Market Insights</b>";
                $static_toc .= "<ul><li>Technology Analysis</li><li>Pricing Analysis</li><li>Supply Chain Analysis</li><li>Value Chain Analysis</li><li>Ecosystem of the Market</li><li>IP Analysis</li><li>Trade Analysis</li><li>Startup Analysis</li><li>Raw Material Analysis</li><li>Innovation Matrix</li><li>Pipeline Product Analysis</li><li>Macroeconomic Indicators</li><li>Top Investment Analysis</li><li>Key Success Factor</li><li>Degree of Competition</li></ul></li>";
                $static_toc .= "<li><b>Market Dynamics & Outlook</b>";
                $static_toc .= "<ul><li>Market Dynamics<ul><li>Drivers</li><li>Opportunities</li><li>Restraints</li><li>Challenges</li></ul></li>";
                $static_toc .= "<li>Regulatory Landscape</li><li>Porters Analysis<ul><li>Competitive rivalry</li><li>Threat of Substitute Products</li><li>Bargaining Power of Buyers</li><li>Threat of New Entrants</li><li>Bargaining Power of Suppliers</li></ul></li>";
                $static_toc .= "<li>Skyquest Special Insights on Future Disruptions<ul><li>Political Impact</li><li>Economic Impact</li><li>Social Impact</li><li>Technical Impact</li><li>Environmental Impact</li><li>Legal Impact</li></ul></li></ul></li>";
                
                // for all segments
                for($k=0;$k<count($segmentArr);$k++){
                    if(isset($segmentArr[$k]) && isset($sub_segmentArr[$k])){
                        $sub_segmentData = array();
                        $sub_segment_str = $sub_segmentArr[$k];
                        $sub_segmentData = explode(",",$sub_segment_str);

                        $static_toc .= "<li><b>".$report_name." by ".$segmentArr[$k]."</b>";
                        if(count($sub_segmentData)>0)
                        {
                            $static_toc .= "<ul><li>Market Overview</li>";
                        }
                        for($s=0;$s<count($sub_segmentData);$s++)
                        {
                            $static_toc .= "<li>".trim($sub_segmentData[$s])."</li>";
                            if($s==(count($sub_segmentData)-1))
                            {
                                $static_toc .= "</ul>";
                            }
                        }
                        $static_toc .= "</li>";
                    }
                }

                // static text by Region
                $static_toc .= "<li><b>".$report_name." Size by Region</b>";
                $static_toc .= "<ul><li>Market Overview</li><li>North America<ul><li>USA</li><li>Canada</li></ul></li><li>Europe<ul><li>Germany</li><li>Spain</li><li>France</li><li>UK</li><li>Rest of Europe</li></ul></li><li>Asia Pacific<ul><li>China</li><li>India</li><li>Japan</li><li>South Korea</li><li>Rest of Asia-Pacific</li></ul></li><li>Latin America<ul><li>Brazil</li><li>Rest of Latin America</li></ul></li><li>Middle East & Africa (MEA)<ul><li>GCC Countries</li><li>South Africa</li><li>Rest of MEA</li></ul></li></ul></li>";
                
                // static text for Competitive Landscape
                $static_toc .= "<li><b>Competitive Landscape</b>";
                $static_toc .= "<ul><li>Top 5 Player Comparison</li><li>Market Positioning of Key Players, 2021</li><li>Strategies Adopted by Key Market Players</li><li>Top Winning Strategies<ul><li>By Development</li><li>By Company</li><li>By Year</li></ul></li><li>Recent Activities in the Market</li><li>Key Companies Market Share (%), 2021</li></ul></li>";
                
                // for Key Company Profiles
                $static_toc .= "<li><b>Key Company Profiles</b>";
                if(isset($metrics['companies_covered']) && count($metrics['companies_covered'])>0) {                  
                    $static_toc .= "<ul>";
                    $static_text_company_profiles = "<ul><li>Company Overview</li><li>Business Segment Overview</li><li>Financial Updates</li><li>Key Developments</li></ul>";
                    for($c=0;$c<count($metrics['companies_covered']);$c++)
                    {
                        $company_name = trim(str_replace('<li>','',str_replace('</li>','',$metrics['companies_covered'][$c])));
                        $static_toc .= "<li>".$company_name.$static_text_company_profiles."</li>";
                    }
                    $static_toc .= "</ul>";
                }
                $static_toc .= "</li>";
                // for TOC field - ends

                // for tables field - starts
                // static text
                //$static_tables = "<h3>List of Tables</h3>";
                $static_tables = "<p>Table 1. ".$report_name." Size, 2021-2028 (USD Million)</p>";
                $static_tables .= "<p>Table 2. ".$report_name." Regulatory Landscape</p>";
                $static_tables .= "<p>Table 3. ".$report_name." IP Analysis</p>";

                // for all segments
                $table_no = 4;
                for($k=0;$k<count($segmentArr);$k++){
                    if(isset($segmentArr[$k]) && isset($sub_segmentArr[$k])){
                        $sub_segmentData = array();
                        $sub_segment_str = $sub_segmentArr[$k];
                        $sub_segmentData = explode(",",$sub_segment_str);

                        $static_tables .= "<p>Table ".$table_no++.". ".$report_name." Research And Analysis By ".$segmentArr[$k].", 2021-2028 (USD Million)</p>";            
                        for($s=0;$s<count($sub_segmentData);$s++)
                        {
                            $static_tables .= "<p>Table ".$table_no++.". ".$report_name." For ".trim($sub_segmentData[$s]).", 2021-2028 (USD Million)</p>";
                        }
                    }
                }

                $static_tables .= "<p>Table ".$table_no++.". ".$report_name." Research And Analysis By Region, 2021-2028 (USD Million)</p>";

                // for all segments by country
                $countries = array("North America",array("US","Canada"),"European",array("UK","Germany","France","Italy","Spain","Rest Of Europe"),"Asia-Pacific",array("China","India","Japan","South Korea","Rest Of Asia-Pacific"),"Latin America",array("Brazil","Rest Of Latin America"),"Middle East And Africa",array("GCC Countries","South Africa","Rest Of Middle East And Africa"));
                for($c=0;$c<count($countries);$c++){
                    if(!is_array($countries[$c]))
                    {
                        $static_tables .= "<p>Table ".$table_no++.". ".$countries[$c]." ".$report_name." Research And Analysis By Country, 2021-2028 (USD Million)</p>";
                        for($k=0;$k<count($segmentArr);$k++){
                            if(isset($segmentArr[$k])){
                                $static_tables .= "<p>Table ".$table_no++.". ".$countries[$c]." ".$report_name." Research And Analysis By ".$segmentArr[$k].", 2021-2028 (USD Million)</p>";
                            }
                        }
                    } else {
                        for($s=0;$s<count($countries[$c]);$s++){
                            for($k=0;$k<count($segmentArr);$k++){
                                if(isset($segmentArr[$k])){
                                    $static_tables .= "<p>Table ".$table_no++.". ".$countries[$c][$s]." ".$report_name." Research And Analysis By ".$segmentArr[$k].", 2021-2028 (USD Million)</p>";
                                }
                            }
                        }
                    }            
                }
                // for tables field - ends

                // for figures field - starts
                // static text
                //$static_figures = "<h3>List of Figures</h3>";
                $static_figures = "<p>Figure 1. ".$report_name." Size, 2021-2028 (USD Million)</p>";
                $static_figures .= "<p>Figure 2. ".$report_name." Wheel Of Fortune</p>";
                $static_figures .= "<p>Figure 3. ".$report_name." Parent Market Analysis</p>";
                $static_figures .= "<p>Figure 4. ".$report_name." Technology Analysis</p>";
                $static_figures .= "<p>Figure 5. ".$report_name." Pricing Analysis</p>";
                $static_figures .= "<p>Figure 6. ".$report_name." Supply Chain Analysis</p>";
                $static_figures .= "<p>Figure 7. ".$report_name." Value Chain Analysis</p>";
                $static_figures .= "<p>Figure 8. ".$report_name." Ecosystem Of The Market</p>";
                $static_figures .= "<p>Figure 9. ".$report_name." Trade Analysis</p>";
                $static_figures .= "<p>Figure 10. ".$report_name." Startup Analysis</p>";
                $static_figures .= "<p>Figure 11. ".$report_name." Raw Material Analysis</p>";
                $static_figures .= "<p>Figure 12. ".$report_name." Innovation Matrix</p>";
                $static_figures .= "<p>Figure 13. ".$report_name." Pipeline Product Analysis</p>";
                $static_figures .= "<p>Figure 14. ".$report_name." Macroeconomic Indicators</p>";
                $static_figures .= "<p>Figure 15. ".$report_name." Top Investment Analysis</p>";
                $static_figures .= "<p>Figure 16. ".$report_name." Key Success Factor</p>";
                $static_figures .= "<p>Figure 17. ".$report_name." Degree Of Competition</p>";
                $static_figures .= "<p>Figure 18. ".$report_name." Porter And Its Impact Analysis</p>";
                $static_figures .= "<p>Figure 19. ".$report_name." Skyquest Special Insights On Future Disruptions</p>";
                
                // for all segments
                $figure_no = 20;
                $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Share By Segment, 2021 Vs 2028 (%)</p>";
                for($k=0;$k<count($segmentArr);$k++){
                    if(isset($segmentArr[$k])){
                        $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Share By ".$segmentArr[$k].", 2021 Vs 2028 (%)</p>";
                    }
                }

                // by country
                $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Share By Region, 2021 Vs 2028 (%)</p>";
                
                $countries = array("North America",array("US","Canada"),"Europe",array("UK","Germany","France","Italy","Spain","Rest Of Europe"),"Asia-Pacific",array("China","India","Japan","South Korea","Rest Of Asia-Pacific"),"Latin America",array("Brazil","Rest Of Latin America"),"Middle East And Africa",array("GCC","South Africa","Rest Of Middle East And Africa"));
                for($c=0;$c<count($countries);$c++){
                    if(!is_array($countries[$c]))
                    {
                        $static_figures .= "<p>Figure ".$figure_no++.". ".$countries[$c]." ".$report_name." Share By Country, 2021-2028 (USD Million)</p>";
                    } else {
                        for($s=0;$s<count($countries[$c]);$s++){
                            $static_figures .= "<p>Figure ".$figure_no++.". ".$countries[$c][$s]." ".$report_name." Size, 2021-2028 (USD Million)</p>";
                        }
                    }            
                }

                // static text
                $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Top 5 Player Comparison</p>";
                $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Positioning Of Key Players, 2021</p>";
                $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Strategies Adopted By Key Market Players</p>";
                $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Top Winning Strategies</p>";
                $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." By Development</p>";
                $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." By Company</p>";
                $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." By Year</p>";
                $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Recent Activities In The Market</p>";
                $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Key Companies Market Share (%), 2021</p>";
                
                // by company
                if(isset($metrics['companies_covered']) && count($metrics['companies_covered'])>0) {
                    for($c=0;$c<count($metrics['companies_covered']);$c++)
                    {
                        $company_name = trim(str_replace('<li>','',str_replace('</li>','',$metrics['companies_covered'][$c])));
                        $static_figures .= "<p>Figure ".$figure_no++.". ".$company_name." Company Overview & Information</p>";
                    }
                }
                // for figures field - ends

                // save to report_tableofcontent
                $tocData = array();
                $tocData['report_id'] = $report_id;
                $tocData['toc'] = $static_toc;
                $tocData['tables'] = $static_tables;
                $tocData['figures'] = $static_figures;
                ReportTableofcontent::create($tocData);
            }
        }                                                                                                                                                                                                                                                                                                                   

        // for Dynamic report type
        if($report_type=='Dynamic'){
            // check title for all mandatory fields
            //$mandatory_fields = array("Report Name","Sector","Industry Group","Industry","Sub-Industry","Country","Report Slug","Report Type","Description");
            $mandatory_fields = array("Report Name","Segments","Image","Sector","Industry Group","Industry","Sub-Industry","Country","Download","Image Alt","Report Slug","Meta Title","Meta Description","Pages","Report Type","Description","Table of Content");
            for($i=0;$i<count($mandatory_fields);$i++){
                if(!in_array($mandatory_fields[$i],$titleArray)){
                    $error_str .= $mandatory_fields[$i]. " does not exist., ";
                }
            }

            // if uploaded file not in pre defined format
            if($error_str!=''){
                return $error_str;
            }
            
            // store in DB
            $segmentArr = array();
            $sub_segmentArr = array();
            $pricingArr = array();
            $faq = array();
            $dbData = array();
            $TOC_Data = array();
            $FAQ_Questions = array();
            $FAQ_Answers = array();
            $f = 0;
            foreach($mainArray as $a => $inner1) {
                if($inner1['title']=='Report Name'){
                    $title = 'name';
                    if(isset($inner1['data'][0])) {
                        $content = "";       
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Report Name does not exist., ";
                        }
                    }else{
                        $error_str .= "Report Name does not exist., ";
                    }
                }
                else if($inner1['title']=='Image'){
                    $title = 'image';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Image does not exist., ";
                        }
                    }else{
                        $error_str .= "Image does not exist., ";
                    }
                }
                else if($inner1['title']=='Country'){
                    $title = 'country';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        $report_country = $content;
                        if(!$content){
                            $error_str .= "Country does not exist., ";
                        }                    
                    }else{
                        $error_str .= "Country does not exist., ";
                    }
                }
                else if($inner1['title']=='Download'){
                    $title = 'download';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){           
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Download does not exist., ";
                        }
                    }else{
                        $error_str .= "Download does not exist., ";
                    }
                }
                else if($inner1['title']=='Image Alt'){
                    $title = 'image_alt';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){           
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Image Alt does not exist., ";
                        }
                    }else{
                        $error_str .= "Image Alt does not exist., ";
                    }
                }
                else if($inner1['title']=='Report Slug'){
                    $title = 'slug';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){           
                            $content .= $inner2;
                        }
                        if(strtolower($content)=='null'){
                            $error_str .= "Report Slug can not be NULL., ";
                        } elseif(trim($content)==''){
                            $error_str .= "Report Slug does not exist., ";
                        } else{
                            // check slug already exists 
                            $reportSlug = checkReportSlug($content);                        
                            if(isset($reportSlug) && ($reportSlug->count() > 0)){
                                $error_str .= "Slug : ".$content." already exists., ";
                            }
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Report Slug does not exist., ";
                        }                    
                    }else{
                        $error_str .= "Report Slug does not exist., ";
                    }
                }
                else if($inner1['title']=='Meta Title'){
                    $title = 'meta_title';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){           
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Meta Title does not exist., ";
                        }
                    }else{
                        $error_str .= "Meta Title does not exist., ";
                    }
                }
                else if($inner1['title']=='Meta Description'){
                    $title = 'meta_description';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){           
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Meta Description does not exist., ";
                        }
                    }else{
                        $error_str .= "Meta Description does not exist., ";
                    }
                }
                else if($inner1['title']=='Pages'){
                    $title = 'pages';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){           
                            $content .= $inner2;
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Pages does not exist., ";
                        }
                    }else{
                        $error_str .= "Pages does not exist., ";
                    }
                }
                else if($inner1['title']=='Methodologies'){
                    $title = 'methodologies';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){
                            if(!is_array($inner2)) {
                                $content .= "<p>".$inner2."</p>";
                            } else {
                                $content .= "<ul>";
                                    foreach($inner2 as $d => $inner3) {
                                        $content .= "<li>".$inner3."</li>";
                                    }
                                $content .= "</ul>";
                            }
                        }
                        $dbData[$title] = $content;
                    }
                }
                else if($inner1['title']=='Analyst Support'){
                    $title = 'analyst_support';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){
                            if(!is_array($inner2)) {
                                $content .= "<p>".$inner2."</p>";                        
                            } else {
                                $content .= "<ul>";
                                    foreach($inner2 as $d => $inner3) {
                                        $content .= "<li>".$inner3."</li>";
                                    }
                                $content .= "</ul>";
                            }
                        }
                        $dbData[$title] = $content;
                    }
                }
                else if($inner1['title']=='Description'){
                    $title = 'description';
                    if(isset($inner1['data'][0])) {
                        $content = "";           
                        $prev_str = "";
                        $list_key = 0;
                        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)+<\/p>/";
                        $reg_exUrl_li = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)+<\/li>/";
                        $url = array();
                        $url_li = array();
                        foreach($inner1['data'] as $b => $inner2){        
                            if(!is_array($inner2)) {
                                $prev_str = $inner2;
                                $content .= "<p>".$inner2."</p>";                                
                            } else {                             
                                $content .= "<ul>";
                                foreach($inner2 as $d => $inner3) {
                                    $content .= "<li>".$inner3."</li>";                                    
                                }
                                $content .= "</ul>";
                            }
                        }

                        // replace URLs with image tags
                        if(preg_match_all($reg_exUrl, $content, $url))
                        {
                            if(count($url)>0){
                                for($l=0;$l<count($url[0]);$l++){
                                    $content = str_replace($url[0][$l], "<img src='".strip_tags($url[0][$l])."' /></p>", $content);
                                    
                                }
                            }
                        }

                        if(preg_match_all($reg_exUrl_li, $content, $url_li))
                        {
                            if(count($url_li)>0){
                                for($l=0;$l<count($url_li[0]);$l++){
                                    $content = str_replace($url_li[0][$l], "<img src='".strip_tags($url_li[0][$l])."' /></p>", $content);
                                    
                                }
                            }
                        }
                        $dbData[$title] = $content;
                        if(!$content){
                            $error_str .= "Description does not exist., ";
                        }                    
                    }else{
                        $error_str .= "Description does not exist., ";
                    }
                }
                else if($inner1['title']=='What\'s Included'){
                    $title = 'whats_included';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){
                            if(!is_array($inner2)) {
                                $content .= "<p>".$inner2."</p>";                        
                            } else {
                                $content .= "<ul>";
                                    foreach($inner2 as $d => $inner3) {
                                        $content .= "<li>".$inner3."</li>";
                                    }
                                $content .= "</ul>";
                            }
                        }
                        $dbData[$title] = $content;
                    }
                }
                else if($inner1['title']=='Sector'){
                    $title = 'sector_id';
                    if(isset($inner1['data'][0])) {
                        $content = "";              
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        if(!$content){
                            $error_str .= "Sector does not exist., ";
                        }
                        $sectorData = Sector::where('title',$content)->first();
                        if(!(isset($sectorData)) && $content!=''){
                            $error_str .= "Sector : ".$content." does not exist in our records., ";
                        }                    
                        $report_sector_id = (isset($sectorData)) ? $sectorData->id : '';
                        $dbData[$title] = (isset($sectorData)) ? $sectorData->id : 0;
                    }else{
                        $error_str .= "Sector does not exist., ";
                    }
                }
                else if($inner1['title']=='Industry Group'){
                    $title = 'industry_group_id';
                    if(isset($inner1['data'][0])) {
                        $content = "";              
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        if(!$content){
                            $error_str .= "Industry Group does not exist., ";
                        }
                        $IndustryGroupData = IndustryGroup::where('title',$content)->first();
                        if(!(isset($IndustryGroupData)) && $content!=''){
                            $error_str .= "Industry Group : ".$content." does not exist in our records., ";
                        }
                        if($report_sector_id && $content!=''){
                            $IndustryGroupData = IndustryGroup::where('title',$content)->where('sector_id',$report_sector_id)->first();
                            if(!(isset($IndustryGroupData))){
                                $error_str .= "Industry Group : ".$content." does not map with given sector., ";
                            }
                        }
                        $report_industry_group_id = (isset($IndustryGroupData)) ? $IndustryGroupData->id : '';
                        $dbData[$title] = (isset($IndustryGroupData)) ? $IndustryGroupData->id : 0;
                    }else{
                        $error_str .= "Industry Group does not exist., ";
                    }
                }
                else if($inner1['title']=='Industry'){
                    $title = 'industry_id';
                    if(isset($inner1['data'][0])) {
                        $content = "";              
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        if(!$content){
                            $error_str .= "Industry does not exist., ";
                        }
                        $IndustryData = Industry::where('title',$content)->first();
                        if(!(isset($IndustryData)) && $content!=''){
                            $error_str .= "Industry : ".$content." does not exist in our records., ";
                        }
                        if($report_industry_group_id){
                            $IndustryData = Industry::where('title',$content)->where('industry_group_id',$report_industry_group_id)->first();
                            if(!(isset($IndustryData)) && $content!=''){
                                $error_str .= "Industry : ".$content." does not map with given industry group., ";
                            }
                        }
                        $report_industry_id = (isset($IndustryData)) ? $IndustryData->id : '';
                        $dbData[$title] = (isset($IndustryData)) ? $IndustryData->id : 0;
                    }else{
                        $error_str .= "Industry does not exist., ";
                    }
                }
                else if($inner1['title']=='Sub-Industry'){
                    $title = 'sub_industry_id';
                    if(isset($inner1['data'][0])) {
                        $content = "";              
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        if(!$content){
                            $error_str .= "Sub-Industry does not exist., ";
                        }
                        $SubIndustryData = SubIndustry::where('title',$content)->first();
                        if(!(isset($SubIndustryData)) && $content!=''){
                            $error_str .= "Sub-Industry : ".$content." does not exist in our records., ";
                        }
                        if($report_industry_id && $content!=''){
                            $SubIndustryData = SubIndustry::where('title',$content)->where('industry_id',$report_industry_id)->first();
                            if(!(isset($SubIndustryData))){
                                $error_str .= "Sub-Industry : ".$content." does not map with given industry., ";
                            }
                        }
                        $report_sub_industry_id = (isset($SubIndustryData)) ? $SubIndustryData->id : '';
                        $dbData[$title] = (isset($SubIndustryData)) ? $SubIndustryData->id : 0;
                    }else{
                        $error_str .= "Sub-Industry does not exist., ";
                    }
                }
                else if(strtolower($inner1['title'])=='segment'){
                    if(isset($inner1['data'][0])) {
                        $content = "";              
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        $segmentArr[] = $content;
                        if(!$content){
                            $error_str .= "Segment does not exist., ";
                        }
                    }else{
                        $error_str .= "Segment does not exist., ";
                    }
                }
                else if((strtolower($inner1['title'])=='sub-segments') || (strtolower($inner1['title'])=='sub-segment')){
                    if(isset($inner1['data'][0])) {
                        $content = "";              
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        $sub_segmentArr[] = $content;
                        if(!$content){
                            $error_str .= "Sub-segment does not exist., ";
                        }
                    }else{
                        $error_str .= "Sub-segment does not exist., ";
                    }
                }
                else if($inner1['title']=='Report Type'){
                    if(isset($inner1['data'][0])) {
                        $content = array();
                        foreach($inner1['data'] as $b => $inner2){
                            if($inner2!=''){
                                $content[] = $inner2;
                            }
                        }
                        $pricingArr = $content;
                        if(!$content){
                            $error_str .= "Report Type does not exist., ";
                        }
                    }else{
                        $error_str .= "Report Type does not exist., ";
                    }
                }
                else if($inner1['title']=='Table of Content'){
                    $title = 'toc';
                    if(isset($inner1['data'][0])) {
                        $content = "";                    
                        foreach($inner1['data'] as $b => $inner2){
                            if(!is_array($inner2)) {
                                $content .= "<p>".$inner2."</p>";                        
                            } else {
                                $content .= "<ul>";
                                    foreach($inner2 as $d => $inner3) {
                                        $content .= "<li>".$inner3."</li>";
                                    }
                                $content .= "</ul>";
                            }
                        }
                        $TOC_Data[$title] = $content;
                        if(!$content){
                            $error_str .= "Table of Content does not exist., ";
                        }
                    }else{
                        $error_str .= "Table of Content does not exist., ";
                    }
                }
                else if($inner1['title']=='FAQ Question'){
                    if(isset($inner1['data'][0])) {
                        $content = "";              
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        $FAQ_Questions[] = $content;
                    }
                }
                else if($inner1['title']=='FAQ Answer'){
                    if(isset($inner1['data'][0])) {
                        $content = "";              
                        foreach($inner1['data'] as $b => $inner2){
                            $content .= $inner2;
                        }
                        $FAQ_Answers[] = $content;
                    }
                }
            }        

            // generate product_id or SKU
            $country_code = '';
            $sector_code = '';
            $sub_industry_initial = '';
            $report_count = '';

            $country_code = ($report_country!='') ? substr($report_country,0,1) : '';
            if($report_sector_id!=''){
                $sectorData = Sector::where('id',$report_sector_id)->pluck('code');      
                $sector_code = $sectorData[0];
            }  
            if($report_sub_industry_id!=''){
                $SubIndustryData = SubIndustry::where('id',$report_sub_industry_id)->pluck('initial');
                $sub_industry_initial = $SubIndustryData[0];
            }
            $combination = $sector_code.$sub_industry_initial;
            $report_count = $this->getReportCount($report_sub_industry_id,$report_type,$combination);

            $sku = "SQMI".$country_code.$sector_code.$sub_industry_initial.$report_count;

            $isSKUExist = checkReportSKU($sku);
            if(isset($isSKUExist) && ($isSKUExist->count() > 0)){
                $error_str .= "SKU : ".$sku." already exists., ";
            }

            // price check
            for($k=0;$k<count($pricingArr);$k++){
                switch($k){
                    case '2':
                    case '5':
                    case '8':
                    case '11':
                    case '14':
                    case '17':
                    case '20':
                    case '23':
                    case '26':
                    case '29':
                    case '32':
                    case '35':
                        if(!is_numeric($pricingArr[$k])){
                            $error_str .= "Price : ".$pricingArr[$k]." is invalid., ";
                        }
                        break;
                }             
            }

            if($error_str!=''){
                return $error_str;
            }

            $dbData['publish_date'] = date("Y-m-d");
            $dbData['report_type'] = 'Dynamic';
            $dbData['product_id'] = $sku;

            $reportData = Report::create($dbData);

            if($reportData->id){
                //upload image from url            
                /*if($dbData['image'] != '' && $dbData['image'] != 'Null'){
                    $contents = file_get_contents($dbData['image']);
                    $imageName = substr($dbData['image'], strrpos($dbData['image'], '/') + 1);
                    Storage::put($imageName, $contents);
                    $path = storage_path() .'/app/'. $imageName;
                    $folder = config('cloudinary.upload_preset') . config('constants.REPORT_PATH');
                    try {
                        $uploadedImg = cloudinary()->upload($path,['folder' => $folder])->getSecurePath();
                        $reportData->image = $uploadedImg;
                        $reportData->save();
                        Storage::delete($imageName);
                    } catch (\Exception $e) {
        
                    }
                }*/
                // insert into report_segments
                $report_id = $reportData->id;
                $report_name = $reportData->name;
                for($k=0;$k<count($segmentArr);$k++){
                    if(isset($segmentArr[$k]) && isset($sub_segmentArr[$k])){
                        $segmentData = array();
                        $segmentData['report_id'] = $reportData->id;
                        $segmentData['name'] = $segmentArr[$k];
                        $segmentData['value'] = $sub_segmentArr[$k];
                        ReportSegment::create($segmentData);
                    }
                }
                // insert into report_pricing
                for($k=0;$k<count($pricingArr);$k++){
                    $pricingData = array();
                    $pricingData['report_id'] = $reportData->id;
                    $pricingData['license_type'] = $pricingArr[$k];
                    $pricingData['file_type'] = $pricingArr[++$k];
                    $pricingData['price'] = $pricingArr[++$k];
                    ReportPricing::create($pricingData);
                }
                
                // insert into report_faq
                for($k=0;$k<count($FAQ_Questions);$k++){
                    if(isset($FAQ_Questions[$k]) && isset($FAQ_Answers[$k])){
                        $faqData = array();
                        $faqData['report_id'] = $report_id;
                        $faqData['faq_question'] = $FAQ_Questions[$k];
                        $faqData['faq_answer'] = $FAQ_Answers[$k];
                        $faqData['is_auto'] = '1';
                        ReportFaq::create($faqData);
                    }
                }
                // insert into report_tableofcontent
                if(isset($TOC_Data) && isset($TOC_Data['toc'])){
                    $tocData = array();
                    $tocData['report_id'] = $report_id;
                    $tocData['toc'] = $TOC_Data['toc'];
                    $tocData['tables'] = "";
                    $tocData['figures'] = "";
                    ReportTableofcontent::create($tocData);
                }
            }
        }
    }

    public function revamptoDB($file,$mode,$report_type)
    {
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($file);
        
        $sections = $phpWord->getSections();
        $mainArray = array();
        $error_str = "";
        $titleArray = array();

        $i = 0;
        
        if(count($sections)==0){
            return array('error',"Unable to read a file.");
        }

        foreach ($sections as $section) {
            $elements = $section->getElements();
            foreach ($elements as $element) {
                switch(get_class($element)) {
                    case 'PhpOffice\PhpWord\Element\Title':
                        if(get_class($element)==='PhpOffice\PhpWord\Element\TextBreak'){
                        } 
                        else if (get_class($element) === 'PhpOffice\PhpWord\Element\TextRun') {
                            $title_text = "";
                            $textRunElements = $element->getElements();
                            foreach ($textRunElements as $textRunElement) {
                                $title_text .= html_entity_decode($textRunElement->getText());
                            }
                            if(trim($title_text)!=''){
                                $mainArray[$i]['title'] = trim($title_text);
                                $titleArray[] = trim($title_text);
                                $i++;
                            }
                        }
                        else{
                            if (is_object($element->getText())) {        
                                $title_text = "";
                                $text_runElement = $element->getText();
                                $textRunElements = $text_runElement->getElements();
                                foreach ($textRunElements as $textRunElement) {
                                    if(get_class($textRunElement)==='PhpOffice\PhpWord\Element\TextBreak'){
                                    }else{
                                        $title_text .= html_entity_decode($textRunElement->getText());
                                    }
                                }
                                if(trim($title_text)!=''){
                                    $mainArray[$i]['title'] = trim($title_text);
                                    $titleArray[] = trim($title_text);
                                    $i++;
                                }
                            } else {
                                if(trim($element->getText())!=''){
                                    $mainArray[$i]['title'] = html_entity_decode(trim($element->getText()));
                                    $titleArray[] = html_entity_decode(trim($element->getText()));
                                    $i++;
                                }
                            }
                        }
                        break;

                    case 'PhpOffice\PhpWord\Element\Text':
                        $format = $element->getFontStyle();
                        if(trim($element->getText())!=''){
                            if($format->isBold()){
                                $mainArray[$i-1]['data'][] = html_entity_decode("<b>".$element->getText()."</b>");
                            } else {
                                $mainArray[$i-1]['data'][] = html_entity_decode($element->getText());
                            }
                        }
                        break;

                    case 'PhpOffice\PhpWord\Element\TextRun':
                        $textRunElements = $element->getElements();
                        $textDataArr = "";
                        foreach ($textRunElements as $textRunElement) {
                            if(get_class($textRunElement)==='PhpOffice\PhpWord\Element\TextBreak'){
                            }
                            else if (get_class($textRunElement) === 'PhpOffice\PhpWord\Element\Link') {
                                $format = $textRunElement->getFontStyle();
                                $source = $textRunElement->getSource();
                                if($source!=''){
                                    $textDataArr .= "<a href='".$source."' target='_blank'>";
                                }
                                if($format->isBold()){
                                    $textDataArr .= html_entity_decode("<b>".$textRunElement->getText()."</b>");
                                } else {
                                    $textDataArr .= html_entity_decode($textRunElement->getText());
                                }
                                if($source!=''){
                                    $textDataArr .= "</a>";
                                }
                            } else if (get_class($textRunElement) === 'PhpOffice\PhpWord\Element\Text') {
                                $format = $textRunElement->getFontStyle();
                                if($format->isBold()){
                                    $textDataArr .= html_entity_decode("<b>".$textRunElement->getText()."</b>");
                                } else{
                                    $textDataArr .= html_entity_decode($textRunElement->getText());    
                                }
                            }
                        }
                        $mainArray[$i-1]['data'][] = $textDataArr;
                        break;

                    case 'PhpOffice\PhpWord\Element\TextBreak':
                        break;

                    case 'PhpOffice\PhpWord\Element\ListItemRun':
                        $textRunElements = $element->getElements();                       
                        $textDataArr = "";
                        foreach ($textRunElements as $textRunElement) {
                            if(get_class($textRunElement)==='PhpOffice\PhpWord\Element\TextBreak'){
                            }else{
                                $source = '';
                                $format = $textRunElement->getFontStyle();
                                if(get_class($textRunElement)==='PhpOffice\PhpWord\Element\Link'){
                                    $source = $textRunElement->getSource();
                                }
                                if($source!=''){
                                    $textDataArr .= "<a href='".$source."' target='_blank'>";
                                }
                                if($format->isBold()){
                                    $textDataArr .= html_entity_decode("<b>".$textRunElement->getText()."</b>");
                                } else{
                                    $textDataArr .= html_entity_decode($textRunElement->getText());
                                }     
                                if($source!=''){
                                    $textDataArr .= "</a>";
                                } 
                            }        
                        }
                        $mainArray[$i-1]['data'][]['list'] = $textDataArr;
                        break;

                    case 'PhpOffice\PhpWord\Element\Link':
                        $textRunElements = $element->getElements();
                        $textDataArr = "";
                        foreach ($textRunElements as $textRunElement) {
                            if(get_class($textRunElement)==='PhpOffice\PhpWord\Element\TextBreak'){
                            }else{
                                $format = $textRunElement->getFontStyle();
                                if($format->isBold()){
                                    $textDataArr .= html_entity_decode("<b>".$textRunElement->getText()."</b>");
                                } else{
                                    $textDataArr .= html_entity_decode($textRunElement->getText());
                                }
                            }
                        }
                        $mainArray[$i-1]['data'][] = $textDataArr;
                        break;
                }                
            }
        }
        
        // store in DB
        $reportData = array();
        // check SKU exists
        $doc_sku_title = ($mainArray[0]['title']!='') ? $mainArray[0]['title'] : '';
        $doc_sku = (array_key_exists('data',$mainArray[0]) && $mainArray[0]['data'][0]!='') ? $mainArray[0]['data'][0] : '';
        
        if(($doc_sku_title != 'SKU') || ($doc_sku == '')){           
            return array('no-sku');
        } else if(($doc_sku_title == 'SKU') || ($doc_sku != '')){
            // fetch report by SKU from DB
            $reportData = Report::where('product_id',$doc_sku)->first();
            
            if(!$reportData){
                return array('sku-notfound');
            }
        }
        
        $report_id = ($reportData) ? $reportData->id : '';

        // for SD report type
        if($report_type=='SD'){
            // check title for all mandatory fields
            //$mandatory_fields = array("Report Name","Sector","Industry Group","Industry","Sub-Industry","Country","Report Slug","Report Type","Market Insights","Regional Insights","Market Dynamics","Competitive Landscape","Key Market Trends");
            $mandatory_fields = array("Report Name","Segments","Image","Sector","Industry Group","Industry","Sub-Industry","Country","Download","Image Alt","Report Slug","Meta Title","Meta Description","Pages","Report Type","Methodologies","Analyst Support","Market Insights","Segmental Analysis","Regional Insights","Market Dynamics","Competitive Landscape","Key Market Trends","SkyQuest Analysis");
            for($i=0;$i<count($mandatory_fields);$i++){
                if(!in_array($mandatory_fields[$i],$titleArray)){
                    $error_str .= $mandatory_fields[$i]. " does not exist., ";
                }
            }

            // if uploaded file not in pre defined format
            if($error_str!=''){
                return $error_str;
            }

            if($reportData){
                $segmentArr = array();
                $sub_segmentArr = array();
                $pricingArr = array();           
                $metrics = array();
                $faq = array();
                $dbData = array();
                $f = 0;
                foreach($mainArray as $a => $inner1) {                        
                    if($inner1['title']=='Report Name'){
                        $title = 'name';
                        if(isset($inner1['data'][0])) {
                            $content = "";       
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Report Name does not exist., ";
                            }                        
                        }else{
                            $error_str .= "Report Name does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Image'){
                        $title = 'image';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Image does not exist., ";
                            }
                        }else{
                            $error_str .= "Image does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Country'){
                        $title = 'country';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            $report_country = $content;
                            if(!$content){
                                $error_str .= "Country does not exist., ";
                            }                        
                        }else{
                            $error_str .= "Country does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Download'){
                        $title = 'download';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){           
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Download does not exist., ";
                            }                        
                        }else{
                            $error_str .= "Download does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Image Alt'){
                        $title = 'image_alt';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){           
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Image Alt does not exist., ";
                            }                        
                        }else{
                            $error_str .= "Image Alt does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Report Slug'){
                        $title = 'slug';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){           
                                $content .= $inner2;
                            }
                            if(strtolower($content)=='null'){
                                $error_str .= "Report Slug can not be NULL., ";
                            } elseif(trim($content)==''){
                                $error_str .= "Report Slug does not exist., ";
                            } else{
                                // check slug already exists 
                                $reportSlug = checkReportSlug($content,'',$report_id);
                                if(isset($reportSlug) && ($reportSlug->count() > 0)){
                                    $error_str .= "Slug : ".$content." already exists., ";
                                }
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Report Slug does not exist., ";
                            }
                        }else{
                            $error_str .= "Report Slug does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Meta Title'){
                        $title = 'meta_title';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){           
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Meta Title does not exist., ";
                            }
                        }else{
                            $error_str .= "Meta Title does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Meta Description'){
                        $title = 'meta_description';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){           
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Meta Description does not exist., ";
                            }
                        }else{
                            $error_str .= "Meta Description does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Pages'){
                        $title = 'pages';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){           
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Pages does not exist., ";
                            }
                        }else{
                            $error_str .= "Pages does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Methodologies'){
                        $title = 'methodologies';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";
                                } else {
                                    $content .= "<ul>";
                                        foreach($inner2 as $d => $inner3) {
                                            $content .= "<li>".$inner3."</li>";
                                        }
                                    $content .= "</ul>";
                                }
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Methodologies does not exist., ";
                            }
                        }else{
                            $error_str .= "Methodologies does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Analyst Support'){
                        $title = 'analyst_support';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";                        
                                } else {
                                    $content .= "<ul>";
                                        foreach($inner2 as $d => $inner3) {
                                            $content .= "<li>".$inner3."</li>";
                                        }
                                    $content .= "</ul>";
                                }
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Analyst Support does not exist., ";
                            }
                        }else{
                            $error_str .= "Analyst Support does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Market Insights'){
                        $title = 'market_insights';      
                                 
                        if(isset($inner1['data'][0])) {
                            $content = "";
                            $faq_answer = "";
                            foreach($inner1['data'] as $b => $inner2){
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";  
                                    // get metrics data
                                    if($b==0)
                                    {   
                                        $inner2 = strip_tags($inner2);
                                        $contentArr = explode(" ",$inner2);
                                        if(is_array($contentArr) && count($contentArr) >0) {
                                            if(str_contains($inner2, "in")) {          
                                                $startyear_key = array_search("in",$contentArr);
                                                $metrics['startyear'] = ($startyear_key!='') ? rtrim($contentArr[$startyear_key+1],",") : '';
                                                if(!$metrics['startyear']) {
                                                    $error_str .= "Can not fetch start year of Market size value for Report Metric., ";
                                                }
                                            }else {
                                                $error_str .= "Can not fetch start year of Market size value for Report Metric., ";
                                            }

                                            if(str_contains($inner2, "CAGR")) {
                                                $growth_rate = trim($this->string_between_two_string($inner2, 'CAGR of', '%'));
                                                $metrics['growth_rate'] = ($growth_rate!='') ? $growth_rate."%" : '';
                                                if(!$metrics['growth_rate']) {
                                                    $error_str .= "Can not fetch growth rate for Report Metric., ";
                                                }
                                            }else {
                                                $error_str .= "Can not fetch growth rate for Report Metric., ";
                                            }

                                            if(str_contains($inner2, "period")) {
                                                $metrics['forecast_period'] = rtrim(ltrim(strip_tags(trim($this->string_between_two_string($inner2, 'period', '.')))));
                                                if(!$metrics['forecast_period']) {
                                                    $error_str .= "Can not fetch forecast period for Report Metric., ";
                                                }
                                            }else {
                                                $error_str .= "Can not fetch forecast period for Report Metric., ";
                                            }
                                        }

                                        if(str_contains($inner2, "by")) {
                                            $endyear_pos = strpos($inner2,"by");
                                            $metrics['endyear']  = substr($inner2, $endyear_pos+3, 4);
                                            if(!$metrics['endyear']) {
                                                $error_str .= "Can not fetch end year of Market size value for Report Metric., ";
                                            }
                                        }else {
                                            $error_str .= "Can not fetch end year of Market size value for Report Metric., ";
                                        }

                                        if(str_contains($inner2, "valued")) {
                                            $startyear_size = trim($this->string_between_two_string($inner2, 'valued', 'in'));
                                            $startyear_arr = explode(" ",$startyear_size);
                                            $startyear_key = array_search("USD",$startyear_arr);
                                            $startyear_size = '';
                                            if($startyear_key){
                                                if(isset($startyear_arr[$startyear_key]))
                                                    $startyear_size = $startyear_arr[$startyear_key];
                                                if(isset($startyear_arr[$startyear_key+1]))
                                                    $startyear_size .= ' '.$startyear_arr[$startyear_key+1];
                                                if(isset($startyear_arr[$startyear_key+2]))
                                                    $startyear_size .= ' '.$startyear_arr[$startyear_key+2];
                                            }
                                            $metrics['startyear_size'] = $startyear_size;
                                            if(!$startyear_size) {
                                                $error_str .= "Can not fetch Market size value of start year for Report Metric., ";
                                            }
                                        }else {
                                            $error_str .= "Can not fetch Market size value of start year for Report Metric., ";
                                        }

                                        if(str_contains($inner2, "by")) {
                                            $end_year_size = $this->string_between_two_string($inner2, 'USD', 'by');
                                            $end_year_arr = explode(" ",$end_year_size);
                                            //$end_year_key = array_search("USD",$end_year_arr);
                                            $end_year_array_keys = array_keys($end_year_arr, "USD");
                                            $end_year_key = $end_year_array_keys[1];
                                            
                                            if(isset($end_year_arr[$end_year_key])){
                                                $metrics['endyear_size'] = $end_year_arr[$end_year_key];
                                                $metrics['forecast_unit'] = $end_year_arr[$end_year_key];
                                            }
    
                                            if(isset($end_year_arr[$end_year_key+1])){
                                                $metrics['endyear_size'] .= ' '.$end_year_arr[$end_year_key+1];
                                            }
    
                                            if(isset($end_year_arr[$end_year_key+2])){
                                                $metrics['endyear_size'] .= ' '.$end_year_arr[$end_year_key+2];
                                                $metrics['forecast_unit'] .= ' '.ucfirst($end_year_arr[$end_year_key+2]);
                                            }

                                            if(!$metrics['endyear_size']) {
                                                $error_str .= "Can not fetch Market size value of end year for Report Metric., ";
                                            }
    
                                            if(!$metrics['forecast_unit']) {
                                                $error_str .= "Can not fetch forecast unit for Report Metric., ";
                                            }
                                        }else {
                                            $error_str .= "Can not fetch Market size value of end year for Report Metric., ";
                                            $error_str .= "Can not fetch forecast unit for Report Metric., ";
                                        }                                                                                  

                                        // get faq data 
                                        $faq_answer .= $inner2;
                                    }
                                } else {
                                    $content .= "<ul>";
                                        foreach($inner2 as $d => $inner3) {
                                            $content .= "<li>".$inner3."</li>";
                                        }
                                    $content .= "</ul>";
                                }
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Market Insights does not exist., ";
                            }
                        
                            // get Faq Q1
                            $faq[0]['faq_question'] = "";
                            $faq[0]['faq_answer'] = "";
                            if($faq_answer!=''){
                                $faq[0]['faq_question'] = "What is the global market size of ";
                                $faq[0]['faq_answer'] = $faq_answer;
                            }
                        }else{
                            $error_str .= "Market Insights does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Segmental Analysis'){
                        $title = 'segmental_analysis';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";                        
                                } else {
                                    $content .= "<ul>";
                                        foreach($inner2 as $d => $inner3) {
                                            $content .= "<li>".$inner3."</li>";
                                        }
                                    $content .= "</ul>";
                                }
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Segmental Analysis does not exist., ";
                            }
                        }else{
                            $error_str .= "Segmental Analysis does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Regional Insights'){
                        $title = 'regional_insights';
                        
                        if(isset($inner1['data'][0])) {
                            $content = "";   
                            $faq_answer = "";
                            $list_counter = 0;
                            foreach($inner1['data'] as $b => $inner2){
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";                                    
                                    // get Faq Q5
                                    if($list_counter==0)
                                    {
                                        $faq_answer = $inner2;
                                        $list_counter++;
                                    }
                                } else {
                                    $content .= "<ul>";
                                        foreach($inner2 as $d => $inner3) {
                                            $content .= "<li>".$inner3."</li>";
                                        }
                                    $content .= "</ul>";
                                }
                            }
                            $dbData[$title] = $content;

                            if(!$content){
                                $error_str .= "Regional Insights does not exist., ";
                            }
                        
                            // get Faq Q5
                            $faq[4]['faq_question'] = "";
                            $faq[4]['faq_answer'] = "";
                            if($faq_answer!=''){
                                $faq[4]['faq_question'] = "Which region accounted for the largest share in ";
                                $faq[4]['faq_answer'] = $faq_answer;
                            }
                        }else{
                            $error_str .= "Regional Insights does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Market Dynamics'){
                        $title = 'market_dynamics';
                        if(isset($inner1['data'][0])) {
                            $content = "";       
                            $faq_answer = "";
                            $list_counter = 0;
                            
                            foreach($inner1['data'] as $b => $inner2){
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";                        
                                } else {                                        
                                    $content .= "<ul>";
                                        foreach($inner2 as $d => $inner3) {
                                            $content .= "<li>".$inner3."</li>";

                                            // get Faq Q3
                                            if($list_counter==0)
                                            {
                                                $faq_answer = $inner3;
                                                $list_counter++;
                                            }
                                        }
                                    $content .= "</ul>";
                                }
                            }
                            $dbData[$title] = $content;

                            if(!$content){
                                $error_str .= "Market Dynamics does not exist., ";
                            }

                            // get Faq Q3
                            $faq[2]['faq_question'] = "";
                            $faq[2]['faq_answer'] = "";
                            if($faq_answer!=''){
                                $faq[2]['faq_question'] = "What is the key driver of ";
                                $faq[2]['faq_answer'] = $faq_answer;
                            }
                        }else{
                            $error_str .= "Market Dynamics does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Competitive Landscape'){
                        $title = 'competitive_landscape';
                        if(isset($inner1['data'][0])) {
                            $content = "";
                            $faq_answer = "";
                            $prev_str = "";
                            
                            $list_key = 0;
                            $metrics['companies_covered'] = array();
                            foreach($inner1['data'] as $b => $inner2){
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";
                                    $prev_str = $inner2;
                                    // get faq data 
                                    if($b==0){                                        
                                        $faq_answer .= $inner2;
                                    }
                                } else {                                             
                                    $str_to_compare = $prev_str;
                                    if(str_contains($str_to_compare, "Top Player"))
                                    {
                                        $list_key = 1;
                                    }

                                    $endstr_to_compare = $prev_str;
                                    if(str_contains($endstr_to_compare, "Recent Development"))
                                    {
                                        $list_key = 0;
                                    }
                                    
                                    $content .= "<ul>";                                     
                                        foreach($inner2 as $d => $inner3) {                                
                                            $content .= "<li>".$inner3."</li>";
                                            if($list_key){
                                                $metrics['companies_covered'][] = "<li>".$inner3."</li>";
                                                $faq_answer .= "'".$inner3."', ";
                                            }
                                        }
                                    $content .= "</ul>";
                                }
                            }
                            if(!$metrics['companies_covered']) {
                                $error_str .= "Can not fetch companies covered for Report Metric., ";
                            }
                            $dbData[$title] = $content;

                            if(!$content){
                                $error_str .= "Competitive Landscape does not exist., ";
                            }

                            // get Faq Q2
                            $faq[1]['faq_question'] = "";
                            $faq[1]['faq_answer'] = "";
                            if($faq_answer!=''){
                                $faq[1]['faq_question'] = "Who are the key vendors in the ";
                                $faq[1]['faq_answer'] = rtrim($faq_answer,", ");
                            }
                        }else{
                            $error_str .= "Competitive Landscape does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Key Market Trends'){
                        $title = 'key_market_trends';
                        if(isset($inner1['data'][0])) {
                            $content = "";   
                            $faq_answer = "";
                            $list_counter = 0;
                            foreach($inner1['data'] as $b => $inner2){       
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";
                                } else {
                                    $content .= "<ul>";
                                        foreach($inner2 as $d => $inner3) {
                                            $content .= "<li>".$inner3."</li>";
                                            
                                            // get Faq Q4
                                            if($list_counter==0)
                                            {
                                                $faq_answer = $inner3;
                                                $list_counter++;
                                            }
                                        }
                                    $content .= "</ul>";
                                }
                            }
                            $dbData[$title] = $content;

                            if(!$content){
                                $error_str .= "Key Market Trends does not exist., ";
                            }
                            // get Faq Q4
                            $faq[3]['faq_question'] = "";
                            $faq[3]['faq_answer'] = "";
                            if($faq_answer!=''){
                                $faq[3]['faq_question'] = "What is the key market trend for ";
                                $faq[3]['faq_answer'] = $faq_answer;
                            }
                        }else{
                            $error_str .= "Key Market Trends does not exist., ";
                        }
                    }
                    else if($inner1['title']=='SkyQuest Analysis'){
                        $title = 'skyQuest_analysis';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";                        
                                } else {
                                    $content .= "<ul>";
                                        foreach($inner2 as $d => $inner3) {
                                            $content .= "<li>".$inner3."</li>";
                                        }
                                    $content .= "</ul>";
                                }
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "SkyQuest Analysis does not exist., ";
                            }
                        }else{
                            $error_str .= "SkyQuest Analysis does not exist., ";
                        }
                    }
                    else if($inner1['title']=='What\'s Included'){
                        $title = 'whats_included';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";                        
                                } else {
                                    $content .= "<ul>";
                                        foreach($inner2 as $d => $inner3) {
                                            $content .= "<li>".$inner3."</li>";
                                        }
                                    $content .= "</ul>";
                                }
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "What's Included does not exist., ";
                            }
                        }else{
                            $error_str .= "What's Included does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Sector'){
                        $title = 'sector_id';
                        if(isset($inner1['data'][0])) {
                            $content = "";              
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            if(!$content){
                                $error_str .= "Sector does not exist., ";
                            }
                            $sectorData = Sector::where('title',$content)->first();
                            if(!(isset($sectorData)) && $content!=''){
                                $error_str .= "Sector : ".$content." does not exist in our records., ";
                            }
                            $report_sector_id = (isset($sectorData)) ? $sectorData->id : '';
                            $dbData[$title] = (isset($sectorData)) ? $sectorData->id : 0;
                        }else{
                            $error_str .= "Sector does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Industry Group'){
                        $title = 'industry_group_id';
                        if(isset($inner1['data'][0])) {
                            $content = "";              
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            if(!$content){
                                $error_str .= "Industry Group does not exist., ";
                            }
                            $IndustryGroupData = IndustryGroup::where('title',$content)->first();
                            if(!(isset($IndustryGroupData)) && $content!=''){
                                $error_str .= "Industry Group : ".$content." does not exist in our records., ";
                            }
                            if($report_sector_id && $content!=''){
                                $IndustryGroupData = IndustryGroup::where('title',$content)->where('sector_id',$report_sector_id)->first();
                                if(!(isset($IndustryGroupData))){
                                    $error_str .= "Industry Group : ".$content." does not map with given sector., ";
                                }
                            }
                            $report_industry_group_id = (isset($IndustryGroupData)) ? $IndustryGroupData->id : '';
                            $dbData[$title] = (isset($IndustryGroupData)) ? $IndustryGroupData->id : 0;
                        }else{
                            $error_str .= "Industry Group does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Industry'){
                        $title = 'industry_id';
                        if(isset($inner1['data'][0])) {
                            $content = "";              
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            if(!$content){
                                $error_str .= "Industry does not exist., ";
                            }
                            $IndustryData = Industry::where('title',$content)->first();
                            if(!(isset($IndustryData)) && $content!=''){
                                $error_str .= "Industry : ".$content." does not exist in our records., ";
                            }
                            if($report_industry_group_id && $content!=''){
                                $IndustryData = Industry::where('title',$content)->where('industry_group_id',$report_industry_group_id)->first();
                                if(!(isset($IndustryData))){
                                    $error_str .= "Industry : ".$content." does not map with given industry group., ";
                                }
                            }
                            $report_industry_id = (isset($IndustryData)) ? $IndustryData->id : '';
                            $dbData[$title] = (isset($IndustryData)) ? $IndustryData->id : 0;
                        }else{
                            $error_str .= "Industry does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Sub-Industry'){
                        $title = 'sub_industry_id';
                        if(isset($inner1['data'][0])) {
                            $content = "";              
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            if(!$content){
                                $error_str .= "Sub-Industry does not exist., ";
                            }
                            $SubIndustryData = SubIndustry::where('title',$content)->first();
                            if(!(isset($SubIndustryData)) && $content!=''){
                                $error_str .= "Sub-Industry : ".$content." does not exist in our records., ";
                            }
                            if($report_industry_id && $content!=''){
                                $SubIndustryData = SubIndustry::where('title',$content)->where('industry_id',$report_industry_id)->first();
                                if(!(isset($SubIndustryData))){
                                    $error_str .= "Sub-Industry : ".$content." does not map with given industry., ";
                                }
                            }
                            $report_sub_industry_id = (isset($SubIndustryData)) ? $SubIndustryData->id : '';
                            $dbData[$title] = (isset($SubIndustryData)) ? $SubIndustryData->id : 0;
                        }else{
                            $error_str .= "Sub-Industry does not exist., ";
                        }
                    }
                    else if(strtolower($inner1['title'])=='segment'){
                        if(isset($inner1['data'][0])) {
                            $content = "";              
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            $segmentArr[] = $content;
                            if(!$content){
                                $error_str .= "Segment does not exist., ";
                            }
                        }else{
                            $error_str .= "Segment does not exist., ";
                        }
                    }
                    else if((strtolower($inner1['title'])=='sub-segments') || (strtolower($inner1['title'])=='sub-segment')){
                        if(isset($inner1['data'][0])) {
                            $content = "";              
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            $sub_segmentArr[] = $content;
                            if(!$content){
                                $error_str .= "Sub-segment does not exist., ";
                            }
                        }else{
                            $error_str .= "Sub-segment does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Report Type'){
                        if(isset($inner1['data'][0])) {
                            $content = array();
                            foreach($inner1['data'] as $b => $inner2){
                                if($inner2!=''){
                                    $price_str = '';
                                    $price_str .= $inner2;
                                    $content[] = $price_str;
                                }
                            }
                            $pricingArr = $content;
                            if(!$content){
                                $error_str .= "Report type does not exist., ";
                            }
                        }else{
                            $error_str .= "Report Type does not exist., ";
                        }
                    }                          
                }

                // price check
                for($k=0;$k<count($pricingArr);$k++){
                    switch($k){
                        case '2':
                        case '5':
                        case '8':
                        case '11':
                        case '14':
                        case '17':
                        case '20':
                        case '23':
                        case '26':
                        case '29':
                        case '32':
                        case '35':
                            if(!is_numeric($pricingArr[$k])){
                                $error_str .= "Price : ".$pricingArr[$k]." is invalid., ";
                            }
                            break;
                    }             
                }
                
                // check old data with uploaded one
                if(($reportData->country!=$report_country) || ($reportData->sector_id!=$report_sector_id) || ($reportData->sub_industry_id!=$report_sub_industry_id)){
                    // generate product_id or SKU
                    $country_code = '';
                    $sector_code = '';
                    $sub_industry_initial = '';
                    $report_count = '';
            
                    $country_code = ($report_country!='') ? substr($report_country,0,1) : '';
                    if($report_sector_id!=''){
                        $sectorData = Sector::where('id',$report_sector_id)->pluck('code');            
                        $sector_code = $sectorData[0];
                    }  
                    if($report_sub_industry_id!=''){
                        $SubIndustryData = SubIndustry::where('id',$report_sub_industry_id)->pluck('initial');
                        $sub_industry_initial = $SubIndustryData[0];
                    }
                    $combination = $sector_code.$sub_industry_initial;
                    $report_count = $this->getReportCount($report_sub_industry_id,$report_type,$combination,$reportData->id);
            
                    $sku = "SQMI".$country_code.$sector_code.$sub_industry_initial.$report_count;

                    $isSKUExist = checkReportSKU($sku,$reportData->id);
                    if(isset($isSKUExist) && ($isSKUExist->count() > 0)){
                        $error_str .= "SKU : ".$sku." already exists., ";
                    }
                    
                    $dbData['product_id'] = $sku;
                }else{
                    $dbData['product_id'] = $reportData->product_id;
                }    

                if($error_str!=''){
                    return array('error',$error_str);
                }

                if($mode=='Revamp'){
                    $dbData['publish_date'] = date("Y-m-d");
                }
                $dbData['report_type'] = 'SD';
                
                Report::where('id',$reportData->id)->update($dbData);
                $updated_report = Report::where('id',$reportData->id)->first();
                
                $report_id = $updated_report->id;
                $report_name = $updated_report->name;

                if($report_id){

                    // delete older image
                    // if($reportData->image) {
                    //     cloudinary()->destroy($reportData->image_id);
                    // }
                    
                    //upload image from url              
                    /* if($dbData['image'] != '' && $dbData['image'] != 'Null'){
                        $contents = file_get_contents($dbData['image']);
                        $imageName = substr($dbData['image'], strrpos($dbData['image'], '/') + 1);
                        Storage::put($imageName, $contents);
                        $path = storage_path() .'/app/'. $imageName;
                        $folder = config('cloudinary.upload_preset') . config('constants.REPORT_PATH');
                        try {
                            $uploadedImg = cloudinary()->upload($path,['folder' => $folder])->getSecurePath();
                            $reportData->image = $uploadedImg;
                            $reportData->save();
                            Storage::delete($imageName);
                        } catch (\Exception $e) {
            
                        }
                    } */
                    
                    if(count($segmentArr)>0){
                        // delete older segments data
                        ReportSegment::where('report_id',$report_id)->delete();
                        // insert into report_segments                
                        for($k=0;$k<count($segmentArr);$k++){
                            if(isset($segmentArr[$k]) && isset($sub_segmentArr[$k])){
                                $segmentData = array();
                                $segmentData['report_id'] = $report_id;
                                $segmentData['name'] = $segmentArr[$k];
                                $segmentData['value'] = $sub_segmentArr[$k];
                                ReportSegment::create($segmentData);
                            }
                        }
                    }

                    if(count($pricingArr)>0){
                        // delete older pricing data
                        ReportPricing::where('report_id',$report_id)->delete();
                        // insert into report_pricing
                        for($k=0;$k<count($pricingArr);$k++){
                            $pricingData = array();
                            $pricingData['report_id'] = $report_id;
                            $pricingData['license_type'] = $pricingArr[$k];
                            $pricingData['file_type'] = $pricingArr[++$k];
                            $pricingData['price'] = $pricingArr[++$k];
                            ReportPricing::create($pricingData);
                        }
                    }
        
                    // insert into report_metrics
                    if(count($metrics)>0){
                        // delete older metrics data
                        ReportMetrics::where('report_id',$report_id)->delete();

                        if(isset($metrics['startyear']) && isset($metrics['startyear_size'])) {
                            $metricsArr = array();
                            $metricsArr['report_id'] = $report_id;
                            $metricsArr['meta_key'] = 'Market size value in '.$metrics['startyear'];
                            $metricsArr['meta_value'] = $metrics['startyear_size'];            
                            ReportMetrics::create($metricsArr);
                        }
        
                        if(isset($metrics['endyear']) && isset($metrics['endyear_size'])) {
                            $metricsArr = array();
                            $metricsArr['report_id'] = $report_id;
                            $metricsArr['meta_key'] = 'Market size value in '.$metrics['endyear'];
                            $metricsArr['meta_value'] = $metrics['endyear_size'];                    
                            ReportMetrics::create($metricsArr);
                        }
        
                        if(isset($metrics['growth_rate'])) {
                            $metricsArr = array();
                            $metricsArr['report_id'] = $report_id;
                            $metricsArr['meta_key'] = 'Growth Rate';
                            $metricsArr['meta_value'] = $metrics['growth_rate'];                    
                            ReportMetrics::create($metricsArr);
                        }
        
                        if(isset($metrics['startyear'])) {
                            $metricsArr = array();
                            $metricsArr['report_id'] = $report_id;
                            $metricsArr['meta_key'] = 'Base year';
                            $metricsArr['meta_value'] = $metrics['startyear'];                    
                            ReportMetrics::create($metricsArr);
                        }
        
                        if(isset($metrics['forecast_period'])) {
                            $metricsArr = array();
                            $metricsArr['report_id'] = $report_id;
                            $metricsArr['meta_key'] = 'Forecast period';
                            $metricsArr['meta_value'] = $metrics['forecast_period'];                    
                            ReportMetrics::create($metricsArr);
                        }
        
                        if(isset($metrics['forecast_unit'])) {
                            $metricsArr = array();
                            $metricsArr['report_id'] = $report_id;
                            $metricsArr['meta_key'] = 'Forecast Unit (Value)';
                            $metricsArr['meta_value'] = $metrics['forecast_unit'];                    
                            ReportMetrics::create($metricsArr);
                        }
                                    
                        if(isset($segmentArr) && (count($segmentArr) > 0)) {
                            $segmentDataArr = '<ul>';
                            for($k=0;$k<count($segmentArr);$k++){
                                if(isset($segmentArr[$k]) && isset($sub_segmentArr[$k])){
                                    $segmentDataArr .= '<li>'.$segmentArr[$k];
                                    $segmentDataArr .= '<ul>';
                                    $segmentDataArr .= '<li>'.$sub_segmentArr[$k].'</li>';
                                    $segmentDataArr .= '</ul>';
                                    $segmentDataArr .= '</li>';
                                }
                            }
                            $segmentDataArr .= '</ul>';
        
                            $metricsArr = array();
                            $metricsArr['report_id'] = $report_id;
                            $metricsArr['meta_key'] = 'Segments covered';
                            $metricsArr['meta_value'] = $segmentDataArr;              
                            ReportMetrics::create($metricsArr);
                        }
        
                        // for Regions covered
                        $regions_covered = "North America (US, Canada), Europe (Germany, France, United Kingdom, Italy, Spain, Rest of Europe), Asia Pacific (China, India, Japan, Rest of Asia-Pacific), Latin America (Brazil, Rest of Latin America), Middle East & Africa (South Africa, GCC Countries, Rest of MEA)";
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Regions covered';
                        $metricsArr['meta_value'] = $regions_covered;
                        ReportMetrics::create($metricsArr);
        
                        // for Companies covered
                        if(isset($metrics['companies_covered']) && count($metrics['companies_covered'])>0) {
                            $companies_covered = "<ul>".implode(" ",$metrics['companies_covered'])."</ul>";
                            $metricsArr = array();
                            $metricsArr['report_id'] = $report_id;
                            $metricsArr['meta_key'] = 'Companies covered';
                            $metricsArr['meta_value'] = $companies_covered;                    
                            ReportMetrics::create($metricsArr);
                        }
        
                        // for Customization scope            
                        $customization_scope = '<p>Free report customization with purchase. Customization includes:-
                        <ul><li>Segments by type, application, etc</li><li>Company profile</li><li>Market dynamics & outlook</li><li>Region</li></ul></p>';            
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Customization scope';
                        $metricsArr['meta_value'] = $customization_scope;                    
                        ReportMetrics::create($metricsArr);
                        
                    }
        
                    if(count($faq)>0){
                        // delete older faq data
                        ReportFaq::where('report_id',$report_id)->where('is_auto','1')->delete();
                        // insert into report_faq
                        for($k=0;$k<count($faq);$k++){
                            if(($faq[$k]['faq_question']!='') && ($faq[$k]['faq_answer']!='')){
                                if($k==0){
                                    $updated_name = str_replace('Global',"",$report_name);
                                    $que = $faq[$k]['faq_question'].$updated_name."?";
                                } else {
                                    $que = $faq[$k]['faq_question'].$report_name."?";
                                }
                
                                $faqData = array();
                                $faqData['report_id'] = $report_id;
                                $faqData['faq_question'] = $que;
                                $faqData['faq_answer'] = $faq[$k]['faq_answer'];
                                $faqData['is_auto'] = '1';
                                ReportFaq::create($faqData);
                            }
                        }
                    }
        
                    // insert into report_tableofcontent
                    // for TOC field - starts
                    // static text
                    //$static_toc = "<h3>".$report_name." Table of Contents</h3>";
                    $static_toc = "<ul><li><b>Executive Summary</b>";
                    $static_toc .= "<ul><li>Market Overview</li><li>Wheel of Fortune</li></ul></li>";        
                    $static_toc .= "<li><b>Research Methodology</b>";
                    $static_toc .= "<ul><li>Information Procurement</li><li>Secondary & Primary Data Sources</li><li>Market Size Estimation</li><li>Market Assumptions & Limitations</li></ul></li>";
                    $static_toc .= "<li><b>Parent Market Analysis</b>";
                    $static_toc .= "<ul><li>Market Overview</li><li>Market Size</li><li>Market Dynamics<ul><li>Drivers</li><li>Opportunities</li><li>Restraints</li><li>Challenges</li></ul></li></ul></li>";
                    $static_toc .= "<li><b>Key Market Insights</b>";
                    $static_toc .= "<ul><li>Technology Analysis</li><li>Pricing Analysis</li><li>Supply Chain Analysis</li><li>Value Chain Analysis</li><li>Ecosystem of the Market</li><li>IP Analysis</li><li>Trade Analysis</li><li>Startup Analysis</li><li>Raw Material Analysis</li><li>Innovation Matrix</li><li>Pipeline Product Analysis</li><li>Macroeconomic Indicators</li><li>Top Investment Analysis</li><li>Key Success Factor</li><li>Degree of Competition</li></ul></li>";
                    $static_toc .= "<li><b>Market Dynamics & Outlook</b>";
                    $static_toc .= "<ul><li>Market Dynamics<ul><li>Drivers</li><li>Opportunities</li><li>Restraints</li><li>Challenges</li></ul></li>";
                    $static_toc .= "<li>Regulatory Landscape</li><li>Porters Analysis<ul><li>Competitive rivalry</li><li>Threat of Substitute Products</li><li>Bargaining Power of Buyers</li><li>Threat of New Entrants</li><li>Bargaining Power of Suppliers</li></ul></li>";
                    $static_toc .= "<li>Skyquest Special Insights on Future Disruptions<ul><li>Political Impact</li><li>Economic Impact</li><li>Social Impact</li><li>Technical Impact</li><li>Environmental Impact</li><li>Legal Impact</li></ul></li></ul></li>";
                    
                    // for all segments
                    for($k=0;$k<count($segmentArr);$k++){
                        if(isset($segmentArr[$k]) && isset($sub_segmentArr[$k])){
                            $sub_segmentData = array();
                            $sub_segment_str = $sub_segmentArr[$k];
                            $sub_segmentData = explode(",",$sub_segment_str);
            
                            $static_toc .= "<li><b>".$report_name." by ".$segmentArr[$k]."</b>";
                            if(count($sub_segmentData)>0)
                            {
                                $static_toc .= "<ul><li>Market Overview</li>";
                            }
                            for($s=0;$s<count($sub_segmentData);$s++)
                            {
                                $static_toc .= "<li>".trim($sub_segmentData[$s])."</li>";
                                if($s==(count($sub_segmentData)-1))
                                {
                                    $static_toc .= "</ul>";
                                }
                            }
                            $static_toc .= "</li>";
                        }
                    }
        
                    // static text by Region
                    $static_toc .= "<li><b>".$report_name." Size by Region</b>";
                    $static_toc .= "<ul><li>Market Overview</li><li>North America<ul><li>USA</li><li>Canada</li></ul></li><li>Europe<ul><li>Germany</li><li>Spain</li><li>France</li><li>UK</li><li>Rest of Europe</li></ul></li><li>Asia Pacific<ul><li>China</li><li>India</li><li>Japan</li><li>South Korea</li><li>Rest of Asia-Pacific</li></ul></li><li>Latin America<ul><li>Brazil</li><li>Rest of Latin America</li></ul></li><li>Middle East & Africa (MEA)<ul><li>GCC Countries</li><li>South Africa</li><li>Rest of MEA</li></ul></li></ul></li>";
                    
                    // static text for Competitive Landscape
                    $static_toc .= "<li><b>Competitive Landscape</b>";
                    $static_toc .= "<ul><li>Top 5 Player Comparison</li><li>Market Positioning of Key Players, 2021</li><li>Strategies Adopted by Key Market Players</li><li>Top Winning Strategies<ul><li>By Development</li><li>By Company</li><li>By Year</li></ul></li><li>Recent Activities in the Market</li><li>Key Companies Market Share (%), 2021</li></ul></li>";
        
                    // for Key Company Profiles
                    $static_toc .= "<li><b>Key Company Profiles</b>";
                    if(isset($metrics['companies_covered']) && count($metrics['companies_covered'])>0) {
                        $static_toc .= "<ul>";
                        $static_text_company_profiles = "<ul><li>Company Overview</li><li>Business Segment Overview</li><li>Financial Updates</li><li>Key Developments</li></ul>";
                        for($c=0;$c<count($metrics['companies_covered']);$c++)
                        {
                            $doc = new \DOMDocument();
                            $doc->loadHTML($metrics['companies_covered'][$c]);
                            $liList = $doc->getElementsByTagName('li');                
                            $liValues = array();
                            foreach ($liList as $li) {
                                $liValues[] = $li->nodeValue;
                            }
                            
                            if(trim($liValues[0])!=''){                                
                                $company_name = trim($liValues[0]);
                                $static_toc .= "<li>".$company_name.$static_text_company_profiles."</li>";
                            }
                        }
                        $static_toc .= "</ul>";
                    }
                    $static_toc .= "</li>";
                    // for TOC field - ends
        
                    // for tables field - starts
                    // static text
                    //$static_tables = "<h3>List of Tables</h3>";
                    $static_tables = "<p>Table 1. ".$report_name." Size, 2021-2028 (USD Million)</p>";
                    $static_tables .= "<p>Table 2. ".$report_name." Regulatory Landscape</p>";
                    $static_tables .= "<p>Table 3. ".$report_name." IP Analysis</p>";
        
                    // for all segments
                    $table_no = 4;
                    for($k=0;$k<count($segmentArr);$k++){
                        if(isset($segmentArr[$k]) && isset($sub_segmentArr[$k])){
                            $sub_segmentData = array();
                            $sub_segment_str = $sub_segmentArr[$k];
                            $sub_segmentData = explode(",",$sub_segment_str);
            
                            $static_tables .= "<p>Table ".$table_no++.". ".$report_name." Research And Analysis By ".$segmentArr[$k].", 2021-2028 (USD Million)</p>";            
                            for($s=0;$s<count($sub_segmentData);$s++)
                            {
                                $static_tables .= "<p>Table ".$table_no++.". ".$report_name." For ".trim($sub_segmentData[$s]).", 2021-2028 (USD Million)</p>";
                            }
                        }
                    }
        
                    $static_tables .= "<p>Table ".$table_no++.". ".$report_name." Research And Analysis By Region, 2021-2028 (USD Million)</p>";
        
                    // for all segments by country
                    $countries = array("North America",array("US","Canada"),"European",array("UK","Germany","France","Italy","Spain","Rest Of Europe"),"Asia-Pacific",array("China","India","Japan","South Korea","Rest Of Asia-Pacific"),"Latin America",array("Brazil","Rest Of Latin America"),"Middle East And Africa",array("GCC Countries","South Africa","Rest Of Middle East And Africa"));
                    for($c=0;$c<count($countries);$c++){
                        if(!is_array($countries[$c]))
                        {
                            $static_tables .= "<p>Table ".$table_no++.". ".$countries[$c]." ".$report_name." Research And Analysis By Country, 2021-2028 (USD Million)</p>";
                            for($k=0;$k<count($segmentArr);$k++){
                                if(isset($segmentArr[$k])){
                                    $static_tables .= "<p>Table ".$table_no++.". ".$countries[$c]." ".$report_name." Research And Analysis By ".$segmentArr[$k].", 2021-2028 (USD Million)</p>";
                                }
                            }
                        } else {
                            for($s=0;$s<count($countries[$c]);$s++){
                                for($k=0;$k<count($segmentArr);$k++){
                                    if(isset($segmentArr[$k])){
                                        $static_tables .= "<p>Table ".$table_no++.". ".$countries[$c][$s]." ".$report_name." Research And Analysis By ".$segmentArr[$k].", 2021-2028 (USD Million)</p>";
                                    }
                                }
                            }
                        }            
                    }
                    // for tables field - ends
        
                    // for figures field - starts
                    // static text
                    //$static_figures = "<h3>List of Figures</h3>";
                    $static_figures = "<p>Figure 1. ".$report_name." Size, 2021-2028 (USD Million)</p>";
                    $static_figures .= "<p>Figure 2. ".$report_name." Wheel Of Fortune</p>";
                    $static_figures .= "<p>Figure 3. ".$report_name." Parent Market Analysis</p>";
                    $static_figures .= "<p>Figure 4. ".$report_name." Technology Analysis</p>";
                    $static_figures .= "<p>Figure 5. ".$report_name." Pricing Analysis</p>";
                    $static_figures .= "<p>Figure 6. ".$report_name." Supply Chain Analysis</p>";
                    $static_figures .= "<p>Figure 7. ".$report_name." Value Chain Analysis</p>";
                    $static_figures .= "<p>Figure 8. ".$report_name." Ecosystem Of The Market</p>";
                    $static_figures .= "<p>Figure 9. ".$report_name." Trade Analysis</p>";
                    $static_figures .= "<p>Figure 10. ".$report_name." Startup Analysis</p>";
                    $static_figures .= "<p>Figure 11. ".$report_name." Raw Material Analysis</p>";
                    $static_figures .= "<p>Figure 12. ".$report_name." Innovation Matrix</p>";
                    $static_figures .= "<p>Figure 13. ".$report_name." Pipeline Product Analysis</p>";
                    $static_figures .= "<p>Figure 14. ".$report_name." Macroeconomic Indicators</p>";
                    $static_figures .= "<p>Figure 15. ".$report_name." Top Investment Analysis</p>";
                    $static_figures .= "<p>Figure 16. ".$report_name." Key Success Factor</p>";
                    $static_figures .= "<p>Figure 17. ".$report_name." Degree Of Competition</p>";
                    $static_figures .= "<p>Figure 18. ".$report_name." Porter And Its Impact Analysis</p>";
                    $static_figures .= "<p>Figure 19. ".$report_name." Skyquest Special Insights On Future Disruptions</p>";
                    
                    // for all segments
                    $figure_no = 20;
                    $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Share By Segment, 2021 Vs 2028 (%)</p>";
                    for($k=0;$k<count($segmentArr);$k++){
                        if(isset($segmentArr[$k])){
                            $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Share By ".$segmentArr[$k].", 2021 Vs 2028 (%)</p>";
                        }
                    }
        
                    // by country
                    $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Share By Region, 2021 Vs 2028 (%)</p>";
                    
                    $countries = array("North America",array("US","Canada"),"Europe",array("UK","Germany","France","Italy","Spain","Rest Of Europe"),"Asia-Pacific",array("China","India","Japan","South Korea","Rest Of Asia-Pacific"),"Latin America",array("Brazil","Rest Of Latin America"),"Middle East And Africa",array("GCC","South Africa","Rest Of Middle East And Africa"));
                    for($c=0;$c<count($countries);$c++){
                        if(!is_array($countries[$c]))
                        {
                            $static_figures .= "<p>Figure ".$figure_no++.". ".$countries[$c]." ".$report_name." Share By Country, 2021-2028 (USD Million)</p>";
                        } else {
                            for($s=0;$s<count($countries[$c]);$s++){
                                $static_figures .= "<p>Figure ".$figure_no++.". ".$countries[$c][$s]." ".$report_name." Size, 2021-2028 (USD Million)</p>";
                            }
                        }            
                    }
        
                    // static text
                    $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Top 5 Player Comparison</p>";
                    $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Positioning Of Key Players, 2021</p>";
                    $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Strategies Adopted By Key Market Players</p>";
                    $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Top Winning Strategies</p>";
                    $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." By Development</p>";
                    $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." By Company</p>";
                    $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." By Year</p>";
                    $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Recent Activities In The Market</p>";
                    $static_figures .= "<p>Figure ".$figure_no++.". ".$report_name." Key Companies Market Share (%), 2021</p>";
                    
                    // by company
                    if(isset($metrics['companies_covered']) && count($metrics['companies_covered'])>0) {
                        for($c=0;$c<count($metrics['companies_covered']);$c++)
                        {
                            $doc = new \DOMDocument();
                            $doc->loadHTML($metrics['companies_covered'][$c]);
                            $liList = $doc->getElementsByTagName('li');                
                            $liValues = array();
                            foreach ($liList as $li) {
                                $liValues[] = $li->nodeValue;
                            }
                            
                            if(trim($liValues[0])!=''){
                                $company_name = trim($liValues[0]);
                                $static_figures .= "<p>Figure ".$figure_no++.". ".$company_name." Company Overview & Information</p>";
                            }
                        }
                    }
                    // for figures field - ends
        
                    // delete older tableofcontent data
                    ReportTableofcontent::where('report_id',$report_id)->delete();

                    // save to report_tableofcontent
                    $tocData = array();
                    $tocData['report_id'] = $report_id;
                    $tocData['toc'] = $static_toc;
                    $tocData['tables'] = $static_tables;
                    $tocData['figures'] = $static_figures;
                    ReportTableofcontent::create($tocData);
                }

            }
        }

        // for Dynamic report type
        if($report_type=='Dynamic'){
            // check title for all mandatory fields
            //$mandatory_fields = array("Report Name","Sector","Industry Group","Industry","Sub-Industry","Country","Report Slug","Report Type","Description");
            $mandatory_fields = array("Report Name","Segments","Image","Sector","Industry Group","Industry","Sub-Industry","Country","Download","Image Alt","Report Slug","Meta Title","Meta Description","Pages","Report Type","Description","Table of Content");
            for($i=0;$i<count($mandatory_fields);$i++){
                if(!in_array($mandatory_fields[$i],$titleArray)){
                    $error_str .= $mandatory_fields[$i]. " does not exist., ";
                }
            }

            // if uploaded file not in pre defined format
            if($error_str!=''){
                return $error_str;
            }

            //dd($reportData);
            if($reportData){
                $segmentArr = array();
                $sub_segmentArr = array();
                $pricingArr = array(); 
                $faq = array();
                $dbData = array();
                $TOC_Data = array();
                $FAQ_Questions = array();
                $FAQ_Answers = array();
                $f = 0;
                foreach($mainArray as $a => $inner1) {                        
                    if($inner1['title']=='Report Name'){
                        $title = 'name';
                        if(isset($inner1['data'][0])) {
                            $content = "";       
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Report Name does not exist., ";
                            }
                        }else{
                            $error_str .= "Report Name does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Image'){
                        $title = 'image';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Image does not exist., ";
                            }
                        }else{
                            $error_str .= "Image does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Country'){
                        $title = 'country';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            $report_country = $content;
                            if(!$content){
                                $error_str .= "Country does not exist., ";
                            }                        
                        }else{
                            $error_str .= "Country does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Download'){
                        $title = 'download';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){           
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Download does not exist., ";
                            }
                        }else{
                            $error_str .= "Download does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Image Alt'){
                        $title = 'image_alt';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){           
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Image Alt does not exist., ";
                            }
                        }else{
                            $error_str .= "Image Alt does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Report Slug'){
                        $title = 'slug';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){           
                                $content .= $inner2;
                            }
                            if(strtolower($content)=='null'){
                                $error_str .= "Report Slug can not be NULL., ";
                            } elseif(trim($content)==''){
                                $error_str .= "Report Slug does not exist., ";
                            } else{
                                // check slug already exists 
                                $reportSlug = checkReportSlug($content,'',$report_id);
                                if(isset($reportSlug) && ($reportSlug->count() > 0)){
                                    $error_str .= "Slug : ".$content." already exists., ";
                                }
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Report Slug does not exist., ";
                            }
                        }else{
                            $error_str .= "Report Slug does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Meta Title'){
                        $title = 'meta_title';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){           
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Meta Title does not exist., ";
                            }
                        }else{
                            $error_str .= "Meta Title does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Meta Description'){
                        $title = 'meta_description';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){           
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Meta Description does not exist., ";
                            }
                        }else{
                            $error_str .= "Meta Description does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Pages'){
                        $title = 'pages';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){           
                                $content .= $inner2;
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Pages does not exist., ";
                            }
                        }else{
                            $error_str .= "Pages does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Methodologies'){
                        $title = 'methodologies';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";
                                } else {
                                    $content .= "<ul>";
                                        foreach($inner2 as $d => $inner3) {
                                            $content .= "<li>".$inner3."</li>";
                                        }
                                    $content .= "</ul>";
                                }
                            }
                            $dbData[$title] = $content;
                        }
                    }
                    else if($inner1['title']=='Analyst Support'){
                        $title = 'analyst_support';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";                        
                                } else {
                                    $content .= "<ul>";
                                        foreach($inner2 as $d => $inner3) {
                                            $content .= "<li>".$inner3."</li>";
                                        }
                                    $content .= "</ul>";
                                }
                            }
                            $dbData[$title] = $content;
                        }
                    }
                    else if($inner1['title']=='Description'){
                        $title = 'description'; 
                        
                        if(isset($inner1['data'][0])) {
                            $content = "";           
                            $prev_str = "";
                            $list_key = 0;
                            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)+<\/p>/";
                            $reg_exUrl_li = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)+<\/li>/";
                            $url = array();
                            $url_li = array();
                            foreach($inner1['data'] as $b => $inner2){        
                                if(!is_array($inner2)) {
                                    $prev_str = $inner2;
                                    $content .= "<p>".$inner2."</p>";
                                } else {    
                                    $content .= "<ul>";
                                    foreach($inner2 as $d => $inner3) {
                                        $content .= "<li>".$inner3."</li>";
                                    }
                                    $content .= "</ul>";
                                }
                            }
    
                            // replace URLs with image tags
                            if(preg_match_all($reg_exUrl, $content, $url))
                            {
                                if(count($url)>0){
                                    for($l=0;$l<count($url[0]);$l++){
                                        $content = str_replace($url[0][$l], "<img src='".strip_tags($url[0][$l])."' /></p>", $content);
                                        
                                    }
                                }
                            }
    
                            if(preg_match_all($reg_exUrl_li, $content, $url_li))
                            {
                                if(count($url_li)>0){
                                    for($l=0;$l<count($url_li[0]);$l++){
                                        $content = str_replace($url_li[0][$l], "<img src='".strip_tags($url_li[0][$l])."' /></p>", $content);
                                        
                                    }
                                }
                            }
                            $dbData[$title] = $content;
                            if(!$content){
                                $error_str .= "Description does not exist., ";
                            }
                        }else{
                            $error_str .= "Description does not exist., ";
                        }
                    }
                    else if($inner1['title']=='What\'s Included'){
                        $title = 'whats_included';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";                        
                                } else {
                                    $content .= "<ul>";
                                        foreach($inner2 as $d => $inner3) {
                                            $content .= "<li>".$inner3."</li>";
                                        }
                                    $content .= "</ul>";
                                }
                            }
                            $dbData[$title] = $content;
                        }
                    }
                    else if($inner1['title']=='Sector'){
                        $title = 'sector_id';
                        if(isset($inner1['data'][0])) {
                            $content = "";              
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            if(!$content){
                                $error_str .= "Sector does not exist., ";
                            }
                            $sectorData = Sector::where('title',$content)->first();
                            if(!(isset($sectorData)) && $content!=''){
                                $error_str .= "Sector : ".$content." does not exist in our records., ";
                            }
                            $report_sector_id = (isset($sectorData)) ? $sectorData->id : '';
                            $dbData[$title] = (isset($sectorData)) ? $sectorData->id : 0;
                        }else{
                            $error_str .= "Sector does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Industry Group'){
                        $title = 'industry_group_id';
                        if(isset($inner1['data'][0])) {
                            $content = "";              
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            if(!$content){
                                $error_str .= "Industry Group does not exist., ";
                            }
                            $IndustryGroupData = IndustryGroup::where('title',$content)->first();
                            if(!(isset($IndustryGroupData)) && $content!=''){
                                $error_str .= "Industry Group : ".$content." does not exist in our records., ";
                            }
                            if($report_sector_id && $content!=''){
                                $IndustryGroupData = IndustryGroup::where('title',$content)->where('sector_id',$report_sector_id)->first();
                                if(!(isset($IndustryGroupData))){
                                    $error_str .= "Industry Group : ".$content." does not map with given sector., ";
                                }
                            }
                            $report_industry_group_id = (isset($IndustryGroupData)) ? $IndustryGroupData->id : '';
                            $dbData[$title] = (isset($IndustryGroupData)) ? $IndustryGroupData->id : 0;
                        }else{
                            $error_str .= "Industry Group does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Industry'){
                        $title = 'industry_id';
                        if(isset($inner1['data'][0])) {
                            $content = "";              
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            if(!$content){
                                $error_str .= "Industry does not exist., ";
                            }
                            $IndustryData = Industry::where('title',$content)->first();
                            if(!(isset($IndustryData)) && $content!=''){
                                $error_str .= "Industry : ".$content." does not exist in our records., ";
                            }
                            if($report_industry_group_id && $content!=''){
                                $IndustryData = Industry::where('title',$content)->where('industry_group_id',$report_industry_group_id)->first();
                                if(!(isset($IndustryData))){
                                    $error_str .= "Industry : ".$content." does not map with given industry group., ";
                                }
                            }
                            $report_industry_id = (isset($IndustryData)) ? $IndustryData->id : '';
                            $dbData[$title] = (isset($IndustryData)) ? $IndustryData->id : 0;
                        }else{
                            $error_str .= "Industry does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Sub-Industry'){
                        $title = 'sub_industry_id';
                        if(isset($inner1['data'][0])) {
                            $content = "";              
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            if(!$content){
                                $error_str .= "Sub-Industry does not exist., ";
                            }
                            $SubIndustryData = SubIndustry::where('title',$content)->first();
                            if(!(isset($SubIndustryData)) && $content!=''){
                                $error_str .= "Sub-Industry : ".$content." does not exist in our records., ";
                            }
                            if($report_industry_id && $content!=''){
                                $SubIndustryData = SubIndustry::where('title',$content)->where('industry_id',$report_industry_id)->first();
                                if(!(isset($SubIndustryData))){
                                    $error_str .= "Sub-Industry : ".$content." does not map with given industry., ";
                                }
                            }
                            $report_sub_industry_id = (isset($SubIndustryData)) ? $SubIndustryData->id : '';
                            $dbData[$title] = (isset($SubIndustryData)) ? $SubIndustryData->id : 0;
                        }else{
                            $error_str .= "Sub-Industry does not exist., ";
                        }
                    }
                    else if(strtolower($inner1['title'])=='segment'){
                        if(isset($inner1['data'][0])) {
                            $content = "";              
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            $segmentArr[] = $content;
                            if(!$content){
                                $error_str .= "Segment does not exist., ";
                            }
                        }else{
                            $error_str .= "Segment does not exist., ";
                        }
                    }
                    else if((strtolower($inner1['title'])=='sub-segments') || (strtolower($inner1['title'])=='sub-segment')){
                        if(isset($inner1['data'][0])) {
                            $content = "";              
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            $sub_segmentArr[] = $content;
                            if(!$content){
                                $error_str .= "Sub-segment does not exist., ";
                            }
                        }else{
                            $error_str .= "Sub-segment does not exist., ";
                        }
                    }
                    else if($inner1['title']=='Report Type'){
                        if(isset($inner1['data'][0])) {
                            $content = array();
                            foreach($inner1['data'] as $b => $inner2){
                                if($inner2!=''){
                                    $price_str = '';                                
                                    $price_str .= $inner2;
                                    $content[] = $price_str;
                                }
                            }
                            $pricingArr = $content;
                            if(!$content){
                                $error_str .= "Report type does not exist., ";
                            }
                        }else{
                            $error_str .= "Report Type does not exist., ";
                        }
                    }                          
                    else if($inner1['title']=='Table of Content'){
                        $title = 'toc';
                        if(isset($inner1['data'][0])) {
                            $content = "";                    
                            foreach($inner1['data'] as $b => $inner2){
                                if(!is_array($inner2)) {
                                    $content .= "<p>".$inner2."</p>";                        
                                } else {
                                    $content .= "<ul>";
                                        foreach($inner2 as $d => $inner3) {
                                            $content .= "<li>".$inner3."</li>";
                                        }
                                    $content .= "</ul>";
                                }
                            }
                            $TOC_Data[$title] = $content;
                            if(!$content){
                                $error_str .= "Table of Content does not exist., ";
                            }
                        }else{
                            $error_str .= "Table of Content does not exist., ";
                        }
                    }
                    else if($inner1['title']=='FAQ Question'){
                        if(isset($inner1['data'][0])) {
                            $content = "";              
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            $FAQ_Questions[] = $content;
                        }
                    }
                    else if($inner1['title']=='FAQ Answer'){
                        if(isset($inner1['data'][0])) {
                            $content = "";              
                            foreach($inner1['data'] as $b => $inner2){
                                $content .= $inner2;
                            }
                            $FAQ_Answers[] = $content;
                        }
                    }
                }

                // price check
                for($k=0;$k<count($pricingArr);$k++){
                    switch($k){
                        case '2':
                        case '5':
                        case '8':
                        case '11':
                        case '14':
                        case '17':
                        case '20':
                        case '23':
                        case '26':
                        case '29':
                        case '32':
                        case '35':
                            if(!is_numeric($pricingArr[$k])){
                                $error_str .= "Price : ".$pricingArr[$k]." is invalid., ";
                            }
                            break;
                    }             
                }
                
                // check old data with uploaded one
                if(($reportData->country!=$report_country) || ($reportData->sector_id!=$report_sector_id) || ($reportData->sub_industry_id!=$report_sub_industry_id)){
                    // generate product_id or SKU
                    $country_code = '';
                    $sector_code = '';
                    $sub_industry_initial = '';
                    $report_count = '';
            
                    $country_code = ($report_country!='') ? substr($report_country,0,1) : '';
                    if($report_sector_id!=''){
                        $sectorData = Sector::where('id',$report_sector_id)->pluck('code');            
                        $sector_code = $sectorData[0];
                    }  
                    if($report_sub_industry_id!=''){
                        $SubIndustryData = SubIndustry::where('id',$report_sub_industry_id)->pluck('initial');
                        $sub_industry_initial = $SubIndustryData[0];
                    }
                    $combination = $sector_code.$sub_industry_initial;
                    $report_count = $this->getReportCount($report_sub_industry_id,$report_type,$combination,$reportData->id);
            
                    $sku = "SQMI".$country_code.$sector_code.$sub_industry_initial.$report_count;

                    $isSKUExist = checkReportSKU($sku,$reportData->id);
                    if(isset($isSKUExist) && ($isSKUExist->count() > 0)){
                        $error_str .= "SKU : ".$sku." already exists., ";
                    }

                    $dbData['product_id'] = $sku;
                }else{
                    $dbData['product_id'] = $reportData->product_id;
                }   

                if($error_str!=''){
                    return array('error',$error_str);
                }

                if($mode=='Revamp'){
                    $dbData['publish_date'] = date("Y-m-d");
                }
                $dbData['report_type'] = 'Dynamic';
        
                Report::where('id',$reportData->id)->update($dbData);
                $updated_report = $reportData = Report::where('id',$reportData->id)->first();
                
                $report_id = $updated_report->id;
                $report_name = $updated_report->name;

                if($report_id){

                    // delete older image
                    // if($reportData->image) {
                    //     cloudinary()->destroy($reportData->image_id);
                    // }
                    
                    //upload image from url              
                    /* if($dbData['image'] != '' && $dbData['image'] != 'Null'){
                        $contents = file_get_contents($dbData['image']);
                        $imageName = substr($dbData['image'], strrpos($dbData['image'], '/') + 1);
                        Storage::put($imageName, $contents);
                        $path = storage_path() .'/app/'. $imageName;
                        $folder = config('cloudinary.upload_preset') . config('constants.REPORT_PATH');
                        try {
                            $uploadedImg = cloudinary()->upload($path,['folder' => $folder])->getSecurePath();
                            $reportData->image = $uploadedImg;
                            $reportData->save();
                            Storage::delete($imageName);
                        } catch (\Exception $e) {
            
                        }
                    } */
                    
                    if(count($segmentArr)>0){
                        // delete older segments data
                        ReportSegment::where('report_id',$report_id)->delete();
                        // insert into report_segments                
                        for($k=0;$k<count($segmentArr);$k++){
                            if(isset($segmentArr[$k]) && isset($sub_segmentArr[$k])){
                                $segmentData = array();
                                $segmentData['report_id'] = $report_id;
                                $segmentData['name'] = $segmentArr[$k];
                                $segmentData['value'] = $sub_segmentArr[$k];
                                ReportSegment::create($segmentData);
                            }
                        }
                    }

                    if(count($pricingArr)>0){
                        // delete older pricing data
                        ReportPricing::where('report_id',$report_id)->delete();
                        // insert into report_pricing
                        for($k=0;$k<count($pricingArr);$k++){
                            $pricingData = array();
                            $pricingData['report_id'] = $report_id;
                            $pricingData['license_type'] = $pricingArr[$k];
                            $pricingData['file_type'] = $pricingArr[++$k];
                            $pricingData['price'] = $pricingArr[++$k];
                            ReportPricing::create($pricingData);
                        }
                    }
                
                    if(count($FAQ_Questions)>0){
                        // delete older faq data
                        ReportFaq::where('report_id',$report_id)->where('is_auto','1')->delete();
                        
                        // insert into report_faq
                        for($k=0;$k<count($FAQ_Questions);$k++){
                            if(isset($FAQ_Questions[$k]) && isset($FAQ_Answers[$k])){
                                $faqData = array();
                                $faqData['report_id'] = $report_id;
                                $faqData['faq_question'] = $FAQ_Questions[$k];
                                $faqData['faq_answer'] = $FAQ_Answers[$k];
                                $faqData['is_auto'] = '1';
                                ReportFaq::create($faqData);
                            }
                        }
                    }
        
                    // insert into report_tableofcontent
                    if(isset($TOC_Data) && isset($TOC_Data['toc'])){
                        // delete older tableofcontent data
                        ReportTableofcontent::where('report_id',$report_id)->delete();

                        $tocData = array();
                        $tocData['report_id'] = $report_id;
                        $tocData['toc'] = $TOC_Data['toc'];
                        $tocData['tables'] = "";
                        $tocData['figures'] = "";
                        ReportTableofcontent::create($tocData);
                    }
                }

            }
        }        
    }
   
    public function saveUpcomingReports($file, $mode)
    {        
        $fname = $file->store('public');
        $name = $file->getClientOriginalName();
        $datasheetArray = Excel::toArray(new UpcomingReportImport, $fname);
        
        $mandatory_fields = array("report name","description","parent market","market overview 1st para","sector","industry group","industry","sub-industry","sc","country","image","image alt","slug","meta_title","meta_description","downloads","pages","single_price","multiple_price","enterprise_price");
        $header_error_string = '';

        foreach ($datasheetArray as $sheet => $rows) {            
            if(count($rows[0])>0 && $sheet === 0) {      
                $errorString = "";
                if(is_array($rows[0])){
                    // check all headers exist or not
                    for($i=0;$i<count($rows[0]);$i++){
                        $titleArray[] = strtolower($rows[0][$i]);
                    }                 
                    
                    for($i=0;$i<count($mandatory_fields);$i++){
                        if(!in_array($mandatory_fields[$i], $titleArray)){
                            $header_error_string .= "<b><span class='text-danger'>".ucfirst($mandatory_fields[$i]). " does not exist.</span></b>, ";
                        }
                    }
                    if($header_error_string!=''){
                        return $header_error_string;
                    }

                    for($r=1;$r<count($rows);$r++){
                        $dbData = array();
                        $companies = '';
                        $report_name = '';
                        $description = '';

                        $report_sector_id = '';
                        $report_industry_group_id = '';
                        $report_industry_id = '';
                        $report_sub_industry_id = '';

                        $single_ppt_price = '';
                        $single_word_price = '';
                        $single_excel_price = '';
                        $single_powerBI_price = '';

                        $multiple_ppt_price = '';
                        $multiple_word_price = '';
                        $multiple_excel_price = '';
                        $multiple_powerBI_price = '';

                        $enterprise_ppt_price = '';
                        $enterprise_word_price = '';
                        $enterprise_excel_price = '';
                        $enterprise_powerBI_price = '';

                        $sku = '';
                        $error_string = '';
                        $report_id = '';
                        $is_row_empty = 0;
                        if(isset($rows[$r]) && $rows[$r]!='' && !empty($rows[$r]) ){
                            if(($rows[$r][0]==null || empty($rows[$r][0])) && 
                            ($rows[$r][1]==null || empty($rows[$r][1])) && 
                            ($rows[$r][2]==null || empty($rows[$r][2])) && 
                            ($rows[$r][3]==null || empty($rows[$r][3])) && 
                            ($rows[$r][4]==null || empty($rows[$r][4])) && 
                            ($rows[$r][5]==null || empty($rows[$r][5])) && 
                            ($rows[$r][6]==null || empty($rows[$r][6])) && 
                            ($rows[$r][7]==null || empty($rows[$r][7])) && 
                            ($rows[$r][8]==null || empty($rows[$r][8])) && 
                            ($rows[$r][9]==null || empty($rows[$r][9])) && 
                            ($rows[$r][10]==null || empty($rows[$r][10])))
                            { $is_row_empty = 1;}
                                                                                    
                            for($i=0;$i<count($rows[0]);$i++){                           
                                if(isset($rows[0][$i])){
                                    switch(strtolower($rows[0][$i]))
                                    {
                                        case 'report name':
                                            if($rows[$r][$i]!=''){
                                                $dbData['name'] = $rows[$r][$i];
                                                $report_name = $rows[$r][$i];
                                            } else{
                                                $error_string .= "Report Name does not exist., ";
                                            }
                                            break;
                                        
                                        case 'description':
                                            if($rows[$r][$i]!=''){
                                                $dbData['description'] = $rows[$r][$i];
                                                $description = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Description does not exist., ";
                                            }
                                            break;
                                        
                                        case 'parent market':
                                            if($rows[$r][$i]!=''){
                                                $dbData['parent_market'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Parent Market does not exist., ";
                                            }
                                            break;
                                        
                                        case 'market overview 1st para':
                                        case 'market overview':
                                            if($rows[$r][$i]!=''){
                                                $dbData['market_insights'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Market overview does not exist., ";
                                            }
                                            break;

                                        case 'sector':
                                            if($rows[$r][$i]!=''){                                        
                                                $sectorData = Sector::where('title',$rows[$r][$i])->first();
                                                if(!(isset($sectorData))){
                                                    $error_string .= "Sector : ".$rows[$r][$i]." does not exist in our records., ";
                                                }         
                                                $report_sector_id = (isset($sectorData)) ? $sectorData->id : '';
                                                $dbData['sector_id'] = (isset($sectorData)) ? $sectorData->id : '';
                                            }else{
                                                $error_string .= "Sector does not exist., ";
                                            }
                                            break;
                                    
                                        case 'industry group':
                                            if($rows[$r][$i]!=''){                                        
                                                $IndustryGroupData = IndustryGroup::where('title',$rows[$r][$i])->first();
                                                if(!(isset($IndustryGroupData))){
                                                    $error_string .= "Industry Group : ".$rows[$r][$i]." does not exist in our records., ";
                                                }
                                                if($report_sector_id){
                                                    $IndustryGroupData = IndustryGroup::where('title',$rows[$r][$i])->where('sector_id',$report_sector_id)->first();
                                                    if(!(isset($IndustryGroupData))){
                                                        $error_string .= "Industry Group : ".$rows[$r][$i]." does not map with given sector., ";
                                                    }
                                                }
                                                $report_industry_group_id = (isset($IndustryGroupData)) ? $IndustryGroupData->id : '';
                                                $dbData['industry_group_id'] = (isset($IndustryGroupData)) ? $IndustryGroupData->id : '';
                                            }else{
                                                $error_string .= "Industry Group does not exist., ";                                                
                                            }
                                            break;

                                        case 'industry':
                                            if($rows[$r][$i]!=''){                                        
                                                $IndustryData = Industry::where('title',$rows[$r][$i])->first();
                                                if(!(isset($IndustryData))){
                                                    $error_string .= "Industry : ".$rows[$r][$i]." does not exist in our records., ";
                                                }
                                                if($report_industry_group_id){
                                                    $IndustryData = Industry::where('title',$rows[$r][$i])->where('industry_group_id',$report_industry_group_id)->first();
                                                    if(!(isset($IndustryData))){
                                                        $error_string .= "Industry : ".$rows[$r][$i]." does not map with given industry group., ";
                                                    }
                                                }
                                                $report_industry_id = (isset($IndustryData)) ? $IndustryData->id : '';
                                                $dbData['industry_id'] = (isset($IndustryData)) ? $IndustryData->id : '';
                                            }else{
                                                $error_string .= "Industry does not exist., ";
                                            }
                                            break;

                                        case 'sub-industry':
                                            if($rows[$r][$i]!=''){                                        
                                                $SubIndustryData = SubIndustry::where('title',$rows[$r][$i])->first();
                                                if(!(isset($SubIndustryData))){
                                                    $error_string .= "Sub-Industry : ".$rows[$r][$i]." does not exist in our records., ";
                                                }
                                                if($report_industry_id){
                                                    $SubIndustryData = SubIndustry::where('title',$rows[$r][$i])->where('industry_id',$report_industry_id)->first();
                                                    if(!(isset($SubIndustryData))){
                                                        $error_string .= "Sub-Industry : ".$rows[$r][$i]." does not map with given industry., ";
                                                    }
                                                }
                                                $report_sub_industry_id = (isset($SubIndustryData)) ? $SubIndustryData->id : '';
                                                $dbData['sub_industry_id'] = (isset($SubIndustryData)) ? $SubIndustryData->id : '';
                                            }else{
                                                $error_string .= "Sub-Industry does not exist., ";
                                            }
                                            break;

                                        case 'sc':
                                            if($rows[$r][$i]!=''){
                                                $sc = $rows[$r][$i];
                                                $dbData['s_c'] = $rows[$r][$i];
                                            } else{
                                                $error_string .= "SC does not exist., ";
                                            }
                                            break;
                                        
                                        case 'country':
                                            if($rows[$r][$i]!=''){
                                                $report_country = $rows[$r][$i];
                                                $dbData['country'] = $rows[$r][$i];
                                            } else{
                                                $error_string .= "Country does not exist., ";
                                            }
                                            break;

                                        case 'image':
                                            if($rows[$r][$i]!=''){
                                                $dbData['image'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Image does not exist., ";
                                            }
                                            break;
                                        
                                        case 'image alt':
                                            if($rows[$r][$i]!=''){
                                                $dbData['image_alt'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Image Alt does not exist., ";
                                            }
                                            break;

                                        case 'slug':
                                            if($rows[$r][$i]!=''){
                                                if(strtolower($rows[$r][$i])=='null'){
                                                    $error_string .= "Slug can not be NULL., ";
                                                } else{
                                                    // check slug already exists 
                                                    $reportSlug = checkReportSlug($rows[$r][$i]);                                                                                        
                                                    if(isset($reportSlug) && ($reportSlug->count() > 0)){
                                                        $error_string .= "Slug : ".$rows[$r][$i]." already exists., ";
                                                    }
                                                }            
                                                $dbData['slug'] = $rows[$r][$i];
                                            } else{
                                                $error_string .= "Slug does not exist., ";
                                            }
                                            break;

                                        case 'meta_title':
                                            if($rows[$r][$i]!=''){
                                                $dbData['meta_title'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Meta title does not exist., ";
                                            }
                                            break;

                                        case 'meta_description':
                                            if($rows[$r][$i]!=''){
                                                $dbData['meta_description'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Meta description does not exist., ";
                                            }
                                            break;
                                        
                                        case 'downloads':
                                            if($rows[$r][$i]!=''){
                                                $dbData['download'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Download does not exist., ";
                                            }
                                            break;

                                        case 'pages':
                                            if($rows[$r][$i]!=''){
                                                $dbData['pages'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Pages does not exist., ";
                                            }
                                            break;
                                        
                                        case 'sample_link':
                                            $dbData['free_sample_report_link'] = $rows[$r][$i];
                                            break;

                                        case 'schema':
                                            $dbData['schema'] = $rows[$r][$i];
                                            break;

                                        case 'companies':
                                            $dbData['competitive_landscape'] = $rows[$r][$i];
                                            break;

                                        case 'single_price':
                                            if($rows[$r][$i]!=''){
                                                $single_price_array = explode("|",$rows[$r][$i]);                                               
                                                if($single_price_array){
                                                    $single_ppt_price = (isset($single_price_array[0]) && is_numeric(trim($single_price_array[0]))) ? trim($single_price_array[0]) : '';
                                                    $single_word_price = (isset($single_price_array[1]) && is_numeric(trim($single_price_array[1]))) ? trim($single_price_array[1]) : '' ;
                                                    $single_excel_price = (isset($single_price_array[2]) && is_numeric(trim($single_price_array[2]))) ? trim($single_price_array[2]) : '';
                                                    $single_powerBI_price = (isset($single_price_array[3]) && is_numeric(trim($single_price_array[3]))) ? trim($single_price_array[3]) : '';
                                                    
                                                    if(!$single_ppt_price || !$single_word_price || !$single_excel_price || !$single_powerBI_price){
                                                        $error_string .= "Single Price: ".$rows[$r][$i]." is invalid., ";
                                                    }
                                                }else {
                                                    $error_string .= "Single Price: ".$rows[$r][$i]." is invalid., ";
                                                }
                                            } else {
                                                $error_string .= "Single Price does not exist., ";
                                            }
                                            break;
                                        
                                        case 'multiple_price':
                                            if($rows[$r][$i]!=''){
                                                $multiple_price_array = explode("|",$rows[$r][$i]);
                                                if($multiple_price_array){
                                                    $multiple_ppt_price = (isset($multiple_price_array[0]) && is_numeric(trim($multiple_price_array[0]))) ? trim($multiple_price_array[0]) : '';
                                                    $multiple_word_price = (isset($multiple_price_array[1]) && is_numeric(trim($multiple_price_array[1]))) ? trim($multiple_price_array[1]) : '';
                                                    $multiple_excel_price = (isset($multiple_price_array[2]) && is_numeric(trim($multiple_price_array[2]))) ? trim($multiple_price_array[2]) : '';
                                                    $multiple_powerBI_price = (isset($multiple_price_array[3]) && is_numeric(trim($multiple_price_array[3]))) ? trim($multiple_price_array[3]) : '';

                                                    if(!$multiple_ppt_price || !$multiple_word_price || !$multiple_excel_price || !$multiple_powerBI_price){
                                                        $error_string .= "Multiple Price: ".$rows[$r][$i]." is invalid., ";
                                                    }
                                                }else {
                                                    $error_string .= "Multiple Price: ".$rows[$r][$i]." is invalid., ";
                                                }
                                            } else {
                                                $error_string .= "Multiple Price does not exist., ";
                                            }
                                            break;

                                        case 'enterprise_price':
                                            if($rows[$r][$i]!=''){
                                                $enterprise_price_array = explode("|",$rows[$r][$i]);
                                                if($enterprise_price_array){
                                                    $enterprise_ppt_price = (isset($enterprise_price_array[0]) && is_numeric(trim($enterprise_price_array[0]))) ? trim($enterprise_price_array[0]) : '';
                                                    $enterprise_word_price = (isset($enterprise_price_array[1]) && is_numeric(trim($enterprise_price_array[1]))) ? trim($enterprise_price_array[1]) : '';
                                                    $enterprise_excel_price = (isset($enterprise_price_array[2]) && is_numeric(trim($enterprise_price_array[2]))) ? trim($enterprise_price_array[2]) : '';
                                                    $enterprise_powerBI_price = (isset($enterprise_price_array[3]) && is_numeric(trim($enterprise_price_array[3]))) ? trim($enterprise_price_array[3]) : '';

                                                    if(!$enterprise_ppt_price || !$enterprise_word_price || !$enterprise_excel_price || !$enterprise_powerBI_price){
                                                        $error_string .= "Enterprise Price: ".$rows[$r][$i]." is invalid., ";
                                                    }
                                                }else {
                                                    $error_string .= "Enterprise Price: ".$rows[$r][$i]." is invalid., ";
                                                }
                                            } else {
                                                $error_string .= "Enterprise Price does not exist., ";
                                            }
                                            break;
                                    }
                                }
                            }

                            $segments = array();
                            $sub_segments = array();
                            $key_playersArr = array();
                            $companies = array();
                            //$reportData = '';

                            if($description!=''){
                                if(str_contains($description, "KEY MARKET SEGMENTS") && strpos($description, "KEY MARKET SEGMENTS")>=0){
                                    $segmentStr = $this->string_between_two_string($description, 'KEY MARKET SEGMENTS', 'KEY PLAYERS');                    
                                    $segmentArr = explode("\n",$segmentStr);
                                    $segmentArr = array_filter($segmentArr);
                                    
                                    $p = 0;
                                    for($s=0;$s<count($segmentArr);$s++)
                                    {
                                        if(isset($segmentArr[$s]) && $segmentArr[$s]!=''){
                                            if((str_contains(trim($segmentArr[$s]),"By")) || (str_contains(trim($segmentArr[$s]),"BY"))){
                                                $segments[$p] = trim(str_replace("BY","",str_replace("By","",trim($segmentArr[$s]))));
                                                $p++;
                                            } else {
                                                $sub_segments[$p-1][] = trim($segmentArr[$s]);
                                            }
                                        }
                                    }
                                    
                                    if(empty($segments)){
                                        $error_string .= "Segments does not exist., ";
                                    }
                                    $pos = strpos($description,"KEY PLAYERS");
                                    $len = strlen("KEY PLAYERS");
                                    $key_players = substr($description, $pos+$len);
                                    $key_playersArr = explode("\n",$key_players);
                    
                                    $key_playersArr = array_filter($key_playersArr);
                                    if(isset($key_playersArr)){
                                        $companies = $key_playersArr;
                                        $dbData['competitive_landscape'] = json_encode(array_values($key_playersArr));                     
                                    }else{
                                        $error_string .= "Key Players does not exist., ";
                                    }
                                }else {
                                    $error_string .= "Key Players does not exist., ";
                                    $error_string .= "Segments does not exist., ";
                                }
                            }else {
                                $error_string .= "Key Players does not exist., ";
                                $error_string .= "Segments does not exist., ";
                            }

                            if(!$single_ppt_price || !$single_word_price || !$single_excel_price || !$single_powerBI_price || !$multiple_ppt_price || !$multiple_word_price || !$multiple_excel_price || !$multiple_powerBI_price || !$enterprise_ppt_price || !$enterprise_word_price || !$enterprise_excel_price || !$enterprise_powerBI_price){
                                $error_string .= "Price is invalid., ";
                            }
                            
                            if($error_string!='' && $is_row_empty==0){
                                $errorString .= '<br><b><span class="text-danger">For '.$report_name.':-</b></span><br>'.$error_string;
                            }

                            if(isset($dbData['name']) && $error_string==''){
                                $dbData['report_type'] = 'Upcoming';
                                $dbData['is_active'] = '1';
                                $dbData['publish_date'] = date("Y-m-d");
                                
                                // generate product_id or SKU
                                $country_code = '';
                                $sector_code = '';
                                $sub_industry_initial = '';
                                $report_count = '';
                                $sc_code = 'MI';
                    
                                if($sc!=''){
                                    switch($sc){
                                        case 'Market intelligence':
                                            $sc_code = 'MI';
                                            break;
                    
                                        case 'Competitor Intelligence':
                                            $sc_code = 'CI';
                                            break;
                                        
                                        case 'Supplier Intelligence':
                                            $sc_code = 'SI';
                                            break;
                    
                                        case 'Open Innovation':
                                            $sc_code = 'OI';
                                            break;
                                    }
                                }
                    
                                $country_code = ($report_country!='') ? substr($report_country,0,1) : '';
                                if($report_sector_id!=''){
                                    $sectorData = Sector::where('id',$report_sector_id)->pluck('code');            
                                    $sector_code = $sectorData[0];
                                }  
                                if($report_sub_industry_id!=''){
                                    $SubIndustryData = SubIndustry::where('id',$report_sub_industry_id)->pluck('initial');
                                    $sub_industry_initial = $SubIndustryData[0];
                                }
                                $combination = $sector_code.$sub_industry_initial;
                                $report_count = $this->getReportCount($report_sub_industry_id,'Upcoming',$combination);
                    
                                $new_sku = "UC".$sc_code.$country_code.$sector_code.$sub_industry_initial.$report_count;
                                $dbData['product_id'] = $new_sku;
                    
                                $reportData = Report::create($dbData);

                                if($reportData){
                    
                                    // set import message
                                    $errorString .= '<br><b><span class="text-success">'.$reportData->name.' report imported successfully.</span></b>';
                                
                                    $report_id = $reportData->id;
                                    $report_name = $reportData->name;
    
                                    $pricingArr = array('Single','PPT',$single_ppt_price,'Single','Word',$single_word_price,'Single','Excel',$single_excel_price,'Single','PowerBI',$single_powerBI_price,'Multiple','PPT',$multiple_ppt_price,'Multiple','Word',$multiple_word_price,'Multiple','Excel',$multiple_excel_price,'Multiple','PowerBI',$multiple_powerBI_price,'Enterprise','PPT',$enterprise_ppt_price,'Enterprise','Word',$enterprise_word_price,'Enterprise','Excel',$enterprise_excel_price,'Enterprise','PowerBI',$enterprise_powerBI_price);                                
                                    for($k=0;$k<count($pricingArr);$k++){
                                        $pricingData = array();
                                        $pricingData['report_id'] = $report_id;
                                        $pricingData['license_type'] = $pricingArr[$k];
                                        $pricingData['file_type'] = $pricingArr[++$k];
                                        $pricingData['price'] = $pricingArr[++$k];
                                        ReportPricing::create($pricingData);
                                    }
                                                        
                                    for($s=0;$s<count($segments);$s++){    
                                        if(isset($segments[$s]) && isset($sub_segments[$s])){            
                                            $report_segments = array();
                                            $report_segments['report_id'] = $report_id;
                                            $report_segments['name'] = $segments[$s];                    
                                            $sub_seg = implode(", ",$sub_segments[$s]);
                                            $report_segments['value'] = $sub_seg;
                                            ReportSegment::create($report_segments);
                                        }
                                    }
                        
                                    // add FAQ                    
                                    // insert into report_faq
                                    $faq = array();
                                    $faq[0]['faq_question'] = "What is the estimated value of the global ".$report_name."?";
                                    $faq[0]['faq_answer'] = "The global market for ".str_replace('Market',"",$report_name). "was estimated to be valued at US$ XX Mn in 2021.";
                        
                                    $faq[1]['faq_question'] = "What is the expected growth rate of the global ".$report_name." during the forecast period?";
                                    $faq[1]['faq_answer'] = "The global ".$report_name." is estimated to grow at a CAGR of XX% by 2028.";
                        
                                    $faq[2]['faq_question'] = "What are the key industry segments covered in the report?";
                                    if(isset($segments)){
                                        $faq[2]['faq_answer'] = "The global ".$report_name." is segmented on the basis of ".implode(", ",$segments).".";
                                    }else {
                                        $faq[2]['faq_answer'] = '';
                                    }
                        
                                    $faq[3]['faq_question'] = "On the basis of region, how is the global ".$report_name." segmented?";
                                    $faq[3]['faq_answer'] = "Based on region, the global ".$report_name." is segmented into North America, Europe, Asia Pacific, Middle East & Africa and Latin America.";
                        
                                    $faq[4]['faq_question'] = "Who are the key players competing in the global ".$report_name."?";
                                    if(isset($companies)){
                                        $faq[4]['faq_answer'] = "The key players operating in the global ".$report_name." are ". implode(", ",$companies).".";
                                    }else{
                                        $faq[4]['faq_answer'] = '';
                                    }
                        
                                    for($k=0;$k<count($faq);$k++){
                                        if(isset($faq[$k]['faq_question']) && isset($faq[$k]['faq_answer'])){
                                            $faqData = array();
                                            $faqData['report_id'] = $report_id;
                                            $faqData['faq_question'] = $faq[$k]['faq_question'];
                                            $faqData['faq_answer'] = $faq[$k]['faq_answer'];
                                            $faqData['is_auto'] = '1';
                                            ReportFaq::create($faqData);
                                        }
                                    }
                                }
                            }                            
                        }
                    }
                }

                if($errorString!=''){
                    return $errorString;
                }
            }            
        }               
    }

    public function saveUpdatedUpcomingReports($file, $mode)
    {        
        $fname = $file->store('public');
        $name = $file->getClientOriginalName();
        $datasheetArray = Excel::toArray(new UpcomingReportImport, $fname);
        
        $mandatory_fields = array("sku","report name","description","parent market","market overview 1st para","sector","industry group","industry","sub-industry","sc","country","image","image alt","slug","meta_title","meta_description","downloads","pages","single_price","multiple_price","enterprise_price");
        $header_error_string = '';

        foreach ($datasheetArray as $sheet => $rows) {            
            if(count($rows[0])>0 && $sheet === 0) {            
                $errorString = "";
                if(is_array($rows[0])){
                    // check all headers exist or not
                    for($i=0;$i<count($rows[0]);$i++){
                        $titleArray[] = strtolower($rows[0][$i]);
                    }  
                    for($i=0;$i<count($mandatory_fields);$i++){
                        if(!in_array($mandatory_fields[$i], $titleArray)){
                            $header_error_string .= "<b><span class='text-danger'>".ucfirst($mandatory_fields[$i]). " does not exist.</span></b>, ";
                        }
                    }
                    if($header_error_string!=''){
                        return $header_error_string;
                    }

                    for($r=1;$r<count($rows);$r++){
                        $dbData = array();
                        $companies = '';
                        $report_name = '';
                        $description = '';

                        $report_sector_id = '';
                        $report_industry_group_id = '';
                        $report_industry_id = '';
                        $report_sub_industry_id = '';

                        $single_ppt_price = '';
                        $single_word_price = '';
                        $single_excel_price = '';
                        $single_powerBI_price = '';

                        $multiple_ppt_price = '';
                        $multiple_word_price = '';
                        $multiple_excel_price = '';
                        $multiple_powerBI_price = '';

                        $enterprise_ppt_price = '';
                        $enterprise_word_price = '';
                        $enterprise_excel_price = '';
                        $enterprise_powerBI_price = '';

                        $sku = '';
                        $new_sku = '';
                        $error_string = '';
                        $report_id = '';
                        $is_row_empty = 0;
                        if(isset($rows[$r]) && $rows[$r]!='' && !empty($rows[$r]) ){ //&& ($rows[$r][1]!='')
                            if(($rows[$r][0]==null || empty($rows[$r][0])) && 
                            ($rows[$r][1]==null || empty($rows[$r][1])) && 
                            ($rows[$r][2]==null || empty($rows[$r][2])) && 
                            ($rows[$r][3]==null || empty($rows[$r][3])) && 
                            ($rows[$r][4]==null || empty($rows[$r][4])) && 
                            ($rows[$r][5]==null || empty($rows[$r][5])) && 
                            ($rows[$r][6]==null || empty($rows[$r][6])) && 
                            ($rows[$r][7]==null || empty($rows[$r][7])) && 
                            ($rows[$r][8]==null || empty($rows[$r][8])) && 
                            ($rows[$r][9]==null || empty($rows[$r][9])) && 
                            ($rows[$r][10]==null || empty($rows[$r][10])))
                            { $is_row_empty = 1;}
                                                                                    
                            for($i=0;$i<count($rows[0]);$i++){                           
                                if(isset($rows[0][$i]) && ($is_row_empty==0)){
                                    switch(strtolower($rows[0][$i]))
                                    {
                                        case 'sku':
                                            $sku = $rows[$r][$i];
                                            if(trim($sku) == '' || trim(strtolower($sku)) == 'null'){
                                                $errorString .= '<br><b><span class="text-danger">Please add SKU details to the file to update the report.</span></b>';
                                            } else if($sku != ''){
                                                // fetch report by SKU from DB
                                                $reportData = Report::where('product_id',$sku)->whereNull('deleted_at')->first();
                                                
                                                if(!$reportData){
                                                    $errorString .= '<br><b><span class="text-danger">SKU does not exists in the database.</span></b>';
                                                }
                                                $report_id = ($reportData) ? $reportData->id : '';
                                            }                                            
                                            break;

                                        case 'report name':
                                            if($rows[$r][$i]!=''){
                                                $dbData['name'] = $rows[$r][$i];
                                                $report_name = $rows[$r][$i];
                                            } else{
                                                $error_string .= "Report Name does not exist., ";
                                            }
                                            break;
                                        
                                        case 'description':
                                            if($rows[$r][$i]!=''){
                                                $dbData['description'] = $rows[$r][$i];
                                                $description = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Description does not exist., ";
                                            }
                                            break;
                                        
                                        case 'parent market':
                                            if($rows[$r][$i]!=''){
                                                $dbData['parent_market'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Parent Market does not exist., ";
                                            }
                                            break;
                                        
                                        case 'market overview 1st para':
                                        case 'market overview':
                                            if($rows[$r][$i]!=''){
                                                $dbData['market_insights'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Market overview does not exist., ";
                                            }
                                            break;

                                        case 'sector':
                                            if($rows[$r][$i]!=''){                                        
                                                $sectorData = Sector::where('title',$rows[$r][$i])->first();
                                                if(!(isset($sectorData))){
                                                    $error_string .= "Sector : ".$rows[$r][$i]." does not exist in our records., ";
                                                }         
                                                $report_sector_id = (isset($sectorData)) ? $sectorData->id : '';
                                                $dbData['sector_id'] = (isset($sectorData)) ? $sectorData->id : '';
                                            }else{
                                                $error_string .= "Sector does not exist., ";
                                            }
                                            break;
                                    
                                        case 'industry group':
                                            if($rows[$r][$i]!=''){                                        
                                                $IndustryGroupData = IndustryGroup::where('title',$rows[$r][$i])->first();
                                                if(!(isset($IndustryGroupData))){
                                                    $error_string .= "Industry Group : ".$rows[$r][$i]." does not exist in our records., ";
                                                }
                                                if($report_sector_id){
                                                    $IndustryGroupData = IndustryGroup::where('title',$rows[$r][$i])->where('sector_id',$report_sector_id)->first();
                                                    if(!(isset($IndustryGroupData))){
                                                        $error_string .= "Industry Group : ".$rows[$r][$i]." does not map with given sector., ";
                                                    }
                                                }
                                                $report_industry_group_id = (isset($IndustryGroupData)) ? $IndustryGroupData->id : '';
                                                $dbData['industry_group_id'] = (isset($IndustryGroupData)) ? $IndustryGroupData->id : '';
                                            }else{
                                                $error_string .= "Industry Group does not exist., ";                                                
                                            }
                                            break;

                                        case 'industry':
                                            if($rows[$r][$i]!=''){                                        
                                                $IndustryData = Industry::where('title',$rows[$r][$i])->first();
                                                if(!(isset($IndustryData))){
                                                    $error_string .= "Industry : ".$rows[$r][$i]." does not exist in our records., ";
                                                }
                                                if($report_industry_group_id){
                                                    $IndustryData = Industry::where('title',$rows[$r][$i])->where('industry_group_id',$report_industry_group_id)->first();
                                                    if(!(isset($IndustryData))){
                                                        $error_string .= "Industry : ".$rows[$r][$i]." does not map with given industry group., ";
                                                    }
                                                }
                                                $report_industry_id = (isset($IndustryData)) ? $IndustryData->id : '';
                                                $dbData['industry_id'] = (isset($IndustryData)) ? $IndustryData->id : '';
                                            }else{
                                                $error_string .= "Industry does not exist., ";
                                            }
                                            break;

                                        case 'sub-industry':
                                            if($rows[$r][$i]!=''){                                        
                                                $SubIndustryData = SubIndustry::where('title',$rows[$r][$i])->first();
                                                if(!(isset($SubIndustryData))){
                                                    $error_string .= "Sub-Industry : ".$rows[$r][$i]." does not exist in our records., ";
                                                }
                                                if($report_industry_id){
                                                    $SubIndustryData = SubIndustry::where('title',$rows[$r][$i])->where('industry_id',$report_industry_id)->first();
                                                    if(!(isset($SubIndustryData))){
                                                        $error_string .= "Sub-Industry : ".$rows[$r][$i]." does not map with given industry., ";
                                                    }
                                                }
                                                $report_sub_industry_id = (isset($SubIndustryData)) ? $SubIndustryData->id : '';
                                                $dbData['sub_industry_id'] = (isset($SubIndustryData)) ? $SubIndustryData->id : '';
                                            }else{
                                                $error_string .= "Sub-Industry does not exist., ";
                                            }
                                            break;

                                        case 'sc':
                                            if($rows[$r][$i]!=''){
                                                $sc = $rows[$r][$i];
                                                $dbData['s_c'] = $rows[$r][$i];
                                            } else{
                                                $error_string .= "SC does not exist., ";
                                            }
                                            break;
                                        
                                        case 'country':
                                            if($rows[$r][$i]!=''){
                                                $report_country = $rows[$r][$i];
                                                $dbData['country'] = $rows[$r][$i];
                                            } else{
                                                $error_string .= "Country does not exist., ";
                                            }
                                            break;

                                        case 'image':
                                            if($rows[$r][$i]!=''){
                                                $dbData['image'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Image does not exist., ";
                                            }
                                            break;
                                        
                                        case 'image alt':
                                            if($rows[$r][$i]!=''){
                                                $dbData['image_alt'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Image Alt does not exist., ";
                                            }
                                            break;

                                        case 'slug':
                                            if($rows[$r][$i]!=''){
                                                if(strtolower($rows[$r][$i])=='null'){
                                                    $error_string .= "Slug can not be NULL., ";
                                                } else{
                                                    if($report_id){                                                    
                                                        // check slug already exists 
                                                        $reportSlug = checkReportSlug($rows[$r][$i],'',$report_id);
                                                        if(isset($reportSlug) && ($reportSlug->count() > 0)){
                                                            $error_string .= "Slug : ".$rows[$r][$i]." already exists., ";
                                                        }
                                                    }
                                                }            
                                                $dbData['slug'] = $rows[$r][$i];
                                            } else{
                                                $error_string .= "Slug does not exist., ";
                                            }
                                            break;

                                        case 'meta_title':
                                            if($rows[$r][$i]!=''){
                                                $dbData['meta_title'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Meta title does not exist., ";
                                            }
                                            break;

                                        case 'meta_description':
                                            if($rows[$r][$i]!=''){
                                                $dbData['meta_description'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Meta description does not exist., ";
                                            }
                                            break;
                                        
                                        case 'downloads':
                                            if($rows[$r][$i]!=''){
                                                $dbData['download'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Download does not exist., ";
                                            }
                                            break;

                                        case 'pages':
                                            if($rows[$r][$i]!=''){
                                                $dbData['pages'] = $rows[$r][$i];
                                            } else {
                                                $error_string .= "Pages does not exist., ";
                                            }
                                            break;
                                        
                                        case 'sample_link':
                                            $dbData['free_sample_report_link'] = $rows[$r][$i];
                                            break;

                                        case 'schema':
                                            $dbData['schema'] = $rows[$r][$i];
                                            break;

                                        case 'companies':
                                            $dbData['competitive_landscape'] = $rows[$r][$i];
                                            break;

                                        case 'single_price':
                                            if($rows[$r][$i]!=''){
                                                $single_price_array = explode("|",$rows[$r][$i]);                                               
                                                if($single_price_array){
                                                    $single_ppt_price = (isset($single_price_array[0]) && is_numeric(trim($single_price_array[0]))) ? trim($single_price_array[0]) : '';
                                                    $single_word_price = (isset($single_price_array[1]) && is_numeric(trim($single_price_array[1]))) ? trim($single_price_array[1]) : '' ;
                                                    $single_excel_price = (isset($single_price_array[2]) && is_numeric(trim($single_price_array[2]))) ? trim($single_price_array[2]) : '';
                                                    $single_powerBI_price = (isset($single_price_array[3]) && is_numeric(trim($single_price_array[3]))) ? trim($single_price_array[3]) : '';
                                                    
                                                    if(!$single_ppt_price || !$single_word_price || !$single_excel_price || !$single_powerBI_price){
                                                        $error_string .= "Single Price: ".$rows[$r][$i]." is invalid., ";
                                                    }
                                                }else {
                                                    $error_string .= "Single Price: ".$rows[$r][$i]." is invalid., ";
                                                }
                                            } else {
                                                $error_string .= "Single Price does not exist., ";
                                            }
                                            break;
                                        
                                        case 'multiple_price':
                                            if($rows[$r][$i]!=''){
                                                $multiple_price_array = explode("|",$rows[$r][$i]);
                                                if($multiple_price_array){
                                                    $multiple_ppt_price = (isset($multiple_price_array[0]) && is_numeric(trim($multiple_price_array[0]))) ? trim($multiple_price_array[0]) : '';
                                                    $multiple_word_price = (isset($multiple_price_array[1]) && is_numeric(trim($multiple_price_array[1]))) ? trim($multiple_price_array[1]) : '';
                                                    $multiple_excel_price = (isset($multiple_price_array[2]) && is_numeric(trim($multiple_price_array[2]))) ? trim($multiple_price_array[2]) : '';
                                                    $multiple_powerBI_price = (isset($multiple_price_array[3]) && is_numeric(trim($multiple_price_array[3]))) ? trim($multiple_price_array[3]) : '';

                                                    if(!$multiple_ppt_price || !$multiple_word_price || !$multiple_excel_price || !$multiple_powerBI_price){
                                                        $error_string .= "Multiple Price: ".$rows[$r][$i]." is invalid., ";
                                                    }
                                                }else {
                                                    $error_string .= "Multiple Price: ".$rows[$r][$i]." is invalid., ";
                                                }
                                            } else {
                                                $error_string .= "Multiple Price does not exist., ";
                                            }
                                            break;

                                        case 'enterprise_price':
                                            if($rows[$r][$i]!=''){
                                                $enterprise_price_array = explode("|",$rows[$r][$i]);
                                                if($enterprise_price_array){
                                                    $enterprise_ppt_price = (isset($enterprise_price_array[0]) && is_numeric(trim($enterprise_price_array[0]))) ? trim($enterprise_price_array[0]) : '';
                                                    $enterprise_word_price = (isset($enterprise_price_array[1]) && is_numeric(trim($enterprise_price_array[1]))) ? trim($enterprise_price_array[1]) : '';
                                                    $enterprise_excel_price = (isset($enterprise_price_array[2]) && is_numeric(trim($enterprise_price_array[2]))) ? trim($enterprise_price_array[2]) : '';
                                                    $enterprise_powerBI_price = (isset($enterprise_price_array[3]) && is_numeric(trim($enterprise_price_array[3]))) ? trim($enterprise_price_array[3]) : '';

                                                    if(!$enterprise_ppt_price || !$enterprise_word_price || !$enterprise_excel_price || !$enterprise_powerBI_price){
                                                        $error_string .= "Enterprise Price: ".$rows[$r][$i]." is invalid., ";
                                                    }
                                                }else {
                                                    $error_string .= "Enterprise Price: ".$rows[$r][$i]." is invalid., ";
                                                }
                                            } else {
                                                $error_string .= "Enterprise Price does not exist., ";
                                            }
                                            break;
                                    }
                                }
                            }

                            $segments = array();
                            $sub_segments = array();
                            $key_playersArr = array();
                            $companies = array();
                            //$reportData = '';

                            if($report_id!=''){
                                if($description!=''){
                                    if(str_contains($description, "KEY MARKET SEGMENTS") && strpos($description, "KEY MARKET SEGMENTS")>=0){
                                        $segmentStr = $this->string_between_two_string($description, 'KEY MARKET SEGMENTS', 'KEY PLAYERS');                    
                                        $segmentArr = explode("\n",$segmentStr);
                                        $segmentArr = array_filter($segmentArr);
                                        $p = 0;
                                        for($s=0;$s<count($segmentArr);$s++)
                                        {
                                            if(isset($segmentArr[$s]) && $segmentArr[$s]!=''){
                                                if((str_contains(trim($segmentArr[$s]),"By")) || (str_contains(trim($segmentArr[$s]),"BY"))){
                                                    $segments[$p] = trim(str_replace("BY","",str_replace("By","",trim($segmentArr[$s]))));
                                                    $p++;
                                                } else {
                                                    $sub_segments[$p-1][] = trim($segmentArr[$s]);
                                                }
                                            }
                                        }
                                        
                                        if(empty($segments)){
                                            $error_string .= "Segments does not exist., ";
                                        }
                                        $pos = strpos($description,"KEY PLAYERS");
                                        $len = strlen("KEY PLAYERS");
                                        $key_players = substr($description, $pos+$len);
                                        $key_playersArr = explode("\n",$key_players);
                        
                                        $key_playersArr = array_filter($key_playersArr);
                                        if(isset($key_playersArr)){
                                            $companies = $key_playersArr;
                                            $dbData['competitive_landscape'] = json_encode(array_values($key_playersArr));                     
                                        }else{
                                            $error_string .= "Key Players does not exist., ";
                                        }
                                    }else {
                                        $error_string .= "Key Players does not exist., ";
                                        $error_string .= "Segments does not exist., ";
                                    }
                                }else {
                                    $error_string .= "Key Players does not exist., ";
                                    $error_string .= "Segments does not exist., ";
                                }

                                if(!$single_ppt_price || !$single_word_price || !$single_excel_price || !$single_powerBI_price || !$multiple_ppt_price || !$multiple_word_price || !$multiple_excel_price || !$multiple_powerBI_price || !$enterprise_ppt_price || !$enterprise_word_price || !$enterprise_excel_price || !$enterprise_powerBI_price){
                                    $error_string .= "Price is invalid., ";
                                }
                                
                                if($error_string!='' && $is_row_empty==0){
                                    $errorString .= '<br><b><span class="text-danger">For '.$report_name.':-</b></span><br>'.$error_string;
                                }
                                
                                if($report_id!='' && $sku!='' && $error_string=='' && ($reportData->count()>0)){
                                    $dbData['report_type'] = 'Upcoming';
                                    $dbData['is_active'] = '1';
                                    if($mode=='Revamp') {
                                        $dbData['publish_date'] = date("Y-m-d");     
                                    }

                                    // check old data with uploaded one
                                    if(($reportData->s_c!=$sc) || ($reportData->country!=$report_country) || ($reportData->sector_id!=$report_sector_id) || ($reportData->sub_industry_id!=$report_sub_industry_id)){
                                        // generate product_id or SKU
                                        $country_code = '';
                                        $sector_code = '';
                                        $sub_industry_initial = '';
                                        $report_count = '';
                                        $sc_code = 'MI';
                            
                                        if($sc!=''){
                                            switch($sc){
                                                case 'Market intelligence':
                                                    $sc_code = 'MI';
                                                    break;
                            
                                                case 'Competitor Intelligence':
                                                    $sc_code = 'CI';
                                                    break;
                                                
                                                case 'Supplier Intelligence':
                                                    $sc_code = 'SI';
                                                    break;
                            
                                                case 'Open Innovation':
                                                    $sc_code = 'OI';
                                                    break;
                                            }
                                        }
                            
                                        $country_code = ($report_country!='') ? substr($report_country,0,1) : '';
                                        if($report_sector_id!=''){
                                            $sectorData = Sector::where('id',$report_sector_id)->pluck('code');            
                                            $sector_code = $sectorData[0];
                                        }  
                                        if($report_sub_industry_id!=''){
                                            $SubIndustryData = SubIndustry::where('id',$report_sub_industry_id)->pluck('initial');
                                            $sub_industry_initial = $SubIndustryData[0];
                                        }
                                        $combination = $sector_code.$sub_industry_initial;
                                        $report_count = $this->getReportCount($report_sub_industry_id,'Upcoming',$combination,$report_id);                                        
                                        $new_sku = "UC".$sc_code.$country_code.$sector_code.$sub_industry_initial.$report_count;
                                        $dbData['product_id'] = $new_sku;
                                    }
                                    
                                    $updated_report = Report::where('product_id',$sku)->update($dbData);
                                    if(isset($new_sku) && $new_sku!=''){
                                        $reportData = Report::where('product_id',$new_sku)->first();
                                    }else{
                                        $reportData = Report::where('product_id',$sku)->first();
                                    }
                                    
                                    if($reportData){                    
                                        // set import message
                                        $errorString .= '<br><b><span class="text-success">'.$reportData->name.' report imported successfully.</span></b>';
                                    
                                        $report_id = $reportData->id;
                                        $report_name = $reportData->name;
        
                                        // on update delete older pricing
                                        ReportPricing::where('report_id',$report_id)->delete();                                    
                                        $pricingArr = array('Single','PPT',$single_ppt_price,'Single','Word',$single_word_price,'Single','Excel',$single_excel_price,'Single','PowerBI',$single_powerBI_price,'Multiple','PPT',$multiple_ppt_price,'Multiple','Word',$multiple_word_price,'Multiple','Excel',$multiple_excel_price,'Multiple','PowerBI',$multiple_powerBI_price,'Enterprise','PPT',$enterprise_ppt_price,'Enterprise','Word',$enterprise_word_price,'Enterprise','Excel',$enterprise_excel_price,'Enterprise','PowerBI',$enterprise_powerBI_price);                                
                                        for($k=0;$k<count($pricingArr);$k++){
                                            $pricingData = array();
                                            $pricingData['report_id'] = $report_id;
                                            $pricingData['license_type'] = $pricingArr[$k];
                                            $pricingData['file_type'] = $pricingArr[++$k];
                                            $pricingData['price'] = $pricingArr[++$k];
                                            ReportPricing::create($pricingData);
                                        }
                                        
                                        // add segments
                                        // delete older segments data
                                        ReportSegment::where('report_id',$report_id)->delete();                        
                                        for($s=0;$s<count($segments);$s++){    
                                            if(isset($segments[$s]) && isset($sub_segments[$s])){            
                                                $report_segments = array();
                                                $report_segments['report_id'] = $report_id;
                                                $report_segments['name'] = $segments[$s];                    
                                                $sub_seg = implode(", ",$sub_segments[$s]);
                                                $report_segments['value'] = $sub_seg;
                                                ReportSegment::create($report_segments);
                                            }
                                        }
                            
                                        // add FAQ                    
                                        // insert into report_faq
                                        $faq = array();
                                        $faq[0]['faq_question'] = "What is the estimated value of the global ".$report_name."?";
                                        $faq[0]['faq_answer'] = "The global market for ".str_replace('Market',"",$report_name). "was estimated to be valued at US$ XX Mn in 2021.";
                            
                                        $faq[1]['faq_question'] = "What is the expected growth rate of the global ".$report_name." during the forecast period?";
                                        $faq[1]['faq_answer'] = "The global ".$report_name." is estimated to grow at a CAGR of XX% by 2028.";
                            
                                        $faq[2]['faq_question'] = "What are the key industry segments covered in the report?";
                                        if(isset($segments)){
                                            $faq[2]['faq_answer'] = "The global ".$report_name." is segmented on the basis of ".implode(", ",$segments).".";
                                        }else {
                                            $faq[2]['faq_answer'] = '';
                                        }
                            
                                        $faq[3]['faq_question'] = "On the basis of region, how is the global ".$report_name." segmented?";
                                        $faq[3]['faq_answer'] = "Based on region, the global ".$report_name." is segmented into North America, Europe, Asia Pacific, Middle East & Africa and Latin America.";
                            
                                        $faq[4]['faq_question'] = "Who are the key players competing in the global ".$report_name."?";
                                        if(isset($companies)){
                                            $faq[4]['faq_answer'] = "The key players operating in the global ".$report_name." are ". implode(", ",$companies).".";
                                        }else{
                                            $faq[4]['faq_answer'] = '';
                                        }
                            
                                        ReportFaq::where('report_id',$report_id)->where('is_auto','1')->delete();                                    
                                        for($k=0;$k<count($faq);$k++){
                                            if(isset($faq[$k]['faq_question']) && isset($faq[$k]['faq_answer'])){
                                                $faqData = array();
                                                $faqData['report_id'] = $report_id;
                                                $faqData['faq_question'] = $faq[$k]['faq_question'];
                                                $faqData['faq_answer'] = $faq[$k]['faq_answer'];
                                                $faqData['is_auto'] = '1';
                                                ReportFaq::create($faqData);
                                            }
                                        }
                                    }
                                }  
                            }                                          
                        }
                    }
                }
                
                if($errorString!=''){
                    return $errorString;
                }
            }       
        }               
    }

    function string_between_two_string($str, $starting_word, $ending_word)
    {
        $subtring_start = strpos($str, $starting_word);
        $subtring_start += strlen($starting_word);
        $size = '';
        $size = strpos($str, $ending_word, $subtring_start) - $subtring_start;
        if($size>0){
            return substr($str, $subtring_start, $size); 
        }else{
            return substr($str, $subtring_start); 
        }
    }

    public function graphimportFiles(Request $request) 
    {
        $reportData = array();
        $mode = '';
        $errorString = "";
        $extensionError = 0;
        if($request->files->count() > 0){
            foreach($request->file('excelfile') as $key => $file) {
                $fname = $file->store('public');
                $name = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                if($extension!='xlsx'){
                    $extensionError = 1;
                    $errorString .= '<b><span class="text-danger">For '.$name.':- please input valid file.</span></b>';
                }
                if($extensionError==0){
                    $product_id = $this->string_between_two_string($name,"_",".");
                    if($product_id!=''){
                        $reportData = Report::where('product_id',$product_id)->first();
                    }
                    if($reportData){
                        // check graph data exists or not
                        if(ReportGraphs::where('report_id',$reportData->id)->exists()) {
                            $mode = 'update';
                        }else{
                            $mode = 'create';
                        }

                        $datasheetArray = Excel::toArray(new ReportGraphsImport, $fname);
                        
                        foreach ($datasheetArray as $sheet => $rows) {
                            $dbData = array();
                            $items = array();
                            $itemData = array();
                            $finalcontent = "";
                            for($r=0;$r<count($rows);$r++){
                                if(is_array($rows[$r])){
                                    for($i=0;$i<count($rows[$r]);$i++){
                                        if(isset($rows[$r][$i])){
                                            $val = $rows[$r][$i];
                                            
                                            switch($val){
                                                case 'position':
                                                    $position = isset($rows[$r][$i+1]) ? $rows[$r][$i+1] : '';
                                                    break;
                                                case 'above_name':
                                                    $above_name = isset($rows[$r][$i+1]) ? $rows[$r][$i+1] : '';
                                                    break;
                                                case 'below_name':
                                                    $below_name = isset($rows[$r][$i+1]) ? $rows[$r][$i+1] : '';
                                                    break;
                                            }                                       
                                        }     
                                    }

                                    if($rows[$r][0]!='position' && $rows[$r][0]!='above_name' && $rows[$r][0]!='below_name' && $rows[$r][0]!=''){
                                        $itemData[] = array_filter($rows[$r]);
                                    }
                                }
                            }
                            if(count($itemData)>0){
                                $finalcontent = json_encode($itemData);
                            }
                            switch($sheet){
                                case '0':
                                    $graph_type = 'global_market_by_region';
                                    break;
                                case '1':
                                    $graph_type = 'country_share';
                                    break;
                                case '2':
                                    $graph_type = 'segment_1_share';
                                    break;
                                case '3':
                                    $graph_type = 'cagr';
                                    break;
                                case '4':
                                    $graph_type = 'segment_2_share';
                                    break;
                                case '5':
                                    $graph_type = 'worldmap';
                                    break;
                            }
                            // save to DB
                            $dbData['report_id'] = $reportData->id;
                            $dbData['graph_type'] = ($graph_type!='') ? $graph_type : '';
                            $dbData['position'] = ($position!='') ? $position : '';
                            $dbData['above_name'] = ($above_name!='') ? $above_name : '';
                            $dbData['below_name'] = ($below_name!='') ? $below_name : '';
                            $dbData['content'] = ($finalcontent!='') ? $finalcontent : '';

                            if($mode == 'create') {
                                $reportGraphData = ReportGraphs::create($dbData);
                            }else{
                                $reportGraphData = ReportGraphs::where('report_id',$reportData->id)->where('graph_type',$graph_type)->update($dbData);
                            }                        
                        }      
                        $errorString .= '<br><b><span class="text-success">'.$name.' - Report graph imported successfully.</span></b>';
                    } else{
                        $errorString .= '<br><b><span class="text-danger">Error in file '.$name.' - </span></b><br> Report with this SKU does not exists in the database.';
                    }
                }
            }

            if(isset($errorString)){
                $notification = ['import_error_message' => $errorString,'alert-class' => 'error'];
                return redirect()->route('admin.report.index')->with($notification);
            }
            
            $notification = ['message' => 'Report graph imported successfully','alert-class' => 'success'];
        } else {
            $notification = ['import_error_message' => '<span class="text-danger">Please select atleast one excel file to import.</span>','alert-class' => 'error'];
		}
		return redirect()->route('admin.report.index')->with($notification);
    }

    // migrate reports, segments, TOC and graphs data
    public function migrateReports()
    {
        ini_set('max_execution_time', -1);
        $csi_reports = new CSIReports;
        $csi_reports->setConnection('pgsql');
        $csi_reports_data = $csi_reports->get();

        $report_data = array();

        if(!$csi_reports_data->isEmpty()){

            foreach($csi_reports_data as $row){                
                $sector_id = '';
                $industry_group_id = '';
                $industry_id = '';
                $sub_industry_id = '';
                $created_at = '';
                $updated_at = '';
                
                $dateData = AuthenticationBasemodel::on('pgsql')->where('uuid',$row['basemodel_ptr_id'])->get();
                $created_at = !($dateData->isEmpty()) ? $dateData[0]->created_at : '';
                $updated_at = !($dateData->isEmpty()) ? $dateData[0]->updated_at : '';

                $sectors = CSISectormodel::on('pgsql')->where('basemodel_ptr_id',$row['sector_id'])->get();
                $sector_name = !($sectors->isEmpty()) ? $sectors[0]->title : '';
                $sectorData = Sector::where('title',$sector_name)->first();
                if(isset($sectorData)){
                    $sector_id = $sectorData->id;
                }

                $industry_groups = CSIIndustrygroupmodel::on('pgsql')->where('basemodel_ptr_id',$row['industry_group_id'])->get();
                $industry_groups_name = !($industry_groups->isEmpty()) ? $industry_groups[0]->title : '';
                $industryGroupData = IndustryGroup::where('title',$industry_groups_name)->first();
                if(isset($industryGroupData)){
                    $industry_group_id = $industryGroupData->id;
                }

                $industries = CSIIndustrymodel::on('pgsql')->where('basemodel_ptr_id',$row['industry_id'])->get();
                $industries_name = !($industries->isEmpty()) ? $industries[0]->title : '';
                $industryData = Industry::where('title',$industries_name)->first();
                if(isset($industryData)){
                    $industry_id = $industryData->id;
                }

                $sub_industries = CSISubindustrymodel::on('pgsql')->where('basemodel_ptr_id',$row['subindustry_id'])->get();
                $sub_industries_name = !($sub_industries->isEmpty()) ? $sub_industries[0]->title : '';
                $subindustryData = SubIndustry::where('title',$sub_industries_name)->first();
                if(isset($subindustryData)){
                    $sub_industry_id = $subindustryData->id;
                }
                
                if($sector_id) $report_data['sector_id'] = $sector_id;
                if($industry_group_id) $report_data['industry_group_id'] = $industry_group_id;
                if($industry_id) $report_data['industry_id'] = $industry_id;
                if($sub_industry_id) $report_data['sub_industry_id'] = $sub_industry_id;

                $report_data['name'] = $row["report_name"];
                $report_data['image'] = $row["img"];
                $report_data['country'] = $row["country"];
                $report_data['product_id'] = $row["product_code"];
                $report_data['download'] = $row["downloads"];
                $report_data['image_alt'] = $row["img_alt"];
                $report_data['slug'] = $row["report_slug"];
                $report_data['meta_title'] = $row["meta_title"];
                $report_data['meta_description'] = $row["meta_description"];
                $report_data['pages'] = $row["pages"];
                $report_data['methodologies'] = $row["methodologies"];
                $report_data['analyst_support'] = $row["analyst_support"];
                $report_data['market_insights'] = $row["description_1"];
                $report_data['segmental_analysis'] = $row["description_2"];
                $report_data['regional_insights'] = $row["description_3"];
                $report_data['market_dynamics'] = $row["description_4"];
                $report_data['competitive_landscape'] = $row["description_5"];
                $report_data['key_market_trends'] = $row["description_6"];
                $report_data['skyQuest_analysis'] = $row["description_7"];
                $report_data['whats_included'] = $row["whats_included"];
                $report_data['s_c'] = $row["sc"];
                $report_data['free_sample_report_link'] = $row["sample_link"];
                $report_data['report_type'] = 'SD';
                $report_data['is_active'] = '1';
                $report_data['created_at'] = $created_at;
                $report_data['updated_at'] = $updated_at;
                $report_data['publish_date'] = $updated_at;

                $report = Report::create($report_data);
                
                $table_of_content = $row['table_of_content'];
                $segments = $row['segments'];
                $basemodel_ptr_id = $row['basemodel_ptr_id'];

                if($report){
                    $report_id = $report->id;

                    // add toc
                    if(isset($table_of_content)){
                        $tocData = array();
                        $tocData['report_id'] = $report_id;
                        $tocData['toc'] = $table_of_content;
                        $tocData['tables'] = '';
                        $tocData['figures'] = '';
                        ReportTableofcontent::create($tocData);
                    }

                    // add segments
                    if(isset($segments)){
                        $segmentArr = json_decode($segments,true);
                        foreach($segmentArr as $segmentData){
                            $report_segments = array();
                            $report_segments['report_id'] = $report_id;
                            $report_segments['name'] = $segmentData['segment'];

                            $sub_seg = implode(", ",$segmentData['sub_segments']);
                            $report_segments['value'] = $sub_seg;
                            ReportSegment::create($report_segments);
                        }
                    }

                    // add graphs
                    if(isset($basemodel_ptr_id)){
                        $graphs = CSIGraphs::on('pgsql')->where('report_id',$basemodel_ptr_id)->get()->toArray();                        
                        if(isset($graphs)){
                            for($g=0;$g<count($graphs);$g++){
                                $graph_type = $graphs[$g]['graph_type'];
                                if($graph_type!='' && $graph_type!='Histogram'){
                                    if($graph_type == 'global_market_byregion') $graph_type = 'global_market_by_region';                            
                                    if($graph_type == 'Worldmap') $graph_type = 'worldmap';

                                    $graph_data = $graphs[$g]['graph_data'];
                                    $graph_data_arr = json_decode($graph_data,true);
                                    
                                    $position = '';
                                    $above_name = '';
                                    $below_name = '';

                                    if(count($graph_data_arr)){
                                        $content = json_encode($graph_data_arr['data']);
                                        $position = (isset($graph_data_arr['position'])) ? intval($graph_data_arr['position']) : '';
                                        $above_name = (isset($graph_data_arr['above_name'])) ? $graph_data_arr['above_name'] : '';
                                        $below_name = (isset($graph_data_arr['below_name'])) ? $graph_data_arr['below_name'] : '';                                    
                                    }

                                    $graphData = array();
                                    $graphData['report_id'] = $report_id;
                                    $graphData['graph_type'] = $graph_type;
                                    $graphData['position'] = $position;
                                    $graphData['above_name'] = $above_name;
                                    $graphData['below_name'] = $below_name;
                                    $graphData['content'] = $content;                                                      
                                    ReportGraphs::create($graphData);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // migrate reports data - image, pricing, metrics and faq
    public function migrateReportData()
    {
        ini_set('max_execution_time', -1);
        $reports = Report::where('is_active','1')->get();
        if(!$reports->isEmpty()){            
            foreach($reports as $dbData){
                $report_id = $dbData->id;
                $report_name = $dbData->name;
                $market_insights = $dbData->market_insights;
                $competitive_landscape = $dbData->competitive_landscape;
                $market_dynamics = $dbData->market_dynamics;
                $key_market_trends = $dbData->key_market_trends;
                $regional_insights = $dbData->regional_insights;
                $table_of_content = $dbData->table_of_content;

                $segmentArr = array();
                $segments = ReportSegment::where('report_id',$report_id)->get();
                $segmentArr = $segments->toArray();
                
                // upload image
                /* $updateData = array();
                if($dbData->image != '' && $dbData->image != 'Null'){
                    $contents = file_get_contents(str_replace(' ', '%20', $dbData->image));
                    $imageName = substr($dbData->image, strrpos($dbData->image, '/') + 1);
                    Storage::put($imageName, $contents);
                    $path = storage_path() .'/app/'. $imageName;
                    $folder = config('cloudinary.upload_preset') . config('constants.REPORT_PATH');
                    try {
                        $uploadedImg = cloudinary()->upload($path,['folder' => $folder])->getSecurePath();
                        $updateData['image'] = $uploadedImg;
                        Report::where('id',$report_id)->update($updateData);
                        Storage::delete($imageName);
                    } catch (\Exception $e) {}
                } */

                // insert into report_pricing
                $pricingArr = array('Single','PPT','4000','Single','Word','4000','Single','Excel','4000','Single','PowerBI','4500','Multiple','PPT','6000','Multiple','Word','6000','Multiple','Excel','6000','Multiple','PowerBI','6500','Enterprise','PPT','8000','Enterprise','Word','8000','Enterprise','Excel','8000','Enterprise','PowerBI','8500');
                for($k=0;$k<count($pricingArr);$k++){
                    $pricingData = array();
                    $pricingData['report_id'] = $report_id;
                    $pricingData['license_type'] = $pricingArr[$k];
                    $pricingData['file_type'] = $pricingArr[++$k];
                    $pricingData['price'] = $pricingArr[++$k];
                    ReportPricing::create($pricingData);
                }

                // insert into report_metrics
                $metrics = array();
                if(isset($market_insights)){
                    $market_insights_para = $this->getFirstPTag($market_insights);
                    $contentArr = explode(" ",$market_insights_para);
                    if(is_array($contentArr) && count($contentArr) >0) {
                        if(strpos($market_insights_para, "in")) {          
                            $startyear_key = array_search("in",$contentArr);
                            $metrics['startyear'] = ($startyear_key!='') ? rtrim($contentArr[$startyear_key+1],",") : '';
                        }

                        if(strpos($market_insights_para, "CAGR")) {                                               
                            $growth_rate = trim($this->string_between_two_string($market_insights_para, 'CAGR of', '%'));
                            $metrics['growth_rate'] = $growth_rate."%";
                        }

                        if(strpos($market_insights_para, "period")) {
                            $metrics['forecast_period'] = str_replace(")","",str_replace("(","",strip_tags(trim($this->string_between_two_string($market_insights_para, 'period', '.')))));
                        }
                    }

                    if(strpos($market_insights_para, "by")) {
                        $endyear_pos = strpos($market_insights_para,"by");
                        $metrics['endyear']  = substr($market_insights_para, $endyear_pos+3, 4);
                    }

                    if(strpos($market_insights_para, "valued")) {
                        $startyear_size = trim($this->string_between_two_string($market_insights_para, 'valued', 'in'));
                        $startyear_arr = explode(" ",$startyear_size);                        
                        $startyear_key = array_search("USD",$startyear_arr);
                        $startyear_size = '';
                        if($startyear_key){
                            if(isset($startyear_arr[$startyear_key]))
                                $startyear_size = $startyear_arr[$startyear_key];
                            if(isset($startyear_arr[$startyear_key+1]))
                                $startyear_size .= ' '.$startyear_arr[$startyear_key+1];
                            if(isset($startyear_arr[$startyear_key+2]))
                                $startyear_size .= ' '.$startyear_arr[$startyear_key+2];
                        }
                        $metrics['startyear_size'] = $startyear_size;
                    }

                    if((strpos($market_insights_para, "USD")!='') && (strpos($market_insights_para, "by")!='')) {
                        $end_year_size = $this->string_between_two_string($market_insights_para, 'USD', 'by');                            
                        $end_year_arr = explode(" ",$end_year_size);
                        $end_year_key = array_search("USD",$end_year_arr);
                        $endyear_size = '';
                        if(isset($end_year_arr[$end_year_key+1]) && (isset($end_year_arr[$end_year_key+2]))){
                            $endyear_size = $end_year_arr[$end_year_key].' '.$end_year_arr[$end_year_key+1].' '.$end_year_arr[$end_year_key+2];
                        }
                        $metrics['endyear_size'] = $endyear_size;
                        $forecast_unit = '';
                        if(isset($end_year_arr[$end_year_key+2])){
                            $forecast_unit = $end_year_arr[$end_year_key].' '.ucfirst($end_year_arr[$end_year_key+2]);
                        }
                        $metrics['forecast_unit'] = $forecast_unit;
                    }
                }

                if(isset($competitive_landscape)){ 
                    $metrics['companies_covered'] = $this->getTopPlayerCompanies($competitive_landscape,'1');
                }

                if(count($metrics)>0){
                    if(isset($metrics['startyear'])) {
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Market size value in '.$metrics['startyear'];
                        $metricsArr['meta_value'] = isset($metrics['startyear_size']) ? $metrics['startyear_size'] : '';            
                        ReportMetrics::create($metricsArr);
                    }

                    if(isset($metrics['endyear'])) {
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Market size value in '.$metrics['endyear'];
                        $metricsArr['meta_value'] = isset($metrics['endyear_size']) ? $metrics['endyear_size'] : '';                    
                        ReportMetrics::create($metricsArr);
                    }

                    if(isset($metrics['growth_rate'])) {
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Growth Rate';
                        $metricsArr['meta_value'] = $metrics['growth_rate'];                    
                        ReportMetrics::create($metricsArr);
                    }

                    if(isset($metrics['startyear'])) {
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Base year';
                        $metricsArr['meta_value'] = $metrics['startyear'];                    
                        ReportMetrics::create($metricsArr);
                    }

                    if(isset($metrics['forecast_period'])) {
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Forecast period';
                        $metricsArr['meta_value'] = $metrics['forecast_period'];                    
                        ReportMetrics::create($metricsArr);
                    }

                    if(isset($metrics['forecast_unit'])) {
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Forecast Unit (Value)';
                        $metricsArr['meta_value'] = $metrics['forecast_unit'];                    
                        ReportMetrics::create($metricsArr);
                    }
                                
                    if(isset($segmentArr) && (count($segmentArr) > 0)) {
                        $segmentDataArr = '<ul>';
                        for($k=0;$k<count($segmentArr);$k++){
                            $segmentDataArr .= '<li>'.$segmentArr[$k]['name'];
                            $segmentDataArr .= '<ul>';
                            $segmentDataArr .= '<li>'.$segmentArr[$k]['value'].'</li>';
                            $segmentDataArr .= '</ul>';
                            $segmentDataArr .= '</li>';
                        }
                        $segmentDataArr .= '</ul>';

                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Segments covered';
                        $metricsArr['meta_value'] = $segmentDataArr;              
                        ReportMetrics::create($metricsArr);
                    }

                    // for Regions covered
                    $regions_covered = "North America (US, Canada), Europe (Germany, France, United Kingdom, Italy, Spain, Rest of Europe), Asia Pacific (China, India, Japan, Rest of Asia-Pacific), Latin America (Brazil, Rest of Latin America), Middle East & Africa (South Africa, GCC Countries, Rest of MEA)";
                    $metricsArr = array();
                    $metricsArr['report_id'] = $report_id;
                    $metricsArr['meta_key'] = 'Regions covered';
                    $metricsArr['meta_value'] = $regions_covered;
                    ReportMetrics::create($metricsArr);

                    // for Companies covered
                    if(isset($metrics['companies_covered'])) {
                        $metricsArr = array();
                        $metricsArr['report_id'] = $report_id;
                        $metricsArr['meta_key'] = 'Companies covered';
                        $metricsArr['meta_value'] = $metrics['companies_covered'];                    
                        ReportMetrics::create($metricsArr);
                    }

                    // for Customization scope            
                    $customization_scope = '<p>Free report customization with purchase. Customization includes:-
                    <ul><li>Segments by type, application, etc</li><li>Company profile</li><li>Market dynamics & outlook</li><li>Region</li></ul></p>';            
                    $metricsArr = array();
                    $metricsArr['report_id'] = $report_id;
                    $metricsArr['meta_key'] = 'Customization scope';
                    $metricsArr['meta_value'] = $customization_scope;                    
                    ReportMetrics::create($metricsArr);
                    
                }

                // insert into report_faq
                $faq = array();
                // Faq Q1 - first para of Market Insights
                $faq_answer1 = '';
                if(isset($market_insights)){                
                    $faq_answer1 = $this->getFirstPTag($market_insights);
                }
                $faq[0]['faq_question'] = "What is the global market size of ";
                $faq[0]['faq_answer'] = $faq_answer1;

                // Faq Q2 - first para and top player company of Competitive Landscape                 
                $faq_answer2 = '';
                if(isset($competitive_landscape)){ 
                    $faq_answer2 = $this->getFirstPTag($competitive_landscape);
                    $faq_answer2 .= $this->getTopPlayerCompanies($competitive_landscape,'0');                        
                }
                $faq[1]['faq_question'] = "Who are the key vendors in the ";
                $faq[1]['faq_answer'] = $faq_answer2;

                // Faq Q3 - text from first list point of Market Dynamics
                $faq_answer3 = '';
                if(isset($market_dynamics)){ 
                    if(strpos($market_dynamics, "<li>")!=''){
                        $faq_answer3 = $this->getFirstLiTag($market_dynamics);
                    }
                }
                $faq[2]['faq_question'] = "What is the key driver of ";
                $faq[2]['faq_answer'] = $faq_answer3;

                // Faq Q4 - first list of Key Market Trends
                $faq_answer4 = '';
                if(isset($key_market_trends)){
                    if(strpos($key_market_trends, "<li>")!=''){
                        $faq_answer4 = $this->getFirstLiTag($key_market_trends);
                    }
                }
                $faq[3]['faq_question'] = "What is the key market trend for ";
                $faq[3]['faq_answer'] = $faq_answer4;

                // Faq Q5 - first para of Regional Insights - Done
                $faq_answer5 = '';
                if(isset($regional_insights)){
                    $faq_answer5 = $this->getFirstPTag($regional_insights);
                }
                //$faq_answer5 = $regional_insights;
                $faq[4]['faq_question'] = "Which region accounted for the largest share in ";
                $faq[4]['faq_answer'] = $faq_answer5;

                for($k=0;$k<count($faq);$k++){
                    if($k==0){
                        $updated_name = str_replace('Global',"",$report_name);
                        $que = $faq[$k]['faq_question'].$updated_name."?";
                    } else {
                        $que = $faq[$k]['faq_question'].$report_name."?";
                    }

                    $faqData = array();
                    $faqData['report_id'] = $report_id;
                    $faqData['faq_question'] = $que;
                    $faqData['faq_answer'] = strip_tags($faq[$k]['faq_answer']);
                    $faqData['is_auto'] = '1';
                    ReportFaq::create($faqData);
                }
            }
        }
    }

    // migrate upcoming reports, segments, pricing and faq
    public function migrateUpcomingReports()
    {
        ini_set('max_execution_time', -1);
        $csi_reports = new CSIUpcomingreports;
        $csi_reports->setConnection('pgsql');
        $csi_reports_data = $csi_reports->get();
        $report_data = array();

        if(!$csi_reports_data->isEmpty()){

            foreach($csi_reports_data as $row){                
                $sector_id = '';
                $industry_group_id = '';
                $industry_id = '';
                $sub_industry_id = '';

                $dateData = AuthenticationBasemodel::on('pgsql')->where('uuid',$row['basemodel_ptr_id'])->get();
                $created_at = !($dateData->isEmpty()) ? $dateData[0]->created_at : '';
                $updated_at = !($dateData->isEmpty()) ? $dateData[0]->updated_at : '';
                
                $sectors = CSISectormodel::on('pgsql')->where('basemodel_ptr_id',$row['sector_id'])->get();
                $sector_name = !($sectors->isEmpty()) ? $sectors[0]->title : '';
                $sectorData = Sector::where('title',$sector_name)->first();
                if(isset($sectorData)){
                    $sector_id = $sectorData->id;
                }

                $industry_groups = CSIIndustrygroupmodel::on('pgsql')->where('basemodel_ptr_id',$row['industry_group_id'])->get();
                $industry_groups_name = !($industry_groups->isEmpty()) ? $industry_groups[0]->title : '';
                $industryGroupData = IndustryGroup::where('title',$industry_groups_name)->first();
                if(isset($industryGroupData)){
                    $industry_group_id = $industryGroupData->id;
                }

                $industries = CSIIndustrymodel::on('pgsql')->where('basemodel_ptr_id',$row['industry_id'])->get();
                $industries_name = !($industries->isEmpty()) ? $industries[0]->title : '';
                $industryData = Industry::where('title',$industries_name)->first();
                if(isset($industryData)){
                    $industry_id = $industryData->id;
                }

                $sub_industries = CSISubindustrymodel::on('pgsql')->where('basemodel_ptr_id',$row['subindustry_id'])->get();
                $sub_industries_name = !($sub_industries->isEmpty()) ? $sub_industries[0]->title : '';
                $subindustryData = SubIndustry::where('title',$sub_industries_name)->first();
                if(isset($subindustryData)){
                    $sub_industry_id = $subindustryData->id;
                }
                
                if($sector_id) $report_data['sector_id'] = $sector_id;
                if($industry_group_id) $report_data['industry_group_id'] = $industry_group_id;
                if($industry_id) $report_data['industry_id'] = $industry_id;
                if($sub_industry_id) $report_data['sub_industry_id'] = $sub_industry_id;

                $report_data['name'] = $row["report_name"];
                $report_data['image'] = $row["img"];
                $report_data['country'] = $row["country"];
                $report_data['product_id'] = $row["product_code"];
                $report_data['download'] = $row["downloads"];
                $report_data['image_alt'] = $row["img_alt"];
                $report_data['slug'] = $row["report_slug"];
                $report_data['meta_title'] = $row["meta_title"];
                $report_data['meta_description'] = $row["meta_description"];
                $report_data['pages'] = $row["pages"];
                $report_data['methodologies'] = $row["methodologies"];
                $report_data['analyst_support'] = $row["analyst_support"];                
                $report_data['description'] = $row["description"];
                $report_data['competitive_landscape'] = $row['companies'];
                $report_data['s_c'] = $row["sc"];
                $report_data['free_sample_report_link'] = $row["sample_link"];
                $report_data['report_type'] = 'Upcoming';
                $report_data['market_insights'] = $row['market_overview'];
                $report_data['parent_market'] = $row['parent_market'];
                $report_data['is_active'] = '1';
                $report_data['created_at'] = $created_at;
                $report_data['updated_at'] = $updated_at;
                $report_data['publish_date'] = $updated_at;

                $report = Report::create($report_data);

                if($report){

                    $report_id = $report->id;
                    $report_name = $row["report_name"];
                    $table_of_content = $row['table_of_content'];

                    // insert into report_pricing
                    $pricingArr = array('Single','PPT','4000','Single','Word','4000','Single','Excel','4000','Single','PowerBI','4500','Multiple','PPT','6000','Multiple','Word','6000','Multiple','Excel','6000','Multiple','PowerBI','6500','Enterprise','PPT','8000','Enterprise','Word','8000','Enterprise','Excel','8000','Enterprise','PowerBI','8500');
                    for($k=0;$k<count($pricingArr);$k++){
                        $pricingData = array();
                        $pricingData['report_id'] = $report_id;
                        $pricingData['license_type'] = $pricingArr[$k];
                        $pricingData['file_type'] = $pricingArr[++$k];
                        $pricingData['price'] = $pricingArr[++$k];
                        ReportPricing::create($pricingData);
                    }

                    // add segments
                    $segmentArr = json_decode($row['segments'],true);
                    $segments = array();
                    $sub_segments = array();                    
                    foreach($segmentArr as $segmentData){
                        $report_segments = array();

                        $segments[] = $segmentData['segment'];
                        $sub_segments[] = $segmentData['sub_segments'];

                        $report_segments['report_id'] = $report_id;
                        $report_segments['name'] = $segmentData['segment'];
                        $sub_seg = implode(", ",$segmentData['sub_segments']);
                        $report_segments['value'] = $sub_seg;                        
                        ReportSegment::create($report_segments);
                    }

                    // add FAQ                    
                    // insert into report_faq
                    $faq = array();
                    $faq[0]['faq_question'] = "What is the estimated value of the global ".$report_name."?";
                    $faq[0]['faq_answer'] = "The global market for ".str_replace('Market',"",$report_name). "was estimated to be valued at US$ XX Mn in 2021.";

                    $faq[1]['faq_question'] = "What is the expected growth rate of the global ".$report_name." during the forecast period?";
                    $faq[1]['faq_answer'] = "The global ".$report_name." is estimated to grow at a CAGR of XX% by 2028.";

                    $faq[2]['faq_question'] = "What are the key industry segments covered in the report?";
                    $faq[2]['faq_answer'] = "The global ".$report_name." is segmented on the basis of ".implode(", ",$segments).".";

                    $faq[3]['faq_question'] = "On the basis of region, how is the global ".$report_name." segmented?";
                    $faq[3]['faq_answer'] = "Based on region, the global ".$report_name." is segmented into North America, Europe, Asia Pacific, Middle East & Africa and Latin America.";

                    $faq[4]['faq_question'] = "Who are the key players competing in the global ".$report_name."?";
                    $faq[4]['faq_answer'] = "The key players operating in the global ".$report_name." are ". implode(", ",json_decode($row['companies'],true)).".";

                    for($k=0;$k<count($faq);$k++){
                        $faqData = array();
                        $faqData['report_id'] = $report_id;
                        $faqData['faq_question'] = $faq[$k]['faq_question'];
                        $faqData['faq_answer'] = $faq[$k]['faq_answer'];
                        $faqData['is_auto'] = '1';
                        ReportFaq::create($faqData);
                    }
                }
            }
        }
    }

    // needed for migrate report data - starts
    function getFirstPTag($string)
    {
        $start = strpos($string, '<p>');
        $end = strpos($string, '</p>', $start);
        $sub_str = substr($string, $start, $end-$start+4);
        return $sub_str;
    }

    function getFirstLiTag($string)
    {
        $start = strpos($string, '<li>');
        $end = strpos($string, '</li>', $start);
        $sub_str = substr($string, $start, $end-$start+5);
        return $sub_str;
    }

    function getTopPlayerCompanies($string,$isList='1')
    {
        $start1 = strpos($string, 'Player');
        $end1 = strpos($string, '</ul>', $start1);
        
        $d = substr($string, $start1, $end1-$start1+5);
        
        $start2 = strpos($d, '<ul>');
        $end2 = strpos($d, '</ul>', $start2);
        
        $str = substr($d, $start2, $end2-$start2+5);

        if($isList=='1'){
            return $str;
        } else {
            $final_str = str_replace("</ul>","",str_replace("<ul>","",str_replace("</li>","',",str_replace("<li>","'",$str))));
            return rtrim($final_str,",");
        }        
    }
    // needed for migrate report data - ends

    // Report pricing module
    public function reportPricing()
    {
        $reports = Report::get();
        $title = 'Report Pricing';
        return view('admin.report-pricing.index',compact('title','reports'));
    }

    public function updateReportPricing(Request $request)
    {
        $request->validate([
            'report_selection'   => 'required',
            'reports'  => 'required_if:report_selection,selection',
            'single_ppt_price' => 'required',
            'single_word_price' => 'required',
            'single_excel_price' => 'required',
            'single_powerBI_price' => 'required',
            'multiple_ppt_price' => 'required',
            'multiple_word_price' => 'required',
            'multiple_excel_price' => 'required',
            'multiple_powerBI_price' => 'required',
            'enterprise_ppt_price' => 'required',
            'enterprise_word_price' => 'required',
            'enterprise_excel_price' => 'required',
            'enterprise_powerBI_price' => 'required',
        ]);
        
        $requestedPricing = ['single_ppt_price' => $request->single_ppt_price,
        'single_word_price' => $request->single_word_price,
        'single_excel_price' => $request->single_excel_price,
        'single_powerBI_price' => $request->single_powerBI_price,
        'multiple_ppt_price' => $request->multiple_ppt_price,
        'multiple_word_price' => $request->multiple_word_price,
        'multiple_excel_price' => $request->multiple_excel_price,
        'multiple_powerBI_price' => $request->multiple_powerBI_price,
        'enterprise_ppt_price' => $request->enterprise_ppt_price,
        'enterprise_word_price' => $request->enterprise_word_price,
        'enterprise_excel_price' => $request->enterprise_excel_price,
        'enterprise_powerBI_price' => $request->enterprise_powerBI_price];

        // update price for selected reports
        if($request->report_selection=='selection'){
            $reports = $request->reports;
            if($reports){                
                for($r=0;$r<count($reports);$r++){                    
                    if(!empty($reports[$r])){
                        $report = Report::find($reports[$r]);
                        if($report->id){
                            ReportPricing::updateOrCreate(['license_type' => 'Single', 'file_type' => 'PPT', 'report_id' => $report->id], ['price' => $request->single_ppt_price]);
                            ReportPricing::updateOrCreate(['license_type' => 'Single', 'file_type' => 'Word', 'report_id' => $report->id], ['price' => $request->single_word_price]);
                            ReportPricing::updateOrCreate(['license_type' => 'Single', 'file_type' => 'Excel', 'report_id' => $report->id], ['price' => $request->single_excel_price]);
                            ReportPricing::updateOrCreate(['license_type' => 'Single', 'file_type' => 'PowerBI', 'report_id' => $report->id], ['price' => $request->single_powerBI_price]);
                            ReportPricing::updateOrCreate(['license_type' => 'Multiple', 'file_type' => 'PPT', 'report_id' => $report->id], ['price' => $request->multiple_ppt_price]);
                            ReportPricing::updateOrCreate(['license_type' => 'Multiple', 'file_type' => 'Word', 'report_id' => $report->id], ['price' => $request->multiple_word_price]);
                            ReportPricing::updateOrCreate(['license_type' => 'Multiple', 'file_type' => 'Excel', 'report_id' => $report->id], ['price' => $request->multiple_excel_price]);
                            ReportPricing::updateOrCreate(['license_type' => 'Multiple', 'file_type' => 'PowerBI', 'report_id' => $report->id], ['price' => $request->multiple_powerBI_price]);
                            ReportPricing::updateOrCreate(['license_type' => 'Enterprise', 'file_type' => 'PPT', 'report_id' => $report->id], ['price' => $request->enterprise_ppt_price]);
                            ReportPricing::updateOrCreate(['license_type' => 'Enterprise', 'file_type' => 'Word', 'report_id' => $report->id], ['price' => $request->enterprise_word_price]);
                            ReportPricing::updateOrCreate(['license_type' => 'Enterprise', 'file_type' => 'Excel', 'report_id' => $report->id], ['price' => $request->enterprise_excel_price]);
                            ReportPricing::updateOrCreate(['license_type' => 'Enterprise', 'file_type' => 'PowerBI', 'report_id' => $report->id], ['price' => $request->enterprise_powerBI_price]);
                        }
                    }
                }
            }            
        }
        
        // update price for all reports
        if($request->report_selection=='all'){        
            // dispatch your queue job for price update
            dispatch(new UpdateAllReportPricing($requestedPricing));
        }

        $notification = ['message' => 'Report pricing updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.reportpricing')->with($notification);

    }

    // Report forecast settings
    public function reportForecastSettings()
    {
        $reports = Report::where('report_type','SD')->get();
        $title = 'Report Forecast Settings';
        return view('admin.report-forecast.index',compact('title','reports'));
    }

    public function updateReportForecastSettings(Request $request)
    {
        $request->validate([
            'report_selection' => 'required',
            'reports' => 'required_if:report_selection,selection',
            'historical_year' => 'required',
            'forecast_year' => 'required',
            'base_year' => 'required',
            'forecast_period' => 'required',
        ]);
        
        $historical_year = $request->historical_year;
        $forecast_year = $request->forecast_year;
        $base_year = $request->base_year;
        $forecast_period = $request->forecast_period;

        $requestedMetrics = ['historical_year'=>$historical_year,
        'forecast_year' => $forecast_year,
        'base_year' => $base_year,
        'forecast_period' => $forecast_period
        ];

        // update price for selected reports
        if($request->report_selection=='selection'){
            $reports = $request->reports;
            if($reports){                
                for($r=0;$r<count($reports);$r++){                    
                    if(!empty($reports[$r])){
                        $report = Report::find($reports[$r]);
                        if($report->id){
                            if($historical_year){
                                ReportMetrics::updateOrCreate(['meta_key' => 'Historical Year', 'report_id' => $report->id], ['meta_value' => $historical_year]);  
                            }                        
                            if($base_year){
                                /* $update_base_year = ReportMetrics::where('meta_key', 'Base year')
                                    ->where('report_id', $report->id)->first();
                                $update_base_year->meta_value = $base_year;
                                $update_base_year->save();  */       
                                $update_base_year = ReportMetrics::where('meta_key', 'like', '%Market size value in%')
                                    ->where('report_id', $report->id)
                                    ->orderBy('id','asc')->limit(1)->first();
                                $update_base_year->meta_key = 'Market size value in '.$base_year;
                                $update_base_year->save();                      
                            }                             
                            if($forecast_year){
                                $update_forecast_year = ReportMetrics::where('meta_key', 'like', '%Market size value in%')
                                    ->where('report_id', $report->id)
                                    ->orderBy('id','desc')->limit(1)->first();
                                $update_forecast_year->meta_key = 'Market size value in '.$forecast_year;
                                $update_forecast_year->save();
                            }                               
                            if($forecast_period){
                                ReportMetrics::updateOrCreate(['meta_key' => 'Forecast period', 'report_id' => $report->id], ['meta_value' => $forecast_period]);  
                            }

                            $market_insights = $report->market_insights;
                            if($market_insights){
                                if($historical_year){
                                    $pattern1 = "/ in \d{4}/";
                                    $replacement1 = " in ".$historical_year;

                                    $market_insights = preg_replace($pattern1, $replacement1, $market_insights);
                                }

                                if($forecast_year){
                                    $pattern2 = "/ by \d{4}/";
                                    $replacement2 = " by ".$forecast_year;

                                    $market_insights = preg_replace($pattern2, $replacement2, $market_insights);
                                }

                                if($base_year){
                                    $pattern3 = "/ in \d{4} to /";
                                    $replacement3 = " in ".$base_year." to ";

                                    $market_insights = preg_replace($pattern3, $replacement3, $market_insights);
                                }

                                if($forecast_period){
                                    //$pattern4 = "/ forecast period \(\d{4}-\d{4}\)/";
                                    $pattern4 = "/ forecast period \((.*)\)/";
                                    $replacement4 = " forecast period (".$forecast_period.")";

                                    $market_insights = preg_replace($pattern4, $replacement4, $market_insights);
                                }
                                
                                $report->market_insights = $market_insights;
                                $report->save();

                                // update answer of first FAQ                
                                $faq_answer1 = $this->getFirstPTag($market_insights);
                                $faq_question = "%What is the global market size of%";
                                $first_faq = ReportFaq::where('faq_question', 'like', $faq_question)
                                    ->where('report_id', $report->id)->first();
                                $first_faq->faq_answer = $faq_answer1;
                                $first_faq->save();
                            }                            
                        }
                    }
                }
            }            
        }
        
        // update price for all reports
        if($request->report_selection=='all'){
            // dispatch your queue job for price update
            dispatch(new UpdateAllReportMetrics($requestedMetrics));
        }

        $notification = ['message' => 'Report forecast settings updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.reportforecastsettings')->with($notification);

    }

    public function publishingDate(){
        $title = 'Publish Date';
        return view('admin.publish-date.publishDate',compact('title'));
    }

    public function publishDateimportFiles(Request $request){
        $checkFilePresent = $request->hasFile('publish_date_excel');
        if($checkFilePresent){
            $getFile = $request->file('publish_date_excel');
            $datasheetArray = Excel::toArray(new PublishImport, $getFile);

            foreach ($datasheetArray as $sheet => $rows) {
                for($r=1;$r<=count($rows);$r++){
                    if(isset($rows[$r][0]) && isset($rows[$r][1])){
                        $date = intval($rows[$r][1]);
                        $getDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date);        
                        Report::where('product_id',$rows[$r][0])->update(['publish_date'=>$getDate]);
                    }
                }
            }
            $notification = ['message' => 'Publish Date file is uploaded successfully!','alert-class' => 'success'];
        }
        return redirect()->back()->with($notification);
    }
}
