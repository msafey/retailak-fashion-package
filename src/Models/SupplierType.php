<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierType extends Model
{
    protected $table = 'supplier_types';

    protected $fillable = ['name'];

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'supplier_type_id');
    }

      public function adjustments(){
        return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
    }
}
