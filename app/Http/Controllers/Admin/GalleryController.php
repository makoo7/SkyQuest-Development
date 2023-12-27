<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Gallery;
use File;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:gallery-list|gallery-add|gallery-edit|gallery-view,admin', ['only' => ['index','store']]);
        $this->middleware('permission:gallery-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:gallery-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:gallery-view,admin', ['only' => ['view']]);
    }

    public function index()
    {
        $title = 'Gallery';
		return view('admin.gallery.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $galleryData = new Gallery;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $galleryData = $galleryData->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
                $q->orWhere('image', 'LIKE', "%{$search}%");
            });
        }
        if ($request->sort_by) {
            $galleryData = $galleryData->orderBy($request->sort_by, $request->sort_order);
        } else {
            $galleryData = $galleryData->orderBy('id', 'Desc');
        }
        $galleryData_count = $galleryData->count();
        $galleryData = $galleryData->paginate($per_page_record);                
        return view('admin.gallery.pagination', compact('galleryData', 'request', 'galleryData_count'));	
	}

    public function add()
    {
        $title = 'Add Gallery';
        $gallery = new Gallery;
        return view('admin.gallery.add',compact('title','gallery'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'image' =>  'required|image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);
        $data = $request->except(['_token','_method']);

        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.GALLERY_PATH');
            try {
                $filename = $request->file('image')->getClientOriginalName();                
                $filename = preg_replace('/\s+/', '_', $filename);                
                $result = checkGalleryImageNameExists($filename);                
                if($result){
                    $notification = ['message' => 'Image file with this name already exists!','alert-class' => 'error'];
		            return redirect()->back()->withInput()->with($notification);
                }else{                    
                    $filename = pathinfo($filename, PATHINFO_FILENAME);
                    $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),[                    
                        "use_filename" => true, 
                        "unique_filename" => true,
                        "public_id" => $filename,
                        "folder" => $folder,
                        ])->getSecurePath();
                }
            } catch (\Exception $e) {

            }
        }
        Gallery::create($data);
        $notification = ['message' => 'Gallery added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.gallery.index')->with($notification);
    }

    public function edit($id)
    {
        $gallery = Gallery::find($id);
        if(!$gallery) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Gallery Details';
        return view('admin.gallery.add',compact('title','gallery'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);

        $data = $request->except(['_token','_method','image_data']);

        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.GALLERY_PATH');
            try {
                $filename = $request->file('image')->getClientOriginalName();                
                $filename = preg_replace('/\s+/', '_', $filename);                
                $result = checkGalleryImageNameExists($filename);                
                if($result){
                    $notification = ['message' => 'Image file with this name already exists!','alert-class' => 'error'];
		            return redirect()->back()->withInput()->with($notification);
                }else{                    
                    $filename = pathinfo($filename, PATHINFO_FILENAME);
                    $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),[                    
                        "use_filename" => true, 
                        "unique_filename" => true,
                        "public_id" => $filename,
                        "folder" => $folder,
                        ])->getSecurePath();
                }
                    
                // remove older image if any
                $gallery = Gallery::find($request->id);
                if($gallery->image_id) {
                    cloudinary()->destroy($gallery->image_id);
                }
            } catch (\Exception $e) {

            }
        }
        Gallery::where('id',$request->id)->update($data);
        $notification = ['message' => 'Gallery updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.gallery.index')->with($notification);
    }

    public function deleteImage(Request $request)
	{
		$gallery = Gallery::find($request->id);
        if($gallery) {
            if($gallery->image_id) {
                cloudinary()->destroy($gallery->image_id);
            }
            $gallery->image = "";
            $gallery->save();
            return response()->json(['message' => 'Gallery image deleted successfully.', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
	}

    public function view($id)
    {
        $gallery = Gallery::find($id);
        if(!$gallery) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'View Gallery Details';
        return view('admin.gallery.view',compact('title','gallery'));
    }
    
}
