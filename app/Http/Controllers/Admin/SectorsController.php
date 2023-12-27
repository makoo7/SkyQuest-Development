<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Sectors;
use File;

class SectorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:sectors-list|sectors-add|sectors-edit|sectors-delete,admin', ['only' => ['index']]);
        $this->middleware('permission:sectors-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:sectors-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:sectors-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Sectors';
		return view('admin.sectors.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $sectorsData = new Sectors;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $sectorsData = $sectorsData->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
            });
        }
        if (!is_null($request->is_active)) {
            $sectorsData = $sectorsData->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $sectorsData = $sectorsData->orderBy($request->sort_by, $request->sort_order);
        } else {
            $sectorsData = $sectorsData->orderBy('id', 'Desc');
        }
        $sectorsData_count = $sectorsData->count();
        $sectorsData = $sectorsData->paginate($per_page_record);            
        return view('admin.sectors.pagination', compact('sectorsData', 'request', 'sectorsData_count'));	
	}

    public function add()
    {
        $title = 'Add Sectors';
        $sectors = new Sectors;
        return view('admin.sectors.add',compact('title','sectors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'image'   => 'required',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);
        $data = $request->except(['_token','_method','image_data']);
        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.SECTORS_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
            } catch (\Exception $e) {

            }
        }
        $sectors = Sectors::create($data);
        $notification = ['message' => 'Sectors added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.sectors.index')->with($notification);
    }

    public function edit($id)
    {
        $sectors = Sectors::find($id);
        if(!$sectors) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Sectors Details';
        return view('admin.sectors.add',compact('title','sectors'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'image'   => 'required',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);

        $data = $request->except(['_token','_method','image_data']);

        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.SECTORS_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
                // remove older image if any
                $sectors = Sectors::find($request->id);
                if($sectors->image_id) {
                    cloudinary()->destroy($sectors->image_id);
                }
            } catch (\Exception $e) {

            }
        }
        Sectors::where('id',$request->id)->update($data);
        $notification = ['message' => 'Sectors updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.sectors.index')->with($notification);
    }

    public function deleteImage(Request $request)
	{
		$sectors = Sectors::find($request->id);
        if($sectors) {
            if($sectors->image_id) {
                cloudinary()->destroy($sectors->image_id);
            }
            $sectors->image = "";
            $sectors->save();
            return response()->json(['message' => 'Sectors Image deleted successfully.', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
	}
    
    public function status(Request $request)
    {
        $status = 0;
        $sectors = Sectors::find($request->id);
        if($sectors) {
            if($sectors->is_active) {
                $sectors->is_active = 0;
                $status = 0;
            } else {
                $sectors->is_active = 1;
                $status = 1;
            }
            $sectors->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function destroy(Request $request)
    {
        $sectors = Sectors::find($request->id);
		if($sectors) {
            // delete older image if any            
            if(isset($sectors->image_id)) {
                cloudinary()->destroy($sectors->image_id);
            }
            $sectors->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }
}
