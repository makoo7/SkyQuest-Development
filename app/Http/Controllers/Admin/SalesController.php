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

class SalesController extends Controller
{
    public function index(Request $request){
        $title = 'Sales Report';
        return view('admin.sales-report-request.index', compact('title'));
    }

    public function store(Request $request){
        dd("Store");
    }

    public function add(Request $request){
        $title = 'Add Sales Report';
        $email_restriction = new SalesReportRequest();
        return view('admin.sales-report-request.add', compact('title','email_restriction'));
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