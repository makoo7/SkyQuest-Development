<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\CaseStudy;
use App\Models\Sectors;
use App\Models\Service;
use App\Models\CaseStudySectors;
use App\Models\CaseStudyService;
use App\Models\UsersBookmark;
use File;

class CaseStudyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:casestudy-list|casestudy-add|casestudy-edit|casestudy-delete,admin', ['only' => ['index','store']]);
        $this->middleware('permission:casestudy-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:casestudy-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:casestudy-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Case Studies';
		return view('admin.casestudy.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $casestudies = new CaseStudy;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $casestudies = $casestudies->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
            });
        }
        if (!is_null($request->is_active)) {
            $casestudies = $casestudies->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $casestudies = $casestudies->orderBy($request->sort_by, $request->sort_order);
        } else {
            $casestudies = $casestudies->orderBy('id', 'Desc');
        }
        $casestudies_count = $casestudies->count();
        $casestudies = $casestudies->paginate($per_page_record);           
        return view('admin.casestudy.pagination', compact('casestudies', 'request', 'casestudies_count'));	
	}

    public function add()
    {
        $title = 'Add Case Study';
        $casestudy = new CaseStudy;
        $sectors = Sectors::where('is_active',1)->get();
        $services = Service::where('is_active',1)->get();
        return view('admin.casestudy.add',compact('title','casestudy','sectors','services'));
    }

    public function store(Request $request)
    {
        $user = auth('admin')->user();
        
        $request->validate([
            'name'      => 'required',
            'description'   => 'required',
            'image'   => 'required',
            'location' => 'required',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);
        $data = $request->except(['_token','_method','image_data']);
        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.CASESTUDY_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
            } catch (\Exception $e) {

            }
        }
        $data['admin_id'] = $user->id;
        $casestudy = CaseStudy::create($data);        

        $notification = ['message' => 'Case Study added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.casestudy.index')->with($notification);
    }

    public function edit($id)
    {
        $casestudy = CaseStudy::with("sectors")->with("service")->find($id);

        if(!$casestudy) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Case Study Details';
        $sectors = Sectors::where('is_active',1)->get();
        $services = Service::where('is_active',1)->get();
        return view('admin.casestudy.add',compact('title','casestudy','sectors','services'));
    }

    public function update(Request $request)
    {        
        $request->validate([
            'name'      => 'required',
            'description'   => 'required',
            'image'   => 'required',
            'location' => 'required',
            'slug' => 'required|unique:case_studies,slug,'.$request->id.',id,deleted_at,NULL',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);

        $data = $request->except(['_token','_method','sectors_ids','service_ids','image_data']);

        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.CASESTUDY_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
                // remove older image if any
                $casestudy = CaseStudy::find($request->id);
                if($casestudy->image_id) {
                    cloudinary()->destroy($casestudy->image_id);
                }
            } catch (\Exception $e) {

            }
        }
        CaseStudy::where('id',$request->id)->update($data);
        
        $notification = ['message' => 'Case Study updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.casestudy.index')->with($notification);
    }

    public function deleteImage(Request $request)
	{
		$casestudy = CaseStudy::find($request->id);
        if($casestudy) {
            if($casestudy->image_id) {
                cloudinary()->destroy($casestudy->image_id);
            }
            $casestudy->image = "";
            $casestudy->save();
            return response()->json(['message' => 'Image deleted successfully.', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
	}
    
    public function status(Request $request)
    {
        $status = 0;
        $casestudy = CaseStudy::find($request->id);
        if($casestudy) {
            if($casestudy->is_active) {
                $casestudy->is_active = 0;
                $status = 0;
            } else {
                $casestudy->is_active = 1;
                $status = 1;
            }
            $casestudy->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function destroy(Request $request)
    {
        $casestudy = CaseStudy::find($request->id);
		if($casestudy) {
            // delete older image if any            
            if(isset($casestudy->image_id)) {
                cloudinary()->destroy($casestudy->image_id);
            }
            $casestudy->delete();

            // delete casestudy from user bookmark
            UsersBookmark::where('entity_type','casestudy')->where('entity_id',$casestudy->id)->delete();

            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }
}
