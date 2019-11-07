<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Input;
use App\Models\Settings;
use Response;

class SettingsController extends Controller
{
    //
    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('settings'))
        {
            return view('admin.un-authorized');
        }

        $settings = \App\Settings::find(1);
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('settings'))
        {
            return view('admin.un-authorized');
        }

        $settings = Settings::find(1);

        $settings->max_amount = $request->input('max_amount');
        $settings->min_amount = $request->input('min_amount');
        $settings->note_ar = $request->input('note_ar');
        $settings->note_en = $request->input('note_en');
        $settings->expiration_days = $request->input('expiration_days');
        if($request->has('freeshipping')){
            $settings->free_shipping = $request->input('freeshipping');
            $settings->applied_amount = $request->input('applied_amount');
        }else{
            $settings->free_shipping = 0;
            $settings->applied_amount = null;
        }

        $settings->save();
        return redirect('/admin/settings')->withSuccess('Settings Updated');
    }

    public function configrations(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('settings'))
        {
            return view('admin.un-authorized');
        }

        $config = Settings::find(1);
        $lang = app('request')->header('lang');

        if ($lang == 'en') {
            $configarray = array('min_amount' => $config->min_amount, 'max_amount' => $config->max_amount, 'note' => $config->note_en);

        } else {
            $configarray = array('min_amount' => $config->min_amount, 'max_amount' => $config->max_amount, 'note' => $config->note_ar);

        }
        return Response::json($configarray, 200);

    }
}
