<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BundleItem extends Model
{
    //
 protected $table = "product_bundle_items";
 protected $fillable = ['id' , 'item_id' , 'parent_bundle_id' ,'qty' ,'created_at' , 'updated_at'];


 public function product()
 {
     return $this->belongsTo('App\Products' , 'item_id' ,'id');
 }
}
