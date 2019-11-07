<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrdersItems extends Model
{
    	protected $table = 'purchase_orders_items';

       	protected $fillable = [ 'item_id','purchase_order_id','qty','item_rate'];

		// public function getProductRate($item_id){
		// 		if(!$item_id){
		// 			return null;
		// 		}
		// 	$product = Products::where('id',$item_id)->first();
		// 	$rate = $product->standard_rate;
		// 	return $rate;
		// }

}
