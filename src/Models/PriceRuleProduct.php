<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceRuleProduct extends Model
{
    protected $table = 'price_rule_product';
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function types()
    {
        return $this->hasMany(TypePriceRuleProduct::class, 'price_rule_product_id');
    }
}
