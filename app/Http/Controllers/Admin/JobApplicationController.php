<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use App\Exports\JobApplicationExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use File, Storage;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:job-application-list|job-application-view|job-application-export|job-application-delete,admin', ['only' => ['index']]);
        $this->middleware('permission:job-application-view,admin', ['only' => ['view']]);
        $this->middleware('permission:job-application-export,admin', ['only' => ['export']]);
        $this->middleware('permission:job-application-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Job Applications';
		return view('admin.jobapplication.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $jobapplications = new JobApplication;
    
        if ($request->keyword) {
            $search = $request->keyword;
            $jobapplications = $jobapplications->where(function ($q) use ($search) {
                $q->Where('first_name', 'LIKE', "%{$search}%");
                $q->orWhere('last_name', 'LIKE', "%{$search}%");
                $q->orWhere('email', 'LIKE', "%{$search}%");
                $q->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        if ($request->sort_by) {
            $jobapplications = $jobapplications->orderBy($request->sort_by, $request->sort_order);
        } else {
            $jobapplications = $jobapplications->orderBy('id', 'Desc');
        }
        $jobapplications_count = $jobapplications->count();
        $jobapplications = $jobapplications->paginate($per_page_record);            
        return view('admin.jobapplication.pagination', compact('jobapplications', 'request', 'jobapplications_count'));	
	}

    public function view($id)
    {
        $jobapplication = JobApplication::find($id);
        if(!$jobapplication) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'View Job Application Details';
        return view('admin.jobapplication.view',compact('title','jobapplication'));
    }

    public function destroy(Request $request)
    {
        $jobapplication = JobApplication::find($request->id);
		if($jobapplication) {
            // delete older resume if any            
            if(isset($jobapplication->resume)) {
                cloudinary()->destroy($jobapplication->resume);
            }
            $jobapplication->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }

    public function getJobApplicationData()
    {
        return Excel::download(new JobApplicationExport, 'jobapplications.xlsx');
    }

    public function download($id)
    {
        $jobapplication = JobApplication::find($id);
        if(!$jobapplication) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
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
