<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantOption extends Model
{
    protected $table = "variant_options";
    protected $hidden = ['pivot'];

    protected $fillable = ['variant_data_id', 'variant_meta_id'];


    public function variationData()
    {
        return $this->belongsTo(Variations::class, 'variant_data_id')
//            ->where('variations.id' , 1)
            ->select('variations.id', 'key', getLang() == 'en' ? 'name_en as name' : 'name' , 'is_color');
    }


    public function variationMeta()
    {
        return $this->belongsTo(VariantsMeta::class, 'variant_meta_id')
//            ->where('variants_metas.id' , 1)
            ->select('variants_metas.id',
                getLang() == 'en' ? 'variation_value_en  as value' : 'variation_value as value', 'code');

    }

}
