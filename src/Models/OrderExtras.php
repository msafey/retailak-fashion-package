<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderExtras extends Model
{
    protected $fillable = ['order_id','item_id','extra_id','qty','rate'];
}
