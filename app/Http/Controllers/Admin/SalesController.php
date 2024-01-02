<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Role as Roles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;
use DB;
use App\Models\SalesReportRequest;
use App\Models\Report;
use Auth;

class SalesController extends Controller
{
    public function index(Request $request){
        $title = 'Sales Report';
        return view('admin.sales-report-request.index', compact('title'));
    }

    public function store(Request $request){
        $report_id = $request->input('report_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $message = $request->input('message');
        $user = auth('admin')->user();
        
        if(($report_id != "") && ($start_date != "") && ($end_date != "") && ($message != ""))
        {
            $SalesReportRequest = new SalesReportRequest();
            $SalesReportRequest->report_id = $report_id;
            $SalesReportRequest->from_id = $user->id;
            $SalesReportRequest->to_id = 0;
            $SalesReportRequest->message = $message;
            $SalesReportRequest->start_date = $start_date;
            $SalesReportRequest->end_date = $end_date;
            $SalesReportRequest->save();
            
            $notification = ['message' => 'Research Request Created Successfully!','alert-class' => 'success'];
		    return redirect()->route('admin.sales-list.index')->with($notification);
        }

    }

    public function add(Request $request){
        $title = 'Add Sales Report';
        $report = Report::select('id', 'name')->get()->toArray();
        $email_restriction = new SalesReportRequest();
        return view('admin.sales-report-request.add', compact('title','email_restriction', 'report'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $email_restrictions = new SalesReportRequest();
        // if ($request->keyword) {
        //     $search = $request->keyword;
        //     $email_restrictions = $email_restrictions->where(function ($q) use ($search) {
        //         $q->Where('email_domain', 'LIKE', "%{$search}%");
        //         $q->orWhere('email_category', 'LIKE', "%{$search}%");
        //     });
        // }
        
        // if ($request->sort_by) {
            // $email_restrictions = $email_restrictions->orderBy($request->sort_by, $request->sort_order);
        // } else {
            $email_restrictions = $email_restrictions->orderBy('id', 'Desc');
        // }
        $email_restrictions_count = $email_restrictions->count();
        $email_restrictions = $email_restrictions->paginate($per_page_record); 
        return view('admin.sales-report-request.pagination', compact('email_restrictions', 'request', 'email_restrictions_count'));	
	}

    public function update(Request $request, $id){
    }
    public function edit(Request $request, $id){
    }
}