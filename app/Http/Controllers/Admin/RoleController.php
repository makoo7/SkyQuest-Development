<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Role as Roles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;
use DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:role-list|role-add|role-edit|role-delete,admin', ['only' => ['index','store']]);
         $this->middleware('permission:role-add,admin', ['only' => ['add','store']]);
         $this->middleware('permission:role-edit,admin', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete,admin', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'Roles';
        $roles = Role::get();
        return view('admin.roles.index',compact('title','roles'));
    }

    public function ajax(Request $request)
	{
        $user = auth('admin')->user();
        $per_page_record = isset($request->per_page) ? $request->per_page : '25';
        $roles = Role::where('id','<>',$user->id);
    
        if ($request->keyword) {
            $search = $request->keyword;
            $roles = $roles->where(function ($q) use ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
            });
        }
        if ($request->sort_by) {
            $roles = $roles->orderBy($request->sort_by, $request->sort_order);
        } else {
            $roles = $roles->orderBy('id', 'Desc');
        }
        $roles_count = $roles->count();
        $roles = $roles->paginate($per_page_record);                
        return view('admin.roles.pagination', compact('roles', 'request', 'roles_count'));	
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $title = 'Add Role';
        $role = new Role;
        $permission = DB::table('permissions')->select('module_name')->groupBy('module_name')->get();
        $rolePermissions = [];
        return view('admin.roles.add',compact('title','permission', 'role', 'rolePermissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array|min:1',
        ]);
    
        $role = Role::create(['name' => $request->input('name'), 'guard_name' => 'admin']);
        $role->syncPermissions($request->input('permission'));
           
        $notification = ['message' => 'Role added successfully!','alert-class' => 'success'];
        return redirect()->route('admin.roles.index')->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = 'Edit Role';
        $role = Role::find($id);
        $permission = DB::table('permissions')->select('module_name')->groupBy('module_name')->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
        return view('admin.roles.add',compact('title','role','permission','rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:roles,id',
            'name' => 'required|unique:roles,name,'.$request->id.',id',
            'permission' => 'required|array|min:1',
        ]);
    
        $role = Role::find($request->id);
        $role->name = $request->input('name');
        $role->save();
    
        $role->syncPermissions($request->input('permission'));
    
        $notification = ['message' => 'Role updated successfully!','alert-class' => 'success'];
        return redirect()->route('admin.roles.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $role = Role::find($request->id);
		if($role) {
            $admins = Admin::where('role_id', $request->id)->first();
            if($admins){
                $msg = 'This role is already assign to an admin so please change the role and then you can able to delete the role.';
                return response()->json(['message' => $msg, 'success' => 0]);
            }else{
                $role->delete();
                return response()->json(['message' => 'Deleted!', 'success' => 1]);
            }            
        } else {
            return response()->json(['message' => 'Invalid Access!', 'success' => 0]);
        }
    }
}
