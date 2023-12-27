<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ClientFeedback;
use App\Models\Sector;
use App\Models\pages;

class ServiceController extends Controller
{
    public function index()
    {
        $title = config('metadata.services.pageTitle');
        $meta_title = config('metadata.services.title');
        $meta_description = config('metadata.services.description');

        $page = pages::where('slug','services')->first();
        $h1 = ($page) ? $page->h1 : '';
        $meta_title = ($page) ? $page->meta_title : '';
        $meta_description = ($page) ? $page->meta_description : '';
        $page_title = ($page) ? $page->page_title : '';
        $meta_keyword = ($page) ? $page->meta_keyword : '';

        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        return view('front.services.index',compact('title','meta_title','meta_description','services','sectors','page','h1','page_title','meta_keyword'));
    }

    public function details($slug)
    {       
        $service = Service::where('slug',$slug)->where('is_active',1)->first();
        
        if(!$service) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = $service->page_title;
        $meta_title = $service->meta_title;
        $meta_description = $service->meta_description;
        $schema = $service->schema;
        $page_title = "";
        $meta_keyword = "";
 
        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        $clientfeedbacks = getClientFeedback();
        
        return view('front.services.details',compact('title','meta_title','meta_description','schema','service','services','sectors','clientfeedbacks','page_title','meta_keyword'));
    }
}