<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Homepage;
use App\Models\HomepageModule;
use App\Models\ClientFeedback;
use App\Models\CaseStudy;
use App\Models\Insight;
use App\Models\Award;

class HomepageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:homepage,admin', ['only' => ['index','update']]);
    }

    public function index()
    {
        $homepage = Homepage::first();
        $title = 'Home Page';
        $clientfeedbacks = ClientFeedback::where('is_active',1)->get();
        $casestudies = CaseStudy::where('is_active',1)->get();
        $insights = Insight::where('is_active',1)->get();
        $awards = Award::where('is_active',1)->get();

        $sel_clientfeedbacks = HomepageModule::where('item_type', 'Feedback')->pluck('item_id')->toArray();
        $sel_casestudies = HomepageModule::where('item_type', 'Case Studies')->pluck('item_id')->toArray();
        $sel_insights = HomepageModule::where('item_type', 'Insignts')->pluck('item_id')->toArray();
        $sel_awards = HomepageModule::where('item_type', 'Awads')->pluck('item_id')->toArray();
        
		return view('admin.homepage.index', compact('title','homepage','sel_clientfeedbacks','sel_casestudies','sel_insights','sel_awards','clientfeedbacks','casestudies','insights','awards'));
    }

    public function update(Request $request)
    {
        // Feedback
        if($request->has('clientfeedback_ids')) {
            HomepageModule::where('item_type', 'Feedback')->forceDelete();
            if(is_array($request->clientfeedback_ids) && count($request->clientfeedback_ids) > 0) {
                foreach($request->clientfeedback_ids as $clientfeedback_id) {
                    HomepageModule::create([
                        'item_id' => $clientfeedback_id,
                        'item_type' => 'Feedback',
                    ]);
                }
            }
        }

        // Case Studies
        if($request->has('casestudy_ids')) {
            HomepageModule::where('item_type', 'Case Studies')->forceDelete();
            if(is_array($request->casestudy_ids) && count($request->casestudy_ids) > 0) {
                foreach($request->casestudy_ids as $casestudy_id) {
                    HomepageModule::create([
                        'item_id' => $casestudy_id,
                        'item_type' => 'Case Studies',
                    ]);
                }
            }
        }

        // Insignts
        if($request->has('insight_ids')) {
            HomepageModule::where('item_type', 'Insignts')->forceDelete();
            if(is_array($request->insight_ids) && count($request->insight_ids) > 0) {
                foreach($request->insight_ids as $insight_id) {
                    HomepageModule::create([
                        'item_id' => $insight_id,
                        'item_type' => 'Insignts',
                    ]);
                }
            }
        }

        // Awads
        if($request->has('award_ids')) {
            HomepageModule::where('item_type', 'Awads')->forceDelete();
            if(is_array($request->award_ids) && count($request->award_ids) > 0) {
                foreach($request->award_ids as $award_id) {
                    HomepageModule::create([
                        'item_id' => $award_id,
                        'item_type' => 'Awads',
                    ]);
                }
            }
        }

        $data = array();
        $data['is_case_study'] = $request->has('is_case_study') ? $request->is_case_study : 0;
        $data['is_feedback'] = $request->has('is_feedback') ? $request->is_feedback : 0;
        $data['is_help'] = $request->has('is_help') ? $request->is_help : 0;
        $data['is_insights'] = $request->has('is_insights') ? $request->is_insights : 0;
        $data['is_process'] = $request->has('is_process') ? $request->is_process : 0;
        $data['is_products'] = $request->has('is_products') ? $request->is_products : 0;
        $data['is_awards'] = $request->has('is_awards') ? $request->is_awards : 0;

        Homepage::updateOrInsert(['id' => $request->id], $data);
        $notification = ['message' => 'Home page settings updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.homepage.index')->with($notification);
    }
    
}