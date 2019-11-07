<?php

namespace App\Filters;

class ProductFilters extends Filters
{
	protected $filters = ['active','inactive','item_group','food_extras'];

	public function active($active){
		if($active == -1){
			return $this->builder->where(function ($query){
			    $query->where('active', 1)->orWhere('active',0);
			});
		}else{
			return $this->builder->where('active',1);
		}
	}
	public function inactive($inactive){
		if($inactive == -1){
			return $this->builder->where(function ($query){
			    $query->where('active', 1)->orWhere('active',0);
			});
		}else{
			return $this->builder->where('active',0);
		}
	}
	public function food_extras($is_extra){
		if($is_extra == 1){
			return $this->builder->where('is_food_extras',1);
		}else{
			return $this->builder->Extras();
		}
	}

	public function item_group($item_group){

		return $this->builder->where('item_group',$item_group);
	}
}