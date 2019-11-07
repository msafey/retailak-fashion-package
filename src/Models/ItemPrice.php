<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPrice extends Model
{
    protected $fillable=['price_list_id','product_id','rate'];

    public function priceList()
    {
        return $this->belongsTo(PriceList::class,'price_list_id');
    }

    public function priceRule()
    {
        return $this->hasOne(PriceRules::class, 'item_price_id');
    }
}
