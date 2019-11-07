<?php

namespace App\Http\Controllers\Branches;

use App\Models\Branch;
use App\Models\Branches;
use App\Models\District;
use App\Http\Controllers\Controller;
use App\Http\Controllers\utilitiesController;
use App\Http\Requests\BranchRequest;
use App\Warehouses;
use Illuminate\Support\Facades\DB;
use Response;
use Validator;
use Yajra\Datatables\Datatables;


class BranchesController extends utilitiesController
{
    public function __construct()
    {
        $path = storage_path('app/cookie.txt');
        if(!defined("COOKIE_FILE"))
        define("COOKIE_FILE", $path);
    }

    public function index()
    {
        return view('admin.branches.list');
    }

    public function branchesList()
    {
        $branches = Branch::orderBy('id', 'DESC')->get();
        foreach($branches as $branch){
            $warehouse = Warehouses::where('id',$branch['warehouse_id'])->first();
            if($warehouse){
                $branch['warehouse_name']= $warehouse->name;
            }else{
                $branch['warehouse_name']='---';
            }
        }
        return Datatables::of($branches)->make(true);
    }

    public function create()
    {
       // dd($this->getAllWarehouses()) ;
        // $result = $this->getAllWarehouses();

        // $warehouses = $result->data;
        $warehouses = Warehouses::where('status',1)->get();
        $districts = District::all();
        return view('admin.branches.add', compact('warehouses', 'districts'));
    }

    public function getAllWarehouses()
    {

        static::erpnextLogin('getAllWarehouses (BranchesController)');

        $ch1 = curl_init(config('goomla.proderpurl') . 'resource/Warehouse');
        curl_setopt($ch1, CURLOPT_COOKIEJAR, COOKIE_FILE);
        curl_setopt($ch1, CURLOPT_COOKIEFILE, COOKIE_FILE);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
        $res = curl_exec($ch1);
        if ($res === false) {
            return 'Curl error: ' . curl_error($ch1);
        }
        return json_decode($res);

    }

    public function store(BranchRequest $request)
    {
        //Branch::create($request->all());
        $branch = new Branch;
        $branch->branch_name = $request->input('branch_name');
        // $branch->warehouse_name = $request->input('warehouse_name');
        $branch->warehouse_id = $request->input('warehouse_id');
        $branch->district_id = json_encode($request->input('district_id'));
        $branch->save();
        return redirect('admin/branches')->with('success', 'Branch  Created Successfully');
    }

    public function edit(Branch $branch)
    {
        // $result = $this->getAllWarehouses();
        // $warehouses = $result->data;
        $warehouses = Warehouses::where('status',1)->get();
        $districts = District::all();
        $selectedDistricts = json_decode($branch->district_id);
        return view('admin.branches.edit', compact('branch', 'warehouses', 'districts','selectedDistricts'));
    }

    public function update(BranchRequest $request, $branchId)
    {
        $data = request()->except(['_method', '_token']);
        $branch = Branch::find($branchId);
        $branch->branch_name = $request->input('branch_name');
        // $branch->warehouse_name = $request->input('warehouse_name');
        $branch->warehouse_id = $request->input('warehouse_id');
        $branch->district_id = json_encode($request->input('district_id'));
        $branch->save();
        //Branch::where('id', $branchId)->update($data);
        return redirect('admin/branches')->with('success', 'Branch  Updated Successfully');
    }

//    public function destroy($branchId)
//    {
//        Branch::where('id', $branchId)->delete();
//        return redirect('admin/branches')->with('success', 'Branch  Deleted Successfully');
//    }

    public function status($branchId)
    {

        $status = Branch::where('id', $branchId)->select('status')->first();
        if ($status->status == 0) {
            Branch::where('id', $branchId)->update(['status' => '1']);
            return redirect('admin/branches')->with('success', 'Branch Activated Successfully');
        }
        Branch::where('id', $branchId)->update(['status' => '0']);
        return redirect('admin/branches')->with('success', 'Branch  Disabled Successfully');


    }
}
