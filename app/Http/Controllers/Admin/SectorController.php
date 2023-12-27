<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Sector;
use File;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:sector-list|sector-add|sector-edit|sector-delete,admin', ['only' => ['index']]);
        $this->middleware('permission:sector-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:sector-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:sector-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Sector';
		return view('admin.sector.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $sectors = new Sector;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $sectors = $sectors->where(function ($q) use ($search) {
                $q->Where('title', 'LIKE', "%{$search}%");
            });
        }
        if (!is_null($request->is_active)) {
            $sectors = $sectors->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $sectors = $sectors->orderBy($request->sort_by, $request->sort_order);
        } else {
            $sectors = $sectors->orderBy('id', 'Desc');
        }
        $sectors_count = $sectors->count();
        $sectors = $sectors->paginate($per_page_record);       
        return view('admin.sector.pagination', compact('sectors', 'request', 'sectors_count'));	
	}

    public function add()
    {
        $title = 'Add Sector';
        $sector = new Sector;
        return view('admin.sector.add',compact('title','sector'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required',
            'page_title' => 'required',
        ]);
        $data = $request->except(['_token','_method']);
        Sector::create($data);
        $notification = ['message' => 'Sector added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.sector.index')->with($notification);
    }

    public function edit($id)
    {
        $sector = Sector::find($id);
        if(!$sector) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Sector Details';
        return view('admin.sector.add',compact('title','sector'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title'      => 'required',
            'slug' => 'required|unique:sector,slug,'.$request->id.',id,deleted_at,NULL',
            'page_title' => 'required',
		]);

        $data = $request->except(['_token','_method']);
        Sector::where('id',$request->id)->update($data);
        $notification = ['message' => 'Sector updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.sector.index')->with($notification);
    }
    
    public function status(Request $request)
    {
        $status = 0;
        $sector = Sector::find($request->id);
        if($sector) {
            if($sector->is_active) {
                $sector->is_active = 0;
                $status = 0;
            } else {
                $sector->is_active = 1;
                $status = 1;
            }
            $sector->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function destroy(Request $request)
    {
        $sector = Sector::find($request->id);
		if($sector) {
            $sector->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }
}
