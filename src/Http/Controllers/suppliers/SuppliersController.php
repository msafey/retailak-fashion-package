<?php

namespace App\Http\Controllers\suppliers;

use App\Http\Controllers\Controller;
use App\Models\Suppliers;
Use DB;
use App\Models\SupplierType;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;

class SuppliersController extends Controller
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

        return view('admin.suppliers.index');
    }


    public function suppliersList()
    {
        if (!Auth::guard('admin_user')->user()->can('suppliers'))
        {
            return view('admin.un-authorized');
        }

        $suppliers = Suppliers::all();

        foreach ($suppliers as $sup) {
            $supplier_type = $sup->supplierType()->first();
            if ($supplier_type) {
                $sup['supplier_type'] = $supplier_type->name;
            } else {
                $sup['supplier_type'] = '';
            }

        }
        return Datatables::of($suppliers)->make(true);
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

        $supplier_types = SupplierType::all();
        return view('admin.suppliers.add', compact('supplier_types'));
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

        return DB::transaction(function () use ($request) {

            $supplier = new Suppliers;
            $supplier->name = $request->name;
            $supplier->supplier_type_id = $request->supplier_type_id;
            $supplier->save();

            $user = Auth::guard('admin_user')->user();

            $supplier->adjustments()->attach($user->id, ['key' => "Supplier", 'action' => "Added", 'content_name' => $supplier->name]);
            return redirect('admin/suppliers')->with('success', 'Supplier Created Successfully');
        });
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

        $supplier = Suppliers::find($id);
        $supplier_types = SupplierType::all();

        return view('admin.suppliers.edit', compact('supplier', 'supplier_types'));
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

        $supplier = Suppliers::find($id);
        // dd($supplier)
        $supplier->name = $request->name;
        $supplier->supplier_type_id = $request->supplier_type_id;
        $supplier->save();
        $user = Auth::guard('admin_user')->user();

        $supplier->adjustments()->attach($user->id, ['key' => "Supplier", 'action' => "Edited", 'content_name' => $supplier->name]);
        return redirect('admin/suppliers')->with('success', 'Supplier Updated Successfully');

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

        $supplier = Suppliers::find($id);
        if ($supplier)
            $user = Auth::guard('admin_user')->user();

        $supplier->adjustments()->attach($user->id, ['key' => "Supplier", 'action' => "Deleted", 'content_name' => $supplier->name]);
        $supplier->delete();

        return 'success';
    }
}
