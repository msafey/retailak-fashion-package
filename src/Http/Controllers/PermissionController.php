<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Auth;
use DB;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class PermissionController extends Controller
{

    public function __construct()
    {
        // $this->middleware('CheckRole');
    }


    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('permissions'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.permissions.index');

    }


    public function permissionsList()
    {
        if (!Auth::guard('admin_user')->user()->can('permissions'))
        {
            return view('admin.un-authorized');
        }
        $data = Permission::all();
        return DataTables::of($data)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('permissions'))
        {
            return view('admin.un-authorized');
        }
        $permissions = Permission::all();
        return view('admin.permissions.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!Auth::guard('admin_user')->user()->can('permissions'))
        {
            return view('admin.un-authorized');
        }

        $permissions = Permission::create($request->except('_token'));

        return back();
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

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function edit($id)
    {
        if (!Auth::guard('admin_user')->user()->can('permissions'))
        {
            return view('admin.un-authorized');
        }
        $permission = Permission::where('id', $id)->first();
        return view('admin.permissions.edit', compact(['permission']));
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
        if (!Auth::guard('admin_user')->user()->can('permissions'))
        {
            return view('admin.un-authorized');
        }

        $permission = Permission::find($id);
        $permission->name = $request->name;
        $permission->display_name = $request->display_name;
        $permission->description = $request->description;
        $permission->save();
        // $permissions = Permission::create($request->except('_token'));

        return redirect($permission->path())->with('success', 'Permission Has Been Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {

        if (!Auth::guard('admin_user')->user()->can('permissions'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($id) {
            $permission = Permission::find($id);
            $permission->delete();
            return 'success';
        });


    }
}

