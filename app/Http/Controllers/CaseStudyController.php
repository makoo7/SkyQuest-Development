<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\CaseStudy;
use App\Models\Service;
use App\Models\ClientFeedback;
use App\Models\Sector;
use App\Models\pages;

class CaseStudyController extends Controller
{
    public function index()
    {
        $title = config('metadata.case-studies.pageTitle');
        $meta_title = config('metadata.case-studies.title');
        $meta_description = config('metadata.case-studies.description');
        
        $page = pages::where('slug','case-studies')->first();
        $meta_title = ($page) ? $page->meta_title : '';
        $meta_description = ($page) ? $page->meta_description : '';
        $page_title = ($page) ? $page->page_title : '';
        $meta_keyword = ($page) ? $page->meta_keyword : '';

        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        return view('front.case-studies.index',compact('title','meta_title','meta_description','services','sectors','page','page_title','meta_keyword'));
    }

    public function ajax(Request $request)
	{
        $title = config('metadata.case-studies.pageTitle');
        $meta_title = config('metadata.case-studies.title');
        $meta_description = config('metadata.case-studies.description');

        $page = pages::where('slug','case-studies')->first();
        $h1 = ($page) ? $page->h1 : '';

        $per_page_record = config('constants.PER_PAGE_RECORD');
        $casestudiesData = CaseStudy::where('is_active',1)->with('casestudy_bookmark')->orderBy('id','desc')->paginate($per_page_record);
        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        return view('front.case-studies.pagination',compact('title','meta_title','meta_description','casestudiesData','services','sectors','page','h1'));
	}

    public function details($slug)
    {
        $casestudy = CaseStudy::where('slug',$slug)->where('is_active',1)->first();

        if(!$casestudy) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }

        $title = (isset($casestudy->name)) ? $casestudy->name : config('metadata.default.pageTitle');
        $meta_title = (isset($casestudy->meta_title)) ? $casestudy->meta_title : config('metadata.default.title');
        $meta_description = (isset($casestudy->meta_description)) ? $casestudy->meta_description : config('metadata.default.description');
        $page_title = "";
        $meta_keyword = "";
        // Share button
        $shareButtons = '';

        $sociallinks = \Share::page(url('case-studies/'.$casestudy->slug), $meta_title)
            ->facebook()
            ->twitter()
            ->linkedin($meta_title)
            ->getRawLinks();

        $imageURL = str_replace('/upload/','/upload/c_fill,w_300,h_300/',$casestudy->image_url);

        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        $clientfeedbacks = getClientFeedback();
        return view('front.case-studies.details',compact('title','meta_title','meta_description','casestudy','services','sectors','clientfeedbacks','shareButtons','sociallinks','imageURL','page_title','meta_keyword'));
    }
}
