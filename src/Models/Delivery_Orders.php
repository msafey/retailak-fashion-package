<?php

namespace App\Models;

use App\RunsheetOrders;
use Illuminate\Database\Eloquent\Model;

class Delivery_Orders extends Model
{

    protected $table = "delivery__orders";

    protected $fillable = ['delivery_id', 'time_section_id', 'orders_id'];

    public function createRunsheetOrders($order_id,$delivery_order_id)
    {
        $attributes = ['order_id' => $order_id,'delivery_order_id' => $delivery_order_id];
        if (!$this->runsheetOrders()->where($attributes)->exists()) {
            $order = $this->runsheetOrders()->create($attributes);
            return $order;
        }
    }


    public function adjustments(){
        return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
    }



    public function runsheetOrders(){
        return $this->hasMany(RunsheetOrders::class,'delivery_order_id');
    }



}
