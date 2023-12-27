<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Career;
use App\Models\Department;
use File, Auth;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:career-list|career-add|career-edit|career-delete,admin', ['only' => ['index','store']]);
        $this->middleware('permission:career-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:career-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:career-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Careers';
		return view('admin.career.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $careers = new Career;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $careers = $careers->where(function ($q) use ($search) {
                $q->Where('position', 'LIKE', "%{$search}%");
            });
        }
        if (!is_null($request->is_active)) {
            $careers = $careers->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $careers = $careers->orderBy($request->sort_by, $request->sort_order);
        } else {
            $careers = $careers->orderBy('id', 'Desc');
        }
        $careers_count = $careers->count();
        $careers = $careers->paginate($per_page_record);                
        return view('admin.career.pagination', compact('careers', 'request','careers_count'));	
	}

    public function add()
    {
        $title = 'Add Career';
        $career = new Career;
        $departments = Department::where('is_active','1')->get();
        return view('admin.career.add',compact('title','career','departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'position'      => 'required',
            'description'   => 'required',
		]);
        $data = $request->except(['_token','_method']);
        $career = Career::create($data);
        $notification = ['message' => 'Career added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.career.index')->with($notification);
    }

    public function edit($id)
    {
        $career = Career::find($id);
        if(!$career) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Career Details';
        $departments = Department::where('is_active','1')->get();
        return view('admin.career.add',compact('title','career','departments'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'position'      => 'required',
            'slug' => 'required|unique:careers,slug,'.$request->id,
            'description'   => 'required',
		]);

        $data = $request->except(['_token','_method']);        
        Career::where('id',$request->id)->update($data);
        $notification = ['message' => 'Career updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.career.index')->with($notification);
    }
    
    public function status(Request $request)
    {
        $status = 0;
        $career = Career::find($request->id);
        if($career) {
            if($career->is_active) {
                $career->is_active = 0;
                $status = 0;
            } else {
                $career->is_active = 1;
                $status = 1;
            }
            $career->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function destroy(Request $request)
    {
        $career = Career::find($request->id);
		if($career) {
            $career->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }
}