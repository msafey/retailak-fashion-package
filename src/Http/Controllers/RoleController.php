<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AdminUser;
use Auth;
// use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class RoleController extends Controller
{


    public function __construct()
    {
        // $this->middleware('CheckRole');

    }


    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('roles'))
        {
            return view('admin.un-authorized');
        }
        return view('admin.roles.index');
    }


    public function rolesList()
    {
        if (!Auth::guard('admin_user')->user()->can('roles'))
        {
            return view('admin.un-authorized');
        }

        $data = Role::all();
        return DataTables::of($data)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (!Auth::guard('admin_user')->user()->can('roles'))
        {
            return view('admin.un-authorized');
        }

        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('roles'))
        {
            return view('admin.un-authorized');
        }
        $role = Role::create($request->except(['permission', '_token']));
        if ($request->has('permission')) {
            foreach ($request->permission as $key => $value) {
                $role->attachPermission($value);
            }
        }

        return redirect($role->path())->with('success', 'Role Has Been Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::guard('admin_user')->user()->can('roles'))
        {
            return view('admin.un-authorized');
        }
        $role = Role::find($id);
        $permissions = Permission::all();
        $role_permissions = $role->perms()->pluck('id', 'id')->toArray();
        // dd($role_permissions);
        return view('admin.roles.edit', compact(['role', 'role_permissions', 'permissions']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('roles'))
        {
            return view('admin.un-authorized');
        }

        $user = AdminUser::where('remember_token', '3sbcTpofQzGDlPnMWPNxBwKdQrMX5qRMQxyaZW1B')->get();
        $role = Role::find($id);
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();

        DB::table('permission_role')->where('role_id', $id)->delete();

        if ($request->permission) {
            foreach ($request->permission as $key => $value) {
                $role->attachPermission($value);
            }
        }

        return redirect($role->path())->with('success', 'Role Has Been Updated Successfully');

    }


    public function delete($id)
    {
        if (!Auth::guard('admin_user')->user()->can('roles'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($id) {
            $role = Role::where('id', $id)->first();
            // dd($role);
            $role->delete();
            return 'success';
        });
    }
}
