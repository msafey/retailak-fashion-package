<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = ['name','supplier_type_id'];

    public function supplierType()
    {
        return $this->belongsTo(SupplierType::class, 'supplier_type_id');
    }


        public function adjustments(){
      return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
  }
}
