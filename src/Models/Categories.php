<?php

namespace App\Models;

use App\Products;
use App\AdminUser;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use Sluggable;
    protected $fillable =
    ['name', 'name_en', 'images', 'item_group_name',
    'parent_item_group', 'item_group_name_en', 'has_sub', 'status', 'sorting_no',
    'user_deleted', 'is_deleted', 'item_code', 'slug_en', 'slug_ar'];

    public function adjustments()
    {
        return $this->belongsToMany(AdminUser::class, 'adjustments', 'content_id', 'user_id')->withPivot('key', 'action')->withTimestamps()->latest('pivot_updated_at');
    }


    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function path()
    {

        return 'admin/categories/';
    }


    public function categoryImage($image)
    {
        $attributes = ['image' => $image];
        if (!$this->images()->where($attributes)->exists()) {
            $category_image = $this->images()->create($attributes);
            return $category_image;
        }
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'content');
    }

    public function imgPath($image)
    {
        return url('/public/imgs/categories/' . $image);
    }

    public function imgThumbPath($image)
    {
        return url('/public/imgs/categories/thumb/' . $image);
    }

    public function category_products()
    {
        return $this->hasMany(Products::class, 'item_group', 'id')->where('active', 1);
    }

    public function sub_category_products()
    {
        return $this->hasMany(Products::class, 'second_item_group')->where('active', 1);
    }

    public function sub_categories()
    {
        return $this->hasMany(Categories::class , 'parent_item_group')
            ->with('products')
            ;
    }

    public function childCategories() {
        return $this->hasMany(Categories::class, 'parent_item_group');
    }

    public function products()
    {
        return $this->belongsToMany(Products::class, 'category_products' , 'category_id', 'product_id')
            ->active()->NotVariant()
            ->select('products.id', 'name',
             'name_en',
             getLang() == 'en' ? 'slug_en as slug':'slug_ar as slug',
             'description', 'standard_rate',
            'has_attributes', 'has_variants', 'season_id'
            )
            ->with('variantsProducts', 'favourite', 'price', 'images');

    }

    public function sluggable() {
        return [
            'slug_en' => [
                'source' => 'name_en',
                'separator' => '-',
            ],
            'slug_ar' => [
                'source' => 'name',
                'separator' => '-',
            ]
        ];
    }

}
