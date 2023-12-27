<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use App\Exports\ContactUsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Models\EmailRestriction;
use File;
use PharIo\Manifest\Email;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:contactus-list|contactus-view|contactus-export,admin', ['only' => ['index']]);
         $this->middleware('permission:contactus-view,admin', ['only' => ['view']]);
         $this->middleware('permission:contactus-export,admin', ['only' => ['export']]);
    }
    
    public function index()
    {
        $title = 'Contact Us';
		return view('admin.contactus.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $contactusData = new ContactUs;
    
        if ($request->keyword) {
            $search = $request->keyword;
            if(auth('admin')->user()->hasAnyRole(['Marketing Admin'])){
                $contactusData = $contactusData->where(function ($q) use ($search) {
                    $q->Where('subject', 'LIKE', "%{$search}%");
                });
            }else{
                $contactusData = $contactusData->where(function ($q) use ($search) {
                    $q->Where('name', 'LIKE', "%{$search}%");
                    $q->orWhere('email', 'LIKE', "%{$search}%");
                    $q->orWhere('phone', 'LIKE', "%{$search}%");
                });
            }
        }
        if ($request->sort_by) {
            $contactusData = $contactusData->orderBy($request->sort_by, $request->sort_order);
        } else {
            $contactusData = $contactusData->orderBy('id', 'Desc');
        }
        $contactusData_count = $contactusData->count();
        $contactusData = $contactusData->paginate($per_page_record);
        
        $emailRestrictions = EmailRestriction::all()->pluck('email_category','email_domain')->toArray();

        return view('admin.contactus.pagination', compact('contactusData', 'request', 'contactusData_count','emailRestrictions'));	
	}

    public function view($id)
    {
        $contactus = ContactUs::find($id);
        $emailRestrictions = EmailRestriction::all()->pluck('email_category','email_domain')->toArray();
        if(!$contactus) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'View Contact Us Details';
        return view('admin.contactus.view',compact('title','contactus','emailRestrictions'));
    }

    public function getContactUsData()
    {
        return Excel::download(new ContactUsExport, 'contactus.xlsx');
    }
}
