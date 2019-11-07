<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    //
 protected $table = "product_bundles";
 protected $fillable= ['id' , 'bundel_id' , 'created_at' , 'updated_at'];

     public function bundel_product()
     {
         return $this->belongsTo('App\Products', 'bundel_id' , 'id');
     }
    public function items()
    {
        return $this->hasMany('App\BundleItem','parent_bundle_id','bundel_id');
    }

}
