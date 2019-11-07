<?php

namespace App\Models;

use App\PurchaseReceipts;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrders extends Model
{
 protected $table = 'purchase_orders';
    protected $fillable = [ 'company_id','required_by_date','tax_and_charges','status','shipping_rule','grand_total_amount','discount','discount_type'];




    public function purchaseOrderItems(){
         return $this->hasMany(PurchaseOrdersItems::class,'purchase_order_id');
    }

    public function submittedReceipts() {
           return $this->purchaseReceipt()->where('status','=', 2);
       }


      public function adjustments(){
        return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
    }


    public function purchaseOrder($item_id,$purchase_order_id,$qty,$item_rate)
    {
        $attributes = ['item_id' => $item_id,'purchase_order_id' => $purchase_order_id,'qty' => $qty,'item_rate'=>$item_rate];
        if (!$this->purchaseOrderItems()->where($attributes)->exists()) {
            $purchase_order = $this->purchaseOrderItems()->create($attributes);
            return $purchase_order;
        }
    }


    public function purchaseReceipt(){
        return $this->hasMany(PurchaseReceipts::class,'purchase_order_id');
    }

    public function deattachPurchaseOrder($purchase_order_id){
        if($purchase_order_id){
            $purchase_orders = $this->purchaseOrderItems()->where('purchase_order_id',$purchase_order_id)->get();
            foreach($purchase_orders as $order){
                $order->delete();
            }
        }
    }

 //    public function path($stock)
 // {
 //     return 'admin/products/'.$stock.'/manage';
 // }
}
