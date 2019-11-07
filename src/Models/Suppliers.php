<?php

namespace App\Models;

use App\SupplierType;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
            public function adjustments(){
         	 return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
    			  }

    public function supplierType(){
        return $this->belongsTo(SupplierType::class,'supplier_type_id');
      }
}
