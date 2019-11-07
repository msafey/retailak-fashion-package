<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\CartItems;

class Cart extends Model
{
	protected $fillable=['user_id'];
    protected $table = "cart";


    public function CartItems(){
        return $this->hasMany(CartItems::class,'cart_id');
    }

    public function items(){
        return $this->hasMany(CartItems::class,'cart_id');
    }

}
