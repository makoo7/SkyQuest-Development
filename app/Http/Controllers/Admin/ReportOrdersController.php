<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use App\Exports\ReportOrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\ReportOrders;
use App\Models\EmailRestriction;
use File;

class ReportOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:report-order-list|report-order-view|report-order-destroy|report-order-export,admin', ['only' => ['index']]);
         $this->middleware('permission:report-order-view,admin', ['only' => ['view']]);
         $this->middleware('permission:report-order-delete,admin', ['only' => ['destroy']]);
         $this->middleware('permission:report-order-export,admin', ['only' => ['export']]);
    }
    
    public function index()
    {
        $title = 'Report Order';
		return view('admin.report-order.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $report_orders = ReportOrders::withAggregate('report','name');

        $report_orders->join('reports', 'reports.id', '=', 'report_orders.report_id');
        $report_orders->select('report_orders.*','reports.name as report_name');

        if ($request->keyword) {
            $search = $request->keyword;
            if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
                $report_orders = $report_orders->where(function ($q) use ($search) {
                    $q->Where('reports.name', 'LIKE', "%{$search}%");
                    //$q->orWhere('report_orders.payment_status', 'LIKE', "%{$search}%");
                    $q->orWhere('report_orders.created_at', 'LIKE', "%{$search}%");
                    $q->orWhere('report_orders.created_at', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
                });
            }else{
                $report_orders = $report_orders->where(function ($q) use ($search) {
                    $q->Where('report_orders.name', 'LIKE', "%{$search}%");
                    $q->orWhere('email', 'LIKE', "%{$search}%");
                    $q->orWhere('phone', 'LIKE', "%{$search}%");
                    $q->orWhere('reports.name', 'LIKE', "%{$search}%");
                    //$q->orWhere('report_orders.payment_status', 'LIKE', "%{$search}%");
                    $q->orWhere('report_orders.created_at', 'LIKE', "%{$search}%");
                    $q->orWhere('report_orders.created_at', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
                });
            }
        }

        if ($request->payment_status) {
            $payment_status = $request->payment_status;
            $report_orders = $report_orders->where(function ($q) use ($payment_status) {
                $q->orWhere('report_orders.payment_status', 'LIKE', "%{$payment_status}%");
            });
        }

        if ($request->sort_by) {
            $report_orders = $report_orders->orderBy($request->sort_by, $request->sort_order);
        } else {
            $report_orders = $report_orders->orderBy('id', 'Desc');
        }
        $report_orders_count = $report_orders->count();
        $report_orders = $report_orders->paginate($per_page_record);  
        
        $emailRestrictions = EmailRestriction::all()->pluck('email_category','email_domain')->toArray();

        return view('admin.report-order.pagination', compact('report_orders', 'request','report_orders_count','emailRestrictions'));	
	}

    public function view($id)
    {
        $report_order = ReportOrders::find($id);
        $emailRestrictions = EmailRestriction::all()->pluck('email_category','email_domain')->toArray();
        if(!$report_order) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'View Report Order Details';
        return view('admin.report-order.view',compact('title','report_order','emailRestrictions'));
    }

    public function destroy(Request $request)
    {
        $report_order = ReportOrders::find($request->id);
		if($report_order) {
            $report_order->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }

    public function getReportOrdersData()
    {
        return Excel::download(new ReportOrdersExport, 'reportorders.xlsx');
    }
}
