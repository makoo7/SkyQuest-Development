<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\IndustryGroup;
use App\Models\Sector;
use File;

class IndustryGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:industry-group-list|industry-group-add|industry-group-edit|industry-group-delete,admin', ['only' => ['index','store']]);
        $this->middleware('permission:industry-group-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:industry-group-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:industry-group-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Industry Group';
		return view('admin.industrygroup.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $industrygroups = new IndustryGroup;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $industrygroups = $industrygroups->where(function ($q) use ($search) {
                $q->Where('title', 'LIKE', "%{$search}%");
            });
        }
        if (!is_null($request->is_active)) {
            $industrygroups = $industrygroups->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $industrygroups = $industrygroups->orderBy($request->sort_by, $request->sort_order);
        } else {
            $industrygroups = $industrygroups->orderBy('id', 'Desc');
        }
        $industrygroups_count = $industrygroups->count();
        $industrygroups = $industrygroups->paginate($per_page_record);       
        return view('admin.industrygroup.pagination', compact('industrygroups', 'request', 'industrygroups_count'));	
	}

    public function add()
    {
        $title = 'Add Industry Group';
        $industrygroup = new IndustryGroup;
        $sector = Sector::where('is_active',1)->get();
        return view('admin.industrygroup.add',compact('title','industrygroup','sector'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required',
            'sector_id'  => 'required'
        ]);
        $data = $request->except(['_token','_method']);
        IndustryGroup::create($data);
        $notification = ['message' => 'Industry Group added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.industry-group.index')->with($notification);
    }

    public function edit($id)
    {
        $industrygroup = IndustryGroup::find($id);
        if(!$industrygroup) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Industry Group Details';
        $sector = Sector::where('is_active',1)->get();
        return view('admin.industrygroup.add',compact('title','industrygroup','sector'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title'      => 'required',
            'sector_id'  => 'required',
            'slug' => 'required|unique:industry_group,slug,'.$request->id.',id,deleted_at,NULL,sector_id,'.$request->sector_id.'',
            'page_title' => 'required',
		]);

        $data = $request->except(['_token','_method']);
        IndustryGroup::where('id',$request->id)->update($data);
        $notification = ['message' => 'Industry Group updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.industry-group.index')->with($notification);
    }
    
    public function status(Request $request)
    {
        $status = 0;
        $industrygroup = IndustryGroup::find($request->id);
        if($industrygroup) {
            if($industrygroup->is_active) {
                $industrygroup->is_active = 0;
                $status = 0;
            } else {
                $industrygroup->is_active = 1;
                $status = 1;
            }
            $industrygroup->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function destroy(Request $request)
    {
        $industrygroup = IndustryGroup::find($request->id);
		if($industrygroup) {
            $industrygroup->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }
}
