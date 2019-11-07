<?php

namespace App\Http\Controllers;

use App\Models\Taxs;
use DB;
use Illuminate\Http\Request;
use Response;
use Yajra\Datatables\Datatables;
use Auth;

class TaxsController extends Controller
{

    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('taxs'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.taxs.index');
    }


    public function taxList()
    {
        if (!Auth::guard('admin_user')->user()->can('taxs'))
        {
            return view('admin.un-authorized');
        }

        $taxs = Taxs::all();
        foreach ($taxs as $tax) {
            if ($tax['type'] == 'Actual') {
                $tax['rate'] = '--';
            } else {
                $tax['amount'] = '--';
            }
        }
        return Datatables::of($taxs)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('taxs'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.taxs.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('taxs'))
        {
            return view('admin.un-authorized');
        }
        $tax = new Taxs;
        if (!$request->status) {
            $request->status = 0;
        }
        if ($request->type == 'Actual') {
            $tax->rate = 0;
            $tax->amount = $request->amount;
        } else {
            $tax->rate = $request->rate;
            $tax->amount = 0;
        }
        $tax->title = $request->title;
        $tax->type = $request->type;
        $tax->status = $request->status;
        $tax->save();
        $user = Auth::guard('admin_user')->user();

        $tax->adjustments()->attach($user->id, ['key' => "Tax", 'action' => "Added", 'content_name' => $tax->title]);

        return redirect('admin/taxs')->with('success', 'Tax Created Successfully');


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
        if (!Auth::guard('admin_user')->user()->can('taxs'))
        {
            return view('admin.un-authorized');
        }

        $tax = Taxs::findOrFail($id);
        return view('/admin/taxs/edit', compact('tax'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('taxs'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($request, $id) {

            $tax = Taxs::find($id);
            if (!$request->status) {
                $request->status = 0;
            }
            if ($request->type == 'Actual') {
                $tax->rate = 0;
                $tax->amount = $request->amount;
            } else {
                $tax->rate = $request->rate;
                $tax->amount = 0;
            }
            $tax->title = $request->title;
            $tax->type = $request->type;
            $tax->status = $request->status;
            $tax->save();

            $user = Auth::guard('admin_user')->user();

            $tax->adjustments()->attach($user->id, ['key' => "Tax", 'action' => "Edited", 'content_name' => $tax->title]);


            return redirect('admin/taxs')->with('success', 'Tax Updated Successfully');
        });

    }


    public function Status($id)
    {
        if (!Auth::guard('admin_user')->user()->can('taxs'))
        {
            return view('admin.un-authorized');
        }

        $status = Taxs::where('id', $id)->select('status')->first();
        if ($status->status == 1) {
            Taxs::where('id', $id)->update(['status' => '0']);
            return redirect('admin/taxs')->with('Tax  De-Activated Successfully');

        } else {
            Taxs::where('id', $id)->update(['status' => '1']);
            return redirect('admin/taxs')->with('success', 'Activated Successfully');

        }
    }

}
