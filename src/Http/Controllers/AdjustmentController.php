<?php

namespace App\Http\Controllers;

use App\Models\Adjustments;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;


class AdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function activities()
    {
        if (!Auth::guard('admin_user')->user()->can('activities'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.adjustments.activities');
    }

    public function activitiesList()
    {
        if (!Auth::guard('admin_user')->user()->can('activities'))
        {
            return view('admin.un-authorized');
        }

        $dbActivities = DB::table('adjustments')->orderBy('created_at', 'desc')->get();
        $dbUsersArray = DB::table('admin_users')->select('id', 'name')->get()->toArray();
        $users = [];
        foreach ($dbUsersArray as $dbUser) {
            $users[$dbUser->id] = $dbUser->name;
        }
        $activities = [];
        $activitiesCounter = 0;
        foreach ($dbActivities as $dbActivity) {
            $activities[$activitiesCounter]['user'] = $users[$dbActivity->user_id];
            $activities[$activitiesCounter]['action'] = $dbActivity->action;
            $activities[$activitiesCounter]['type'] = $dbActivity->key;
            $activities[$activitiesCounter]['id'] = $dbActivity->content_id;
            $activities[$activitiesCounter]['content_name'] = $dbActivity->content_name;
            $activities[$activitiesCounter]['time'] = $dbActivity->created_at;
            $activitiesCounter++;

        }

        $collection = collect($activities);

        return Datatables::of($collection)->make(true);
    }

}
