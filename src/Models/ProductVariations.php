<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariations extends Model
{
    protected $table="products_variations";
    protected $fillable=['products_id','variations_id','variation_values','parent_variant_id','variations_data' , 'product_id','variant_id'];

    public function productImage($image,$image_order)
    {
        $attributes = ['image' => $image,'image_order'=>$image_order];
        if (!$this->images()->where($attributes)->exists()) {
            $product_image = $this->images()->create($attributes);
            return $product_image;
        }
    }
      public function images(){
        return $this->morphMany(Image::class, 'content');
    }

}
