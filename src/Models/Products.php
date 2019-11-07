<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use SoftDeletes, Sluggable;

    protected $table = 'products';

    protected $hidden = ['pivot'];
    protected $with = ['stock'];

    protected $fillable = [
        'name', 'name_en', 'description', 'description_en', 'is_bundle', 'item_code',
        'item_group', 'second_item_group', 'standard_rate', 'uom', 'weight', 'active', 'stock_qty', 'brand_id', 'is_variant', 'parent_variant_id', 'has_variants', 'user_deleted', 'is_deleted', 'has_attributes', 'is_food_extras',
        'food_taste_available', 'food_type', 'food_optional_note', 'cost', 'season_id', 'slug_en', 'slug_ar'
    ];

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function path()
    {
        return 'admin/products/';
    }

    public function foodExtras()
    {
        return $this->hasMany(FoodExtas::class, 'related_product_id');
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttributes::class, 'products_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeExtras($query)
    {
        return $query->where('is_food_extras', 0);
    }

    public function scopeIsExtra($query)
    {
        return $query->where('is_food_extras', 1);
    }

    public function scopeIsVariant($query)
    {
        return $query->where('is_variant', 1);
    }

    public function scopeHasNoVariants($query)
    {
        return $query->where('has_variants', 0);
    }

    public function itemGroup()
    {
        return $this->belongsTo(Categories::class, 'item_group');
    }

    public function brand_name()
    {
        return $this->belongsTo(Brands::class, 'brand_id');
    }

    public function seconditemGroup()
    {
        return $this->belongsTo(Categories::class, 'second_item_group');
    }

    public function uom()
    {
        return $this->belongsTo(UOM::class, 'uom');
    }

    public function uomRelation() {
        return $this->belongsTo(UOM::class, 'uom');
    }


    public function season()
    {
        return $this->belongsTo(Seasons::class, 'season_id');
    }

    public function priceRules()
    {
        return $this->hasMany(PriceRules::class, 'product_id');
    }

    public function priceRuleRelation()
    {
        return $this->hasOne(PriceRules::class, 'product_id');
    }

    public function priceList()
    {
        return $this->hasMany(ItemPrice::class, 'product_id');
    }

    public function stocks()
    {
        return $this->hasMany(Stocks::class, 'product_id');
    }


    public function productBundles()
    {
        return $this->hasMany(ProductBundle::class, 'product_id');
    }

    public function productImage($image, $image_order)
    {
        $attributes = ['image' => $image, 'image_order' => $image_order];
        if (!$this->images()->where($attributes)->exists()) {
            $product_image = $this->images()->create($attributes);
            return $product_image;
        }
    }

    public function addAttribute($value_id, $attribute_id)
    {
        $attributes = ['value_id' => $value_id, 'attribute_id' => $attribute_id];
        if (!$this->attributes()->where($attributes)->exists()) {
            $attributes = $this->attributes()->create($attributes);
            return $attributes;
        }
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'content')
            ->select('id', 'content_id', 'content_type', 'image');
    }


    public function image()
    {
        return $this->hasone(Image::class, 'content_id')
            ->where('content_type', get_class($this))
            ->select('content_id', 'content_type', 'image');
    }

    public function imgPath($image)
    {
        return url('/public/admin/imgs/products/' . $image);
    }

    public function imgThumbPath($image)
    {
        return url('/public/imgs/products/thumb/' . $image);
    }

    public function adjustments()
    {
        return $this->belongsToMany(AdminUser::class, 'adjustments', 'content_id', 'user_id')->withPivot('key', 'action')->withTimestamps()->latest('pivot_updated_at');
    }

    protected $dates = ['deleted_at'];

    //	********************************************


    public function brand()
    {
        return $this->belongsTo(Brands::class, 'brand_id');
    }

    public function favourite()
    {
        $user_id = authUser() ? authUser()->id : null;

        return $this->hasOne(Favorite::class, 'product_id')
            ->where('user_id', $user_id)
            ->select('product_id');
    }

    public function price()
    {
        //  price_list_id => 1

        return $this->hasOne(ItemPrice::class, 'product_id')
            ->where('price_list_id', 1)
            ->select('product_id', 'rate');
    }

    public function itemPrice()
    {
        return $this->hasMany(ItemPrice::class, 'product_id');
    }

    public function scopeVariant($query)
    {
        return $query->where('is_variant', 1);
    }

    public function scopeNotVariant($query)
    {
        return $query->where('is_variant', 0);
    }

    public function stock()
    {
        $warehouse_id = 1;

        $district_id = request()->header('district_id');

        if ($district_id) {
            $district = District::where('id', $district_id)->first();
            $warehouse_id = isset($district->warehouse_id) ? $district->warehouse_id : 1;
        } else {

            $token = request()->header('Authorization');
            if ($token == null) {
                $token = request()->header('token');
            }
            $user = User::where('token', $token)
                ->with('district')->first();

            if (isset($user->district->warehouse_id)) {
                $warehouse_id = $user->district->warehouse_id;
            }
        }

        return $this->belongsToMany(Warehouses::class, 'products_warehouses', 'product_id', 'warehouse_id')
            ->wherePivot('warehouse_id', $warehouse_id)
            ->select('projected_qty');
    }

    public function variantStock()
    {
        return $this->belongsToMany(Warehouses::class, 'products_warehouses', 'product_id', 'warehouse_id')
            ->withPivot('projected_qty');
    }

    /*
     * return all variant product related to parent product
     * */
    public function variantsProducts()
    {
        return $this->hasMany(Products::class, 'parent_variant_id')
            ->active()->variant()->HasNoVariants()
            ->select(
                'products.id',
                'parent_variant_id',
                'item_group',
                'second_item_group',
                getLang() == 'en' ? 'name_en as name' : 'name',
                getLang() == 'en' ? 'slug_en as slug' : 'slug_ar as slug',
                getLang() == 'en' ? 'description_en as description' : 'description',
                'season_id'
            )
            ->with(['variations', 'price', 'images']);
    }

    public function variations()
    {
        //TODO Change table  name ( variants meta )

        return $this->belongsToMany(
            VariantOption::class,
            'product_variations',
            'product_id',
            'variant_option_id'
        )
            ->select('variant_data_id', 'variant_meta_id')
            ->with(['variationData', 'variationMeta']);
    }


    public static function getVariants($variations, $variants, $variants_keys)
    {
        $x = 0;
        foreach ($variations as $variation) {
            $key_id = $variation['variation_data']['id'];
            $value_id = $variation['variation_meta']['id'];
            $name = $variation['variation_data']['name'];
            $key = $variation['variation_data']['key'];
            $value = $variation['variation_meta']['value'];
            $code = $variation['variation_meta']['code'];

            $variation_options['key_id'] = $key_id;
            $variation_options['value_id'] = $value_id;
            $variation_options['value'] = $value;
            $variation_options['key'] = $key;
            $variation_options['name'] = $name;
            $variation_options['code'] = $code;

            $variants[$x]['key'] = $key;
            $variants[$x]['name'] = $name;
            $variants[$x]['id'] = $key_id;

            $str = $key_id . "#" . $value_id . "#" . $value . "#" . $key . "#" . $name . "#" . $code;


            if (!in_array($str, $variants_keys)) {
                $variants[$x]['values'][] = $variation_options;
            }
            $variants_keys[] = $str;


            $x++;
        }
        return array($variants, $variants_keys);
    }


    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'category_products', 'product_id', 'category_id');
    }

    public function sluggable()
    {
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
