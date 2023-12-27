<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\OurTeam;
use File;

class OurTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:our-team-list|our-team-add|our-team-edit|our-team-delete,admin', ['only' => ['index']]);
        $this->middleware('permission:our-team-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:our-team-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:our-team-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Our Team';
		return view('admin.our-team.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $ourteamData = new OurTeam;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $ourteamData = $ourteamData->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
            });
        }
        if (!is_null($request->is_active)) {
            $ourteamData = $ourteamData->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $ourteamData = $ourteamData->orderBy($request->sort_by, $request->sort_order);
        } else {
            $ourteamData = $ourteamData->orderBy('id', 'Desc');
        }
        $ourteamData_count = $ourteamData->count();
        $ourteamData = $ourteamData->paginate($per_page_record);                
        return view('admin.our-team.pagination', compact('ourteamData', 'request', 'ourteamData_count'));	
	}

    public function add()
    {
        $title = 'Add Team Member';
        $ourteam = new OurTeam;
        return view('admin.our-team.add',compact('title','ourteam'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'designation'   => 'required',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);
        $data = $request->except(['_token','_method']);

        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.OURTEAM_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
            } catch (\Exception $e) {

            }
        }
        OurTeam::create($data);
        $notification = ['message' => 'Team member added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.our-team.index')->with($notification);
    }

    public function edit($id)
    {
        $ourteam = OurTeam::find($id);
        if(!$ourteam) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Team Member Details';
        return view('admin.our-team.add',compact('title','ourteam'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'designation'   => 'required',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);

        $data = $request->except(['_token','_method']);

        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.OURTEAM_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
                // remove older image if any
                $ourteam = OurTeam::find($request->id);
                if($ourteam->image_id) {
                    cloudinary()->destroy($ourteam->image_id);
                }
            } catch (\Exception $e) {

            }
        }
        OurTeam::where('id',$request->id)->update($data);
        $notification = ['message' => 'Team member updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.our-team.index')->with($notification);
    }

    public function deleteImage(Request $request)
	{
		$ourteam = OurTeam::find($request->id);
        if($ourteam) {
            if($ourteam->image_id) {
                cloudinary()->destroy($ourteam->image_id);
            }
            $ourteam->image = "";
            $ourteam->save();
            return response()->json(['message' => 'Member image deleted successfully.', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
	}
    
    public function status(Request $request)
    {
        $status = 0;
        $ourteam = OurTeam::find($request->id);
        if($ourteam) {
            if($ourteam->is_active) {
                $ourteam->is_active = 0;
                $status = 0;
            } else {
                $ourteam->is_active = 1;
                $status = 1;
            }
            $ourteam->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function destroy(Request $request)
    {
        $ourteam = OurTeam::find($request->id);
		if($ourteam) {
            // delete older image if any            
            if(isset($ourteam->image_id)) {
                cloudinary()->destroy($ourteam->image_id);
            }
            $ourteam->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }
}
