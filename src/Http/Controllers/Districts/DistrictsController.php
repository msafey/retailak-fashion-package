<?php

namespace App\Http\Controllers\Districts;


use App\Models\District;
use App\Models\Pam;
use App\Http\Controllers\Controller;
use App\Http\Requests\DistrictRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\utilitiesController;
use Validator;
use App\Models\ShippingRule;
use Response;
use Auth;
use Yajra\Datatables\Datatables;


class DistrictsController extends utilitiesController
{

    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('districts'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.districts.list');
    }

    public function districtsList()
    {
        if (!Auth::guard('admin_user')->user()->can('districts'))
        {
            return view('admin.un-authorized');
        }

        $districts = District::orderBy('id', 'DESC');
        return Datatables::of($districts)->make(true);
    }

    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('districts'))
        {
            return view('admin.un-authorized');
        }
        $parentDistricts = District::where('parent_id', 0)->get();
        $shippingrules = ShippingRule::where('disabled', 0)->get();

        return view('admin.districts.add', compact('parentDistricts', 'shippingrules'));
    }


    public function store(DistrictRequest $request)
    {
        if (!Auth::guard('admin_user')->user()->can('districts'))
        {
            return view('admin.un-authorized');
        }

        $district = new District;
        $district->parent_id = $request->parent_id;
        $district->district_ar = $request->district_ar;
        $district->district_en = $request->district_en;
        $district->shipping_role = $request->shipping_role;

        if (isset($shipping))
            $district->shipping_rate = $shipping;

        if ($request->active == "on")
            $district->active = true;
        else
            $district->active = false;
        $district->save();


        $sync_data = [];
        $from_weight = $request->from_weight;

        for ($i = 0; $i < count($from_weight); $i++) {
            $shipping_rule[$i] = isset($request->shipping_rule_id[$i]) ? $request->shipping_rule_id[$i] : $request->shipping_role;
            $sync_data[$shipping_rule[$i]] = ['from_weight' => $from_weight[$i], 'to_weight' => $request->to_weight[$i]];
        }

        $district->shipping()->sync($sync_data);

        $user = Auth::guard('admin_user')->user();

        $district->adjustments()->attach($user->id, ['key' => "District", 'action' => "Added", 'content_name' => $district->district_en]);


        return redirect('admin/districts')->with('success', 'District  Created Successfully');
    }

    public function edit(District $district)
    {
        if (!Auth::guard('admin_user')->user()->can('districts'))
        {
            return view('admin.un-authorized');
        }
        $parentDistricts = District::where('parent_id', 0)->get();
        $shippingrules = ShippingRule::where('disabled', 0)->get();
        return view('admin.districts.edit', compact('district', 'parentDistricts', 'shippingrules'));
    }

    public function update(DistrictRequest $request, $districtId)
    {
        if (!Auth::guard('admin_user')->user()->can('districts'))
        {
            return view('admin.un-authorized');
        }

        $data = request()->except(['_method', '_token']);
        $district = District::where('id', $districtId)->first();
        $district->update($data);


        $sync_data = [];
        $from_weight = $request->from_weight;

        for ($i = 0; $i < count($from_weight); $i++) {
            $shipping_rule[$i] = isset($request->shipping_rule_id[$i]) ? $request->shipping_rule_id[$i] : $request->shipping_role;
            $sync_data[$shipping_rule[$i]] = ['from_weight' => $from_weight[$i], 'to_weight' => $request->to_weight[$i]];
        }

        $district->shipping()->sync($sync_data);

        $user = Auth::guard('admin_user')->user();

        $district->adjustments()->attach($user->id, ['key' => "District", 'action' => "Added", 'content_name' => $district->district_en]);


        return redirect('admin/districts')->with('success', 'District  Updated Successfully');
    }


    public function status($districtId)
    {

        if (!Auth::guard('admin_user')->user()->can('districts'))
        {
            return view('admin.un-authorized');
        }
        $status = District::where('id', $districtId)->select('active')->first();
        if ($status->active == 0) {
            District::where('id', $districtId)->update(['active' => '1']);
            // return redirect('admin/districts')->with('success', 'District Activated Successfully');
        } else {
            District::where('id', $districtId)->update(['active' => '0']);
        }

        return 'success';
    }


}
