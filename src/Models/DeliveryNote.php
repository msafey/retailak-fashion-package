<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    protected $fillable=['order_id','productlist','date','shipping_role_id','user_id','delivery_note_id','delivery_order_id'];
}
