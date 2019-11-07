<?php

namespace App\Models;

use App\Scopes\OrderScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Orders extends Model
{
    protected $fillable = ['status', 'user_id', 'address_id', 'date', 'total_price', 'discount', 'shipping_rule',
        'shipping_rate', 'payment_method', 'shipping_role_id', 'note', 'payment_order_id','device_os'];


    /**
     * The "booting" method of the model.
     * return all orders except in active orders (status = inactive)
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderScope);
    }

    public function scopeNotCancelled($q)
    {
        return $q->where('status', '!=', 'Cancelled');
    }


    public function address()
    {
        return $this->belongsTo(Addresses::class, 'address_id');
    }

    public function adjustments()
    {
        return $this->belongsToMany(AdminUser::class, 'adjustments', 'content_id', 'user_id')->withPivot('key', 'action')->withTimestamps()->latest('pivot_updated_at');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');

    }

    public function time()
    {
        return $this->belongsTo('App\TimeSection', 'timesection_id');

    }

    public function shipment()
    {
        return $this->hasOne(AramexShipment::class, 'order_id');

    }

    public function totalPrice()
    {
        return $this->hasMany(OrderItems::class, 'order_id')
            ->where('returned', 0)
//            ->select('order_items.id','order_id')
            ->select('order_items.id', 'order_id', DB::raw('(order_items.rate * order_items.qty) as price'));
    }

    // $order->orderitem;
    public function OrderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id')->where('returned', 0);
    }

    public function allOrderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }

    public function returnedOrderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id')->where('returned', 1);
    }

    public function items()
    {
        return $this->hasMany(OrderItems::class, 'order_id')
            ->with('product');
    }

    public function OrderItem($item_id, $order_id, $qty, $price, $item_name)
    {
        $attributes = ['item_id' => $item_id, 'order_id' => $order_id, 'qty' => $qty, 'rate' => $price, 'item_name' => $item_name];
        // return $attributes;
        if (!$this->OrderItems()->where($attributes)->exists()) {
            $order = $this->OrderItems()->create($attributes);
            return $order;
        }
    }

    public function deleteOrderItem($order_id)
    {
        $order_items = $this->OrderItems()->where('order_id', $order_id)->get();
        if (count($order_items) > 0) {
            foreach ($order_items as $item) {
                if (checkProductConfig('foods') && checkProductConfig('maintaining_stocks') == false) {
                    if (isset($item->orderExtras)) {
                        $extras = $item->orderExtras()->delete();
                    }
                }
                $item->delete();
            }
        }
    }



    public function usedPromo()
    {
        return $this->hasOne(UsedPromocode::class, 'order_id');
    }

}
