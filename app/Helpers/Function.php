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
    else if($page == 2 || $page == 3){
        $resp["frame"] = "dummy-content";
        $resp["data"] = null;
    }
    else if($page == 4){
        $resp["frame"] = "frame";
        $resp["data"] = ["name" => $Obj->name, "title" => "TABLE OF CONTENTS", "content" => [], "style" => "center"];
    }
    else if($page == 5){
        $resp["frame"] = "frame";
        $resp["data"] = ["name" => $Obj->name, "title" => "TABLE OF CONTENTS", "content" => config('constants.TABLE_OF_CONTENTS_1'), "style" => "full"];
    }
    else if($page == 6){
        $resp["frame"] = "frame";
        $resp["data"] = ["name" => $Obj->name, "title" => "TABLE OF CONTENTS", "content" => config('constants.TABLE_OF_CONTENTS_2'), "style" => "full"];
    }
    else if($page == 7){
        $resp["frame"] = "content";
        $resp["data"] = [];
    }
    else if($page == 8){
        $resp["frame"] = "frame";
        $resp["data"] = ["name" => 1, "title" => "INTRODUCTION", "content" => [], "style" => "center"];
    }
    else if($page == 9){
        $resp["frame"] = "content";
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
    return $resp;
}