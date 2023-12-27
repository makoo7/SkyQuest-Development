<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use App\Exports\PageNotFoundExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\PageNotFoundInquiry;
use File;

class PageNotFoundInquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:404-inquiry-list|404-inquiry-view|404-inquiry-export|404-inquiry-delete,admin', ['only' => ['index']]);
        $this->middleware('permission:404-inquiry-view,admin', ['only' => ['view']]);
        $this->middleware('permission:404-inquiry-export,admin', ['only' => ['export']]);
        $this->middleware('permission:404-inquiry-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = '404 Inquiry';
		return view('admin.404-inquiry.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $pagenotfoundData = new PageNotFoundInquiry;
    
        if ($request->keyword) {
            $search = $request->keyword;
            if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
                $pagenotfoundData = $pagenotfoundData->where(function ($q) use ($search) {
                    $q->WhereHas('country', function ($qq) use ($search) {
                        $qq->where('name','like','%'.$search.'%');
                    });
                    $q->orWhere('designation', 'LIKE', "%{$search}%");
                    $q->orWhere('created_at', 'LIKE', "%{$search}%");
                    $q->orWhere('created_at', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
                });
            }else{
                $pagenotfoundData = $pagenotfoundData->where(function ($q) use ($search) {
                    $q->Where('name', 'LIKE', "%{$search}%");
                    $q->orWhere('email', 'LIKE', "%{$search}%");
                    $q->orWhere('phone', 'LIKE', "%{$search}%");
                    $q->orWhere('company_name', 'LIKE', "%{$search}%");
                    $q->orWhere('created_at', 'LIKE', "%{$search}%");
                    $q->orWhere('created_at', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");
                });
            }
        }
        if ($request->sort_by) {
            $pagenotfoundData = $pagenotfoundData->orderBy($request->sort_by, $request->sort_order);
        } else {
            $pagenotfoundData = $pagenotfoundData->orderBy('id', 'Desc');
        }
        $pagenotfoundData_count = $pagenotfoundData->count();
        $pagenotfoundData = $pagenotfoundData->paginate($per_page_record);            
        return view('admin.404-inquiry.pagination', compact('pagenotfoundData', 'request', 'pagenotfoundData_count'));	
	}

    public function view($id)
    {
        $pagenotfound = PageNotFoundInquiry::find($id);
        if(!$pagenotfound) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'View 404 Inquiry Details';
        return view('admin.404-inquiry.view',compact('title','pagenotfound'));
    }

    public function destroy(Request $request)
    {
        $pagenotfound = PageNotFoundInquiry::find($request->id);
		if($pagenotfound) {
            $pagenotfound->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }

    public function getPageNotFoundData()
    {
        return Excel::download(new PageNotFoundExport, '404-inquiry.xlsx');
    }
}
