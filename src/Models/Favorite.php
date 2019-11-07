<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = "favorite";

    protected $fillable = ['product_name', 'product_code','user_id'];
}
