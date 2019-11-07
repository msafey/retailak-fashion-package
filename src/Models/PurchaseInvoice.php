<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
     protected $table = 'purchase_invoices';
    protected $fillable = ['purchased_item_details','status','grand_total_amount','purchase_receipt_id'];

       public function path($purchase_receipt_id)
    {
        return 'admin/purchase-receipt/'.$purchase_receipt_id.'/invoice';
    }
}
