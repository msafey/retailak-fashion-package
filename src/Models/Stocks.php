<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{

    protected $table = 'stocks';
    protected $fillable = ['product_id','supplier_id','warehouse_id','stock_qty','status','price'];

       public function path($stock)
    {
        return 'admin/products/'.$stock.'/manage';
    }

}
