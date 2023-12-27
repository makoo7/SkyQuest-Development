<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CaseStudy;
use App\Models\Insight;
use App\Models\Service;
use App\Models\Report;
use App\Models\Sector;
use App\Models\IndustryGroup;
use App\Models\Industry;
use App\Models\SubIndustry;

class SitemapXmlController extends Controller
{
    public function index()
    {
        $case_studies = CaseStudy::where('is_active','1')->get();
        $insights = Insight::where('is_active','1')->get();
        $services = Service::where('is_active','1')->get();
        $reports = Report::where('is_active','1')->get();
        $sectorData = Sector::where('is_active','1')->get();
        $industry_group_data = IndustryGroup::where('is_active','1')->get();
        $industry_data = Industry::where('is_active','1')->get();
        $sub_industry_data = SubIndustry::where('is_active','1')->get();

        return response()->view('front.home.sitemap',compact('case_studies','insights','services','reports','sectorData','industry_group_data','industry_data','sub_industry_data'))->header('Content-Type', 'text/xml');
    }
}