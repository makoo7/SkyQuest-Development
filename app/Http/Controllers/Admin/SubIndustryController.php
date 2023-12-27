<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\SubIndustry;
use App\Models\Industry;
use File;

class SubIndustryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:sub-industry-list|sub-industry-add|sub-industry-edit|sub-industry-delete,admin', ['only' => ['index']]);
        $this->middleware('permission:sub-industry-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:sub-industry-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:sub-industry-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Sub Industry';
		return view('admin.subindustry.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $subindustryData = new SubIndustry;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $subindustryData = $subindustryData->where(function ($q) use ($search) {
                $q->Where('title', 'LIKE', "%{$search}%");
            });
        }
        if (!is_null($request->is_active)) {
            $subindustryData = $subindustryData->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $subindustryData = $subindustryData->orderBy($request->sort_by, $request->sort_order);
        } else {
            $subindustryData = $subindustryData->orderBy('id', 'Desc');
        }
        $subindustryData_count = $subindustryData->count();
        $subindustryData = $subindustryData->paginate($per_page_record);       
        return view('admin.subindustry.pagination', compact('subindustryData', 'request', 'subindustryData_count'));	
	}

    public function add()
    {
        $title = 'Add Sub Industry';
        $subindustry = new SubIndustry;
        $industryData = Industry::where('is_active',1)->get();
        return view('admin.subindustry.add',compact('title','industryData','subindustry'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required',
            'industry_id'  => 'required',
            'initial'  => 'required',
            'page_title' => 'required',
        ]);
        $data = $request->except(['_token','_method']);
        SubIndustry::create($data);
        $notification = ['message' => 'Sub Industry added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.sub-industry.index')->with($notification);
    }

    public function edit($id)
    {
        $subindustry = SubIndustry::find($id);
        if(!$subindustry) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Sub Industry Details';
        $industryData = Industry::where('is_active',1)->get();
        return view('admin.subindustry.add',compact('title','subindustry','industryData'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title'      => 'required',
            'industry_id'  => 'required',
            'slug' => 'required|unique:sub_industry,slug,'.$request->id.',id,deleted_at,NULL,industry_id,'.$request->industry_id.'',
            'initial'  => 'required',
            'page_title' => 'required',
		]);

        $data = $request->except(['_token','_method']);
        SubIndustry::where('id',$request->id)->update($data);
        $notification = ['message' => 'Sub Industry updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.sub-industry.index')->with($notification);
    }
    
    public function status(Request $request)
    {
        $status = 0;
        $subindustry = SubIndustry::find($request->id);
        if($subindustry) {
            if($subindustry->is_active) {
                $subindustry->is_active = 0;
                $status = 0;
            } else {
                $subindustry->is_active = 1;
                $status = 1;
            }
            $subindustry->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function destroy(Request $request)
    {
        $subindustry = SubIndustry::find($request->id);
		if($subindustry) {
            $subindustry->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }
}
