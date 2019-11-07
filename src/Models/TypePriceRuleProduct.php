<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypePriceRuleProduct extends Model
{
    protected $table = 'type_price_rule_product';
    protected $guarded = ['id'];

    public function rule()
    {
        return $this->belongsTo(PriceRuleProduct::class);
    }
}
