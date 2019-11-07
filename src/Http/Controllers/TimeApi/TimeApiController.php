<?php

namespace App\Http\Controllers\TimeApi;

use App\Http\Controllers\Controller;
use App\Models\User;
use Response;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Models\Delivery_Man;
use App\Models\TimeSection;

class TimeApiController extends Controller
{



    public function TimeSections(Request $request)
    {
        try{

        $lang = getLang();
        $token = getTokenFromReq($request);

        if ($token != null) {
            $user = User::where('token', '=',$token)->first();
            if (empty($user)) {
                if ($lang == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get to Time-sections, Please login.'], 401);
                } else {
                    return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولا.'], 401);

                }
            } else {
                if ($lang == 'en') {

                    $time_sections = DB::table('time_sections')->select('name_en as name', 'from', 'to', 'status', 'id')->where('status','1')->get();
                } else {

                    $time_sections = DB::table('time_sections')->select('name', 'from', 'to', 'status', 'id')->where('status','1')->get();
                }
                return Response::json($time_sections, 200);

            }
        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
        }
         }catch (\Exception $e) {
          $error = array($e->getMessage(),$e->getFile(),$e->getLine());
          event(new ErrorEmail($error ));
          return $error;
      }


    }


}
