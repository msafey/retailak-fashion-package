<?php

namespace App\Http\Controllers\Shipping;

use App\Http\Controllers\Controller;
use App\Models\ShippingRule;
use Auth;
use DB;
use Illuminate\Http\Request;
use Response;
use Yajra\Datatables\Datatables;

class ShippingRuleController extends Controller
{

    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('shipping_rules'))
        {
            return view('admin.un-authorized');
        }
        return view('admin.shipping_rules.index');
    }


    public function shippingrulesList()
    {
        if (!Auth::guard('admin_user')->user()->can('shipping_rules'))
        {
            return view('admin.un-authorized');
        }

        $shipping_rules = ShippingRule::all();
        return Datatables::of($shipping_rules)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('shipping_rules'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.shipping_rules.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('shipping_rules'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($request) {

            $shipping_rule = new ShippingRule;
            $shipping_rule->shipping_rule_label = $request->shipping_rule_label;
            $shipping_rule->key = $request->key;
            $shipping_rule->rate = $request->rate;
            $shipping_rule->calculate_based_on = $request->calculate_based_on;
            if ($request->has('disabled')) {
                $shipping_rule->disabled = $request->disabled;
            } else {
                $shipping_rule->disabled = 0;
            }
            $shipping_rule->save();
            $user = Auth::guard('admin_user')->user();

            $shipping_rule->adjustments()->attach($user->id, ['key' => "ShippingRule", 'action' => "Added", 'content_name' => $shipping_rule->shipping_rule_label]);


            return redirect('admin/shipping-rules')->with('success', 'Shipping Rule Created Successfully');

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
        if (!Auth::guard('admin_user')->user()->can('shipping_rules'))
        {
            return view('admin.un-authorized');
        }
        $shipping_rule = ShippingRule::findOrFail($id);
        return view('/admin/shipping_rules/edit', compact('shipping_rule'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('shipping_rules'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($request, $id) {

            $shipping_rule = ShippingRule::find($id);
            $shipping_rule->shipping_rule_label = $request->shipping_rule_label;
            $shipping_rule->key = $request->key;
            $shipping_rule->rate = $request->rate;
            $shipping_rule->calculate_based_on = $request->calculate_based_on;
            if ($request->has('disabled')) {
                $shipping_rule->disabled = $request->disabled;
            } else {
                $shipping_rule->disabled = 0;
            }
            $shipping_rule->save();
            $user = Auth::guard('admin_user')->user();

            $shipping_rule->adjustments()->attach($user->id, ['key' => "ShippingRule", 'action' => "Edited", 'content_name' => $shipping_rule->shipping_rule_label]);


            return redirect('admin/shipping-rules')->with('success', 'Shipping Rule Updated Successfully');
        });

    }


    public function Status($id)
    {

        if (!Auth::guard('admin_user')->user()->can('shipping_rules'))
        {
            return view('admin.un-authorized');
        }
        $status = ShippingRule::where('id', $id)->select('disabled')->first();
        if ($status->disabled == 1) {
            ShippingRule::where('id', $id)->update(['disabled' => '0']);
            return redirect('admin/shipping-rules')->with('Shipping Rule  Activated Successfully');

        } else {
            ShippingRule::where('id', $id)->update(['disabled' => '1']);
            return redirect('admin/shipping-rules')->with('success', 'Shipping Rule   De-Activated Successfully');

        }
    }


}
