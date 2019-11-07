<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{

    protected $fillable = ['district_en', 'district_ar', 'parent_id', 'shipping_role', 'active'];

    public function adjustments()
    {
        return $this->belongsToMany(AdminUser::class, 'adjustments', 'content_id', 'user_id')->withPivot('key', 'action')->withTimestamps()->latest('pivot_updated_at');
    }

    public function shipping()
    {
        return $this->belongsToMany(ShippingRule::class,'district_shipping_rules',  'district_id', 'shipping_rule_id')
            ->withPivot('from_weight','to_weight');
    }
}
