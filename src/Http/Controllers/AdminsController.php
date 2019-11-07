<?php

namespace App\Http\Controllers;


use App\Models\AdminUser;
use App\Models\Role;
use App\Http\Requests\AddAdminRequest;
use App\Http\Requests\EditAdminRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Warehouses;
use Auth;
use Hash;
use Yajra\Datatables\Datatables;


class AdminsController extends Controller
{

    public function __construct()
    {
        // $this->middleware('CheckRole');

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!Auth::guard('admin_user')->user()->can('cmsusers')) {
            return view('admin.un-authorized');
        }
        $user = Auth::guard('admin_user')->user();
        $user_id = $user->id;

        return view('admin.admins.index', compact('user_id'));
    }


    public function adminsList()
    {
        if (!Auth::guard('admin_user')->user()->can('cmsusers'))
        {
            return view('admin.un-authorized');
        }

        $data = AdminUser::all();
        foreach ($data as $dat) {
            $role_user = DB::table('role_user')->where('user_id', $dat->id)->first();
            if ($role_user) {
                $role = Role::where('id', $role_user->role_id)->first();
                if ($role) {
                    $dat['role'] = $role->name;
                }
            }
        }
        return DataTables::of($data)->make(true);
    }


    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('cmsusers'))
        {
            return view('admin.un-authorized');
        }
        $user_role = Role::all();
        $warehouses = Warehouses::where('status', 1)->get();
        return view('admin.admins.create', compact('user_role', 'warehouses'));
    }


    public function store(AddAdminRequest $request)
    {
        if (!Auth::guard('admin_user')->user()->can('cmsusers'))
        {
            return view('admin.un-authorized');
        }
        return DB::transaction(function () use ($request) {
            if ($request->has('warehouse_id') && $request->warehouse_id != "" && $request->role == 3) {
                $warehouse = $request->warehouse_id;
            } else {
                $warehouse = null;
            }
            $admin = AdminUser::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'role' => $request->role,
                'warehouse_id' => $warehouse
            ]);
            $role = $request->role;
            $admin->attachRole(Role::where('id', $role)->first());
            return redirect($admin->path())->with('success', 'Admin Created Successfully');
        });
    }


    public function edit($id)
    {
        if (!Auth::guard('admin_user')->user()->can('cmsusers'))
        {
            return view('admin.un-authorized');
        }
        $auth_user = Auth::guard('admin_user')->user();
        $cmsuser = AdminUser::find($id);
        $all_roles = Role::all();
        $warehouses = Warehouses::where('status', 1)->get();
        $user_role = DB::table('role_user')->where('user_id', $id)->first();
        if ($user_role) {
            $selected_role = Role::where('id', $user_role->role_id)->first();
            $selected_role = $selected_role->id;
        } else {
            $selected_role = 0;
        }
        return view('admin.admins.edit', compact('user_warehouses', 'warehouses', 'cmsuser', 'all_roles', 'selected_role', 'auth_user'));
    }


    public function changePassView($id)
    {


        $cmsuser = AdminUser::where('id', $id)->first();
        return view('admin.admins.change_password', compact('cmsuser'));
    }


    public function changePassword(ChangePasswordRequest $request, $id)
    {

        $user = AdminUser::where('id', $id)->first();

        $user->password = bcrypt($request['password']);;
        $user->save();
        return redirect($user->path())->with('success', 'Password Updated Successfully');
    }


    public function update(EditAdminRequest $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('cmsusers'))
        {
            return view('admin.un-authorized');
        }
        $cmsuser = AdminUser::find($id);
        $auth_user = Auth::guard('admin_user')->user();
        if ($auth_user->id != $id) {
            if (!$request->has('role')) {
                return redirect()->back()->withErrors('Role Is Missing');
            }
        }
        if ($request->has('warehouse_id') && $request->warehouse_id != "" && $request->role == 3) {
            $warehouse = $request->warehouse_id;
        } else {
            $warehouse = null;
        }
        $cmsuser->warehouse_id = $warehouse;
        $cmsuser->name = $request->name;
        $cmsuser->email = $request->email;
        $cmsuser->save();


        $role = $request->role;

        if ($role) {
            DB::table('role_user')->where('user_id', $cmsuser->id)->delete();
            $cmsuser->attachRole(Role::where('id', $role)->first());
        }


        return redirect($cmsuser->path())->with('success', 'Admin Updated Successfully');

    }


    public function delete($id)
    {
        if (!Auth::guard('admin_user')->user()->can('cmsusers'))
        {
            return view('admin.un-authorized');
        }
        $auth_user = Auth::guard('admin_user')->user();
        $user = AdminUser::find($id);
        if ($auth_user->id == $user->id) {
            return 'false';
        } else {
            $user->delete();
            DB::table('role_user')->where('user_id', $user->id)->delete();
        }
        return 'success';
    }

}
