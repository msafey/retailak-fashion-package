<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderPayment extends Model
{
    protected $fillable =['order_id','sales_invoice_id','payment_mode_id','date','paid_amount','unallocated_amount','final_total_amount','final_total_amount_after_discount','status'];

}
