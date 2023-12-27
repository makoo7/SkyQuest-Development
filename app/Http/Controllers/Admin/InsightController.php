<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Insight;
use App\Models\UsersBookmark;
use File;

class InsightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:insight-list|insight-add|insight-edit|insight-delete,admin', ['only' => ['index','store']]);
        $this->middleware('permission:insight-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:insight-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:insight-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Insights';
		return view('admin.insight.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $insights = new Insight;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $insights = $insights->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
            });
        }
        if (!is_null($request->is_active)) {
            $insights = $insights->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $insights = $insights->orderBy($request->sort_by, $request->sort_order);
        } else {
            $insights = $insights->orderBy('id', 'Desc');
        }
        $insights_count = $insights->count();
        $insights = $insights->paginate($per_page_record);           
        return view('admin.insight.pagination', compact('insights', 'request', 'insights_count'));	
	}

    public function add()
    {
        $title = 'Add Insight';
        $insight = new Insight;
        return view('admin.insight.add',compact('title','insight'));
    }

    public function store(Request $request)
    {
        $user = auth('admin')->user();
        
        $request->validate([
            'name'      => 'required',
            'description'   => 'required',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);
        
        $data = $request->except(['_token','_method','image_data']);        
        
        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.INSIGHT_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
            } catch (\Exception $e) {

            }
        }

        if($request->hasFile('writer_image')){
            $folder = config('cloudinary.upload_preset') . config('constants.INSIGHT_PATH');
            try {
                $data['writer_image'] = cloudinary()->upload($request->file('writer_image')->getRealPath(),['folder' => $folder])->getSecurePath();
            } catch (\Exception $e) {

            }
        }
        
        $data['publish_date'] = ($request->publish_date!='') ? date("Y-m-d", strtotime($request->publish_date)) : NULL;
        $data['admin_id'] = $user->id;
        Insight::create($data);        
        $notification = ['message' => 'Insight added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.insight.index')->with($notification);
    }

    public function edit($id)
    {
        $insight = Insight::find($id);
        if(!$insight) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Insight Details';
        return view('admin.insight.add',compact('title','insight'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'description'   => 'required',
            'slug' => 'required|unique:insights,slug,'.$request->id.',id,deleted_at,NULL',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);

        $data = $request->except(['_token','_method','image_data']);

        $data['publish_date'] = ($request->publish_date!='') ? date('Y-m-d', strtotime($request->publish_date)) : NULL;
        
        $folder = config('cloudinary.upload_preset') . config('constants.INSIGHT_PATH');
        $insight = Insight::find($request->id);

        if($request->hasFile('image')){            
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
                // remove older image if any
                if($insight->image_id) {
                    cloudinary()->destroy($insight->image_id);
                }
            } catch (\Exception $e) {

            }
        }

        if($request->hasFile('writer_image')){
            try {
                $data['writer_image'] = cloudinary()->upload($request->file('writer_image')->getRealPath(),['folder' => $folder])->getSecurePath();
                // remove older writer image if any
                if($insight->writer_image_id) {
                    cloudinary()->destroy($insight->writer_image_id);
                }
            } catch (\Exception $e) {

            }
        }

        Insight::where('id',$request->id)->update($data);
        $notification = ['message' => 'Insight updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.insight.index')->with($notification);
    }

    public function deleteImage(Request $request)
	{
		$insight = Insight::find($request->id);
        if($insight) {
            if($insight->image_id) {
                cloudinary()->destroy($insight->image_id);
            }
            $insight->image = "";
            $insight->save();
            return response()->json(['message' => 'Image deleted successfully.', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
	}

    public function deleteWriterImage(Request $request)
	{
		$insight = Insight::find($request->id);
        if($insight) {
            if($insight->writer_image_id) {
                cloudinary()->destroy($insight->writer_image_id);
            }
            $insight->writer_image = "";
            $insight->save();
            return response()->json(['message' => 'Writer Image deleted successfully.', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
	}
    
    public function status(Request $request)
    {
        $status = 0;
        $insight = Insight::find($request->id);
        if($insight) {
            if($insight->is_active) {
                $insight->is_active = 0;
                $status = 0;
            } else {
                $insight->is_active = 1;
                $status = 1;
            }
            $insight->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function destroy(Request $request)
    {
        $insight = Insight::find($request->id);
		if($insight) {
            // delete older image if any            
            if(isset($insight->image_id)) {
                cloudinary()->destroy($insight->image_id);
            }
            if($insight->writer_image_id) {
                cloudinary()->destroy($insight->writer_image_id);
            }
            $insight->delete();

            // delete insight from user bookmark
            UsersBookmark::where('entity_type','insight')->where('entity_id',$insight->id)->delete();

            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }

    public function checkImage(Request $request)
    {
        $insight = Insight::find($request->id);
        
        if($insight) {
            if($insight->image_id) {
                return response()->json(['message' => 'yes', 'success' => 1, 'is_image' => 1]);
            }else{
                return response()->json(['message' => 'no', 'success' => 1, 'is_image' => 0]);
            }
        }
        return response()->json(['message' => 'no', 'success' => 0, 'is_image' => 0]);
    }
}
