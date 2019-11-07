<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $hidden = ['pivot'];

    public function products()
    {
        return $this->belongsToMany(Products::class, 'collection_items', 'collection_id', 'product_id')
            ->select('products.id',
                getLang() == 'en' ? 'slug_en as slug' : 'slug_ar as slug',
                getLang() == 'en' ? 'name_en as name' : 'name',
                getLang() == 'en' ? 'description_en as description' : 'description')
            ->has('variantsProducts')
            ->with('variantsProducts', 'favourite','images');
    }

}
