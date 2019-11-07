<?php

namespace App\Filters;

class CategoryFilters extends Filters
{

	protected $filters = ['active','inactive'];
	public function active($active){
		return $this->builder->where('status',1);
	}
	public function inactive($inactive){
		return $this->builder->where('status',0);
	}
}