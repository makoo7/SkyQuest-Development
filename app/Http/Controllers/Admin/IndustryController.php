<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Industry;
use App\Models\IndustryGroup;
use File;

class IndustryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:industry-list|industry-add|industry-edit|industry-delete,admin', ['only' => ['index']]);
        $this->middleware('permission:industry-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:industry-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:industry-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Industry';
		return view('admin.industry.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $industryData = new Industry;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $industryData = $industryData->where(function ($q) use ($search) {
                $q->Where('title', 'LIKE', "%{$search}%");
            });
        }
        if (!is_null($request->is_active)) {
            $industryData = $industryData->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $industryData = $industryData->orderBy($request->sort_by, $request->sort_order);
        } else {
            $industryData = $industryData->orderBy('id', 'Desc');
        }
        $industryData_count = $industryData->count();
        $industryData = $industryData->paginate($per_page_record);       
        return view('admin.industry.pagination', compact('industryData', 'request', 'industryData_count'));	
	}

    public function add()
    {
        $title = 'Add Industry';
        $industry = new Industry;
        $industrygroups = IndustryGroup::where('is_active',1)->get();
        return view('admin.industry.add',compact('title','industrygroups','industry'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required',
            'page_title' => 'required',
            'industry_group_id'  => 'required',
        ]);
        $data = $request->except(['_token','_method']);
        Industry::create($data);
        $notification = ['message' => 'Industry added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.industry.index')->with($notification);
    }

    public function edit($id)
    {
        $industry = Industry::find($id);
        if(!$industry) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Industry Details';
        $industrygroups = IndustryGroup::where('is_active',1)->get();
        return view('admin.industry.add',compact('title','industry','industrygroups'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title'      => 'required',
            'industry_group_id'  => 'required',
            'slug' => 'required|unique:industry,slug,'.$request->id.',id,deleted_at,NULL,industry_group_id,'.$request->industry_group_id.'',
            'page_title' => 'required',
		]);

        $data = $request->except(['_token','_method']);
        Industry::where('id',$request->id)->update($data);
        $notification = ['message' => 'Industry updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.industry.index')->with($notification);
    }
    
    public function status(Request $request)
    {
        $status = 0;
        $industry = Industry::find($request->id);
        if($industry) {
            if($industry->is_active) {
                $industry->is_active = 0;
                $status = 0;
            } else {
                $industry->is_active = 1;
                $status = 1;
            }
            $industry->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function destroy(Request $request)
    {
        $industry = Industry::find($request->id);
		if($industry) {
            $industry->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }
}
