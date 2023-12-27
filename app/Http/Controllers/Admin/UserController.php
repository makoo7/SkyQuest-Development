<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\User;
use File, Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:user-list|user-edit|user-export,admin', ['only' => ['index']]);
        $this->middleware('permission:user-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:user-export,admin', ['only' => ['export']]);
    }

    public function index()
    {
        $title = 'Users';
		return view('admin.user.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $users = new User;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $users = $users->where(function ($q) use ($search) {
                $q->Where('user_name', 'LIKE', "%{$search}%");
                $q->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        if (!is_null($request->is_active)) {
            $users = $users->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $users = $users->orderBy($request->sort_by, $request->sort_order);
        } else {
            $users = $users->orderBy('id', 'Desc');
        }
        $users_count = $users->count();
        $users = $users->paginate($per_page_record);                
        return view('admin.user.pagination', compact('users', 'request', 'users_count'));	
	}

    public function edit($id)
    {
        $user = User::find($id);
        if(!$user) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit user Details';
        return view('admin.user.add',compact('title','user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'user_name'      => 'required',
            'email' => 'required|email:filter|max:255|unique:users,email,'.$request->id.',id',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);

        $data = $request->except(['_token','_method']);      
        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.USER_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
                // remove older image if any
                $user = User::find($request->id);
                if($user->image_id) {
                    cloudinary()->destroy($user->image_id);
                }
            } catch (\Exception $e) {

            }
        }  
        User::where('id',$request->id)->update($data);
        $notification = ['message' => 'User updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.user.index')->with($notification);
    }
    
    public function status(Request $request)
    {
        $status = 0;
        $user = User::find($request->id);
        if($user) {
            if($user->is_active) {
                $user->is_active = 0;
                $status = 0;
            } else {
                $user->is_active = 1;
                $status = 1;
            }
            $user->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function destroy(Request $request)
    {
        $user = User::find($request->id);
		if($user) {
            $user->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }

    public function deleteImage(Request $request)
	{
		$user = User::find($request->id);
        if($user) {
            if($user->image_id) {
                cloudinary()->destroy($user->image_id);
            }
            $user->image = "";
            $user->save();
            return response()->json(['message' => 'User Profile Picture deleted successfully.', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
	}
    
    public function getUserData()
    {
        return Excel::download(new UserExport, 'users.xlsx');
    }
}