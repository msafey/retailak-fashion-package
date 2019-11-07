<?php

namespace App\Models;

use App\Variations;
use Illuminate\Database\Eloquent\Model;

class VariantsMeta extends Model {

	protected $fillable = ['variation_value_en', 'variation_value', 'variant_data_id', 'code', 'item_code', 'variant_code'];

	public function variantData() {
		return $this->belongsTo(Variations::class, 'variant_data_id');
	}

}
