<?php

namespace App\Http\Controllers\ResetPasswordApi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\utilitiesController;
use App\Reset_Passwrod;
use Illuminate\Http\Request;
use Mail;
use Response;
use Validator;
use DB;
use App\Models\PAM;
use Carbon\Carbon;


class ResetPasswordController extends utilitiesController
{
    public function __construct()
    {

        if(!defined("COOKIE_FILE"))
        {
            $path = storage_path('app/cookie.txt');
        define("COOKIE_FILE", $path);
        }


    }

    public function ShowResetPasswordForm($token)
    {
        $user = Reset_Passwrod::where('token', $token)->first();
        if ($user) {
            $email = $user->email;
            return view('admin.reset-password.reset_password', compact('user'));
        } else {
            return view('erorr');
        }


    }
    private function createUserErp($erpnextName,$password,$phone='',$location ='',$email,$numberOfRetries)
    {
        $userDataArray = array('name' => $erpnextName, 'first_name' => $erpnextName, 'change_password' => $password, 'email' => $email, 'phone' => $phone, 'location' => $location);

        $createUserCh =  curl_init(config('goomla.proderpurl') . "resource/User");
        curl_setopt($createUserCh, CURLOPT_COOKIEJAR, COOKIE_FILE);
        curl_setopt($createUserCh, CURLOPT_COOKIEFILE, COOKIE_FILE);
        curl_setopt($createUserCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($createUserCh, CURLOPT_POSTFIELDS, array('data' => json_encode($userDataArray)));

        $createUserResult = curl_exec($createUserCh);
        $curlStatus       = curl_getinfo($createUserCh);
        if (($curlStatus['http_code'] == 401 || $curlStatus['http_code'] == 403) && $numberOfRetries > 0) {
          $numberOfRetries-=1;
            static::erpnextLogin('createUserErp (Users Controller)');
            return $this->createUserErp($erpnextName,$password,$phone,$location,$email,$numberOfRetries);
        } else {
            if (!isset($PAM)) {
                $PAM = new Pam;
            }
            if ($PAM->checkCurlError($createUserCh, $createUserResult, 'Create Customer Curl') == 1) {
                return 'error';
            } else {
                curl_close($createUserCh);
                return $createUserResult;
            }
        }
    }
    private function getUserByEmail($email,$password){
         $checkUserCh = curl_init(config('goomla.proderpurl') . "resource/User/" . $email);
         $this->GetCurlOptions($checkUserCh);
         $customerLoginResult = curl_exec($checkUserCh);
         $curlStatus       = curl_getinfo($checkUserCh);
         if($curlStatus['http_code']== 404)
            {
                $user = DB::table('users')->where('email',$email)->first();
                if($user)
                {
                    $this->createUserErp($user->erpnext_name,$password,$phone='',$location ='',$email,4);
                    return 0;
                }
                return 0;

            }
            else
            {
                return 1;
            }

         //return $curlStatus['http_code'];

    }

     private function customerPasResetErpnext($password,$email, $numberOfRetries)
    {
        $userExists =  $this->getUserByEmail($email,$password);
        $customerLoginCh = curl_init(config('goomla.proderpurl') . "resource/User/" . $email);
       curl_setopt($customerLoginCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($customerLoginCh, CURLOPT_CUSTOMREQUEST, "PUT");
        // curl_setopt($ch10, CURLOPT_COOKIEJAR, COOKIE_FILE);
        curl_setopt($customerLoginCh, CURLOPT_COOKIEFILE, COOKIE_FILE);
        $arr2 = array('new_password' => $password, 'email' => $email);
        curl_setopt($customerLoginCh, CURLOPT_POSTFIELDS, array('data' => json_encode($arr2)));
        $customerLoginResult = curl_exec($customerLoginCh);
        $curlStatus       = curl_getinfo($customerLoginCh);



        if (($curlStatus['http_code'] == 401 || $curlStatus['http_code'] == 403) && $numberOfRetries > 0) {

          $numberOfRetries-=1;
            static::erpnextLogin('customerForgetPasswordErpnext');
            return $this->customerPasResetErpnext($password,$email, $numberOfRetries);
        } else {
            if (!isset($PAM)) {
                $PAM = new Pam;
            }
            if ($PAM->checkCurlError($customerLoginCh, $customerLoginResult, 'Customer Login') == 1) {
                return 'error';
            } else {
                curl_close($customerLoginCh);
                return $customerLoginResult;
            }
        }
    }

    public function ResetPassword(Request $request)
    {
        $currentx = new Carbon();

        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
        ]);

        $password = $request->input('password');
        $token = $request->input('token');
        $user = Reset_Passwrod::where('token', $token)->first();
        $email = $user->email;
        $expired_token = $user->token_expired;



//dd(date('d/M/Y H:i:s', $expired_token));

        // static::erpnextLogin('Update User Password (ResetPasswordController)');

        // $ch10 = curl_init(config('goomla.proderpurl') . "resource/User/" . $email);
        // curl_setopt($ch10, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch10, CURLOPT_CUSTOMREQUEST, "PUT");
        // // curl_setopt($ch10, CURLOPT_COOKIEJAR, COOKIE_FILE);
        // curl_setopt($ch10, CURLOPT_COOKIEFILE, COOKIE_FILE);
        // $arr2 = array('new_password' => $password, 'email' => $email);
        // curl_setopt($ch10, CURLOPT_POSTFIELDS, array('data' => json_encode($arr2)));
        $result2 = $this->customerPasResetErpnext($password,$email, 4);
        $new_password = $password;

        $data = array('email' => $email, 'new_password' => $new_password,
            'from' => 'info@retailak.com',
            'from_name' => 'Retailak');
        $userExists = DB::table('users')->where('email',$email)->first();
        if($userExists)
        Mail::send('admin.reset-password.send-pass-email', $data, function ($message) use ($data) {
            $message->to($data['email'])->from($data['from'], $data['from_name'])->subject('Khotwh Password Reset');
        });

//        dd($currentx);

//        $result = date_diff($currentx, $expired_token);
//        if (($result->i) >= 30) {
            Reset_Passwrod::where('email', $email)->update(['token' => md5(rand(111, 999))]);
//        }
        return view('admin.reset-password.after-reset_password');
    }


}
