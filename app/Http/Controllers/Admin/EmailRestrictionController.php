<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailRestriction;

class EmailRestrictionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:email-restriction-list|email-restriction-add|email-restriction-edit|email-restriction-delete,admin', ['only' => ['index']]);
        $this->middleware('permission:email-restriction-add,admin', ['only' => ['add','store']]);
        $this->middleware('permission:email-restriction-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:email-restriction-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Email Restriction';
		return view('admin.email-restriction.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $email_restrictions = new EmailRestriction();
        if ($request->keyword) {
            $search = $request->keyword;
            $email_restrictions = $email_restrictions->where(function ($q) use ($search) {
                $q->Where('email_domain', 'LIKE', "%{$search}%");
                $q->orWhere('email_category', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->sort_by) {
            $email_restrictions = $email_restrictions->orderBy($request->sort_by, $request->sort_order);
        } else {
            $email_restrictions = $email_restrictions->orderBy('id', 'Desc');
        }
        $email_restrictions_count = $email_restrictions->count();
        $email_restrictions = $email_restrictions->paginate($per_page_record); 
        return view('admin.email-restriction.pagination', compact('email_restrictions', 'request', 'email_restrictions_count'));	
	}

    public function add()
    {
        $title = 'Add Email Restriction';
        $email_restriction = new EmailRestriction();
        return view('admin.email-restriction.add',compact('title','email_restriction'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'email_domain'      => 'required',
            'email_category' => 'required',
        ]);

        $data = $request->except(['_token','_method']);
        $data['email_domain'] = strtolower($data['email_domain']);
        EmailRestriction::create($data);
        
        $notification = ['message' => 'Email Restriction added successfully!','alert-class' => 'success'];
		return redirect()->route('admin.email-restriction.index')->with($notification);
    }

    public function edit($id)
    {
        $email_restriction = EmailRestriction::find($id);
        if(!$email_restriction) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Email Restriction Details';
        return view('admin.email-restriction.add',compact('title','email_restriction'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'email_domain'      => 'required',
            'email_category' => 'required',
		]);

        $data = $request->except(['_token','_method']);
        $data['email_domain'] = strtolower($data['email_domain']);
        EmailRestriction::where('id',$request->id)->update($data);

        $notification = ['message' => 'Page updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.email-restriction.index')->with($notification);
    }
    
    

    public function destroy(Request $request)
    {
        $email_restriction = EmailRestriction::find($request->id);
		if($email_restriction) {
            $email_restriction->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }

}
