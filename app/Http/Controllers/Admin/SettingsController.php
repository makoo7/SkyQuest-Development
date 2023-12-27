<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Settings;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:system-settings,admin', ['only' => ['index','update']]);
    }

    public function index()
    {
        $settings = Settings::first();
        $title = 'System Settings';        
		return view('admin.setting.index', compact('title','settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token','_method']);  
        Settings::updateOrInsert(['id' => $request->id], $data);
        $notification = ['message' => 'System settings updated successfully!','alert-class' => 'success'];
		return redirect()->route('admin.systemsettings')->with($notification);
    }
    
}