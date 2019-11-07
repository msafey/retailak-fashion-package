<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouses extends Model
{
    protected $fillable=['name','name_en','description','description_en','district_id','status','warehouse_code','default_warehouse'];
    protected $table='warehouses';
    protected $hidden = ['pivot'];

	public function path()
	{
	   return 'admin/warehouses/';
	}

	 public function adjustments(){
	   return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
	}
	public function cmsUsers(){
		return $this->belongsToMany(AdminUser::class,'user_warehouses','warehouse_id','admin_id');
	}

    public function products(){
        return $this->belongsToMany(Products::class, 'products_warehouses', 'warehouse_id','product_id');
    }
    public function productsCount(){
        return $this->belongsToMany(Products::class, 'products_warehouses', 'warehouse_id','product_id')
            ->distinct('product_id')
            ;
    }

}
