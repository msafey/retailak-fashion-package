<?php
namespace App\Models;
  use Response;
  use Log;

  use App\Events\ErrorEmail;
  class Pam{

  public function checkCurlError($curlChannel,$curlResult,$function)
    {
      //return $curlChannel;
      $curlStatus = curl_getinfo($curlChannel);
     // return $curlResult;

      //return  Response::json($curlStatus['http_code'],417);
      if($curlStatus['http_code']== 500)
      {
        //return  $curlStatus['http_code'];
        $string = ' ' . $curlResult;
        $ini = strpos($string, 'InternalError: (');
        if ($ini == 0) return '';
        $ini += strlen('InternalError:');
        $len = strpos($string, 'Home', $ini) - $ini;
        $err = substr($string, $ini, $len);
        event(new ErrorEmail($err));
        //return $err;
        return  1;


      }
      else if($curlStatus['http_code']!== 200)
      {
      	//$err = "Wrong Erpnext Url";
      	//$err = strip_tags($err);
          Log::useDailyFiles(storage_path() . '/logs/errors/debugUrl.log');
          Log::info(["LatestError" => $curlResult, "Time" => \Carbon\Carbon::now()]);

          $err ="Function : ".$function;
          $err .= "<br>Status Code: ".$curlStatus['http_code'];
          $err .= "<br>Response: ".$curlResult;
          if($curlStatus['http_code']!== 417 && $function!=='Customer Login')
          {
      	     event(new ErrorEmail($err));
             return 1;
          }

      }
      // else if($curlStatus['http_code']== 403)
      // {
      // 	$err = "Wrong Erpnext Account";
      // 	$err .="<br /> Function : <b>".$function."</b> <br />";
      // 	event(new ErrorEmail($curlResult));
      // 	return 1;
      // }
      return false;
    }


    public function restoreStocks($order){
    $user_id = $order->user_id;
    $user = User::where('id',intval($user_id))->first();
   if($user){
        $address = Addresses::where('user_id',$user->id)->first();
        if($address && isset($address->district_id)){
            $warehouses= Warehouses::all();
           foreach($warehouses as $warehouse){
                   if(in_array($address->district_id,json_decode($warehouse->district_id))){
                       $warehouse_id = $warehouse->id;
                   }
           }
        }
   }
    $order_items = $order->orderItems;
    foreach($order_items as $item){
        if(isset($warehouse_id)){
            $item_warehouse = ItemWarehouse::where('product_id',$item->item_id)->where('warehouse_id',$warehouse_id)->first();
            if($item_warehouse){
                $item_warehouse->projected_qty += $item->qty;
                $item_warehouse->save();

            }
        }
    }
}

  public function getDistrictBranch($token,$districtId = null)
    {
        if(is_null($districtId) || $districtId < 1)
        {
           $user = \App\User::where('token', '=', $token)->first();
                if($user)
                {
                    $address = Addresses::where('user_id',$user->id)->first();
                    if($address)
                    {
                        $districtId =$address->regoin;
                    }
                }
        }
        else
        {
            $district = District::find($districtId);
            // dd($district)
            // return $district;
            if($district){
                ;
            }
            else
            {
                $districtId =1;
            }
        }
        $branch     = null;
        $dbBranches = Warehouses::get();
        // return $dbBranches;
        foreach ($dbBranches as $dbBranch)
        {
            if (in_array($districtId, json_decode($dbBranch->district_id)))
            {
                $branch = $dbBranch;
            }
        }
        if(is_null($branch))
        {
            $districtId = 1;
            foreach ($dbBranches as $dbBranch)
            {
                if (in_array($districtId, json_decode($dbBranch->district_id)))
                $branch = $dbBranch;
            }
        }
        return $branch;
    }


}
