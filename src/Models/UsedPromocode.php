<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsedPromocode extends Model
{
    protected $fillable = ['code','discount_rate','order_id','user_id'];
}
