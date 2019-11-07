<?php

namespace App\Http\Controllers\DeliveryApi;

use App\Models\Delivery_Man;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class DeliveryApiController extends Controller
{

    public function login(Request $request)
    {
        if ($request->input('password') != null && $request->input('mobile') != null) {
            $password = md5($request->input('password'));

            $mobile = $request->input('mobile');

            $exist = DB::table('delivery__men')->where('mobile', $mobile)->where('password', $password)
                ->select('name', 'mobile', 'token')->first();
            if ($exist) {
                return Response::json($exist, 200);
            } else {
                return Response::json(['Status' => 'Erorr', 'message' => 'Your Phone Or Password is not corectly.'], 401);

            }
        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);

        }


    }

    public function changePassword(Request $request)
    {
        if (app('request')->header('token') != null) {
            $password = $request->input('password');
            $newpassword = $request->input('newpassword');

//            dd(md5($password));
            $delivery_man = Delivery_Man::where('token', '=', app('request')->header('token'))->first();

            if (empty($delivery_man)) {
                return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get to Delivered Order, Please login.'], 401);
            } else {
                $pass_update =  DB::table('delivery__men')->where('password',md5($password))->update(['password' =>md5($newpassword)]);
                if ($pass_update) {
                    return Response::json(['Status' => 'Success', 'message' => 'Your Password Changed Successfully'], 200);
                } else {
                    return Response::json(['Status' => 'Erorr', 'message' => 'Your Password is not Correct'], 401);
                }
            }
        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
        }
    }


}
