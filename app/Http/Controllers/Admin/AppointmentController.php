<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use App\Exports\AppointmentExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Appointment;
use File,DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:appointment-list|appointment-view|appointment-export,admin', ['only' => ['index']]);
         $this->middleware('permission:appointment-view,admin', ['only' => ['view']]);
         $this->middleware('permission:appointment-export,admin', ['only' => ['export']]);
    }
    
    public function index()
    {
        $title = 'Appointments';
		return view('admin.appointment.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $appointments = new Appointment;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $appointments = $appointments->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
                $q->orWhere('email', 'LIKE', "%{$search}%");
                $q->orWhere('company_name', 'LIKE', "%{$search}%");
                $q->orWhere('phone', 'LIKE', "%{$search}%");
                $q->orWhere('appointment_time', 'LIKE', "%{$search}%");
                $q->orWhere('appointment_time', 'LIKE', "%".date("Y-m-d",strtotime(str_replace("/","-",$search)))."%");     
            });
        }
        if ($request->sort_by) {
            $appointments = $appointments->orderBy($request->sort_by, $request->sort_order);
        } else {
            $appointments = $appointments->orderBy('id', 'Desc');
        }
        $appointments_count = $appointments->count();
        $appointments = $appointments->paginate($per_page_record);        
        return view('admin.appointment.pagination', compact('appointments', 'request', 'appointments_count'));	
	}

    public function view($id)
    {
        $appointment = Appointment::find($id);
        if(!$appointment) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'View Appointment Details';
        return view('admin.appointment.view',compact('title','appointment'));
    }

    public function getAppointmentData()
    {
        return Excel::download(new AppointmentExport, 'appointments.xlsx');
    }
}
