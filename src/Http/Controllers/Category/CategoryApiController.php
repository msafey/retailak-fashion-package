<?php

namespace App\Http\Controllers\Category;

use App\Models\Categories;
use App\Models\CategoryProducts;
use App\Events\ErrorEmail;
use App\Http\Controllers\utilitiesController;
use App\Http\Transformers\CategoriesTransformer;
use App\Http\Transformers\CollectionTransformer;
use App\Http\Transformers\ProductsTransformer;
use App\Models\ItemWarehouse;
use App\Models\Products;
use App\Models\User;
use App\Models\VariantsMeta;
use App\Models\Variations;
use App\Models\Warehouses;
use DB;
use Illuminate\Http\Request;
use Image;
use Response;

class CategoryApiController extends utilitiesController
{

    public function getCategoryProduct()
    {
        if (request()->has('id')) {
            $category_id = request('id');
            if (!$category_id) {
                if (getLang() == 'en') {
                    return Response::json(
                        ['Status' => 'Error', 'message' =>
                        "Category id is required"],
                        400
                    );
                }
                return Response::json(['Status' => 'Error', 'message' => "من فضلك ادخل رقم القسم"], 400);
            }

            $categories_id = Categories::where('id', $category_id)
                ->orWhere('parent_item_group', $category_id)
                ->pluck('id');


            if (!count($categories_id) > 0) {
                if (getLang() == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => "Can't get this category now, please try again later "], 400);
                }
                return Response::json(['Status' => 'Error', 'message' => "لايمكن أيجاد هذا القسم فى الوقت الحالى, من فضلك قم بالمحاولة فى وقت لاحق "], 400);
            }

            $products_id = CategoryProducts::whereIn('category_id', $categories_id)
                ->pluck('product_id');


            if (!count($products_id) > 0) {
                if (getLang() == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => "Can't get this category now, please try again later "], 400);
                }
                return Response::json(['Status' => 'Error', 'message' => "لايمكن أيجاد هذا القسم فى الوقت الحالى, من فضلك قم بالمحاولة فى وقت لاحق "], 400);
            }

            $products = Products::whereIn('id', $products_id)
                ->active()->NotVariant()
                ->select(
                    'products.id',
                    'parent_variant_id',
                    getLang() == 'en' ? 'slug_en as slug' : 'slug_ar as slug',
                    getLang() == 'en' ? 'name_en as name' : 'name',
                    getLang() == 'en' ? 'description_en as description' : 'description',
                    'season_id'
                )
                ->has('variantsProducts')
                ->with('variantsProducts', 'favourite', 'images')
                ->get();
        } else if (request()->has('slug')) {
            $category_slug = request('slug');
            if (!$category_slug) {
                if (getLang() == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => "Category id is required"], 400);
                }
                return Response::json(['Status' => 'Error', 'message' => "من فضلك ادخل رقم القسم"], 400);
            }
            $lang = getLang();
            $category = Categories::where('slug_' . $lang, $category_slug)->first();
            $categories_id = $category->childCategories()->pluck('id')->toArray();
            if (count($categories_id) <= 0 && $category->id > 0) {
                $categories_id = [$category->id];
            }
            if (!count($categories_id) > 0) {
                if (getLang() == 'en') {
                    return Response::json(
                        [
                            'Status' => 'Error',
                            'message' => "Can't get this category now, please try again later "
                        ],
                        400
                    );
                }
                return Response::json(
                    [
                        'Status' => 'Error',
                        'message' => "لايمكن أيجاد هذا القسم فى الوقت الحالى, من فضلك قم بالمحاولة فى وقت لاحق "
                    ],
                    400
                );
            }

            $products_id = CategoryProducts::whereIn('category_id', $categories_id)
                ->pluck('product_id');
            if (!count($products_id) > 0) {
                return response()->json([], 200);
                // if (getLang() == 'en') {
                //     return Response::json([
                //         'Status' => 'Error',
                //         'message' => "Can't get products now, please try again later "
                //     ], 400);
                // }
                // return Response::json([
                //     'Status' => 'Error',
                //     'message' => "لايمكن أيجاد هذا القسم فى الوقت الحالى, من فضلك قم بالمحاولة فى وقت لاحق "
                // ], 400);
            }

            $products = Products::whereIn('id', $products_id)
                ->active()->NotVariant()
                ->select(
                    'products.id',
                    'parent_variant_id',
                    getLang() == 'en' ? 'slug_en as slug' : 'slug_ar as slug',
                    getLang() == 'en' ? 'name_en as name' : 'name',
                    getLang() == 'en' ? 'description_en as description' : 'description',
                    'season_id'
                )
                ->has('variantsProducts')
                ->with('variantsProducts', 'favourite', 'images')
                ->get();
        }

        $product_transformer = new ProductsTransformer;
        $products_array = count($products) > 0 ? $product_transformer->transformCollection($products->toArray()) : [];

        return $products_array;
    }


    public function getCategories(Request $request)
    {
        try {
            updateUserDeviceData();
            if (getFromCache('Categories')) {
                return getFromCache('Categories');
                // dd('Cached', getFromCache('Categories'));
            }
            //            $categories = Categories::has('category_products')->where('status', 1)->orderBy('sorting_no')->get();
            $categories = Categories::where('status', 1)->orderBy('sorting_no')->get();

            $Categories_array = $this->getArrayCategories($categories);

            return Response::json(putInCache('Categories', $Categories_array));
        } catch (\Exception $e) {
            $error = array($e->getMessage(), $e->getFile(), $e->getLine(), $request->all());
            event(new ErrorEmail($error));
            return $error;
        }
    }

    public function getArrayCategories($categories)
    {
        $lang = getLang();
        $Categories_array = array();
        foreach ($categories as $key => $category) {
            $Categories_array[$key]["id"] = $category->id;
            $Categories_array[$key]["name"] = $lang == 'en' ? trim($category->name_en) : trim($category->name);
            $Categories_array[$key]["slug"] = $lang == 'en' ? trim($category->slug_en) : trim($category->slug_ar);
            $Categories_array[$key]["hasSubCategories"] = $category->has_sub == 0 ? false : true;
            $Categories_array[$key]["images"] = $this->getCategoryImgs($category);
        }
        return $Categories_array;
    }

    public function getsubcategories(Request $request, $param)
    {
        $categories_array = [];
        $lang = getLang();
        if ((int) $param > 0) {
            $parentCategory = Categories::where('id', $param)
            ->firstOrFail();
        } else {
            $parentCategory = Categories::where('slug_' . $lang, $param)
            ->firstOrFail();
        }
        $parentCategory = Categories::where('id', $param)
            ->orWhere('slug_' . $lang, $param)
            ->firstOrFail();

        if (!$parentCategory) {
            if ($lang == 'en') {
                return Response::json(['Status' => 'Error', 'message' => "Can't get this category now, please try again later "], 400);
            }
            return Response::json(['Status' => 'Error', 'message' => "لايمكن أيجاد هذا القسم فى الوقت الحالى, من فضلك قم بالمحاولة فى وقت لاحق "], 400);
        }

        $subCategories = Categories::has('category_products')->where('parent_item_group', $parentCategory->id)->where('status', 1)
            ->orderBy('sorting_no', 'DESC')->get();

        if (!count($subCategories) > 0) {
            if (getLang() == 'en') {
                return Response::json(['Status' => 'Error', 'message' => "Can't get child for this category now, please try again later "], 400);
            }
            return Response::json(['Status' => 'Error', 'message' => "لايمكن أيجاد هذا القسم فى الوقت الحالى, من فضلك قم بالمحاولة فى وقت لاحق "], 400);
        }
        $x = 0;
        foreach ($subCategories as $category) {

            $Categories[$x]['id'] = $category->id;
            $Categories[$x]['name'] = getLang() == 'en' ? trim($category->name_en) : trim($category->name);
            $Categories[$x]['slug'] = getLang() == 'en' ? trim($category->slug_en) : trim($category->slug_ar);
            $Categories[$x]['hasSubCategories'] = $category->has_sub == 0 ? false : true;
            $Categories[$x]['images'] = $this->getCategoryImgs($category);
            $x++;
        }

        return $Categories;
    }

    public function getCategoryImgs($type)
    {
        $images_array = array();
        $Images = $type->images()->orderBy('id')->get();
        if (count($Images) > 0) {
            foreach ($Images as $image) {
                $images_array[] = $type->imgPath($image->image);
            }
        }
        return $images_array;
    }

    public function getCategoryProductsFilters()
    {
        $category_param = request()->param;
        $lang = getLang();

        if (!$category_param) {
            if (getLang() == 'en') {
                return Response::json(['Status' => 'Error', 'message' => "Category id is required"], 400);
            }
            return Response::json(['Status' => 'Error', 'message' => "من فضلك ادخل رقم القسم"], 400);
        }
        $categoryQuery = Categories::query();
        if ((int) $category_param > 0) {
            $categoryQuery->where('id', (int) $category_param)
                ->orWhere('parent_item_group', (int) $category_param);
        } else {
            $categoryQuery->where('slug_' . $lang, $category_param);
        }

        $category = $categoryQuery->first();
        $categories_id = $category->childCategories()->pluck('id')->toArray();
       if (count($categories_id) <= 0 && $category->id > 0) {
        $categories_id = [$category->id];
       }
        if (!count($categories_id) > 0) {
            if (getLang() == 'en') {
                return Response::json([
                    'Status' => 'Error',
                    'message' => "Can't get this category now, please try again later "
                ], 400);
            }
            return Response::json([
                'Status' => 'Error',
                'message' => "لايمكن أيجاد هذا القسم فى الوقت الحالى, من فضلك قم بالمحاولة فى وقت لاحق "
            ], 400);
        }

        $products_id = CategoryProducts::whereIn('category_id', $categories_id)
            ->pluck('id');

        if (!count($products_id) > 0) {
            if (getLang() == 'en') {
                // return Response::json(['Status' => 'Error',
                //  'message' => "Can't get products now, please try again later "], 400);
                return response()->json([], 200);
            }
            return response()->json([], 200);
            // return Response::json(['Status' => 'Error',
            // 'message' => "لايمكن أيجاد هذا القسم فى الوقت الحالى, من فضلك قم بالمحاولة فى وقت لاحق "],
            //  400);
        }


        $products = Products::whereIn('id', $products_id)
            ->active()->NotVariant()
            ->select(
                'products.id',
                getLang() == 'en' ? 'slug_en as slug' : 'slug_ar as slug',
                getLang() == 'en' ? 'name_en as name' : 'name',
                getLang() == 'en' ? 'description_en as description' : 'description',
                'standard_rate',
                'has_attributes',
                'has_variants',
                'season_id'
            )
            ->with('variantsProducts')
            ->get()->toArray();

        $variants = $variants_keys = [];
        foreach ($products as $product) {
            foreach ($product['variants_products'] as $variant_product) {

                list($variants, $variants_keys) = Products::getVariants($variant_product['variations'], $variants, $variants_keys);
            }
        }
        return $variants;
    }

    public function getVariants($variations, $variants, $variants_keys)
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

            $variants[$x]['is_color'] = $key;
            $variants[$x]['name'] = $name;
            $variants[$x]['key_id'] = $key_id;

            $str = $key_id . "#" . $value_id . "#" . $value . "#" . $key . "#" . $name . "#" . $code;


            if (!in_array($str, $variants_keys)) {
                $variants[$x]['values'][] = $variation_options;
            }
            $variants_keys[] = $str;


            $x++;
        }
        return array($variants, $variants_keys);
    }

    public function gatCategoryProductsFilters2()
    {
        $lang = getLang();
        $categoryProducts = $this->getCategoryProduct(1);
        if ($categoryProducts->count() > 0) {
            $categoryProductsIds = array();
            foreach ($categoryProducts as $categoryProduct) {
                $categoryProductsIds[] = $categoryProduct->id;
            }

            if (getFromCache('Filter_' . $categoryProduct->id)) {
                //                return getFromCache('Filter_' . $categoryProduct->id);
                // dd('Cached', getFromCache('Filter_' . $categoryProduct->id));
            }

            foreach ($categoryProductsIds as $categoryProductId) {

                $product = Products::find($categoryProductId);

                if ($product) {
                    if ($product->attributes->count() > 0) {
                        foreach ($product->attributes as $productAttribute) {
                            $productAttributesArray[] = array('attribute_id' => $productAttribute->attribute_id, 'value_id' => $productAttribute->value_id, 'product_id' => $productAttribute->products_id);
                        }
                    }
                    $productVariants = getEveryVariations($product, $lang);

                    if (count($productVariants) > 0) {
                        foreach ($productVariants as $productVariant) {
                            if (isset($productVariant->values) && is_array($productVariant->values)) {
                                $productVariantValues = $productVariant->values;
                                foreach ($productVariantValues as $productVariantValue) {
                                    $productAttributesArray[] = array('attribute_id' => $productVariantValue->key_id, 'value_id' => $productVariantValue->value_id, 'product_id' => $product->id);
                                }
                            }
                        }
                    }
                }
            }
            //return $productAttributesArray;
            if (isset($productAttributesArray)) {
                $attributesArray = [];
                foreach ($productAttributesArray as $productAttribute) {
                    if (is_array($productAttribute) && isset($productAttribute['attribute_id'])) {
                        $attr_id = $productAttribute['attribute_id'];
                        $val_id = $productAttribute['value_id'];
                        if (isset($attributesArray[$attr_id]) && is_array($attributesArray[$attr_id])) {
                            if (!in_array($val_id, $attributesArray[$attr_id])) {
                                $attributesArray[$attr_id][] = $val_id;
                            }
                        } else {
                            $attributesArray[$attr_id][] = $val_id;
                        }
                    }
                }
                $attributesReturnArray = [];
                foreach ($attributesArray as $variationId => $variationValues) {
                    $attribute = Variations::find($variationId);

                    if ($attribute) {
                        if ($lang == 'en') {
                            $attributeName = $attribute->name_en;
                        } else {
                            $attributeName = $attribute->name;
                        }
                        $attributesReturnObject['Key'] = $attribute->key;
                        $attributesReturnObject['Text'] = $attributeName;

                        $attributesReturnObject['id'] = $variationId;
                        $valuesArray = [];
                        foreach ($variationValues as $variationValue) {
                            $variationValue = VariantsMeta::find($variationValue);
                            if ($variationValue) {
                                if ($lang == 'en') {
                                    $variationValueMetaName = $variationValue->variation_value_en;
                                } else {
                                    $variationValueMetaName = $variationValue->variation_value;
                                }
                            }

                            $ValueObject['var_attr_name'] = $attributeName;
                            $ValueObject['key_id'] = $variationId;
                            if (strtolower(trim($attributeName)) === 'color') {

                                $ValueObject['color_code'] = $variationValue->code;
                            } else {
                                if (isset($ValueObject['color_code'])) {
                                    unset($ValueObject['color_code']);
                                }
                            }

                            $ValueObject['var_attr_value'] = $variationValueMetaName;
                            $ValueObject['value_id'] = $variationValue->id;
                            $valuesArray[] = $ValueObject;
                        }
                        $attributesReturnObject['value'] = $valuesArray;

                        $attributesReturnArray[] = $attributesReturnObject;
                        $attributesReturnObject = null;
                    }
                }

                //                return putInCache('Filter_' . $categoryProduct->id, $attributesReturnArray);
                // dd('Cache', putInCache('Filter_' . $categoryProduct->id, $attributesReturnArray));

                // return $attributesReturnArray;
            } else {
                return array();
            }
        } else {

            return "notCollection";
        }
    }


    public function getCategoryProduct2($filters = null)
    {
        try {
            $lang = getLang();
            $token = request()->header('Authorization');
            if ($token == null) {
                $token = request()->header('token');
            }
            if (isset($_GET['id'])) {
                $item_group = trim($_GET['id']);

                if (getFromCache('Products_category_' . $item_group)) {
                    //                    return getFromCache('Products_category_' . $item_group);
                    // dd('Cached', getFromCache('Products_category_' . $item_group));
                }

                $cats = Categories::where('id', $item_group)->first();

                if (!$cats) {
                    if ($lang == 'en') {
                        return Response::json(['Status' => 'Error', 'message' => "Can't get this category now, please try again later "], 400);
                    } else {
                        return Response::json(['Status' => 'Error', 'message' => "لايمكن أيجاد هذا القسم فى الوقت الحالى, من فضلك قم بالمحاولة فى وقت لاحق "], 400);
                    }
                }
                if ($cats) {
                    if ($cats->has_sub) {
                        $subcats = Categories::where('parent_item_group', $cats->id)->get();
                        $itemgroups = array();
                        foreach ($subcats as $subcat) {
                            $subitemgroups[] = $subcat->id;
                        }
                    }
                }
                $itemGroupId[] = $cats->id;

                $products = Products::where('active', 1);
                if ($filters !== 1) {
                    $products = $products->isVariant();
                }
                if ($lang == 'en') {
                    if (isset($subitemgroups)) {

                        // $products = $products->wherein('item_group', $subitemgroups)->orWhereIn('second_item_group', $subitemgroups)->select('id', 'image', 'has_variants', 'name_en as name', 'description_en as description', 'item_group', 'item_code', 'uom', 'weight', 'brand_id', 'has_attributes')->orderBy('sorting_no', 'desc')->get();
                        $itemGroup = $cats->id;
                        $products = $products->Where(function ($query) use ($subitemgroups) {
                            $query->whereIn('item_group', $subitemgroups)->orWhereIn('second_item_group', $subitemgroups);
                        })->select(
                            'id',
                            'name_en as name',
                            getLang() == 'en' ? 'slug_en as slug' : 'slug_ar as slug',
                            'image',
                            'has_variants',
                            'description_en as description',
                            'brand_id',
                            'item_group',
                            'item_code',
                            'uom',
                            'weight',
                            'has_attributes'
                        )->orderBy('sorting_no', 'desc')->get();
                    } else {
                        // $products = $products->where('item_group', $cats->id)->orWhere('second_item_group', $cats->id)->select('id', 'name_en as name', 'image', 'has_variants', 'description_en as description', 'brand_id', 'item_group', 'item_code', 'uom', 'weight', 'has_attributes')->orderBy('sorting_no', 'desc')->get();
                        $itemGroup = $cats->id;
                        $products = $products->Where(function ($query) use ($itemGroup) {
                            $query->where('item_group', $itemGroup)->orWhere('second_item_group', $itemGroup);
                        })->select('id', 'name_en as name', 'image', 'has_variants', 'description_en as description', 'brand_id', 'item_group', 'item_code', 'uom', 'weight', 'has_attributes')->orderBy('sorting_no', 'desc')->get();
                    }

                    if (isset($itemGroupId)) {
                        if (is_array($itemGroupId)) {
                            foreach ($products as $product) {
                                if ($token != null) {
                                    $product = isFavouriteProduct($product, $token);
                                    $product = productCartQty($token, $product);
                                }
                                $product = handleMultiImages($product);
                                $product->standard_rate = itemSellingPrice($product->id);
                                $Cat = Categories::where('id', $product->item_group)->first();
                                if ($Cat) {
                                    if ($Cat->parent_item_group == null) {
                                        $product->main_cat = array('id' => $Cat->id, 'name' => $Cat->name_en);
                                        $product->sub_cat = null;
                                    } else {
                                        $parent_category = Categories::where('id', $Cat->parent_item_group)->first();
                                        if ($parent_category) {
                                            $product->main_cat = array('id' => $parent_category->id, 'name' => $parent_category->name_en);
                                        } else {
                                            $product->main_cat = null;
                                        }
                                        $product->sub_cat = array('id' => $Cat->id, 'name' => $Cat->name_en);
                                    }
                                } else {
                                    $product->main_cat = null;
                                    $product->sub_cat = null;
                                }
                                $product->item_group = $itemGroupId[0];
                            }
                        }
                    }
                } else {
                    if (isset($subitemgroups)) {

                        $products = $products->wherein('item_group', $subitemgroups)->orWhereIn('second_item_group', $subitemgroups)->select('id', 'image', 'description', 'has_variants', 'name', 'item_group', 'item_code', 'standard_rate', 'uom', 'weight', 'has_attributes', 'stock_qty', 'brand_id')->orderBy('sorting_no', 'desc')->get();
                    } else {
                        $products = $products->where('item_group', $cats->id)->orWhere('second_item_group', $cats->id)->select('id', 'name', 'image', 'description', 'has_variants', 'item_group', 'item_code', 'standard_rate', 'uom', 'has_attributes', 'weight', 'brand_id')->orderBy('sorting_no', 'desc')->get();
                    }

                    if (isset($itemGroupId)) {
                        if (is_array($itemGroupId)) {
                            foreach ($products as $product) {
                                $product = handleMultiImages($product);
                                $product->standard_rate = itemSellingPrice($product->id);
                                if ($token != null) {
                                    $product = isFavouriteProduct($product, $token);
                                    $product = productCartQty($token, $product);
                                }
                                $Cat = Categories::where('id', $product->item_group)->first();
                                if ($Cat) {
                                    if ($Cat->parent_item_group == null) {
                                        $product->main_cat = array('id' => $Cat->id, 'name' => $Cat->name);
                                        $product->sub_cat = null;
                                    } else {
                                        $parent_category = Categories::where('id', $Cat->parent_item_group)->first();
                                        if ($parent_category) {
                                            $product->main_cat = array('id' => $parent_category->id, 'name' => $parent_category->name);
                                        } else {
                                            $product->main_cat = null;
                                        }
                                        $product->sub_cat = array('id' => $Cat->id, 'name' => $Cat->name);
                                    }
                                } else {
                                    $product->main_cat = null;
                                    $product->sub_cat = null;
                                }
                                // if ($filters !== 1) {
                                $product->item_group = $itemGroupId[0];
                                // }
                            }
                        }
                    }
                }
                if ($filters == 1) {
                    //dd(0);
                    return $products;
                }

                $products = getProductWithVariations($products, $lang, 1);


                //                return $products ;
                // return putInCache('Products_category_' . $item_group, $products);
                // dd('Cache', putInCache('Products_category_' . $item_group, $products));
                return Response::json(putInCache('Products_category_' . $item_group, $products), 200);

                // return Response::json($products, 200);
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'Bad Request'], 400);
            }
        } catch (\Exception $e) {
            $error = array($e->getMessage(), $e->getFile(), $e->getLine());
            event(new ErrorEmail($error));
            return $error;
        }
    }

    public function categorytree()
    {
        try {
            $lang = app('request')->header('lang');
            $categorytree = array();
            $lang = getLang();
            $counter1 = 0;

            if (getFromCache('CategoryTree')) {
                return getFromCache('CategoryTree');
                // dd('Cached', getFromCache('CategoryTree'));
            }
            $parentcategories = Categories::Where(function ($query) {
                $query->whereNull('parent_item_group')
                    ->orWhere('parent_item_group', '=', 0);
            })
                //                ->leftJoin('products', 'categories.id', '=', 'products.item_group')
                ->where('status', 1)->get()->sortBy('sorting_no');

            //                        dd($parentcategories);
            $key = 0;
            foreach ($parentcategories as $parentcategory) {
                $categorytree[$key]['id'] = $parentcategory->id;
                $categorytree[$key]['name'] = $lang == "en" ? $parentcategory->name_en : $parentcategory->name;
                $categorytree[$key]['slug'] = getLang() == 'en' ? trim($parentcategory->slug_en) : trim($parentcategory->slug_ar);
                $categorytree[$key]['hasSubCategories'] = $parentcategory->has_sub == 0 ? false : true;
                $categorytree[$key]['images'] = $this->getCategoryImgs($parentcategory);
                if ($parentcategory->has_sub == 1) {
                    $childcategories = Categories::where('parent_item_group', $parentcategory->id)
                        //                         to return only categories has products
                        //                        ->join('products', 'categories.id', '=', 'products.item_group')
                        //                        ->groupBy('categories.id')
                        //                        ->select('categories.*')
                        ->where('status', 1)
                        ->get()->sortBy('sorting_no');
                    $childs = array();
                    $k = 0;
                    foreach ($childcategories as $childcategory) {
                        //                        dd($childcategory);
                        $childs[$k]['id'] = $childcategory->id;
                        $childs[$k]['name'] = $lang == "en" ? $childcategory->name_en : $childcategory->name;
                        $childs[$k]['slug'] = getLang() == 'en' ? trim($childcategory->slug_en) : trim($childcategory->slug_ar);
                        $childs[$k]['hasSubCategories'] = $parentcategory->has_sub == 0 ? false : true;
                        $childs[$k]['images'] = $this->getCategoryImgs($childcategory);
                        $k++;
                    }
                    $categorytree[$key]['sub_categories'] = $childs;
                }
                $key++;
            }
            // // return putInCache('CategoryTree', $categorytree);
            // dd('Cache', putInCache('CategoryTree', $categorytree));
            return Response::json(putInCache('CategoryTree', $categorytree), 200);

            //             return Response::json($categorytree, 200);
        } catch (\Exception $e) {
            $error = array($e->getMessage(), $e->getFile(), $e->getLine(), \request()->all());
            event(new ErrorEmail($error));
            return $error;
        }
    }

    public function suggestions(\Illuminate\Http\Request $request)
    {
        $headers = getallheaders();
        $districtId = getDistrictId();
        if (!is_null($districtId) && $districtId > 0) {
            $branch = null;
            $dbBranches = Warehouses::where('status', 1)->get();
            foreach ($dbBranches as $dbBranch) {
                if (in_array($districtId, json_decode($dbBranch->district_id))) {
                    $branch = $dbBranch;
                }
            }
            //$branch = Branch::where('district_id',$districtId)->first();
            if (!is_null($branch)) {
                $warehouse = $branch->id;
                $warehouseProducts = ItemWarehouse::where('warehouse_id', $warehouse)->get();
                $productQtys = array();
                foreach ($warehouseProducts as $warehouseProduct) {
                    $productQtys[$warehouseProduct->product_id] = $warehouseProduct->projected_qty;
                }
            }
        }

        $token = getTokenFromReq($request);
        $orderdItems = array();
        $lang = app('request')->header('lang');
        if ($token != null) {
            $user = \App\User::where('token', '=', $token)->first();
        }
        if (empty($user)) {
            if ($lang == 'en') {
                return Response::json(['Status' => 'Error', 'message' => 'Unauthorized , Please login.'], 401);
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولاًً'], 401);
            }
        } else {
            // $priceList = $this->getProductPrices();
            $userOrderedItems = array();

            $userOrders = \App\Orders::where('user_id', $user->id)->get();
            if ($userOrders && $userOrders->count() > 0) {
                foreach ($userOrders as $userOrder) {
                    $orderItems = \App\OrderItem::where('order_id', $userOrders->id)->get();
                    if ($orderItems) {
                        foreach ($orderItems as $orderItem) {
                            if (!isset($userOrderedItems[$orderItem->item_id])) {
                                $userOrderedItems[$orderItem->item_id] = 1;
                            } else {
                                $userOrderedItems[$orderItem->item_id] += 1;
                            }
                        }
                    }
                }
            }
        }

        arsort($userOrderedItems);
        $userOrderdFromCategories = $orderedItemsPrices = array();

        foreach ($userOrderedItems as $itemCode => $count) {

            $dbProduct = \App\Products::active()->isVariant()->where('id', $itemCode)->first();
            if (!$dbProduct) {
                unset($userOrderedItems[$itemCode]);
            } else {
                // if (isset($priceList[$itemCode])) {
                //     $orderedItemsPrices[$itemCode] = $priceList[$itemCode];
                // }

                if (isset($userOrderdFromCategories[$dbProduct->item_group])) {
                    $userOrderdFromCategories[$dbProduct->id] += 1;
                } else {
                    $userOrderdFromCategories[$dbProduct->id] = 1;
                }
            }
        } //
        arsort($userOrderdFromCategories);

        if (count($userOrderdFromCategories) > 5) {
            $userOrderdFromCategories = array_slice($userOrderdFromCategories, 0, 4);
        }
        $suggestedProductsIds = array();
        $userOrderdFromCategoriesCount = count($userOrderdFromCategories);
        switch ($userOrderdFromCategoriesCount) {
            case 5:
                $noOfCategoryItems = 5;
                $productCodes = \App\Products::active()->isVariant()->take(5)->pluck('id')->toArray();
                break;
            case 4:
                $noOfCategoryItems = 6;
                $productCodes = \App\Products::active()->isVariant()->take(6)->pluck('id')->toArray();
                break;
            case 3:
                $noOfCategoryItems = 7;
                $productCodes = \App\Products::isVariant()->active()->take(9)->pluck('id')->toArray();
                break;
            case 2:
                $noOfCategoryItems = 10;
                $productCodes = \App\Products::active()->isVariant()->take(10)->pluck('id')->toArray();
                break;
            case 1:
                $noOfCategoryItems = 15;
                $productCodes = \App\Products::active()->isVariant()->take(15)->pluck('id')->toArray();
                break;
            default:
                $noOfCategoryItems = 0;
                break;
        }
        // return $noOfCategoryItems;
        if ($noOfCategoryItems > 0) {
            // re9turn $productCodes;
            if (isset($productCodes)) {
                foreach ($userOrderdFromCategories as $itemGroup => $count) {
                    $DBproductCodes = \App\Products::active()->isVariant()->where('item_group', $itemGroup)->take($noOfCategoryItems)->pluck('id')->toArray();

                    $productCodes = array_merge($productCodes, $DBproductCodes);
                }
            }
        } else {
            //topItems
            $productCodes = DB::table('order_items')
                ->select(DB::raw('count(*) as count, item_id'))->groupBy('item_id')->take(30)
                ->orderBy('count', 'desc')->pluck('item_id', 'count')->toArray();
        }

        $suggestedProducts = array();
        $productCodes = array_unique($productCodes);

        $salesOrderController = new SalesOrderController;
        // dd($salesOrderController->getcart($request)->getData());
        $userCart = $salesOrderController->getcart($request)->getData();
        // return $productCodes;

        foreach ($userCart as $userCartItem) {
            if (($key = array_search($userCartItem->item_id, $productCodes)) !== false) {
                unset($productCodes[$key]);
            }

            // else
            //     $userOrderedItems[$userCartItem->item_code]=1;
        }
        if ($lang == 'en') {
            foreach ($productCodes as $productCode) {
                $product = \App\Products::where('item_code', $productCode)->first();
                if (isset($productQtys[$product->item_code]) && $productQtys[$product->item_code] > 0) {
                    $suggestedProducts[] = \App\Products::where('item_code', $productCode)->select('id', 'name_en', 'name', 'description_en', 'description', 'image', 'item_group', 'item_code', 'max_discount', 'standard_rate', 'uom', 'weight', 'stock_qty', 'brand_id')->first();
                }
            }
        } else //ol
        {
            foreach ($productCodes as $productCode) {
                $product = \App\Products::where('item_code', $productCode)->first();
                if (isset($productQtys[$product->item_code]) && $productQtys[$product->item_code] > 0) {
                    $suggestedProducts[] = \App\Products::where('item_code', $productCode)->select('id', 'name', 'name_en', 'description', 'description_en', 'image', 'item_group', 'min_order_qty', 'item_code', 'max_discount', 'standard_rate', 'uom', 'weight', 'brand_id', 'stock_qty')->first();
                }
            }
        }
        return $suggestedProducts;
    }

    public function userItemNotify()
    {
        $usersItems = UserItemNotification::where('notification_sent', '!=', 1)->get();
        $usersNotifications = array();
        foreach ($usersItems as $usersItem) {
            $itemcode = $usersItem->product_id;
            $warehouse_id = $usersItem->warehouse_id;
            $item_warehouse = ItemWarehouse::where('warehouse+id', $warehouse_name)->where('product_id', $itemcode)->first();
            if ($item_warehouse) {
                if ($item_warehouse->projected_qty > 0) {
                    $usersNotifications[] = array('user_id' => $usersItem->user_id, 'warehouse_id' => $warehouse_id, 'product_id' => $itemcode, 'item_notification_id' => $usersItem->id);
                }
            }
        }
        foreach ($usersNotifications as $usersNotification) {
            $userId = $usersNotification['user_id'];
            $userItemNotificationId = $usersNotification['item_notification_id'];
            $userItemNotificationRow = UserItemNotification::find($userItemNotificationId);
            if ($userItemNotificationRow) {
                $itemCode = $userItemNotificationRow->product_id;
                $product = Products::where('id', $itemCode)->first();
                if ($product) {
                    $productName = $product->name;
                }

                if (isset($productName)) {
                    $message = "عزيزي العميل المنتج ($productName) أصبح متاح الان";
                    $data = ['message' => $message, 'product_id' => $product->id];
                }
            }
            $userRecord = User::find($userId);
            $deviceTokenRow = deviceToken::where('user_id', $userId)->latest()->first();
            if ($userRecord && $deviceTokenRow) {
                $title = 'جملة أونلاين';
                if (!isset($message)) {
                    $message = 'عزيزي العميل المنتج ';
                }
                if (!isset($data)) {
                    $data = ['message' => $message];
                }

                $targetDevices = [$deviceTokenRow->device_token];
                $optionBuilder = new OptionsBuilder();
                $optionBuilder->setTimeToLive(60 * 20);
                $notificationBuilder = new PayloadNotificationBuilder($title);
                $notificationBuilder->setBody($message)->setSound('default');
                $dataBuilder = new PayloadDataBuilder();
                $dataBuilder->addData($data);
                $option = $optionBuilder->build();
                $notification = $notificationBuilder->build();
                $setData = $dataBuilder->build();
                $downstreamResponse = FCM::sendTo($targetDevices, $option, $notification, $setData);

                if ($downstreamResponse->numberSuccess() == 1) {
                    $userItemNotificationId = $usersNotification['item_notification_id'];
                    $userItemNotificationRow = UserItemNotification::find($userItemNotificationId);
                    if ($userItemNotificationRow) {
                        $userItemNotificationRow->notification_sent = 1;
                        $userItemNotificationRow->save();
                    }
                }
                return Response::json($downstreamResponse->numberSuccess());
            }
        }
    }
}
