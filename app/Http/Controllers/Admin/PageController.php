<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\pages;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:pages-list|pages-edit|pages-delete,admin', ['only' => ['index']]);
        $this->middleware('permission:pages-edit,admin', ['only' => ['edit','update']]);
        $this->middleware('permission:pages-delete,admin', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Pages';
		return view('admin.pages.index', compact('title'));
    }

    public function ajax(Request $request)
	{
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $pages = new pages();
        if ($request->keyword) {
            $search = $request->keyword;
            $pages = $pages->where(function ($q) use ($search) {
                $q->Where('slug', 'LIKE', "%{$search}%"); 
            });
        }
        
        if ($request->sort_by) {
            $pages = $pages->orderBy($request->sort_by, $request->sort_order);
        } else {
            $pages = $pages->orderBy('id', 'Desc');
        }
        $pages_count = $pages->count();
        $pages = $pages->paginate($per_page_record); 
        return view('admin.pages.pagination', compact('pages', 'request', 'pages_count'));	
	}

    public function edit($id)
    {
        $pages = pages::find($id);
        if(!$pages) {
            $notification = ['message' => 'Invalid Access!','alert-class' => 'error'];
            return redirect()->back()->withInput()->with($notification);
        }
        $title = 'Edit Pages Details';
        return view('admin.pages.add',compact('title','pages'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'page_title' => 'required',
		]);
        $data = $request->except(['_token','_method']);
        pages::where('id',$request->id)->update($data);
        $notification = ['message' => 'Page updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.pages.index')->with($notification);
    }
    
    

    public function destroy(Request $request)
    {
        $pages = pages::find($request->id);
		if($pages) {
            $pages->delete();
            return response()->json(['message' => 'Deleted!', 'success' => 1]);
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }

}
