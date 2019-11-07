<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\OrderExtras;

class OrderItems extends Model
{
    protected $fillable = ['item_id', 'rate', 'qty', 'order_id', 'item_name', 'returned'];

    public function orderExtras()
    {
        return $this->hasMany(OrderExtras::class, 'order_item_id');
    }

    public function OrderExtra($order_item_id, $item_id, $extra_id, $qty, $rate)
    {
        $attributes = ['order_item_id' => $order_item_id, 'item_id' => $item_id, 'qty' => $qty, 'rate' => $rate, 'extra_id' => $extra_id];
        if (!$this->OrderExtras()->where($attributes)->exists()) {
            $order = $this->OrderExtras()->create($attributes);
            return $order;
        }
    }


    public function product()
    {
        return $this->belongsTo(Products::class, 'item_id');
    }


}
