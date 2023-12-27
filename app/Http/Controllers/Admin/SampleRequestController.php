<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use App\Exports\SampleRequestExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\ReportSampleRequest;
use App\Models\EmailRestriction;
use File;

class SampleRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:free-sample-request-list|free-sample-request-view|free-sample-request-destroy|free-sample-request-export,admin', ['only' => ['index']]);
         $this->middleware('permission:free-sample-request-view,admin', ['only' => ['view']]);
         $this->middleware('permission:free-sample-request-delete,admin', ['only' => ['destroy']]);
         $this->middleware('permission:free-sample-request-export,admin', ['only' => ['export']]);
    }
    
    public function index()
    {
        $title = 'Free Sample Request';
		return view('admin.free-sample-request.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $samplerequests = ReportSampleRequest::withAggregate('report','name');

        $samplerequests->join('reports', 'reports.id', '=', 'report_sample_request.report_id');
        $samplerequests->select('report_sample_request.*','reports.name as report_name');

        if ($request->keyword) {
            $search = $request->keyword;
            if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
                $samplerequests = $samplerequests->where(function ($q) use ($search) {
                    $q->Where('reports.name', 'LIKE', "%{$search}%");
                    $q->orWhere('report_sample_request.created_at', 'LIKE', "%{$search}%");
                    $q->orWhere('report_sample_request.created_at', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
                });
            }else{
                $samplerequests = $samplerequests->where(function ($q) use ($search) {
                    $q->Where('report_sample_request.name', 'LIKE', "%{$search}%");
                    $q->orWhere('email', 'LIKE', "%{$search}%");
                    // $q->orWhere('category', 'LIKE', "%{$search}%");
                    $q->orWhere('phone', 'LIKE', "%{$search}%");
                    $q->orWhere('reports.name', 'LIKE', "%{$search}%");
                    $q->orWhere('report_sample_request.created_at', 'LIKE', "%{$search}%");
                    $q->orWhere('report_sample_request.created_at', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
                });
            }
        }
        if ($request->sort_by) {
            $samplerequests = $samplerequests->orderBy($request->sort_by, $request->sort_order);
        } else {
            $samplerequests = $samplerequests->orderBy('id', 'Desc');
        }
        $samplerequests_count = $samplerequests->count();
        $samplerequests = $samplerequests->paginate($per_page_record);   

        $emailRestrictions = EmailRestriction::all()->pluck('email_category', 'email_domain')->toArray();
        
        return view('admin.free-sample-request.pagination', compact('samplerequests', 'request','samplerequests_count','emailRestrictions'));	
	}

    public function view($id)
    {
        $samplerequest = ReportSampleRequest::find($id);
        if(!$samplerequest) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'View Free Sample Request Details';
        $emailRestrictions = EmailRestriction::all()->pluck('email_category', 'email_domain')->toArray();

        return view('admin.free-sample-request.view',compact('title','samplerequest','emailRestrictions'));
    }

    public function destroy(Request $request)
    {
        $samplerequest = ReportSampleRequest::find($request->id);
		if($samplerequest) {
            $samplerequest->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }

    public function getSampleRequestData()
    {
        return Excel::download(new SampleRequestExport, 'samplerequest.xlsx');
    }
}
