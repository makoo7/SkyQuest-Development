<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Report;
use App\Models\ReportSampleRequest;
use App\Models\ReportInquiry;
use App\Models\ReportSubscribeNow;
use App\Models\ReportOrders;
use App\Models\Service;
use App\Models\Career;
use App\Models\User;
use App\Models\Role as Roles;
use App\Models\CaseStudy;
use App\Models\Sectors;
use App\Models\Award;
use App\Models\Insight;
use App\Models\ClientFeedback;
use App\Models\Sector;
use App\Models\IndustryGroup;
use App\Models\Industry;
use App\Models\SubIndustry;
use App\Models\Appointment;
use App\Models\ContactUs;
use App\Models\JobApplication;
use App\Models\OurTeam;
use App\Models\PageNotFoundInquiry;
use App\Models\Gallery;
use App\Models\Homepage;
use Spatie\Permission\Models\Role;
use File, Auth, Hash, DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:admin-list|admin-add|admin-edit|admin-delete,admin', ['only' => ['index','store']]);
         $this->middleware('permission:admin-add,admin', ['only' => ['add','store']]);
         $this->middleware('permission:admin-edit,admin', ['only' => ['edit','update']]);
         $this->middleware('permission:admin-delete,admin', ['only' => ['destroy']]);
    }

    public function dashboard()
    {
        $admin = Admin::count();
        $role = Role::count();
        $report = Report::count();
        $report_sample_request = ReportSampleRequest::count();
        $report_inquiry = ReportInquiry::count();
        $report_subscribe_now = ReportSubscribeNow::count();
        $report_orders = ReportOrders::count();
        $service = Service::count();
        $career = Career::count();
        $user = User::count();
        $sectors = Sectors::count();
        $casestudy = CaseStudy::count();
        $award = Award::count();
        $insight = Insight::count();
        $clientfeedback = ClientFeedback::count();
        $sector = Sector::count();
        $industrygroup = IndustryGroup::count();
        $industry = Industry::count();
        $subindustry = SubIndustry::count();
        $appointment = Appointment::count();
        $contactus = ContactUs::count();
        $jobapplication = JobApplication::count();
        $ourteam = OurTeam::count();
        $pagenotfound = PageNotFoundInquiry::count();
        $gallery = Gallery::count();

		return view('admin.dashboard', [
			'title' => 'Dashboard',
			'admin' => $admin,
			'role' => $role,
            'report' => $report,
            'report_sample_request' => $report_sample_request,
            'report_inquiry' => $report_inquiry,
            'report_subscribe_now' => $report_subscribe_now,
            'report_orders' => $report_orders,
            'service' => $service,
            'career' => $career,
            'user' => $user,
            'sectors' => $sectors,
            'casestudy' => $casestudy,
            'award' => $award,
            'insight' => $insight,
            'clientfeedback' => $clientfeedback,
            'sector' => $sector,
            'industrygroup' => $industrygroup,
            'industry' => $industry,
            'subindustry' => $subindustry,
            'appointment' => $appointment,
            'contactus' => $contactus,
            'jobapplication' => $jobapplication,
            'ourteam' => $ourteam,
            'pagenotfound' => $pagenotfound,
            'gallery' => $gallery
		]);        
    }

    public function profile()
    {
        $title = 'Manage Profile';
        $user = auth('admin')->user();
        return view('admin.profile',compact('title','user'));
    }

    public function updateme(Request $request)
    {
        $user = auth('admin')->user();
        $validator = Validator::make($request->all(), [
            'user_name'      => 'required',
            'email' => 'required|email:filter|unique:admins,email,'.$user->id,
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);

        if ($validator->fails()) {
            $msg = $validator->errors()->first();
            $notification = ['message' => $msg,'alert-class' => 'error'];
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with($notification);
        }else{
            $data = $request->except(['_token','_method']);
            if($request->hasFile('image')){
                $folder = config('cloudinary.upload_preset') . config('constants.ADMIN_PATH');
                $result = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder]);
                $data['image'] = $result->getSecurePath();

                // delete older image if any
                if(isset($user->image_id)) {
                    cloudinary()->destroy($user->image_id);
                }
            }

            $admin = Admin::where('id',$user->id)->update($data);
            $notification = ['message' => 'Profile has been updated successfully!','alert-class' => 'success'];
            return redirect()->back()->withInput()->with($notification);
        }
    }

    public function deleteAvatar(Request $request)
	{
		$admin = Admin::find($request->id);
        if($admin) {
            if($admin->image_id) {
                cloudinary()->destroy($admin->image_id);
            }
            $admin->image = "";
            $admin->save();
            return response()->json(['message' => 'Profile Picture deleted successfully.', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
	}

    public function changPassword()
    {
        $title = 'Change Password';
        return view('admin.changepassword',['title' => $title]);
    }

    public function updatePassword(Request $request)
    {
        $user = auth('admin')->user();
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
            $admin = Admin::where('id',$user->id)->update(['password' => Hash::make($request->new_password)]);
            $notification = ['message' => 'Password has been changed successfully!','alert-class' => 'success'];
            return  redirect()->back()->with($notification);
        }
    }
    
    public function index()
	{
		$title = 'Admin Users';
		return view('admin.admin.index', compact('title'));
	}

    public function ajax(Request $request)
	{
        $user = auth('admin')->user();
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $admins = Admin::where('id','<>',$user->id);
    
        if ($request->keyword) {
            $search = $request->keyword;
            $admins = $admins->where(function ($q) use ($search) {
                $q->Where('user_name', 'LIKE', "%{$search}%");
                $q->orWhere('email', 'LIKE', "%{$search}%");
                $q->orWhereHas('role', function ( $q ) use ( $search ) {
                    $q->where('roles.name' , 'like' , "%$search%");
               });
            });
        }
        if (!is_null($request->is_active)) {
            $admins = $admins->where(function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            });
        }
        if ($request->sort_by) {
            $admins = $admins->orderBy($request->sort_by, $request->sort_order);
        } else {
            $admins = $admins->orderBy('id', 'Desc');
        }
        $admins_count = $admins->count();
        $admins = $admins->paginate($per_page_record);                
        return view('admin.admin.pagination', compact('admins', 'request','admins_count'));	
	}

    public function status(Request $request)
    {
        $status = 0;
        $admin = Admin::find($request->id);
        if($admin) {
            if($admin->is_active) {
                $admin->is_active = 0;
                $status = 0;
            } else {
                $admin->is_active = 1;
                $status = 1;
            }
            $admin->save();
            return response()->json(['message' => 'Status Changed!', 'success' => 1, 'status' => $status]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0, 'status' => $status]);
        }
    }

    public function add()
    {
        $title = 'Add Admin';
        $admin = new Admin;
        $roles = Role::where('guard_name','admin')->get();
        return view('admin.admin.add',compact('title','admin','roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id'      => 'required',
            'user_name'      => 'required',
            'email' => 'required|email:filter|max:255|unique:admins,email',
            'password'      => 'required',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);
        $data = $request->except(['_token','_method']);
        //$password = generatePassword(8);
        $data['password'] = Hash::make($request->password);
        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.ADMIN_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
            } catch (\Exception $e) {
                $notification = ['message' => 'Error in uploading image!','alert-class' => 'error'];
		        return redirect()->route('admin.admin.index')->with($notification);
            }
        }
        $admin = Admin::create($data);
        $admin->assignRole($request->input('role_id'));
        $admin->sendNewUserNotification($request->password);
        $notification = ['message' => 'Admin user added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.admin.index')->with($notification);
    }
    
    public function edit($id)
	{
		$admin = Admin::find($id);
        if(!$admin) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Admin Details';
        $roles = Role::where('guard_name','admin')->get();
        return view('admin.admin.add',compact('title','admin','roles'));
	}

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:admins,id',
            'role_id'      => 'required',
            'user_name'      => 'required',
            'email' => 'required|email:filter|max:255|unique:admins,email,'.$request->id.',id',
            'image' =>  'image|max:10240',
        ], [
			'image.uploaded' => 'The file size should not be greater than 10MB.'
		]);

        $data = $request->except(['_token','_method']);

        if($request->hasFile('image')){
            $folder = config('cloudinary.upload_preset') . config('constants.ADMIN_PATH');
            try {
                $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(),['folder' => $folder])->getSecurePath();
                // remove older image if any
                $admin = Admin::find($request->id);
                if($admin->image_id) {
                    cloudinary()->destroy($admin->image_id);
                }
            } catch (\Exception $e) {

            }
        }
        Admin::where('id',$request->id)->update($data);
        
        $admin = Admin::find($request->id);
        DB::table('model_has_roles')->where('model_id',$request->id)->delete();    
        $admin->assignRole($request->input('role_id'));

        $notification = ['message' => 'Admin user updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.admin.index')->with($notification);
    }

    public function destroy(Request $request)
    {
        $admin = Admin::find($request->id);
		if($admin) {
            // delete older image if any            
            if(isset($admin->image_id)) {
                cloudinary()->destroy($admin->image_id);
            }
            $admin->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }
}
