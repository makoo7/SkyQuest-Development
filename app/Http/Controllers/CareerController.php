<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Career;
use App\Models\Department;
use App\Models\JobApplication;
use App\Models\Sector;
use App\Models\pages;
use App\Mail\JobApplication as JobApplicationMail;
use App\Jobs\SendJobApplicationEmail;
use Mail;
use Illuminate\Support\Facades\Log;
use App\Rules\ScriptPreventRule;
use Storage;

class CareerController extends Controller
{
    public function index()
    {
        $title = config('metadata.careers.pageTitle');
        $meta_title = config('metadata.careers.title');
        $meta_description = config('metadata.careers.description');
        
        $page = pages::where('slug','careers')->first();
        $h1 = ($page) ? $page->h1 : '';
        $meta_title = ($page) ? $page->meta_title : '';
        $meta_description = ($page) ? $page->meta_description : '';
        $page_title = ($page) ? $page->page_title : '';
        $meta_keyword = ($page) ? $page->meta_keyword : '';

        $services = Service::where('is_active',1)->get();
        $departments = Department::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
		return view('front.careers.index', compact('title','meta_title','meta_description','services','sectors','departments','page','h1','page_title','meta_keyword'));
    }

    public function list($departmentslug)
    {
        $title = config('metadata.careers-list.pageTitle');
        $meta_title = config('metadata.careers-list.title');
        $meta_description = config('metadata.careers-list.description');
        $page_title = "";
        $meta_keyword = "";
        if($departmentslug != '')
        {
            $slug = ucwords(str_replace('-', ' ', $departmentslug));
            $title = $slug.' Jobs';
            $meta_title = $title;
            $meta_description = $title;
        }
        
        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        $department = Department::where('slug',$departmentslug)->where('is_active',1)->first();
        if(!$department) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }

        $careersData = Career::where('department_id',$department->id)->where('is_active',1)->get();
        
        return view('front.careers.list', compact('page_title','meta_keyword','title','meta_title','meta_description','services','sectors','careersData','department'));
    }

    public function details($departmentslug,$slug)
    {        
        $title = config('metadata.careers-details.pageTitle');
        $meta_title = config('metadata.careers-details.title');
        $meta_description = config('metadata.careers-details.description');
        $page_title = "";
        $meta_keyword = "";

        $services = Service::where('is_active',1)->get();
        $sectors = Sector::where('is_active',1)->get();
        $department = Department::where('slug',$departmentslug)->where('is_active',1)->first();
        if(!$department) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $careers = Career::where('department_id',$department->id)->where('slug',$slug)->where('is_active',1)->first();
        
        if(!$careers) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title .= $careers->position;
        return view('front.careers.details', compact('page_title','meta_keyword','title','meta_title','meta_description','services','sectors','careers','department'));
    }
    
    public function saveJobApplication(Request $request)
    {
        $request->validate([
            'first_name' => ['required', new ScriptPreventRule()],
            'last_name' => ['required', new ScriptPreventRule()],
            'phone' => 'required',
            'email' => 'required|email:filter',
            'work_experience' => ['required', new ScriptPreventRule()],
            'notice_period' => ['required', new ScriptPreventRule()],
            'current_ctc' => ['required', new ScriptPreventRule()],
            'expected_ctc' => ['required', new ScriptPreventRule()],
            'portfolio_or_web' => ['required', 'url', new ScriptPreventRule()],
            'resume' =>  'required|mimes:doc,pdf,docx,zip',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'The captcha field is required.',
            'g-recaptcha-response.captcha' => 'Invalid captcha',
        ]);
        
        $data = $request->except(['_token','hiddenRecaptcha']);

        if($request->hasFile('resume')){
            $extension = $request->file('resume')->getClientOriginalExtension();
            $data['extension'] = $extension;

            $folder = config('cloudinary.upload_preset') . config('constants.JOBAPPLICATION_PATH');
            $result = cloudinary()->upload($request->file('resume')->getRealPath(), [
                "use_filename" => true,
                "unique_filename" => true,
                "resource_type" => "auto",
                'folder' => $folder,
            ]);
            $data['resume'] = $result->getSecurePath();
        }

        $jobapplication = JobApplication::create($data);

        try {
            // dispatch your queue job
            dispatch(new SendJobApplicationEmail($jobapplication));
        } catch (\Exception $e) {
            Log::error('Error while sending job application email.'.$e->getMessage());
        }
        $notification = ['message' => 'Your job application request has been send successfully!', 'alert-class' => 'success'];
        return redirect()->back()->with($notification);
    }

    public function download($id)
    {
        $jobapplication = JobApplication::find($id);
        if(!$jobapplication) {
            $notification = ['message' => 'Invalid Access 123!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $filepath = $jobapplication->resume;
        $uploadedFileName = basename($jobapplication->resume);
        $uploadedFileExtension = '';
        if(strpos($uploadedFileName, ".")){
            $uploadedFileExtensionArr = explode(".",$uploadedFileName);
            $uploadedFileExtension = $uploadedFileExtensionArr[1];
        }        
        $extension = ($jobapplication->extension) ? $jobapplication->extension : $uploadedFileExtension;
        $extension = ($extension) ? $extension : 'docx'; // for older raw files
        if($extension=='doc' || $extension=='docx' || $extension=='pdf'){
            $file = $filepath;
            $fileContent = file_get_contents($file);
            $fileName = time().'.'.$extension;
            Storage::disk('local')->put($fileName, $fileContent);
            return response()->download(storage_path().'/app/'.$fileName)->deleteFileAfterSend(true);
        }
        return false;
    }
}