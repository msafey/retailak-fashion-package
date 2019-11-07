<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReceipts extends Model
{
    protected $fillable = ['accepted_qty','grand_total_amount','status','purchase_order_id'];

       public function path($purchase_order_id)
    {
        return 'admin/purchase-orders/'.$purchase_order_id.'/purchase-receipts';
    }

}
