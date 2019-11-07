<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\utilitiesController;
use App\Models\ShopType;
use App\Models\StoreDetails;
use File;
use Carbon\Carbon;
use DB;
use Request;
use Response;
use Validator;
use App\Models\Pam;
use input;
use App\Models\Addresses;

//use Illuminate\Http\Request;

class StoreApiController extends utilitiesController
{
    public function getAllShopTypes(){
        $shop_types = ShopType::all();
        if(!$shop_types){
            $shop_types=[];
        }
         return Response::json($shop_types);
    }

    public function updateStoreDetails(\Illuminate\Http\Request $request){
         $lang = app('request')->header('lang');
         $token = getTokenFromReq($request);
         if($token != null){
             $user = \App\User::where('token', '=', $token)->first();
         }
         if(empty($user)) {
             return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to Edit Store Details, Please login.'], 401);
         }else {
            $shop_details = StoreDetails::where('user_id',$user->id)->first();
            $address = Addresses::where('user_id',$user->id)->first();
            $shop_detail['user_id']= $user->id;
            $shop_detail['id'] = $shop_details->id;
            $shop_detail['shop_address'] = $shop_details->store_address;
            $shop_detail['shop_type_id'] = $shop_details->shop_type_id;
            $shop_detail['shop_name'] = $shop_details->store_name;

            if($request->has('shop_type_id')){
                $shop_type    = $request->input('shop_type_id');
                $shop_detail['shop_type_id'] = $shop_type;
                $shop_details->shop_type_id  = $shop_type;
            }

            if($request->has('shop_address')){
                $shop_address    = $request->input('shop_address');
                $shop_details->store_address = $shop_address;
                $shop_detail['shop_address'] = $shop_address;
            }

            if($request->has('shop_name')){
                $shop_name    = $request->input('shop_name');
                $shop_details->store_name  = $shop_name;
                $shop_detail['shop_name'] = $shop_name;
            }

            if($request->has('tax_card')){
                $tax_card         = $request->input('tax_card');
                if(!is_base64($tax_card)){
                    return Response::json(['Status' => 'Error', 'message' => 'Tax Card is not base64'], 412);
                }
                $TaxCard = $shop_details->tax_card;
                if($TaxCard){
                    File::delete( public_path().'/imgs/store_details/'.$TaxCard);
                }
                $tax_card = storeImageBase64($tax_card,'store_details');
                $shop_details->tax_card = $tax_card;
                $shop_detail['tax_card']= url('/public/imgs/store_details/' . $shop_details->tax_card);
            }else{
                    $shop_detail['tax_card'] = isset($shop_details->tax_card) ? url('/public/imgs/store_details/' . $shop_details->tax_card) : null;
            }


           if($request->has('commercial_register')){
            $commercial_register = $request->input('commercial_register');
               if(!is_base64($commercial_register)){
                    return Response::json(['Status' => 'Error', 'message' => 'Commercial Register is not base64'], 412);
               }
               $CommercialRegister = $shop_details->commercial_register;
               if($CommercialRegister){
                   File::delete( public_path().'/imgs/store_details/'.$CommercialRegister);
               }
               $commercial_register = storeImageBase64($commercial_register,'store_details');
               $shop_details->commercial_register = $commercial_register;
               $shop_detail['commercial_register'] =url('/public/imgs/store_details/' . $shop_details->commercial_register);

           }else{
                $shop_detail['commercial_register'] =isset($shop_details->commercial_register) ? url('/public/imgs/store_details/' . $shop_details->commercial_register) : null;
           }
            $shop_details->save();

            if($address){
                if($request->has('district_id')){
                    $address->district_id = $request->district_id;
                }
                if($request->has('lat')){
                    $address->lat = $request->lat;
                }
                if($request->has('lng')){
                    $address->lng = $request->lng;
                }
                $address->street = $shop_detail['shop_address'];

                $address->save();
                $shop_detail['lat'] = $address->lat;
                $shop_detail['lng'] = $address->lng;
                $shop_detail['district_id'] = $address->district_id;
            }

        }
        return Response::json($shop_detail, 200);
    }





}
