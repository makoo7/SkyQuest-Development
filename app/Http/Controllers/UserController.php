<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use App\Models\Sector;
use App\Models\ReportOrders;
use App\Models\Report;
use App\Models\UsersBookmark;
use App\Models\Settings;
use File, Auth;

class UserController extends Controller
{
    public function myReports()
    {
        $title = 'My Reports';
        $user = auth('web')->user();
        if(!$user) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $order_reports = ReportOrders::where('user_id',$user->id)->whereIn('payment_status',array('paid','captured','COMPLETED'))->get();
        
        $meta_title = "";
        $meta_description = "";
        $page_title = "";
        $meta_keyword = "";

        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        $settings = Settings::first();
		return view('front.user.myreports', compact('page_title','meta_keyword','title','meta_title','meta_description','services','sectors','user','order_reports','settings'));
    }
    
    public function myBookmarks()
    {
        $title = 'My Bookmarks';
        $per_page_record = config('constants.PER_PAGE_RECORD');
        $user = auth('web')->user();
        if(!$user) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        
        $insightsBookmark = UsersBookmark::with('insights')->where('user_id',$user->id)->where('entity_type','insight')
                    ->whereHas('insights', function ($query) {
                        $query->where('is_active', 'like', '1');
                    })->get();
                
        $casestudiesBookmark = UsersBookmark::with('casestudies')->where('user_id',$user->id)->where('entity_type','casestudy')
                    ->whereHas('casestudies', function ($query) {
                        $query->where('is_active', 'like', '1');
                    })->get();
        
        $reportsBookmark = UsersBookmark::with('reports')->where('user_id',$user->id)->where('entity_type','report')
                    ->whereHas('reports', function ($query) {
                        $query->where('is_active', 'like', '1');
                    })->get();
        
        $meta_title = "";
        $meta_description = "";
        $page_title = "";
        $meta_keyword = "";

        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        $settings = Settings::first();
		return view('front.user.mybookmarks', compact('page_title','meta_keyword','title','meta_title','meta_description','services','sectors','user','insightsBookmark','casestudiesBookmark','reportsBookmark','settings'));
    }

    public function settings()
    {
        $title = 'Settings';
        $user = auth('web')->user();
        if(!$user) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $meta_title = "";
        $meta_description = "";
        $page_title = "";
        $meta_keyword = "";

        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
		return view('front.user.settings', compact('page_title','meta_keyword','title','meta_title','meta_description','services','sectors','user'));
    }

    public function myProfile()
    {
        $title = 'Edit Profile';
        $user = auth('web')->user();
        if(!$user) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $meta_title = "";
        $meta_description = "";
        $page_title = "";
        $meta_keyword = "";

        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
		return view('front.user.editprofile', compact('page_title','meta_keyword','title','meta_title','meta_description','services','sectors','user'));
    }

    public function editProfile(Request $request)
    {
        $request->validate([
            'user_name'      => 'required',
            'email' => 'required|email:filter|max:255|unique:users,email,'.$request->id.',id',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);

        $data = $request->except(['_token']);

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
        $notification = ['message' => 'Profile has been updated successfully!','alert-class' => 'success'];
		return redirect('settings')->with($notification);
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
            return response()->json(['message' => 'Profile Picture deleted successfully.', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
	}

    public function changePassword()
    {
        $title = 'Change Password';
        $user = auth('web')->user();
        if(!$user) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $meta_title = "";
        $meta_description = "";
        $page_title = "";
        $meta_keyword = "";

        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        return view('front.user.changepassword',compact('page_title','meta_keyword','title','meta_title','meta_description','services','sectors','user'));
    }
    
    public function savePassword(Request $request)
    {
        $user = User::find($request->id);
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!\Hash::check($value, $user->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
            'new_password'              => 'required|min:8|different:current_password',
            'password_confirmation' => 'required_with:new_password',
        ],[
            'current_password.current_password' => "Your current password doesn't matches with this"
        ]);

        if ($validator->fails()) {
            $msg = $validator->errors()->first();
            $notification = ['message' => $msg,'alert-class' => 'error'];
            return redirect()->back()->withErrors($validator)->withInput()->with($notification);
        } else {
            $userData = User::where('id',$user->id)->update(['password' => \Hash::make($request->new_password)]);
            $notification = ['message' => 'Password has been changed successfully!','alert-class' => 'success'];
            return redirect('settings')->with($notification);
        }
    }

    public function toggleBookmark(Request $request)
    {
        $user_id = $request->user_id;
        $entity_type = $request->entity_type;
        $entity_id = $request->entity_id;
        $isOnDetailPage = $request->isOnDetailPage;

        $html = "";

        if($user_id!='' && $entity_type!='' && $entity_id!=''){
            $bookmarks = UsersBookmark::where('user_id',$user_id)->where('entity_type',$entity_type)->where('entity_id',$entity_id)->get();
            
            if(!$bookmarks->isEmpty()){
                // remove bookmark
                UsersBookmark::where('user_id',$user_id)->where('entity_type',$entity_type)->where('entity_id',$entity_id)->delete();
                if(isset($isOnDetailPage)){
                    $html .= asset('assets/frontend/images/icon_unbookmark.png');
                } else{
                    $html .= asset('assets/frontend/images/bookmark-white.png');
                }
                return response()->json(['html' => $html, 'success' => 1, 'message' => 'Bookmark removed!']);
            }else{
                // add bookmark
                $bookmark_data = array('user_id' => $user_id,
                                    'entity_type' => $entity_type,
                                    'entity_id' => $entity_id);

                UsersBookmark::create($bookmark_data);
                if(isset($isOnDetailPage)){
                    $html .= asset('assets/frontend/images/icon_bookmark.png');
                } else{
                    $html .= asset('assets/frontend/images/bookmark-black.png');
                }
                return response()->json(['html' => $html, 'success' => 1, 'message' => 'Bookmark added!']);
            }

        }else{
            return response()->json(['html' => $html, 'success' => 0, 'message' => 'Please try again!']);
        }
    }

    public function removeBookmark(Request $request)
    {
        $user_id = $request->user_id;
        $entity_type = $request->entity_type;
        $entity_id = $request->entity_id;
        $total_count = 0;

        if($user_id!='' && $entity_type!='' && $entity_id!=''){
            $bookmarks = UsersBookmark::where('user_id',$user_id)->where('entity_type',$entity_type)->where('entity_id',$entity_id)->delete();

            $insightsCount = UsersBookmark::with('insights')->where('user_id',$user_id)->where('entity_type','insight')->count();
                        
            $casestudiesCount = UsersBookmark::with('casestudies')->where('user_id',$user_id)->where('entity_type','casestudy')->count();
            
            $reportsCount = UsersBookmark::with('reports')->where('user_id',$user_id)->where('entity_type','report')->count();
                        
            if($entity_type=='insight'){
                $count = $insightsCount;
            } else if($entity_type=='casestudy'){
                $count = $casestudiesCount;
            } else if($entity_type='report'){
                $count = $reportsCount;
            }

            $total_count = $insightsCount + $casestudiesCount + $reportsCount;

            return response()->json(['total_count' => $total_count, 'count' => $count, 'success' => 1, 'message' => 'Bookmark removed!']);            
        }else{
            return response()->json(['total_count' => $total_count, 'count' => '', 'success' => 0, 'message' => 'Please try again!']);
        }
    }
}