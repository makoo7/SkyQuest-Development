<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Award;
use File;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:award-list|award-add|award-edit|award-delete,admin', ['only' => ['index','store']]);
        $this->middleware('permission:award-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:award-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:award-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Awards';
		return view('admin.award.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $awards = new Award;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $awards = $awards->where(function ($q) use ($search) {
                $q->Where('title', 'LIKE', "%{$search}%");
            });
        }
        if (!is_null($request->is_active)) {
            $awards = $awards->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $awards = $awards->orderBy($request->sort_by, $request->sort_order);
        } else {
            $awards = $awards->orderBy('id', 'Desc');
        }
        $awards_count = $awards->count();
        $awards = $awards->paginate($per_page_record);            
        return view('admin.award.pagination', compact('awards', 'request', 'awards_count'));	
	}

    public function add()
    {
        $title = 'Add Award';
        $award = new Award;
        return view('admin.award.add',compact('title','award'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required',
            'image'      => 'required',
            'short_description'      => 'required',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);
        $data = $request->except(['_token','_method','image_data']);
        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.AWARD_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
            } catch (\Exception $e) {

            }
        }
        Award::create($data);
        $notification = ['message' => 'Award added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.award.index')->with($notification);
    }

    public function edit($id)
    {
        $award = Award::find($id);
        if(!$award) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Award Details';
        return view('admin.award.add',compact('title','award'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title'      => 'required',
            'image'      => 'required',
            'short_description'      => 'required',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);

        $data = $request->except(['_token','_method','image_data']);

        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.AWARD_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
                // remove older image if any
                $award = Award::find($request->id);
                if($award->image_id) {
                    cloudinary()->destroy($award->image_id);
                }
            } catch (\Exception $e) {

            }
        }
        Award::where('id',$request->id)->update($data);
        $notification = ['message' => 'Award updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.award.index')->with($notification);
    }

    public function deleteImage(Request $request)
	{
		$award = Award::find($request->id);
        if($award) {
            if($award->image_id) {
                cloudinary()->destroy($award->image_id);
            }
            $award->image = "";
            $award->save();
            return response()->json(['message' => 'Award Image deleted successfully.', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
	}
    
    public function status(Request $request)
    {
        $status = 0;
        $award = Award::find($request->id);
        if($award) {
            if($award->is_active) {
                $award->is_active = 0;
                $status = 0;
            } else {
                $award->is_active = 1;
                $status = 1;
            }
            $award->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function destroy(Request $request)
    {
        $award = Award::find($request->id);
		if($award) {
            // delete older image if any            
            if(isset($award->image_id)) {
                cloudinary()->destroy($award->image_id);
            }
            $award->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }
}
