<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use App\Exports\ReportInquiryExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\ReportInquiry;
use App\Models\EmailRestriction;
use Carbon\Carbon;
use File;

class ReportInquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:report-inquiry-list|report-inquiry-view|report-inquiry-destroy|report-inquiry-export,admin', ['only' => ['index']]);
         $this->middleware('permission:report-inquiry-view,admin', ['only' => ['view']]);
         $this->middleware('permission:report-inquiry-delete,admin', ['only' => ['destroy']]);
         $this->middleware('permission:report-inquiry-export,admin', ['only' => ['export']]);
    }
    
    public function index()
    {
        // if(session()->has('report_inquiry.start_date') && session()->has('report_inquiry.end_date')){
        //     session()->forget('report_inquiry');
        // }
        $title = 'Report Inquiry';
		return view('admin.report-inquiry.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $report_inquiries = ReportInquiry::withAggregate('report','name');

        $report_inquiries->join('reports', 'reports.id', '=', 'report_inquiry.report_id');
        $report_inquiries->select('report_inquiry.*','reports.name as report_name');

        if ($request->keyword || $request->start_date || $request->end_date) {
            $search = $request->keyword;
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
                if($search){
                    $report_inquiries = $report_inquiries->where(function ($q) use ($search) {
                        $q->Where('reports.name', 'LIKE', "%{$search}%");
                        $q->orWhere('report_inquiry.created_at', 'LIKE', "%{$search}%");
                        $q->orWhere('report_inquiry.created_at', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
                    });
                }
            }else{
                if($search){
                    $report_inquiries = $report_inquiries->where(function ($q) use ($search) {
                        $q->Where('report_inquiry.name', 'LIKE', "%{$search}%");
                        $q->orWhere('email', 'LIKE', "%{$search}%");
                        $q->orWhere('phone', 'LIKE', "%{$search}%");
                        $q->orWhere('reports.name', 'LIKE', "%{$search}%");
                        $q->orWhere('report_inquiry.created_at', 'LIKE', "%{$search}%");
                        $q->orWhere('report_inquiry.created_at', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
                    });
                }
            }
            if($start_date && $end_date){
                $report_inquiries = $report_inquiries->where(function($q) use ($start_date,$end_date){
                    $q->whereDate('report_inquiry.created_at','>=',$start_date)
                    ->whereDate('report_inquiry.created_at','<=',$end_date);
                });
            }
            if($start_date){
                $report_inquiries = $report_inquiries->whereDate('report_inquiry.created_at','>=',$start_date);
            } 
            if($end_date) {
                $report_inquiries = $report_inquiries->whereDate('report_inquiry.created_at','<=',$end_date);
            }
        }
        if ($request->sort_by) {
            $report_inquiries = $report_inquiries->orderBy($request->sort_by, $request->sort_order);
        } else {
            $report_inquiries = $report_inquiries->orderBy('id', 'Desc');
        }
        $report_inquiries_count = $report_inquiries->count();
        $report_inquiries = $report_inquiries->paginate($per_page_record);

        $emailRestrictions = EmailRestriction::all()->pluck('email_category','email_domain')->toArray();

        return view('admin.report-inquiry.pagination', compact('report_inquiries', 'request','report_inquiries_count','emailRestrictions'));	
	}

    public function view($id)
    {
        $report_inquiry = ReportInquiry::find($id);
        $emailRestrictions = EmailRestriction::all()->pluck('email_category','email_domain')->toArray();
        if(!$report_inquiry) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'View Report Inquiry Details';
        return view('admin.report-inquiry.view',compact('title','report_inquiry','emailRestrictions'));
    }

    public function destroy(Request $request)
    {
        $report_inquiries = ReportInquiry::find($request->id);
		if($report_inquiries) {
            $report_inquiries->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }

    public function getReportInquiryData(Request $request)
    {
        $filter = [
            'search'=>$request->search,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date
        ];
        return Excel::download(new ReportInquiryExport($filter), 'reportinquiry.xlsx');
    }
}