<?php

namespace App\Http\Controllers\pushApi;

use App\Http\PushNotificationBase;
use App\Http\Controllers\ApiController;
use App\Models\deviceToken;
use App\Models\Promocode;
use App\Models\PushNorification;
use App\Models\Products;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use phpDocumentor\Reflection\Types\Null_;
use Response;

class pushApiController extends ApiController
{
    public function addDeviceToken(Request $request)
    {

        $user_id = null;

        $device_os = app('request')->header('deviceOs');
        $app_version = request()->header('applicationVersion');
        $token = null;
        if (app('request')->header('token') != null) {
            $token = request()->header('token');
        } elseif (app('request')->header('Authorization') != null) {
            $token = request()->header('Authorization');
        }
        if ($token != null) {
            $user = User::where('token', $token)->first();
            if ($user) {

                $user_id = $user->id;
            }
        }
        if (!$request->has("deviceId")) {
            return $this->respondValidationErorr("Error", "device_id Is Required");
        } //in request body
        if (!$request->has("device_token")) {
            return $this->respondValidationErorr("Error", "device_Token Is Required");
        }

        $device_id = $request->input('deviceId');
        $device_token = $request->input('device_token');
        if ($request->has("deviceId") && $request->has("device_token")) {
            $createToken = deviceToken::updateOrCreate(
                ['device_id' => $device_id, 'device_os' => $device_os],
                ['device_token' => $device_token, 'app_version' => $app_version, 'user_id' => $user_id]);
            if (!$createToken) {
                return $this->respondInternalErorr();
            }
            return $this->respondCeated();

        }
    }

    public function showPushNotificationForm()
    {
        $promoCodes = Promocode::where('active', 1)->select('code')->distinct('code')->latest()->get();
        $products = products::where('active', 1)->latest()->get();
        return view('admin.push-notification.add', compact('promoCodes','products'));
    }

    public function getAppVersion()
    {
        $device_os = Input::get('device_os');

        $appVersions = deviceToken::whereNotNull('app_version')->whereDeviceOs($device_os)
            ->select('app_version')->distinct('app_version')->get();

        $data = view('admin.push-notification.app-version', compact('appVersions'))->render();
        return response()->json(['data' => $data]);

    }

    public function sendPushNotification(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'message' => 'required',
            'device_os' => 'required',
        ]);

        $title = $request->input('title');
        $message = $request->input('message');
        $deviceOs = $request->input('device_os');
        $appVersion = $request->input('app_version');
        $data = [
            'message' => $message,
        ];
        if ($request->has('promocode_id')) {
            $data['promocode_id'] = $request->input('promocode_id');
        }
        if ($request->has('product_id')) {
            $data['product_id'] = $request->input('product_id');
        }
        $tokens = DB::table('device_tokens')->where(function ($query) use ($deviceOs, $appVersion) {
            if ($deviceOs != "Both") {
                $query->where('device_os', '=', $deviceOs);
                if ($appVersion != "all") {
                    if ($appVersion == "others") {
                        $query->whereNull('app_version');
                    } else {
                        $query->where('app_version', '=', $appVersion);
                    }
                }
            }
        })->get();


        $push = new PushNorification;
        $push->push_title = $title;
        $push->push_message = $message;
        $push->push_os = $deviceOs;
        $push->push_time = Carbon::now();
        $push->save();


        $targetDevices = [];
        foreach ($tokens as $token) {
            $targetDevices[] = $token->device_token;
        }
        (new PushNotificationBase($targetDevices, $title, $message, $push->id, $data));

        return redirect('admin/notifications')->withSuccess('Push Notifcation Send Successfully');


    }

    public function showUsersPushNotificationForm()
    {

//        $users = deviceToken::where('device_id','!=',null);
        $users = User::join('device_tokens', 'users.device_id', 'device_tokens.device_id')->select('name', 'email', 'device_token')->get();
        $promoCodes = Promocode::where('active', 1)->select('code')->distinct('code')->latest()->get();
        $products = products::where('active', 1)->latest()->get();

        return view('admin.push-notification.send-to-user', compact('users', 'promoCodes','products'));
    }

    public function sendUsersPushNotification(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'message' => 'required',
            'device_token' => 'required',
        ]);

        $title = $request->input('title');
        $message = $request->input('message');
        $targetDevices = $request->input('device_token');
        $data = [
            'message' => $message,
        ];
        if ($request->has('promocode_id')) {
            $data['promocode_id'] = $request->input('promocode_id');
        }
        if ($request->has('product_id')) {
            $data['product_id'] = $request->input('product_id');
        }

        $push = new PushNorification;
        $push->push_title = $title;
        $push->push_message = $message;
        $push->push_os = " ";
        $push->push_time = Carbon::now();
        $push->save();


        (new PushNotificationBase($targetDevices, $title, $message, $push->id, $data));

        return redirect('admin/notifications')->withSuccess('Push Notifcation Send Successfully');


    }

    public function listPushNotifications()
    {
        $push_notifications = DB::table('push_notifications')->orderBy('created_at', 'DESC')->get();

        return view('admin.push-notification.list', compact('push_notifications'));

    }

}

