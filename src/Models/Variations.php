<?php

namespace App\Models;

use App\VariantsMeta;
use Illuminate\Database\Eloquent\Model;

class Variations extends Model
{
    protected $table = 'variations';

    protected $hidden = ['pivot'];
    protected $fillable = ['name', 'name_en', 'affecting_stock', 'has_special_images', 'status', 'is_color', 'key', 'is_size', 'variant_code'];

    public function path()
    {
        return 'admin/variants/';
    }

    public function adjustments()
    {
        return $this->belongsToMany(AdminUser::class, 'adjustments', 'content_id', 'user_id')->withPivot('key', 'action')->withTimestamps()->latest('pivot_updated_at');
    }

    public function variantsMeta()
    {
        return $this->hasMany(VariantsMeta::class, 'variant_data_id');
    }

    public function getGenresIdsAttribute()
    {
        $series_genre = DB::table('series_genre')->where('series_id', $this->id)->first();

        $genresIds = [];
        if ($series_genre->genre_id != "null") {
            foreach (json_decode($series_genre->genre_id) as $genreId) {
                $genresIds[] = (int)$genreId;
            }
        }

        return $genresIds;
    }

    public function getVariationsNameAttribute()
    {
        $variation_meta = $this->variantsMeta;
        $variantArray = [];
        if ($variation_meta != "null") {
            foreach ($variation_meta as $variant) {
                $variantArray[] = $variant->attribute_name;
            }
        }
        return $variantArray;

    }

    public function variation($variation_value_en, $variation_value, $variation_data_id, $color_code, $item_code, $variant_code = '')
    {
        if ($variant_code !== '' && $variant_code !== null) {
            $attributes = ['variation_value_en' => $variation_value_en, 'variation_value' => $variation_value, 'variant_data_id' => $variation_data_id, 'code' => $color_code, 'item_code' => $item_code, 'variant_code' => $variant_code];
        } else {
            $attributes = ['variation_value_en' => $variation_value_en, 'variation_value' => $variation_value, 'variant_data_id' => $variation_data_id, 'code' => $color_code, 'item_code' => $item_code];
        }

        if (!$this->variantsMeta()->where($attributes)->exists()) {

            $variation = $this->variantsMeta()->create($attributes);
            return $variation;
        }
    }

    public function meta()
    {
        return $this->hasMany(VariantsMeta::class, 'variant_data_id')
//            ;
            ->select('variants_metas.id as value_id',
                'variant_data_id' ,getLang() == 'en' ? 'variation_value_en  as value' : 'variation_value as value', 'code');
    }


}
