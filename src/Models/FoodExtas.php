<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class FoodExtas extends Model
{
    protected $fillable = ['extra_product_id','food_extra_price','is_optional','related_product_id','user_deleted','is_deleted'];
    // use SoftDeletes;
}
