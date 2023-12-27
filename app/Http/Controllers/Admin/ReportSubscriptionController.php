<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use App\Exports\ReportSubscribeNowExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\ReportSubscribeNow;
use App\Models\EmailRestriction;
use File;

class ReportSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:report-subscription-list|report-subscription-view|report-subscription-destroy|report-subscription-export,admin', ['only' => ['index']]);
         $this->middleware('permission:report-subscription-view,admin', ['only' => ['view']]);
         $this->middleware('permission:report-subscription-delete,admin', ['only' => ['destroy']]);
         $this->middleware('permission:report-subscription-export,admin', ['only' => ['export']]);
    }
    
    public function index()
    {
        $title = 'Report Subscription';
		return view('admin.report-subscription.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $report_subscriptions = ReportSubscribeNow::withAggregate('report','name');

        $report_subscriptions->join('reports', 'reports.id', '=', 'report_subscribe_now.report_id');
        $report_subscriptions->select('report_subscribe_now.*','reports.name as report_name');

        if ($request->keyword) {
            $search = $request->keyword;
            if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
                $report_subscriptions = $report_subscriptions->where(function ($q) use ($search) {
                    $q->Where('reports.name', 'LIKE', "%{$search}%");
                    $q->orWhere('report_subscribe_now.created_at', 'LIKE', "%{$search}%");
                    $q->orWhere('report_subscribe_now.created_at', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
                });
            }else{
                $report_subscriptions = $report_subscriptions->where(function ($q) use ($search) {
                    $q->Where('report_subscribe_now.name', 'LIKE', "%{$search}%");
                    $q->orWhere('email', 'LIKE', "%{$search}%");
                    $q->orWhere('phone', 'LIKE', "%{$search}%");
                    $q->orWhere('reports.name', 'LIKE', "%{$search}%");
                    $q->orWhere('report_subscribe_now.created_at', 'LIKE', "%{$search}%");
                    $q->orWhere('report_subscribe_now.created_at', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
                });
            }
        }
        if ($request->sort_by) {
            $report_subscriptions = $report_subscriptions->orderBy($request->sort_by, $request->sort_order);
        } else {
            $report_subscriptions = $report_subscriptions->orderBy('id', 'Desc');
        }
        $report_subscriptions_count = $report_subscriptions->count();
        $report_subscriptions = $report_subscriptions->paginate($per_page_record);  
        
        $emailRestrictions = EmailRestriction::all()->pluck('email_category','email_domain')->toArray();

        return view('admin.report-subscription.pagination', compact('report_subscriptions', 'request','report_subscriptions_count','emailRestrictions'));	
	}

    public function view($id)
    {
        $report_subscription = ReportSubscribeNow::find($id);
        $emailRestrictions = EmailRestriction::all()->pluck('email_category','email_domain')->toArray();
        if(!$report_subscription) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'View Report Subscription Details';
        return view('admin.report-subscription.view',compact('title','report_subscription','emailRestrictions'));
    }

    public function destroy(Request $request)
    {
        $report_subscription = ReportSubscribeNow::find($request->id);
		if($report_subscription) {
            $report_subscription->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }

    public function getReportInquiryData()
    {
        return Excel::download(new ReportSubscribeNowExport, 'reportsubscription.xlsx');
    }
}
