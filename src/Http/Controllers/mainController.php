<?php

namespace App\Http\Controllers;

use App\Events\ErrorEmail;
use App\Models\Promocode;
use App\Models\UsedPromocode;
use DB;
use Log;
use Request;
use Response;
use App\Models\Addresses;
use App\Models\Branch;
use App\Models\Bundle;
use App\Models\BundleItem;
use App\Models\Cart;
use App\Models\District;
use App\Models\ItemWarehouse;
use App\Models\Pam;
use App\Models\Products;
use \Carbon\Carbon;
use App\Http\Controllers\CategoryController;

class mainController extends Controller
{


    //Erpnext Login function
    public static function loginCurlRequest()
    {
        $loginCh = curl_init(config('goomla.prodloginurl'));
        curl_setopt($loginCh, CURLOPT_POST, true);
        curl_setopt($loginCh, CURLOPT_POSTFIELDS, array(
            'usr' => config('goomla.produsername'),
            'pwd' => config('goomla.prodpassword'),
        ));
        curl_setopt($loginCh,CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($loginCh,CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($loginCh, CURLOPT_COOKIEJAR, COOKIE_FILE);
        curl_setopt($loginCh, CURLOPT_COOKIEFILE, COOKIE_FILE);
        curl_setopt($loginCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($loginCh, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($loginCh, CURLOPT_SSL_VERIFYPEER, 0);
        curl_exec($loginCh);
    }

    //setting curl options for getting data
    private function setGetDataCurlOptions($Channel)
    {
        curl_setopt($Channel, CURLOPT_COOKIEJAR, COOKIE_FILE);
        curl_setopt($Channel, CURLOPT_COOKIEFILE, COOKIE_FILE);
        curl_setopt($Channel, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Channel, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($Channel, CURLOPT_SSL_VERIFYPEER, 0);
    }



    public function getDistrictBranch($token,$districtId = null)
    {
      if(is_null($districtId) || $districtId < 1 || empty(District::find($districtId))){
        dd(23);
        if(empty($district)){ // if district doesnt exist in database get user's default district
          $user = \App\User::where('token', '=', $token)->first();
          if(empty($user)){   // if user doesnt exist get the default district
            $districtId =District::first()->id;
          }
          else
          {
            $address = Addresses::where('user_id',$user->id)->first();
            if(empty($address)){ // if user doesnt have an address assign the default district
              $districtId =District::first()->id;;
            }
            else{
              $districtId =$address->regoin;
            }
          }
        }
      }

      $branch     = null;
        $dbBranches = Branch::get();
        foreach ($dbBranches as $dbBranch)
        {
            if (in_array($districtId, json_decode($dbBranch->district_id)))
            {
                $branch = $dbBranch;
                //dd($branch);
                break;

            }
        }
        dd($branch);
        return $branch;
    }

}
