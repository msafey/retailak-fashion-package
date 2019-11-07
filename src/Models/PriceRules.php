<?php

namespace App\Models;

use App\Products;
use Illuminate\Database\Eloquent\Model;

class PriceRules extends Model
{
    protected $fillable = ['price_rule_name', 'min_price' , 'max_price',
        'valid_from','valid_to','discount_type','discount_rate', 'product_id',
        'min_qty','max_qty','item_price_id'];


    public function product()
    {
        return $this->belongsTo(Products::class,'product_id');
    }

    public function itemPrice()
    {
        return $this->belongsTo(ItemPrice::class, 'item_price_id');
    }
}
