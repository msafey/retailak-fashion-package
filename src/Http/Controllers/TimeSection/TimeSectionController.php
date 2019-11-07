<?php

namespace App\Http\Controllers\TimeSection;

use App\Http\Controllers\Controller;
use App\Http\Requests\TimeSectionRequest;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Auth;
use Illuminate\Http\Request;

use App\Models\TimeSection;

class TimeSectionController extends Controller
{
    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('time')) {
            return view('admin.un-authorized');
        }

        return view('admin/time-section/list');

    }

    public function timelist()
    {
        if (!Auth::guard('admin_user')->user()->can('time'))
        {
            return view('admin.un-authorized');
        }

        $active = 0;
        $inactive = 0;
        if (isset($_GET['active'])) {
            $active = $_GET['active'];
        }
        if (isset($_GET['inactive'])) {
            $inactive = $_GET['inactive'];
        }

        if ($active == 1 && $inactive == 1) {
            $time_section = DB::table('time_sections')->orderBy('id', 'DESC')->get();

        } elseif ($active == 1 && $inactive == 0) {
            $time_section = DB::table('time_sections')->where('status', 1)->orderBy('id', 'DESC')->get();

        } elseif ($active == 0 && $inactive == 1) {

            $time_section = DB::table('time_sections')->where('status', 0)->orderBy('id', 'DESC')->get();

        } else {

            $time_section = DB::table('time_sections')->orderBy('id', 'DESC')->get();
        }
        return Datatables::of($time_section)->make(true);
    }

    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('time'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.time-section.add');
    }


    public function store(TimeSectionRequest $request)
    {
        if (!Auth::guard('admin_user')->user()->can('time'))
        {
            return view('admin.un-authorized');
        }

        $time_section = new  TimeSection;
        $time_section->name = $request->input('name');
        $time_section->name_en = $request->input('name_en');
        $time_section->from = $request->input('from');
        $time_section->to = $request->input('to');
        $time_section->save();
        $user = Auth::guard('admin_user')->user();

        $time_section->adjustments()->attach($user->id, ['key' => "TimeSection", 'action' => "Added", 'content_name' => $time_section->name]);

        return redirect('admin/time/section')->with('Time Section  Created Successfully');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (!Auth::guard('admin_user')->user()->can('time'))
        {
            return view('admin.un-authorized');
        }

        $time_section = TimeSection::findOrFail($id);
        return view('/admin/time-section/edit', compact('time_section'));
    }

    public function update(TimeSectionRequest $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('time'))
        {
            return view('admin.un-authorized');
        }

        $time_section = TimeSection::findOrFail($id);
        $time_section->name = $request->input('name');
        $time_section->name_en = $request->input('name_en');
        $time_section->from = $request->input('from');
        $time_section->to = $request->input('to');
        $time_section->save();
        $user = Auth::guard('admin_user')->user();

        $time_section->adjustments()->attach($user->id, ['key' => "TimeSection", 'action' => "Edited", 'content_name' => $time_section->name]);

        return redirect('admin/time/section')->with('success', 'Time Section Updated Successfully');

    }

    public function Status($id)
    {
        if (!Auth::guard('admin_user')->user()->can('time'))
        {
            return view('admin.un-authorized');
        }

        $password = md5(rand(000, 9999));

        $status = TimeSection::where('id', $id)->select('status')->first();
        if ($status->status == 0) {
            TimeSection::where('id', $id)->update(['status' => '1']);
            return 'success';

        } else {
            TimeSection::where('id', $id)->update(['status' => '0']);
            return 'success';

        }
    }

}
