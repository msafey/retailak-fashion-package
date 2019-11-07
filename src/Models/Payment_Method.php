<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment_Method extends Model
{
    protected $table = "payment_method";

    protected $fillable = ['mode_of_payment', 'name','type','user_id'];


//
}
