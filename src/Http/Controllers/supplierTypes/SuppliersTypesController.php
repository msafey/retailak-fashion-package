<?php

namespace App\Http\Controllers\supplierTypes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SupplierType;
use DB;
use Auth;
use Yajra\Datatables\Datatables;


class SuppliersTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('suppliers'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.supplier_types.index');
    }


    public function supplierTypesList()
    {
        if (!Auth::guard('admin_user')->user()->can('suppliers'))
        {
            return view('admin.un-authorized');
        }
        $supplier_types = SupplierType::all();
        return Datatables::of($supplier_types)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('suppliers'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.supplier_types.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('suppliers'))
        {
            return view('admin.un-authorized');
        }

        $supplier_type = new SupplierType;
        $supplier_type->name = $request->name;
        $supplier_type->save();
        $user = Auth::guard('admin_user')->user();

        $supplier_type->adjustments()->attach($user->id, ['key' => "SupplierType", 'action' => "Added", 'content_name' => $supplier_type->name]);
        return redirect('admin/supplier-types')->with('success', 'Supplier type created successfully');
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
        if (!Auth::guard('admin_user')->user()->can('suppliers'))
        {
            return view('admin.un-authorized');
        }

        $supplier_type = SupplierType::find($id);
        return view('admin.supplier_types.edit', compact('supplier_type'));
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
        if (!Auth::guard('admin_user')->user()->can('suppliers'))
        {
            return view('admin.un-authorized');
        }

        $supplier_type = SupplierType::find($id);
        $supplier_type->name = $request->name;
        $supplier_type->save();
        $user = Auth::guard('admin_user')->user();

        $supplier_type->adjustments()->attach($user->id, ['key' => "SupplierType", 'action' => "Edited", 'content_name' => $supplier_type->name]);
        return redirect('admin\supplier-types')->with('success', 'Supplier type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Auth::guard('admin_user')->user()->can('suppliers'))
        {
            return view('admin.un-authorized');
        }

        $supplier_type = SupplierType::find($id);
        if ($supplier_type)
            $user = Auth::guard('admin_user')->user();

        $supplier_type->adjustments()->attach($user->id, ['key' => "SupplierType", 'action' => "Deleted", 'content_name' => $supplier_type->name]);
        $supplier_type->delete();

        return 'success';
    }
}
