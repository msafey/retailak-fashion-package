<?php

namespace App\Http\Controllers;



use App\Models\Pam;
use Carbon\Carbon;
use DB;


class utilitiesController extends Controller
{
    //

    public function GetCurlOptions($Channel,$type = null)
    {
        curl_setopt($Channel, CURLOPT_COOKIEFILE, COOKIE_FILE);
        curl_setopt($Channel, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($Channel, CURLOPT_AUTOREFERER, true );
        curl_setopt($Channel, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($Channel, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($Channel, CURLOPT_SSL_VERIFYPEER, 0);
        if($type == 'PUT')
        {
            curl_setopt($Channel, CURLOPT_CUSTOMREQUEST, "PUT");
        }

        return $Channel;
    }

    public function setGetDataCurlOptions($Channel)
    {
        curl_setopt($Channel, CURLOPT_COOKIEJAR, COOKIE_FILE);
        curl_setopt($Channel, CURLOPT_COOKIEFILE, COOKIE_FILE);
        curl_setopt($Channel, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($Channel, CURLOPT_AUTOREFERER, true );
        curl_setopt($Channel, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($Channel, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($Channel, CURLOPT_SSL_VERIFYPEER, 0);

        return $Channel;
    }

    public static function erpnextLogin($message)
    {
        DB::table('erpnext_login_logs')->insert(
            ['message' => "$message",'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]
        );
        $loginCh = curl_init(config('goomla.prodloginurl'));
        curl_setopt($loginCh, CURLOPT_POST, true);
        curl_setopt($loginCh, CURLOPT_POSTFIELDS, array(
            'usr' => config('goomla.produsername'),
            'pwd' => config('goomla.prodpassword'),
        ));
        curl_setopt($loginCh,CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($loginCh,CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($loginCh, CURLOPT_COOKIESESSION, true );
        curl_setopt($loginCh, CURLOPT_COOKIEJAR, COOKIE_FILE);
        curl_setopt($loginCh, CURLOPT_COOKIEFILE, COOKIE_FILE);
        curl_setopt($loginCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($loginCh, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($loginCh, CURLOPT_SSL_VERIFYPEER, 0);
        return curl_exec($loginCh);
    }




    public function getBin($encodeParameters , $numberOfRetries)
    {
        $getBinCh = curl_init(config('goomla.proderpurl') . 'resource/Bin/?' . $encodeParameters);
        curl_setopt($getBinCh, CURLOPT_COOKIEJAR, COOKIE_FILE);
        curl_setopt($getBinCh, CURLOPT_COOKIEFILE, COOKIE_FILE);
        curl_setopt($getBinCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($getBinCh, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($getBinCh, CURLOPT_SSL_VERIFYPEER, 0);

            $getBinResult = curl_exec($getBinCh);
            $curlStatus          = curl_getinfo($getBinCh);
            if (($curlStatus['http_code'] == 401 || $curlStatus['http_code'] == 403) && $numberOfRetries > 0) {
            $numberOfRetries -=1;
            static::erpnextLogin('getBin (utilitiesController)');
            $this->getBin($encodeParameters ,$numberOfRetries);
        } else {
            if (!isset($PAM)) {
                $PAM = new Pam;
            }

            if ($PAM->checkCurlError($getBinCh, $getBinResult, 'Bin Curl (get Bin Curl )') == 1) {
                return 'error';
            } else {
                curl_close($getBinCh);
                return $getBinResult;
            }
        }
    }

    //  public static function loginCurlRequest()
    // {
    //     $loginCh = curl_init(config('goomla.prodloginurl'));
    //     curl_setopt($loginCh, CURLOPT_POST, true);
    //     curl_setopt($loginCh, CURLOPT_POSTFIELDS, array(
    //         'usr' => config('goomla.produsername'),
    //         'pwd' => config('goomla.prodpassword'),
    //     ));
    //     curl_setopt($loginCh,CURLOPT_CUSTOMREQUEST, 'POST');
    //     curl_setopt($loginCh,CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    //     curl_setopt($loginCh, CURLOPT_COOKIESESSION, true );
    //     curl_setopt($loginCh, CURLOPT_COOKIEJAR, COOKIE_FILE);
    //     curl_setopt($loginCh, CURLOPT_COOKIEFILE, COOKIE_FILE);
    //     curl_setopt($loginCh, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($loginCh, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($loginCh, CURLOPT_SSL_VERIFYPEER, 0);
    //     return curl_exec($loginCh);
    // }
}
