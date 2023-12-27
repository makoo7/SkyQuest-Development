<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Service;
use File, Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:service-list|service-add|service-edit|service-delete,admin', ['only' => ['index']]);
        $this->middleware('permission:service-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:service-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:service-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Services';
		return view('admin.service.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $services = new Service;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $services = $services->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
            });
        }
        if (!is_null($request->is_active)) {
            $services = $services->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $services = $services->orderBy($request->sort_by, $request->sort_order);
        } else {
            $services = $services->orderBy('id', 'Desc');
        }
        $services_count = $services->count();
        $services = $services->paginate($per_page_record);                
        return view('admin.service.pagination', compact('services', 'request','services_count'));	
	}

    public function add()
    {
        $title = 'Add Service';
        $service = new Service;
        return view('admin.service.add',compact('title','service'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'description'   => 'required',
            'short_description'   => 'required',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);
        $data = $request->except(['_token','_method']);
        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.SERVICE_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
            } catch (\Exception $e) {

            }
        }
        $service = Service::create($data);
        $notification = ['message' => 'Service added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.service.index')->with($notification);
    }

    public function edit($id)
    {
        $service = Service::find($id);
        if(!$service) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Service Details';
        return view('admin.service.add',compact('title','service'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'description'   => 'required',
            'short_description'   => 'required',
            'slug' => 'required|unique:services,slug,'.$request->id.',id,deleted_at,NULL',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);

        $data = $request->except(['_token','_method']);

        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.SERVICE_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
                // remove older image if any
                $service = Service::find($request->id);
                if($service->image_id) {
                    cloudinary()->destroy($service->image_id);
                }
            } catch (\Exception $e) {

            }
        }
        Service::where('id',$request->id)->update($data);
        $notification = ['message' => 'Service updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.service.index')->with($notification);
    }

    public function deleteImage(Request $request)
	{
		$service = Service::find($request->id);
        if($service) {
            if($service->image_id) {
                cloudinary()->destroy($service->image_id);
            }
            $service->image = "";
            $service->save();
            return response()->json(['message' => 'Service Image deleted successfully.', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
	}
    
    public function status(Request $request)
    {
        $status = 0;
        $service = Service::find($request->id);
        if($service) {
            if($service->is_active) {
                $service->is_active = 0;
                $status = 0;
            } else {
                $service->is_active = 1;
                $status = 1;
            }
            $service->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function destroy(Request $request)
    {
        $service = Service::find($request->id);
		if($service) {
            // delete older image if any            
            if(isset($service->image_id)) {
                cloudinary()->destroy($service->image_id);
            }
            $service->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }
}
