<?php

namespace App\Models;

use App\Delivery_Orders;
use Illuminate\Database\Eloquent\Model;

class Delivery_Man extends Model
{
    protected $table= "delivery__men";

    protected $fillable = ["name","password","mobile",'district_id','route'];

    public function deliveryOrders(){
    	return $this->hasMany(Delivery_Orders::class,'delivery_id');
    }



      public function adjustments(){
        return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
    }

}
