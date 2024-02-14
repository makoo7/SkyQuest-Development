<?php
use Illuminate\Support\Facades\Auth;
use App\Models\Homepage;
use App\Models\HomepageModule;
use App\Models\Settings;
use App\Models\ClientFeedback;
use App\Models\CaseStudy;
use App\Models\Insight;
use App\Models\Award;
use App\Models\Service;
use App\Models\Sectors;
use App\Models\Admin;
use App\Models\User;
use App\Models\Sector;
use App\Models\IndustryGroup;
use App\Models\Industry;
use App\Models\Report;
use App\Models\SubIndustry;
use App\Models\ReportPricing;
use App\Models\Gallery;
use Cartalyst\Stripe\Stripe;

function getAdminEmails()
{
    $to_emails = array();
    if(config('constants.TO_EMAILS') != ''){
       $to_emails = explode(",",config('constants.TO_EMAILS'));
    }
    return $to_emails;
}

function getLeadEmails()
{
    $lead_emails = array();
    if(config('constants.LEADS_EMAILS') != ''){
       $lead_emails = explode(",",config('constants.LEADS_EMAILS'));
    }
    return $lead_emails;
}

function getCCEmails()
{
    $cc_emails = array();
    if(config('constants.CC_EMAILS') != ''){
       $cc_emails = explode(",",config('constants.CC_EMAILS'));
    }
    return $cc_emails;
}

function getHREmails()
{
    $hr_emails = array();
    if(config('constants.HR_EMAILS') != ''){
       $hr_emails = explode(",",config('constants.HR_EMAILS'));
    }
    return $hr_emails;
}

function getAccountEmails()
{
    $account_emails = array();
    if(config('constants.ACCOUNT_EMAILS') != ''){
       $account_emails = explode(",",config('constants.ACCOUNT_EMAILS'));
    }
    return $account_emails;
}

function getBuyNowEmails()
{
    $buynow_emails = array();
    if(config('constants.BUYNOW_EMAILS') != ''){
       $buynow_emails = explode(",",config('constants.BUYNOW_EMAILS'));
    }
    return $buynow_emails;
}

function getBuyNowCCEmails()
{
    $buynow_cc_emails = array();
    if(config('constants.BUYNOW_CC_EMAILS') != ''){
       $buynow_cc_emails = explode(",",config('constants.BUYNOW_CC_EMAILS'));
    }
    return $buynow_cc_emails;
}

function getBuyNowUserCCEmails()
{
    $buynow_user_cc_emails = array();
    if(config('constants.BUYNOW_USER_CC_EMAILS') != ''){
       $buynow_user_cc_emails = explode(",",config('constants.BUYNOW_USER_CC_EMAILS'));
    }
    return $buynow_user_cc_emails;
}

function getBuyNowReplyToEmails()
{
    $buynow_replyto_emails = array();
    if(config('constants.BUYNOW_REPLYTO_EMAILS') != ''){
       $buynow_replyto_emails = explode(",",config('constants.BUYNOW_REPLYTO_EMAILS'));
    }
    return $buynow_replyto_emails;
}

function getExportReportEmail()
{
    $export_emails = array();
    if(config('constants.REPORTEXPORT_EMAILS') != ''){
       $export_emails = explode(",",config('constants.REPORTEXPORT_EMAILS'));
    }
    return $export_emails;
}

function getSuperAdminEmail()
{
    $superadmin_emails = array();
    if(config('constants.SUPERADMIN_EMAILS') != ''){
       $superadmin_emails = explode(",",config('constants.SUPERADMIN_EMAILS'));
    }
    return $superadmin_emails;
}

function checkUserExist($email)
{
    return User::where('email',$email)->where('is_active',1)->first();
}

function checkReportSlug($slug,$sku='',$reportId='')
{
    if($sku!=''){
        return Report::where('slug',$slug)->where('product_id','<>',$sku)->where('is_active',1)->whereNull('deleted_at')->get();
    }else if($reportId!=''){
        return Report::where('slug',$slug)->where('id','<>',$reportId)->where('is_active',1)->whereNull('deleted_at')->get();
    }else {
        return Report::where('slug',$slug)->where('is_active',1)->whereNull('deleted_at')->get();
    }
}

function checkReportSKU($sku,$reportId='')
{
    if($reportId!=''){
        return Report::where('product_id',$sku)->where('id','<>',$reportId)->where('is_active',1)->whereNull('deleted_at')->get();
    }else {
        return Report::where('product_id',$sku)->where('is_active',1)->whereNull('deleted_at')->get();
    }
}

function getStripeKey()
{
    if(config('constants.STRIPE_MODE') == "live"){
        return config('constants.STRIPE_SK_LIVE');
    } else {
        return config('constants.STRIPE_SK_TEST');
    }
}

function generatePassword($number = 8)
{
    $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
    $pass = substr(str_shuffle($data), 0, $number);
    return $pass;
}

function getSubMenuOnReport($sector_id)
{
    return IndustryGroup::where('sector_id',$sector_id)->where('is_active',1)->get();
}

function getMegaMenuReports()
{
    $html = "";
    $sectors = Sector::where('is_active',1)->get();
    if(!$sectors->isEmpty()){
        $html .= "<ul class='menutxt-sub report-menu'>";
        foreach ($sectors as $sector) {
            $html .= "<li><span><a href='".url('industries/'.$sector->slug)."'>".$sector->title."</a></span>";
            $industryGroups = IndustryGroup::where('is_active',1)->where('sector_id',$sector->id)->get();
            if(!$industryGroups->isEmpty()){
                $html .= "<ul class='list-bar'>";
                foreach ($industryGroups as $industryGroup) {
                    $html .= "<li class='list'><a href='".url('industries/'.$industryGroup->slug)."' class='heading'>".$industryGroup->title."</a>";
                    $industries = Industry::where('is_active',1)->where('industry_group_id',$industryGroup->id)->get();
                    if(!$industries->isEmpty()){
                        $html .= "<ul class='list-inner'>";
                        foreach ($industries as $industry) {
                            $html .= "<li class='lists'><a href='".url('industries/'.$industry->slug)."' class='green-heading'>".$industry->title."</a>";
                            $subindustries = SubIndustry::where('is_active',1)->where('industry_id',$industry->id)->get();
                            if(!$subindustries->isEmpty()){
                                $html .= "<ul class='listing'>";
                                foreach ($subindustries as $sub_industry) {
                                    $html .= "<li><a href='".url('industries/'.$sub_industry->slug)."'>".$sub_industry->title."</a></li>";
                                }
                                $html .= "</ul>";
                            }
                        }
                        $html .= "</ul>";
                    }
                }
                $html .= "</ul>";
            }
            $html .= "</li>";
        }
        $html .= "</ul>";
    }
    return $html;
}

/* Convert UTC to IST time */
function convertUtcToIst($dateTime,$return_date_format = "")
{
    if(!$return_date_format) {
        $return_date_format = "Y-m-d H:i:s";
    }
    $date = new DateTime($dateTime, new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone("Asia/Calcutta"));
    $ret = date_format($date, $return_date_format);
    return $ret;
}

// fetch client feedbacks from home page settings
function getClientFeedback(){
    $home_clientfeedbacks = HomepageModule::where('item_type', 'Feedback')->pluck('item_id')->toArray();
    $clientfeedbacks = ClientFeedback::whereIn('id', $home_clientfeedbacks)->get();
    return $clientfeedbacks;
}

function domain_exists($email, $record = 'MX'){
    list($user, $domain) = explode('@', $email);
    return checkdnsrr($domain, $record);
}

// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

// Function to check image name exists or not
function checkGalleryImageNameExists($image){
    $gallery = Gallery::selectRaw("LOWER(SUBSTRING_INDEX(image, '/', -1)) as image_name")->pluck('image_name')->toArray();
    
    $image = str_replace("jpeg", "jpg", strtolower($image));

    if(in_array($image,$gallery)){
        return true;
    }else{
        return false;
    }
}

// To fetch from report data
function getFirstPTag($string)
{
    $start = strpos($string, '<p>');
    $end = strpos($string, '</p>', $start);
    $sub_str = substr($string, $start, $end-$start+4);
    return $sub_str;
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

function getPageResult($Obj, $page)
{
    $frame = "intro";
    $resp = [];
    if($page == 1){
        $resp["frame"] = $frame;
        $resp["data"] = ["name" => $Obj->name];
    }
    else if($page == 2){
        $resp["frame"] = "dummy-content";
        $resp["data"] = ["name" => $Obj->name, "title" => "", "content" => [], "style" => "center"];
    }
    else if($page == 3){
        $resp["frame"] = "dummy-content";
        $resp["data"] = ["name" => $Obj->name, "title" => "FEW OF OUR CLIENTS", "content" => [], "style" => "center"];
    }
    else if($page == 4){
        $resp["frame"] = "page-4";
        $resp["data"] = ["name" => $Obj->name, "title" => "", "content" => [], "style" => "center"];
    }
    else if($page == 5){
        $resp["frame"] = "page-5";
        $resp["data"] = ["name" => $Obj->name, "title" => "TABLE OF CONTENTS", "content" => config('constants.TABLE_OF_CONTENTS_1'), "style" => "full"];
    }
    else if($page == 6){
        $resp["frame"] = "page-6";
        $resp["data"] = ["name" => $Obj->name, "title" => "TABLE OF CONTENTS", "content" => config('constants.TABLE_OF_CONTENTS_2'), "style" => "full"];
    }
    else if($page == 7){
        $resp["frame"] = "content";
        $resp["data"] = ["style" => ""];
    }
    else if($page == 8){
        $resp["frame"] = "page-8";
        $resp["data"] = ["name" => "01", "title" => "INTRODUCTION", "content" => [], "style" => "center"];
    }
    else if($page == 9){
        $resp["frame"] = "page-9";
        $resp["data"] = ["title" => "1.1 OBJECTIVES OF THE STUDY", "content" => config('constants.OBJECTIVES_OF_THE_STUDY'), "style" => "full", "dData" => $Obj->name];
    }
    else if($page == 10){
        $resp["frame"] = "flowchart-10";
        $resp["data"] = ["title" => "1.2 GEOGRAPHIC SCOPE", "style" => "center", "dData" => $Obj->name];
    }
    else if($page == 11){
        $resp["frame"] = "flowchart-10";
        $resp["data"] = ["title" => "1.3 MARKET SEGMENTAL SCOPE", "style" => "center", "dData" => $Obj->name];
    }
    else if($page == 12){
        $resp["frame"] = "flowchart-10";
        $resp["data"] = ["title" => "1.3 MARKET SEGMENTAL SCOPE", "style" => "center", "dData" => $Obj->name];
    }
    else if($page == 13){
        $resp["frame"] = "page-13";
        $resp["data"] = ["title" => "1.4 KEY DATA POINTS COVERED IN THE REPORT", "content" => config('constants.KEY_DATA_POINTS_COVERED_IN_REPORT'), "style" => "full", "dData" => $Obj->name];
    }
    else if($page == 14){
        $resp["frame"] = "page-14";
        $resp["data"] = ["title" => "2. RESEARCH METHODOLOGY", "content" => [], "style" => "center"];
    }
    // else if($page == 15){
    //     $resp["frame"] = "page-15";
    //     $resp["data"] = ["name" => "2. RESEARCH METHODOLOGY", "content" => config('constants.KEY_DATA_POINTS_COVERED_IN_REPORT'), "style" => "full", "dData" => $Obj->name];
    // }
    else if($page == 15){
        $resp["frame"] = "page-15";
        $resp["data"] = ["title" => "2. RESEARCH METHODOLOGY", "content" => [], "style" => "center"];
    }
    else if($page == 16){
        $resp["frame"] = "page-16";
        $resp["data"] = ["title" => "2.1 SECONDARY RESEARCH", "content" => config('constants.SECONDARY_RESEARCH'), "style" => "full", "dData" => $Obj->name];
    }
    else if($page == 17){
        $resp["frame"] = "page-17";
        $resp["data"] = ["title" => "2.2 PRIMARY RESEARCH", "content" => config('constants.PRIMARY_RESEARCH'), "style" => "full", "dData" => $Obj->name];
    }
    else if($page == 18){
        $resp["frame"] = "page-18";
        $resp["data"] = ["style" => "", "title" => "2.3 PRIMARY RESEARCH APPROACH & KEY RESPONDENTS"];
    }
    else if($page == 19){
        $resp["frame"] = "page-19";
        $resp["data"] = ["style" => "", "title" => "2.4 DATA TRIANGULATION & INSIGHT GENERATION"];
    }
    else if($page == 20){
        $resp["frame"] = "page-20";
        $resp["data"] = ["title" => "2.4.1 MARKET SIZE ESTIMATION", "name" => $Obj->name];
    }
    else if($page == 21){
        $resp["frame"] = "page-21";
        $resp["data"] = ["title" => "2.5 STUDY ASSUMPTIONS & MARKET DEFINITION", "name" => $Obj->name];
    }
    else if($page == 22){
        $resp["frame"] = "page-22";
        $resp["data"] = ["title" => "2.6 QUALITY ASSURANCE PROCESS", "name" => $Obj->name];
    }
    else if($page == 23){
        $resp["frame"] = "page-23";
        $resp["data"] = ["title" => "", "name" => $Obj->name];
    }
    else if($page == 24){
        $resp["frame"] = "page-24";
        $resp["data"] = ["title" => "3 EXECUTIVE SUMMARY (1/4)", "name" => $Obj->name];
    }
    else if($page == 25){
        $resp["frame"] = "page-25";
        $resp["data"] = ["title" => "3 EXECUTIVE SUMMARY (2/4)", "name" => $Obj->name];
    }
    else if($page == 26){
        $resp["frame"] = "page-26";
        $resp["data"] = ["title" => "3 EXECUTIVE SUMMARY (3/4)", "name" => $Obj->name];
    }
    else if($page == 27){
        $resp["frame"] = "page-27";
        $resp["data"] = ["title" => "3 EXECUTIVE SUMMARY (4/4)", "name" => $Obj->name];
    }
    else if($page == 28){
        $resp["frame"] = "page-28";
        $resp["data"] = ["title" => "", "name" => $Obj->name];
    }
    else if($page == 29){
        $resp["frame"] = "page-29";
        $resp["data"] = ["title" => "4.1 RECENT INDUSTRY DEVELOPMENTS", "name" => $Obj->name];
    }
    else if($page == 30){
        $resp["frame"] = "page-30";
        $resp["data"] = ["title" => "4.2 INDUSTRY ANALYSIS - PORTERS 5 FORCE ANALYSIS", "name" => $Obj->name];
    }
    else if($page == 31){
        $resp["frame"] = "page-31";
        $resp["data"] = ["title" => "MARKET DYNAMICS", "name" => $Obj->name];
    }
    else if($page == 32){
        $resp["frame"] = "page-32";
        $resp["data"] = ["title" => "MARKET DRIVERS & OPPORTUNITIES (1/3)", "name" => $Obj->name];
    }
    else if($page == 33){
        $resp["frame"] = "page-33";
        $resp["data"] = ["title" => "MARKET CHALLENGES (1/2)", "name" => $Obj->name];
    }
    else if($page == 34){
        $resp["frame"] = "page-34";
        $resp["data"] = ["title" => "", "name" => $Obj->name];
    }
    else if($page == 35){
        $resp["frame"] = "page-35";
        $resp["data"] = ["title" => "DEGREE OF COMPETITION (1/2)", "name" => $Obj->name];
    }
    else if($page == 36){
        $resp["frame"] = "page-36";
        $resp["data"] = ["title" => "MARKET ECOSYSTEM MAP, 2023", "name" => $Obj->name];
    }
    else if($page == 37){
        $resp["frame"] = "page-37";
        $resp["data"] = ["title" => "KEY SUCCESS FACTORS FOR THE MARKET", "name" => $Obj->name];
    }
    else if($page == 38){
        $resp["frame"] = "page-38";
        $resp["data"] = ["title" => "DEGREE OF COMPETITION", "name" => $Obj->name];
    }
    else if($page == 39){
        $resp["frame"] = "page-39";
        $resp["data"] = ["title" => "DEGREE OF COMPETITION", "name" => $Obj->name];
    }
    else if($page == 40){
        $resp["frame"] = "page-40";
        $resp["data"] = ["title" => "DEGREE OF COMPETITION", "name" => $Obj->name];
    }
    else if($page == 41){
        $resp["frame"] = "page-41";
        $resp["data"] = ["title" => "PRICING ANALYSIS", "name" => $Obj->name];
    }
    else if($page == 42){
        $resp["frame"] = "page-42";
        $resp["data"] = ["title" => "", "name" => $Obj->name];
    }
    else if($page == 43){
        $resp["frame"] = "page-43";
        $resp["data"] = ["title" => "GLOBAL MARKET SIZE", "name" => $Obj->name];
    }
    else if($page == 44){
        $resp["frame"] = "page-44";
        $resp["data"] = ["title" => "", "name" => $Obj->name];
    }
    else if($page == 45){
        $resp["frame"] = "page-45";
        $resp["data"] = ["title" => "GLOBAL MARKET SIZE BY REGION OVERVIEW", "name" => $Obj->name];
    }
    else if($page == 46){
        $resp["frame"] = "page-46";
        $resp["data"] = ["title" => "GLOBAL MARKET SIZE BY REGION", "name" => $Obj->name];
    }
    else if($page == 47){
        $resp["frame"] = "page-47";
        $resp["data"] = ["title" => "GLOBAL MARKET SIZE BY SEGMENT", "name" => $Obj->name];
    }
    else if($page == 48){
        $resp["frame"] = "page-48";
        $resp["data"] = ["title" => "GLOBAL MARKET SIZE BY REGION KEY TAKEAWAYS", "name" => $Obj->name];
    }
    else if($page == 49){
        $resp["frame"] = "page-49";
        $resp["data"] = ["title" => "", "name" => $Obj->name];
    }
    else if($page == 50){
        $resp["frame"] = "page-50";
        $resp["data"] = ["title" => "MARKET SIZE BY GEOGRAPHY", "name" => $Obj->name];
    }
    else if($page == 51){
        $resp["frame"] = "page-51";
        $resp["data"] = ["title" => "GLOBAL MARKET SIZE BY REGION KEY TAKEAWAYS", "name" => $Obj->name];
    }
    else if($page == 52){
        $resp["frame"] = "page-52";
        $resp["data"] = ["title" => "", "name" => $Obj->name];
    }
    else if($page == 53){
        $resp["frame"] = "page-53";
        $resp["data"] = ["title" => "NORTH AMERICA MARKET SIZE BY COUNTRY", "name" => $Obj->name];
    }
    else if($page == 54){
        $resp["frame"] = "page-54";
        $resp["data"] = ["title" => "NORTH AMERICA MARKET SIZE BY COUNTRY", "name" => $Obj->name];
    }
    else if($page == 55){
        $resp["frame"] = "page-55";
        $resp["data"] = ["title" => "", "name" => $Obj->name];
    }
    else if($page == 56){
        $resp["frame"] = "page-56";
        $resp["data"] = ["title" => "MARKET SHARE ANALYSIS BY COMPANY", "name" => $Obj->name];
    }
    else if($page == 57){
        $resp["frame"] = "page-57";
        $resp["data"] = ["title" => "MARKET PLAYER MAPPING", "name" => $Obj->name];
    }
    else if($page == 58){
        $resp["frame"] = "page-58";
        $resp["data"] = ["title" => "MARKET POSITIONING OF KEY PLAYERS IN THE MARKET, 2023", "name" => $Obj->name];
    }
    else if($page == 59){
        $resp["frame"] = "page-59";
        $resp["data"] = ["title" => "INTENSITY OF COMPETITIVE RIVALRY", "name" => $Obj->name];
    }
    else if($page == 60){
        $resp["frame"] = "page-60";
        $resp["data"] = ["title" => "", "name" => $Obj->name];
    }
    else if($page == 61){
        $resp["frame"] = "page-61";
        $resp["data"] = ["title" => "FULL COMPANY NAME - COMPANY OVERVIEW", "name" => $Obj->name];
    }
    return $resp;
}