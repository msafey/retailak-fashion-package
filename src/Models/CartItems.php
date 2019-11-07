<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\CartExtras;

class CartItems extends Model
{
    protected $fillable = ['item_id', 'qty', 'cart_id', 'key', 'note', 'price'];

    public function cartExtras()
    {
        return $this->hasMany(CartExtras::class, 'cart_item_id');
    }

    public function products()
    {
        return $this->belongsTo(Products::class, 'item_id');
    }

    ## :DD I don't know if I removed the old one will cause any problem or not
    public function product()
    {
        return $this->belongsTo(Products::class, 'item_id');
    }

}
