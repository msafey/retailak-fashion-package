<?php

namespace App\Http\Controllers\Products;

use App\Models\Addresses;
use App\Models\Brands;
use App\Models\Categories;
use App\Models\CategoryProducts;
use App\Events\ErrorEmail;
use App\Http\Controllers\Controller;
use App\Http\Transformers\ProductsTransformer;
use App\Models\ItemWarehouse;
use App\Models\PriceList;
use App\Models\ProductAttributes;
use App\Models\Products;
use App\Models\ProductVariations;
use App\Models\Warehouses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;

class ProductsApiController extends Controller
{

    public function getProduct()
    {
        if (request()->has('id')) {
            $product_id = request('id');
            if (!$product_id) {
                if (getLang() == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => "Product is required"], 400);
                }
                return Response::json(['Status' => 'Error', 'message' => "من فضلك ادخل المنتج"], 400);
            } else {
                $product = $this->getProductByKey('id', $product_id);
                if ($product) {
                    $product_transformer = new ProductsTransformer;
                    return response()->json($product_transformer->transform($product->toArray()), 200);
                } else {
                    return response()->json('Product not found', 404);
                }
            }
        } else if (request()->has('slug')) {
            $product_slug = request('slug');
            if (!$product_slug) {
                if (getLang() == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => "Product is required"], 400);
                }
                return Response::json(['Status' => 'Error', 'message' => "من فضلك ادخل المنتج"], 400);
            } else {
                $lang = getLang();
                $product = $this->getProductByKey('slug_' . $lang, $product_slug);
                if ($product) {
                    $product_transformer = new ProductsTransformer;
                    return response()->json($product_transformer->transform($product->toArray()), 200);
                } else {
                    return response()->json('Product not found', 404);
                }
            }
        } else {
            if (getLang() == 'en') {
                return Response::json(['Status' => 'Error', 'message' => "Product is required"], 400);
            }
            return Response::json(['Status' => 'Error', 'message' => "من فضلك ادخل المنتج"], 400);
        }
    }

    public function relatedProducts()
    {
        $product_id = \request('id');
        if ($product_id > 0) {
            $products_array = [];
            $category_id = CategoryProducts::where('product_id', $product_id)
                ->distinct('category_id')
                ->pluck('category_id');
            if (count($category_id) > 0) {
                $categories_id = Categories::where('id', $category_id)
                    ->orWhere('parent_item_group', $category_id)
                    ->pluck('id');

                if (!count($categories_id) > 0) {
                    if (getLang() == 'en') {
                        return Response::json(['Status' => 'Erorr', 'message' => "Can't get this category now, please try again later "], 400);
                    }
                    return Response::json(['Status' => 'Erorr', 'message' => "لايمكن أيجاد هذا القسم فى الوقت الحالى, من فضلك قم بالمحاولة فى وقت لاحق "], 400);
                }

                $products_id = CategoryProducts::whereIn('category_id', $categories_id)
                    ->distinct('product_id')
                    ->pluck('product_id');

                if (!count($products_id) > 0) {
                    if (getLang() == 'en') {
                        return Response::json(['Status' => 'Erorr', 'message' => "Can't get this category now, please try again later "], 400);
                    }
                    return Response::json(['Status' => 'Erorr', 'message' => "لايمكن أيجاد هذا القسم فى الوقت الحالى, من فضلك قم بالمحاولة فى وقت لاحق "], 400);
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


                $product_transformer = new ProductsTransformer;
                $products_array = count($products) > 0 ? $product_transformer->transformCollection($products->toArray()) : [];
            } else {
                $products_array = [];
            }
            return response()->json($products_array);
        } else if (\request('slug')) {
            $product_slug = \request('slug');
            $lang = getLang();
            $slugLang = $lang == 'en' ? 'slug_en' : 'slug_ar';
            $product_id = Products::where($slugLang, $product_slug)->pluck('id')->toArray();
            $products_array = [];
            $category_id = CategoryProducts::where('product_id', $product_id[0])
                ->distinct('category_id')
                ->pluck('category_id');
            if (count($category_id) > 0) {
                $categories_id = Categories::where('id', $category_id)
                    ->orWhere('parent_item_group', $category_id)
                    ->pluck('id');

                if (!count($categories_id) > 0) {
                    if (getLang() == 'en') {
                        return Response::json(['Status' => 'Erorr', 'message' => "Can't get this category now, please try again later "], 400);
                    }
                    return Response::json(['Status' => 'Erorr', 'message' => "لايمكن أيجاد هذا القسم فى الوقت الحالى, من فضلك قم بالمحاولة فى وقت لاحق "], 400);
                }

                $products_id = CategoryProducts::whereIn('category_id', $categories_id)
                    ->distinct('product_id')
                    ->pluck('product_id');

                if (!count($products_id) > 0) {
                    if (getLang() == 'en') {
                        return Response::json(['Status' => 'Erorr', 'message' => "Can't get this category now, please try again later "], 400);
                    }
                    return Response::json(['Status' => 'Erorr', 'message' => "لايمكن أيجاد هذا القسم فى الوقت الحالى, من فضلك قم بالمحاولة فى وقت لاحق "], 400);
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


                $product_transformer = new ProductsTransformer;
                $products_array = count($products) > 0 ? $product_transformer->transformCollection($products->toArray()) : [];
            } else {
                $products_array = [];
            }
            return response()->json($products_array);
        } else {
            return response()->json('no related products', 400);
        }
    }

    public function filterProducts(Request $request)
    {
        $lang = getLang();
        $category = $request->category;
        $filters = $request->filters;
        if ((int) $category > 0) {
            $categoryObject = Categories::where('id', $category)->first();
        } else {
            $categoryObject = Categories::where('slug_' . $lang, $category)->first();
        }

        $keys_id = $values_id = [];

        foreach ($filters as $filter) {
            $keys_id[] = $filter['key_id'];
            $values_id[] = $filter['value_id'];
        }

        $products = Products::whereHas(
            'variantsProducts.variations',
            function ($query) use ($keys_id, $values_id) {
                $query->whereIn('variant_data_id', $keys_id);
                $query->whereIn('variant_meta_id', $values_id);
            }
        )
            ->whereHas('categories', function ($query) use ($categoryObject, $lang) {
                $query->where('categories.id', $categoryObject->id);
                $query->Orwhere('categories.parent_item_group', $categoryObject->id);
            })
            ->select(
                'products.id',
                'parent_variant_id',
                getLang() == 'en' ? 'slug_en as slug' : 'slug_ar as slug',
                getLang() == 'en' ? 'name_en as name' : 'name',
                getLang() == 'en' ? 'description_en as description' : 'description',
                'season_id'
            )
            ->with('variantsProducts.variations', 'favourite', 'images')
            ->get();
        $product_transformer = new ProductsTransformer;
        $products_array = count($products) > 0 ? $product_transformer->transformCollection($products->toArray()) : [];

        return $products_array;
    }

    public function getProductCms()
    {
        try {
            if (isset($_GET['id'])) {
                $lang = app('request')->header('lang');
                $token = request()->header('Authorization');
                if ($token == null) {
                    $token = request()->header('token');
                }

                $id = $_GET['id'];

                if (getFromCache('Product_' . $id)) {
                    return getFromCache('Product_' . $id);
                    // dd('Cached', getFromCache('Product_' . $id));
                }

                $product = Products::where('active', 1)->where('id', $id)->select('id', 'brand_id', 'name_en', 'description_en', 'name', 'description_en', 'standard_rate', 'item_code', 'name', 'description', 'item_group', 'has_attributes', 'uom', 'weight', 'stock_qty', 'has_variants', 'image')->first();
                if (!$product) {
                    return Response::json(['Status' => 'Error', 'message' => 'Bad Request,There is not product with id of ' . $id], 400);
                }
                $Cat = Categories::where('id', $product->item_group)->first();
                if ($Cat) {
                    $parent_category = Categories::where('id', $Cat->parent_item_group)->first();
                    if ($parent_category) {
                        $parent_category->name = $lang == "en" ? $parent_category->name_en : $parent_category->name;
                        $product->main_cat = array('id' => $parent_category->id, 'name' => $parent_category->name);
                    } else {
                        $product->main_cat = null;
                    }
                    $Cat->name = $lang == 'en' ? $Cat->name_en : $Cat->name;
                    $product->sub_cat = array('id' => $Cat->id, 'name' => $Cat->name);
                } else {
                    $product->main_cat = null;
                    $product->sub_cat = null;
                }
                if ($product) {
                    if ($token != null) {
                        $product = isFavouriteProduct($product, $token);
                        $product = productCartQty($token, $product);
                    }

                    $product = getProductObject($product);
                    $product->standard_rate = itemSellingPrice($product->id);
                    putInCache('Product_' . $id, $product);
                    $product = getProductWithVariations($product, $lang, 1);

                    if (is_array($product)) {
                        $product = $product[0];
                    }
                    return Response::json($product, 200);
                }
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'Bad Request'], 400);
            }
        } catch (\Exception $e) {
            $error = array($e->getMessage(), $e->getFile(), $e->getLine());
            event(new ErrorEmail($error));
            return $error;
        }
    }
    // Search For Products by key parameter name
    public function searchProducts()
    {

        $product_name = \request('name');
        $products = Products::active()->NotVariant()
            ->where('name', 'like', '%' . $product_name . '%')
            ->orwhere('name_en', 'like', '%' . $product_name . '%')
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


        $product_transformer = new ProductsTransformer;
        $products_array = count($products) > 0 ? $product_transformer->transformCollection($products->toArray()) : [];

        return $products_array;
    }
    // Get All Products Names
    public function allProductSearch()
    {
        $products = Products::active()->NotVariant()
            ->select(
                'products.id',
                'parent_variant_id',
                getLang() == 'en' ? 'slug_en as slug' : 'slug_ar as slug',
                getLang() == 'en' ? 'name_en as name' : 'name',
                getLang() == 'en' ? 'description_en as description' : 'description'
            )
            ->with('images')
            ->get();


        $products_array = [];
        foreach ($products as $product) {
            $products_array[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'description' => $product['description'],
                'images' => $this->getImages($product['images']),

            ];
        }
        return $products_array;
    }

    public function getImages($images)
    {
        $urls = [];
        foreach ($images as $image) {
            $image_url = url('/public/imgs/products/' . $image['image']);
            $urls[] = $image_url;
        }
        return $urls;
    }

    public function getProducts()
    {
        try {
            $lang = getLang();
            $token = request()->header('Authorization');
            if ($token == null) {
                $token = request()->header('token');
            }

            $products = Products::where('products.is_bundle', 0)->where('products.active', 1)->where('is_variant', 0)
                ->join('categories', 'products.item_group', '=', 'categories.id')
                ->orderBy('products.sorting_no', 'asc')
                ->select('products.*', 'categories.name as category_name', 'categories.name_en as category_name')->take(10)
                ->get();
            $product_array = [];
            foreach ($products as $product) {
                if ($token != null) {
                    $product = isFavouriteProduct($product, $token);
                    $product = productCartQty($token, $product);
                }
                $product->standard_rate = itemSellingPrice($product->id);
                $product->discount = $product->priceRuleRelation ? [
                    'price_list' => $product->priceRuleRelation->itemPrice->priceList->price_list_name,
                    'item_price_id' => $product->priceRuleRelation->itemPrice->id,
                    'min_price' => $product->priceRuleRelation->min_price,
                    'max_price' => $product->priceRuleRelation->max_price,
                    'min_qty' => $product->priceRuleRelation->min_qty,
                    'max_qty' => $product->priceRuleRelation->max_qty,
                    'discount_value' => $product->priceRuleRelation->discount_rate,
                    'discount_type' => $product->priceRuleRelation->discount_type,
                ] : null;
                $product = getProductObject($product);
                if ($product->stock_qty < 1) {
                    continue;
                }
                if (isset($product->standard_rate) && $product->standard_rate != 0) {
                    $product_array[] = $product;
                }
            }
            if ($product_array) {
                return Response::json($product_array, 200);
            } else {
                return Response::json([], 200);
            }
        } catch (\Exception $e) {
            $error = array($e->getMessage(), $e->getFile(), $e->getLine());
            event(new ErrorEmail($error));
            return $error;
        }
    }
    // Get Products Related to product in the parameter
    public function relatedProducts2()
    {
        try {
            $lang = getLang();
            $token = request()->header('Authorization');
            if ($token == null) {
                $token = request()->header('token');
            }

            if (isset($_GET['id'])) {
                $product_id = $_GET['id'];

                if (getFromCache('RelatedProducts_' . $product_id)) {
                    return getFromCache('RelatedProducts_' . $product_id);
                    // dd('Cached', getFromCache('RelatedProducts_' . $product_id));
                }

                $product = Products::where('id', $product_id)->first();
                if (!$product) {
                    if ($lang == 'en') {
                        return Response::json(['Status' => 'Erorr', 'message' => "Can't get this product now, please try again later "], 400);
                    } else {
                        return Response::json(['Status' => 'Erorr', 'message' => "لا يمكن أيجاد هذا المنتج فى الوقت الحالى, من فضلك قم بالمحاولة فى وقت لاحق "], 400);
                    }
                } else {
                    $relatedProducts = Products::where('active', 1)->where('item_group', $product->item_group)->isVariant()->where('id', '!=', $product->id)->select('id', 'image', 'name_en', 'name', 'description_en', 'description', 'item_group', 'item_code', 'weight', 'has_attributes', 'brand_id', 'has_variants')->orderBy('sorting_no', 'desc')->get();
                    foreach ($relatedProducts as $relatedProduct) {
                        if ($token != null) {
                            $relatedProduct = isFavouriteProduct($relatedProduct, $token);
                            $relatedProduct = productCartQty($token, $relatedProduct);
                        }
                        $relatedProduct = getProductObject($relatedProduct);
                        if ($relatedProduct->stock_qty < 1) {
                            continue;
                        }
                        $relatedProduct->standard_rate = itemSellingPrice($relatedProduct->id);
                    }
                    $returnProducts = getProductWithVariations($relatedProducts, $lang, 1);
                    return Response::json(putInCache('RelatedProducts_' . $product_id, $returnProducts), 200);
                }
            } else {
                return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
            }
        } catch (\Exception $e) {
            $error = array($e->getMessage(), $e->getFile(), $e->getLine());
            event(new ErrorEmail($error));
            return $error;
        }
    }
    //
    public function getProductBrandByCategoryID()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $lang = getLang();
            $token = app('request')->header('token');
            if ($token == null) {
                $token = app('request')->header('Authorization');
            }
            $warehouses_array = [];
            if ($token != null) {
                $user = \App\User::where('token', '=', $token)->first();
                if ($user) {
                    $address = Addresses::where('user_id', $user->id)->first();
                    if ($address && isset($address->district_id)) {
                        $districtId = $address->district_id;
                    } else {
                        $districtId = 1;
                    }
                    $warehouses = Warehouses::all();
                    foreach ($warehouses as $warehouse) {
                        if (in_array($address->district_id, json_decode($warehouse->district_id))) {
                            $warehouses_array[] = $warehouse->id;
                        }
                    }
                }
            }
            if (empty($user)) {
                return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get Category Products, Please login.'], 401);
            } else {
                $query = DB::table('products')->where('active', 1)->where('is_variant', 0)->Where(function ($query) use ($id) {
                    $query->where('item_group', '=', $id)
                        ->orWhere('second_item_group', '=', $id);
                });
                $distincts = $query->join('brands', 'brands.id', '=', 'products.brand_id')
                    ->select('brands.id')->distinct()->pluck('brands.id')->toArray();
                $product_array = [];
                foreach ($distincts as $distinct) {
                    ${$distinct} = $query;
                    $brand = Brands::where('id', $distinct)->first();
                    $products = DB::table('products')->where('active', 1)->where('is_variant', 0)->Where(function ($query) use ($id) {
                        $query->where('item_group', '=', $id)
                            ->orWhere('second_item_group', '=', $id);
                    })
                        ->join('item_prices', function ($join) {
                            $item_list = PriceList::where('price_list_name', 'Standard Selling')->first();
                            $join->on('products.id', '=', 'item_prices.product_id')
                                ->where('item_prices.price_list_id', '=', $item_list->id);
                        })->where('products.brand_id', intval($distinct))->select('products.*', 'item_prices.rate as standard_rate')->get();
                    if (count($products) == 0) {
                        continue;
                    }
                    foreach ($products as $product) {
                        $stock = 0;
                        if ($lang == "en") {
                            $product->brand = $brand->name_en;
                            $product->description = $product->description_en;
                            $product->name = $product->name_en;
                        } else {
                            $product->brand = $brand->name;
                            $product->description = $product->description;
                            $product->name = $product->name;
                        }
                        foreach ($warehouses_array as $warehouse) {
                            $item_stock = ItemWarehouse::where('warehouse_id', $warehouse)->where('product_id', $product->id)->first();
                            if ($item_stock) {
                                $stock += $item_stock->projected_qty;
                            }
                        }
                        $product->stock_qty = $stock;
                        $product->discount = $product->priceRuleRelation ? [
                            'price_list' => $product->priceRuleRelation->itemPrice->priceList->price_list_name,
                            'item_price_id' => $product->priceRuleRelation->itemPrice->id,
                            'min_price' => $product->priceRuleRelation->min_price,
                            'max_price' => $product->priceRuleRelation->max_price,
                            'min_qty' => $product->priceRuleRelation->min_qty,
                            'max_qty' => $product->priceRuleRelation->max_qty,
                            'discount_value' => $product->priceRuleRelation->discount_rate,
                            'discount_type' => $product->priceRuleRelation->discount_type,
                        ] : null;
                        $product = handleMultiImages($product);
                    }
                    if ($lang == "en") {
                        $product_array[] = (object) array('brand_name' => $brand->name_en, 'products' => $products);
                    } else {
                        $product_array[] = (object) array('brand_name' => $brand->name, 'products' => $products);
                    }
                }

                if (isset($product_array)) {
                    return Response::json($product_array, 200);
                }
            }
        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
        }
    }


    public function filterProductsFix(Request $request)
    {
        $filters = $request->filters;
        $lang = getLang();
        if (!empty($request->all())) {
            if ((!$request->has('category')) || (!is_array($request->filters)) || (!$request->has('filters'))) {
                return Response::json(['Status' => 'Error', 'message' => 'Bad Request'], 400);
            }

            if (count($filters) <= 0) {

                if ((int) $request->category > 0) {
                    $cats = Categories::where('id', $request->category)->first();
                } else {
                    $cats = Categories::where('slug_' . $lang, $request->category)->first();
                }
                if ($request->category != 0 && $cats) {
                    $id = $request->category;
                    if ($cats->has_sub) {
                        $subcats = Categories::where('parent_item_group', $cats->id)->get();
                        $subitemgroups = array();
                        foreach ($subcats as $subcat) {
                            $subitemgroups[] = $subcat->id;
                        }
                    }
                    if (isset($subitemgroups) && is_array($subitemgroups) && count($subitemgroups) > 0) {
                        $products = Products::where('active', 1)->isVariant()->Where(function ($query) use ($subitemgroups) {
                            $query->whereIn('item_group', $subitemgroups)
                                ->orWhereIn('second_item_group', $subitemgroups);
                        });
                    } else {
                        $products = Products::where('active', 1)->isVariant()->Where(function ($query) use ($id) {
                            $query->where('item_group', '=', $id)
                                ->orWhere('second_item_group', '=', $id);
                        });
                    }

                    if ($lang == 'en') {
                        $products = $products->select(
                            'id',
                            'name_en as name',
                            'slug_en as slug',
                            'image',
                            'description_en as description',
                            'has_variants',
                            'item_group',
                            'item_code',
                            'second_item_group',
                            'standard_rate',
                            'uom',
                            'has_attributes',
                            'weight',
                            'brand_id'
                        )->orderBy('sorting_no', 'desc')->get();
                    } else {
                        $products = $products->select(
                            'id',
                            'name',
                            'slug_ar as slug',
                            'image',
                            'description',
                            'has_variants',
                            'second_item_group',
                            'item_group',
                            'item_code',
                            'standard_rate',
                            'uom',
                            'has_attributes',
                            'weight',
                            'brand_id'
                        )
                            ->orderBy('sorting_no', 'desc')->get();
                    }
                } else {
                    if ($lang == 'en') {
                        $products = Products::where('active', 1)->scopeIsVariant()->select('id', 'name_en as name', 'slug_en as slug', 'image', 'description_en as description', 'has_variants', 'item_group', 'item_code', 'second_item_group', 'standard_rate', 'uom', 'has_attributes', 'weight', 'brand_id')->orderBy('sorting_no', 'desc')->get();
                    } else {
                        $products = Products::where('active', 1)->scopeIsVariant()->select('id', 'name', 'slug_ar as slug', 'image', 'second_item_group', 'description', 'has_variants', 'item_group', 'item_code', 'standard_rate', 'uom', 'has_attributes', 'weight', 'brand_id')->orderBy('sorting_no', 'desc')->get();
                    }
                }
                $products = getProductWithVariations($products, $lang, 1);
                foreach ($products as $product) {
                    $product->standard_rate = itemSellingPrice($product->id);
                    $product = handleMultiImages($product);
                    $product->stock_qty = getApiProductStocks($product);
                }
            } else {
                $products = [];
                $childs = [];
                $keys = [];
                $values = [];
                $products_variations = ProductVariations::where('variations_data', '!=', "")->get();
                //return $products_variations;
                $attributes = [];
                foreach ($filters as $filter) {

                    if ($filter['is_variant'] == 1) {
                        $filter_key = ($filter['key_id'] . ':' . $filter['value_id']);
                        foreach ($products_variations as $variant) {
                            $filterKeysArray = json_decode($variant->variations_data);
                            //$X[]= $filterKeysArray;
                            if (in_array($filter_key, $filterKeysArray)) {
                                $childs[] = $variant->parent_variant_id;
                            }
                        }
                        $childs = array_unique($childs);
                    } else {
                        $keys[] = $filter['key_id'];
                        $values[] = $filter['value_id'];
                    }
                }

                if (count($keys) > 0 && count($values) > 0) {
                    $attributes_records = ProductAttributes::whereIn('attribute_id', $keys)->whereIn('value_id', $values)->get();
                    foreach ($attributes_records as $attribute) {
                        if (!in_array($attribute->id, $attributes)) {
                            $attributes[] = $attribute->products_id;
                        }
                    }
                }

                if ((int) $request->category > 0) {
                    $cats = Categories::where('id', $request->category)->first();
                } else {
                    $cats = Categories::where('slug_' . $lang, $request->category)->first();
                }
                if ($request->category != 0 && $cats) {
                    $id = $request->category;
                    if ($cats->has_sub) {
                        $subcats = Categories::where('parent_item_group', $cats->id)->get();
                        $subitemgroups = array();
                        foreach ($subcats as $subcat) {
                            $subitemgroups[] = $subcat->id;
                        }
                    }

                    if (isset($subitemgroups) && is_array($subitemgroups) && count($subitemgroups) > 0) {
                        $query = DB::table('products')->where('active', 1)->whereNull('deleted_at')
                            ->where('is_variant', 0)->Where(function ($query) use ($subitemgroups) {
                                $query->whereIn('item_group', $subitemgroups)
                                    ->orWhereIn('second_item_group', $subitemgroups);
                            });
                    } else {
                        $query = DB::table('products')->where('active', 1)
                            ->whereNull('deleted_at')
                            ->where('is_variant', 0)->Where(function ($query) use ($id) {
                                $query->where('item_group', '=', $id)
                                    ->orWhere('second_item_group', '=', $id);
                            });
                    }
                } else {
                    $query = DB::table('products')->where('active', 1)->whereNull('deleted_at')->where('is_variant', 0);
                }

                if (count($childs) > 0 && count($attributes) > 0) {
                    $query = $query->where('has_variants', 1)->where('has_attributes', 1)->whereIn('id', $childs)->whereIn('id', $attributes);
                } elseif (count($childs) > 0 && count($attributes) == 0) {
                    $query = $query->where('has_variants', 1)->whereIn('id', $childs);
                } elseif (count($attributes) > 0 && count($childs) == 0) {
                    $query = $query->where('has_attributes', 1)->whereIn('id', $attributes);
                }
                if ($lang == "en") {
                    $products = $query->select('id', 'name_en as name', 'image', 'description_en as description', 'has_variants', 'item_group', 'item_code', 'second_item_group', 'standard_rate', 'uom', 'has_attributes', 'weight', 'brand_id', 'season_id')->orderBy('sorting_no', 'desc')->get();
                } else {
                    $products = $query->select('id', 'name', 'image', 'description', 'has_variants', 'item_group', 'item_code', 'second_item_group', 'standard_rate', 'uom', 'has_attributes', 'weight', 'brand_id', 'season_id')->orderBy('sorting_no', 'desc')->get();
                }
                // dd("products", $products);
                $products = getProductWithVariations($products, $lang, 1);
            }

            return Response::json($products, 200);
        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
        }
    }

    public function getProductByKey($key, $value)
    {
        $product = Products::where($key, $value)
            ->select(
                'products.id',
                'parent_variant_id',
                getLang() == 'en' ? 'slug_en as slug' : 'slug_ar as slug',
                getLang() == 'en' ? 'name_en as name' : 'name',
                getLang() == 'en' ? 'description_en as description' : 'description',
                'season_id'
            )

            //            ->has('variantsProducts')
            ->with('variantsProducts', 'favourite', 'images')
            ->first();

        if (!$product) {
            return;
        } else {
            return $product;
        }
    }
}
