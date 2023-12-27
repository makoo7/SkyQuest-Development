<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\ClientFeedback;
use File;

class ClientFeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:client-feedback-list|client-feedback-add|client-feedback-edit|client-feedback-delete,admin', ['only' => ['index','store']]);
        $this->middleware('permission:client-feedback-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:client-feedback-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:client-feedback-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Client Feedback';
		return view('admin.client-feedback.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $clientfeedbackData = new ClientFeedback;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $clientfeedbackData = $clientfeedbackData->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
            });
        }
        if (!is_null($request->is_active)) {
            $clientfeedbackData = $clientfeedbackData->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $clientfeedbackData = $clientfeedbackData->orderBy($request->sort_by, $request->sort_order);
        } else {
            $clientfeedbackData = $clientfeedbackData->orderBy('id', 'Desc');
        }
        $clientfeedbackData_count = $clientfeedbackData->count();
        $clientfeedbackData = $clientfeedbackData->paginate($per_page_record);                
        return view('admin.client-feedback.pagination', compact('clientfeedbackData', 'request', 'clientfeedbackData_count'));	
	}

    public function add()
    {
        $title = 'Add Client Feedback';
        $clientfeedback = new ClientFeedback;
        return view('admin.client-feedback.add',compact('title','clientfeedback'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'feedback'   => 'required',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);
        $data = $request->except(['_token','_method']);
        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.CLIENTFEEDBACK_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
            } catch (\Exception $e) {

            }
        }
        $clientfeedback = ClientFeedback::create($data);
        $notification = ['message' => 'Client Feedback added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.client-feedback.index')->with($notification);
    }

    public function edit($id)
    {
        $clientfeedback = ClientFeedback::find($id);
        if(!$clientfeedback) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Client Feedback Details';
        return view('admin.client-feedback.add',compact('title','clientfeedback'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'feedback'   => 'required',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);

        $data = $request->except(['_token','_method']);

        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.CLIENTFEEDBACK_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
                // remove older image if any
                $clientfeedback = ClientFeedback::find($request->id);
                if($clientfeedback->image_id) {
                    cloudinary()->destroy($clientfeedback->image_id);
                }
            } catch (\Exception $e) {

            }
        }
        ClientFeedback::where('id',$request->id)->update($data);
        $notification = ['message' => 'Client Feedback updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.client-feedback.index')->with($notification);
    }

    public function deleteImage(Request $request)
	{
		$clientfeedback = ClientFeedback::find($request->id);
        if($clientfeedback) {
            if($clientfeedback->image_id) {
                cloudinary()->destroy($clientfeedback->image_id);
            }
            $clientfeedback->image = "";
            $clientfeedback->save();
            return response()->json(['message' => 'Client Image deleted successfully.', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
	}
    
    public function status(Request $request)
    {
        $status = 0;
        $clientfeedback = ClientFeedback::find($request->id);
        if($clientfeedback) {
            if($clientfeedback->is_active) {
                $clientfeedback->is_active = 0;
                $status = 0;
            } else {
                $clientfeedback->is_active = 1;
                $status = 1;
            }
            $clientfeedback->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function destroy(Request $request)
    {
        $clientfeedback = ClientFeedback::find($request->id);
		if($clientfeedback) {
            // delete older image if any            
            if(isset($clientfeedback->image_id)) {
                cloudinary()->destroy($clientfeedback->image_id);
            }
            $clientfeedback->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }
}
