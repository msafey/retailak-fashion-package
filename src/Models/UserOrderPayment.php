<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOrderPayment extends Model
{

    protected $table = 'user_order_payments';

    protected $primaryKey = 'id';

    protected $fillable = ['user_id','order_id','sales_order_id','status'];

}
