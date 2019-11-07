<?php

namespace App\Http\Controllers\Products;

use App\Models\UOM;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use File;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;


class UOMController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('uom'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.uom.index');
    }


    public function uomsList()
    {
        $uoms = UOM::orderBy('id', 'ASC')->get();


        return Datatables::of($uoms)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('uom'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.uom.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('uom'))
        {
            return view('admin.un-authorized');
        }


        $uom = new UOM;
        $uom->type = $request->name;
        $uom->save();

        $user = Auth::guard('admin_user')->user();

        $uom->adjustments()->attach($user->id, ['key' => "UOM", 'action' => "Added", 'content_name' => $uom->type]);


        return redirect('admin/uom')->with('success', 'UOM Created Successfully');

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
        if (!Auth::guard('admin_user')->user()->can('uom'))
        {
            return view('admin.un-authorized');
        }

        $uom = UOM::findOrFail($id);
        return view('/admin/uom/edit', compact('uom'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('uom'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($request, $id) {

            $uom = UOM::find($id);
            $uom->type = $request->name;
            $uom->save();


            $user = Auth::guard('admin_user')->user();

            $uom->adjustments()->attach($user->id, ['key' => "UOM", 'action' => "Edited", 'content_name' => $uom->type]);


            return redirect($uom->path())->with('success', 'UOM Updated Successfully');
        });

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Auth::guard('admin_user')->user()->can('uom'))
        {
            return view('admin.un-authorized');
        }

        $uom = UOM::findOrFail($id);

        if ($uom) {


            $user = Auth::guard('admin_user')->user();
            $uom->adjustments()->attach($user->id, ['key' => "UOM", 'action' => "Deleted", 'content_name' => $uom->type]);
            $uom->delete();
            return 'true';
        } else {
            return 'false';
        }
    }
}
