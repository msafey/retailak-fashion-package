<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    protected $fillable = ['currency_code','price_list_name','type'];


    public function item()
    {
        return $this->belongsTo(Products::class,'product_id');
    }
}
