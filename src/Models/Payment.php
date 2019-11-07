<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $table = 'payments';

    protected $fillable = ['invoice_id', 'payment_mode_id', 'reference', 'posting_date','status','warehouse_id'];

    public function path()
    {
        return 'admin/payments';
    }
    public function invoice()
    {
        return $this->hasOne(PurchaseInvoice::class , 'invoice_id');
    }

}
