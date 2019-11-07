<?php

namespace App\Http\Controllers\Complains;

use App\Models\CustomerComplains;
use App\Http\Controllers\Controller;
use App\Http\Resources\ComplainsResource;
use App\Http\Controllers\ApiController;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Response;

class CustomerComplainsApiController extends ApiController
{

    public function applicationTypes(){
      $types = CustomerComplains::getPossibleTypes();
      return Response::json($types, 200);
    }
    public function storeComplainForm(Request $request)
    {
      $token = getTokenFromReq($request);
      $lang=getLang();
         if($token != null){
            $user = User::where('token', $token)->first();
            if (empty($user)) {
               if ($lang == 'en') {
                   return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get Complain , Please login.'], 401);
               } else {
                   return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولا.'], 401);

               }
            }
        }else{
          return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
        }
       if(!$request->has('message') && !$request->has('complain_type')){
        return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
       }
       $requestData=$request->only(['message','complain_type']);
       $complain = CustomerComplains::create(['application_type'=>$requestData['complain_type'],'message'=>$requestData['message'],'user_id'=> $user->id]);
       $complain->complain_type = $complain->application_type;
       unset($complain->application_type);

       if (!$complain) {
           return $this->respondInternalErorr();
       }else{
          $complain['ticket_id']=$complain->id;
          return Response::json($complain, 200);
       }
    }


     public function getComplain(){
        $token = null;
        $lang = getLang();
        if (app('request')->header('token') != null) {
            $token = request()->header('token');
        } elseif (app('request')->header('Authorization') != null) {
            $token = request()->header('Authorization');
        }

          if ($token != null) {
             $user = User::where('token', '=',$token)->first();
             if (empty($user)) {
                 if ($lang == 'en') {
                     return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get Complain , Please login.'], 401);
                 } else {
                     return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولا.'], 401);

                 }
             }
           }elseif (app('request')->header('Authorization') != null) {
              $token = request()->header('Authorization');
          }else{
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
          }

          $complains = CustomerComplains::where('user_id',$user->id)->OrderBy('id','Desc')->get();
         foreach($complains as $complain){
            $complain->complain_type = $complain->application_type;
             unset($complain->application_type);

         }

        return Response::json($complains, 200);
     }


     // public function sendEmail(Request $request){
        // $requestData= $request->only('fullname','email','message');
        // Mail::send(new JasadInfoMail($requestData));
     // }


}
