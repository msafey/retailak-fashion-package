<?php

use App\Addresses;
use App\Brands;
use App\Cart;
use App\CartItems;
use App\Categories;
use App\District;
use App\Favorite;
use App\ItemPrice;
use App\ItemWarehouse;
use App\PriceList;
use App\ProductAttributes;
use App\Products;
use App\ProductVariations;
use App\Promocode;
use App\SalesReport;
use App\Seasons;
use App\Settings;
use App\ShippingRule;
use App\Taxs;
use App\UsedPromocode;
use App\User;
use App\VariantsMeta;
use App\Variations;
use App\Warehouses;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;


function getFromCache($key)
{
    $str = cahceKey($key);
    if (Cache::has($str)) {
        $data = Cache::get($str);
        return $data;
    }
}

function putInCache($key, $content)
{
    return Cache::remember(cahceKey($key), 86400, function () use ($content) {
        return $content;
    });
}

function cahceKey($key): string
{
    return $key . config('app.name');
}

function shippingRate($addressId)
{
    $address = Addresses::find($addressId);
    $rate = 0;
    if (!is_null($address)) {
        $region = $address->district_id;
        $district = District::find($region);
        if (!is_null($district)) {
            $shippingRole = $district->shipping_role;
            $shipping_rule = ShippingRule::where('id', $shippingRole)->first();
            if ($shipping_rule) {
                $rate = $shipping_rule->rate;
            }
        }
    }
    return $rate;
}

function updateUserDeviceData()
{
    $lang = getLang();
    $device_id = request()->header('deviceId');
    $device_os = request()->header('deviceOs');
    $app_version = request()->header('applicationVersion');
    $token = request()->header('Authorization');
    if ($token) {
        $user = Auth::guard()->user();
        if ($user != null) {
            $user->update([
                'device_id' => $device_id,
                'device_os' => $device_os,
                'app_version' => $app_version,
            ]);
        }
    } else {
        return false;
    }
}

function getAddressWarehouses($address_id)
{
    $address = Addresses::where('id', $address_id)->first();
    if ($address) {
        $warehouse = getDistrictWarehouse($address->district_id);
        return $warehouse;
    } else {
        return null;
    }
}

function variationsValues($variant_id, $parent_id, $lang)
{
    $variant_array = [];
    $variant_code = [];
    $product = Products::find($variant_id);
    $productStock = getApiProductStocks($product);

    $variation_record = ProductVariations::where('products_id', $variant_id)->where('parent_variant_id', $parent_id)->first();
    if ($variation_record && $productStock > 0) {

//        ["1:45","2:19"]
        $variation_values = json_decode($variation_record->variations_data);
        if (is_array($variation_values) && count($variation_values) > 0) {
            foreach ($variation_values as $value) {
                $exp = explode(':', $value);
                $variation = Variations::where('id', $exp[0])->first();
                $variant_meta = VariantsMeta::where('id', $exp[1])->first();
                if ($variant_meta && $variation) {
                    if ($lang == "en") {
                        $variant_code[] = $variant_meta->variation_value_en;
                        $variant_array[] = (object)array('key' => $variation->key, 'name' => $variation->name_en, 'value' => $variant_meta->variation_value_en, 'code' => $variant_meta->code, 'value_id' => $variant_meta->id, 'key_id' => $variation->id);
                    } else {
                        $variant_code[] = $variant_meta->variation_value;
                        $variant_array[] = (object)array('key' => $variation->key, 'name' => $variation->name, 'value' => $variant_meta->variation_value, 'code' => $variant_meta->code, 'value_id' => $variant_meta->id, 'key_id' => $variation->id);
                    }
                }
            }
        }
    }

    $response_array['variant_array'] = $variant_array;
    $response_array['variant_code'] = $variant_code;

    return $response_array;
}

function updateSalesReport($product_id, $qty)
{
    $date = date('Y-m-d');
    $product = Products::find($product_id);
    if ($product) {
        $categoryId = $product->item_group;
        $category = Categories::find($categoryId);
        if ($category) {

            $salesReport = SalesReport::where('date', $date)->where('product_id', $product_id)->first();
            if ($salesReport) {
                $orgQty = $salesReport->qty;
                $newQty = $orgQty + $qty;
                $salesReport->qty = $newQty;
                $salesReport->save();

            } else {
                $salesReport = new SalesReport;
                $salesReport->product_id = $product_id;
                $salesReport->category_id = $category->id;
                $salesReport->date = $date;
                $salesReport->qty = $qty;
                $salesReport->save();
            }
        }

    }

}

// Getting Every Variation Values For Product example : color :[red,blue],size:[M,L]
function getEveryVariations($parent_product, $lang)
{
    $variant_products = Products::where('parent_variant_id', $parent_product->id)->get();
    $array = array();
    foreach ($variant_products as $variant) {
        $variantStockNo = getApiProductStocks($variant);
        if ($variantStockNo > 0) {
            $variationvalues = variationsValues($variant->id, $parent_product->id, $lang);
            $variation_values = $variationvalues['variant_array'];
            $variant_code = $variationvalues['variant_code'];

            foreach ($variation_values as $k => $val) {
                if (isset($array[$val->name])) {
                    if (in_array($val->value, $array[$val->name])) {
                        continue;
                    }
                }
                $array[$val->name][] = $val;
            }
        }

    }

    $variant_array = [];
    foreach ($array as $k => $value) {

        $value = my_array_unique($value, false);

        if (isset($value[0])) {
            $key_color = $value[0]->key;
            $key_id = $value[0]->key_id;
        } else {
            $key_color = null;
            $key_id = null;
        }
        $variant_array[] = (object)array('is_color' => $key_color, 'name' => $k, 'values' => $value, 'key_id' => $key_id);
    }
    return $variant_array;
}

// checking if there's duplicate objects and uset it
function my_array_unique($array, $keep_key_assoc = false)
{
    $duplicate_keys = array();
    $tmp = array();
    foreach ($array as $key => $val) {
        // convert objects to arrays, in_array() does not support objects
        if (is_object($val)) {
            $val = (array)$val;
        }

        if (!in_array($val, $tmp)) {
            $tmp[] = $val;
        } else {
            $duplicate_keys[] = $key;
        }

    }

    foreach ($duplicate_keys as $key) {
        unset($array[$key]);
    }

    return $keep_key_assoc ? $array : array_values($array);
}

// Getting Products That has_variants
function getProductWithVariations($products, $lang, $onlyAvailable = null)
{
    $newProducts = array();
    foreach ($products as $key => $product) {
        $parentStockQty = getApiProductStocks($product);
        if ($product->has_variants == 1) {
            $variation_option = [];
            if ($lang == "en") {
                $variants = Products::where('parent_variant_id', $product->id)
                    ->select('id', 'name_en as name', 'item_code', 'standard_rate', 'brand_id', 'season_id')->get();
            } else {
                $variants = Products::where('parent_variant_id', $product->id)
                    ->select('id', 'name', 'item_code', 'standard_rate', 'brand_id', 'season_id')->get();
            }
            $variantStocks = array();
            $newVariants = array();
            foreach ($variants as $variant) {
                $variant = getProductObject($variant);

                $variantStockNo = getApiProductStocks($variant);
                $variantStocks[] = $variantStockNo;

                if (($onlyAvailable === 1 && $variantStockNo > 0) || ($onlyAvailable === null)) {
                    $variant->stock_qty = $variantStockNo;
                    $variant->brand = trim(getBrandName($variant));
                    $variationvalues = variationsValues($variant->id, $product->id, $lang);
                    $variation_values = $variationvalues['variant_array'];
                    $variant_code = $variationvalues['variant_code'];
                    $variant->parent_id = $product->id;
                    $variant->variant_option = $variation_values;
                    $variant->variant_code = $variant_code;
                    $season = Seasons::find($variant->season_id);
                    if ($season) {
                        $variant->season = $season->name_en;
                    } else {
                        $variant->season = '';
                    }

                    $variant_images = handleMultiImages($variant);
                    $newVariants[] = $variant;
                }
            }
            $product->stock_qty = empty($variantStocks) ? 0 : max($variantStocks);
            $product->variants = getEveryVariations($product, $lang);
            $product->variants_products = $newVariants;
            $product->brand = trim(getBrandName($product));

            $season = Seasons::find($product->season_id);
            if ($season) {
                $product->season = $season->name_en;
            } else {
                $product->season = '';
            }

            $product->standard_rate = $product->standard_rate ?: itemSellingPrice($product->id);
            $product->cost = itemBuyingPrice($product->id);
            $product->discount =  $product->priceRuleRelation ? [
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
        } else {
            $product->brand = trim(getBrandName($product));

            $product->stock_qty = $parentStockQty;

            $product->standard_rate = $product->standard_rate ?: itemSellingPrice($product->id);
            $product->cost = itemBuyingPrice($product->id);

        }
        if (isset($product->has_attributes) && $product->has_attributes == 1) {
            $product_attributes = ProductAttributes::where('products_id', $product->id)->get();
            $attribute_array = [];
            foreach ($product_attributes as $attribute) {
                $attribute_object = Variations::where('id', $attribute['attribute_id'])->first();
                $attribute_values = VariantsMeta::where('id', $attribute['value_id'])->first();
                $attribute_array[] = (object)array('var_attr_name' => $attribute_object->name_en, 'var_attr_value' => $attribute_values->variation_value_en, 'key_id' => $attribute_object->id, 'value_id' => $attribute_values->id);
            }
            $product->var_attributes = $attribute_array;
        }
        if (($onlyAvailable === 1 && $product->stock_qty > 0) || $onlyAvailable === null) {
            $newProducts[] = $products[$key];
        }
    }
    return $newProducts;
}

// Getting Total Amount After Adding Shipping Rule
function shippingRule($order, $grand_total_amount)
{
    $shipping_rule = ShippingRule::where('id', $order->shipping_rule)->first();
    if ($shipping_rule) {
        $shipping_rule_rate = $shipping_rule->rate;
        $grand_total_amount += $shipping_rule_rate;
    } else {
        $shipping_rule_rate = 0;
    }
    $array['shipping_rule_rate'] = $shipping_rule_rate;
    $array['grand_total_amount'] = $grand_total_amount;
    return $array;
}

// getCategories btgeb el categories parent level we child level bos 3ala el category select htfhm htla2eha levels
function getCategories($categories)
{
    $parent_category = [];
    foreach ($categories as $category) {
        if (!is_null($category->parent_item_group) || $category->parent_item_group == 0) {
            $parent = Categories::where('id', $category->parent_item_group)->where('status', 1)->first();
            if ($parent) {
                $parent_category[$parent->name_en][] = $category;
            }
        } else {
            $parent_category['Parent Categories'][] = $category;
        }
    }
    return $parent_category;
}

// Getting Total Amount After Adding Taxs
function taxes($order, $grand_total_amount)
{
    $selected_tax = Taxs::where('id', $order->tax_and_charges)->first();
    if ($selected_tax) {
        if ($selected_tax->type == 'Actual') {
            $taxs_amount = $selected_tax->amount;
            $grand_total_amount += $taxs_amount;
            $taxs_rate = 0;
        } else {
            $taxs_rate = $selected_tax->rate;
            $total_percentage = $grand_total_amount / 100 * $taxs_rate;
            $grand_total_amount += $total_percentage;
            $taxs_amount = 0;
        }
    } else {
        $taxs_amount = 0;
        $taxs_rate = 0;
    }
    $array['taxs_rate'] = $taxs_rate;
    $array['taxs_amount'] = $taxs_amount;
    $array['grand_total_amount'] = $grand_total_amount;
    return $array;
}

// Product Buying Price For PO
function itemBuyingPrice($product_id)
{
    $item_rate = 0;
    $item_details = ItemPrice::where('product_id', $product_id)->get();
    foreach ($item_details as $price) {
        $price_list = PriceList::where('id', $price->price_list_id)->first();
        if ($price_list) {
            foreach (json_decode($price_list->type) as $type) {
                if ($type == 1) {
                    $item_rate = $price->rate;
                }
            }
        }

    }
    return $item_rate;
}

// Product Selling Price For SO
function itemSellingPrice($product_id)
{
    $product = Products::where('id', $product_id)->first();

    if (checkProductConfig('maintaining_stocks') == true) {
        $item_rate = 0;

//        price_list_id => 1
        $item_details = ItemPrice::where('product_id', $product_id)->get();

        if ($item_details) {

            foreach ($item_details as $price) {

                $price_list = PriceList::where('id', $price->price_list_id)->first();

                if ($price_list) {
                    foreach (json_decode($price_list->type) as $type) {
                        if ($type == 0) {
                            $item_rate = $price->rate;
                        }
                    }
                }
            }
        }

        if ($product && $product->is_variant == 1 && $item_rate == 0) {
            $parent = Products::where('id', $product->parent_variant_id)->first();
            $item_rate = itemSellingPrice($parent->id);
        }
    } else {
        $item_rate = $product->standard_rate;
    }

    return $item_rate;
}

// Getting Selected Taxs Amount
function selectedTaxs($purchase_order)
{
    $taxs_rate = 0;
    $taxs_amount = 0;
    $selected_tax = Taxs::where('id', $purchase_order->tax_and_charges)->first();
    if ($selected_tax) {
        $taxs_amount = $selected_tax->type == "Actual" ? $selected_tax->amount : 0;
        $taxs_rate = $selected_tax->type != "Actual" ? $selected_tax->rate : 0;
    }
    $array['selected_tax'] = $selected_tax;
    $array['taxs_rate'] = $taxs_rate;
    $array['taxs_amount'] = $taxs_amount;
    return $array;
}

// Function used in cms to get All Stocks Of Product related to which warehouse ... everything
function getProductStocks($product_id)
{
    $product_stock_qty = 0;
    $stocks_warehouse = [];
    $array = array();
    $product = Products::where('id', $product_id)->first();
    if ($product) {
        $product_stocks = ItemWarehouse::where('product_id', $product_id)->get();
        if ($product_stocks) {
            foreach ($product_stocks as $stock) {
                $product_stock_qty += $stock->projected_qty;
                $stock_warehouse = Warehouses::where('id', $stock->warehouse_id)->first();
                if ($stock_warehouse) {
                    $stocks_warehouse[] = (object)array('warehouse_name' => $stock_warehouse->name, 'projected_qty' => $stock->projected_qty, 'product_name' => $product->name_en);
                }
            }

        }
        $array[$product->id] = $stocks_warehouse;
    } else {
        return redirect()->back()->withErrors(["Product id $product_id does not exist or in-active"]);
    }
    $array['product_name'] = isset($product->name_en) ? $product->name_en : "";
    $array['item_code'] = $product->item_code;
    $array['stocks_warehouse'] = $stocks_warehouse;
    $array['selling_price'] = itemSellingPrice($product->id);
    $array['cost'] = itemBuyingPrice($product->id);

    $array['product_stock_qty'] = $product_stock_qty;

    return $array;
}

function getProductsStocks($productsIds)
{
    if (is_array($productsIds)) {

        $productStocks = [];
        $itemWarehouses = ItemWarehouse::whereIn('product_id', $productsIds)->get();

        foreach ($itemWarehouses as $itemWarehouse) {

            $product_id = $itemWarehouse->product_id;
            $warehouseId = $itemWarehouse->warehouse_id;
            $projectedQty = $itemWarehouse->projected_qty;
            $warehouse = Warehouses::find($warehouseId);
            if ($warehouse) {
                $warehouseName = $warehouse->name_en;

                $productStocks[$product_id][$warehouseName] = $projectedQty;
            }
        }

        return $productStocks;

        $product_stocks_warehouseIds = ItemWarehouse::whereIn('product_id', $productsIds)->pluck('warehouse_id');

        $stock_warehouses = Warehouses::whereIn('id', $product_stocks_warehouseIds)->get();
        if ($stock_warehouses) {
            foreach ($stock_warehouses as $stock_warehouse) {
                if (isset($product_stocks_warehouses[$stock_warehouse->id])) {
                    dd($product_stocks_warehouses[$stock_warehouse->id]);

                    $stocks_warehouse[] = (object)array('warehouse_name' => $stock_warehouse->name, 'projected_qty' => $product_stocks_warehouses[$stock_warehouse->id]);
                    $array[$product_stocks_warehouses[$stock_warehouse->id]->product_id] = $stocks_warehouse;

                }
            }
        }

        return $array;
    }

}

function getTokenFromReq(\Illuminate\Http\Request $request)
{
    $token = app('request')->header('token');
    if ($token == null) {
        $token = app('request')->header('Authorization');
    }
    return $token;
}

function getDistrictBranch($token, $districtId = null)
{
    if (is_null($districtId) || $districtId < 1) {
        $user = \App\User::where('token', '=', $token)->first();
        if ($user) {
            $address = Addresses::where('user_id', $user->id)->first();
            if ($address) {
                $districtId = $address->regoin;
            }
        }
    } else {
        $district = District::find($districtId);
        // dd($district)
        // return $district;
        if ($district) {
            ;
        } else {
            $districtId = 1;
        }
    }
    $branch = null;
    $dbBranches = Warehouses::get();
    // return $dbBranches;
    foreach ($dbBranches as $dbBranch) {
        if (in_array($districtId, json_decode($dbBranch->district_id))) {
            $branch = $dbBranch;
        }
    }
    if (is_null($branch)) {
        $districtId = 1;
        foreach ($dbBranches as $dbBranch) {
            if (in_array($districtId, json_decode($dbBranch->district_id))) {
                $branch = $dbBranch;
            }

        }
    }
    return $branch;
}

function unsetFoods($product)
{
    unset($product->is_food_extras);
    unset($product->food_taste_available);
    unset($product->food_optional_note);
    unset($product->food_type);
    return $product;
}

function unsetAccordingToType($product)
{
    if (checkProductConfig('variations')) {
        $product = unsetFoods($product);
    } elseif (!checkProductConfig('variations') && !checkProductConfig('foods')) {
        $product = unsetFoods($product);
        unset($product->has_variants);
        unset($product->is_variant);
        unset($product->parent_variant_id);
        unset($product->has_attributes);
    }
    unset($product->created_at);
    unset($product->updated_at);
    unset($product->deleted_at);
    unset($product->is_deleted);
    unset($product->user_deleted);
    unset($product->name_en);
    unset($product->description_en);
    return $product;
}

function getProductObject($product)
{

    $lang = getLang();
    $product->name = isset($product->name) && isset($product->name_en) && $lang == 'en'
        ? $product->name_en : $product->name;
    if ($lang == 'en' && isset($product->description_en)) {
        $product->description = $product->description_en;
    } else {
        if (!isset($product->description)) {
            $product->description = '';
        }

    }

    if (isset($product->brand_id)) {
        $brand = Brands::where('id', $product->brand_id)->first();
        if ($brand) {
            $product->brand = $lang == "en" ? $brand->name_en : $brand->name;
        }
    }
    $product->standard_rate = itemSellingPrice($product->id);
    $product->cost = itemBuyingPrice($product->id);
    $product = handleMultiImages($product);
    $product->stock_qty = getApiProductStocks($product);
    $product->discount =  $product->priceRuleRelation ? [
        'price_list' => $product->priceRuleRelation->itemPrice->priceList->price_list_name,
        'item_price_id' => $product->priceRuleRelation->itemPrice->id,
        'min_price' => $product->priceRuleRelation->min_price,
        'max_price' => $product->priceRuleRelation->max_price,
        'min_qty' => $product->priceRuleRelation->min_qty,
        'max_qty' => $product->priceRuleRelation->max_qty,
        'discount_value' => $product->priceRuleRelation->discount_rate,
        'discount_type' => $product->priceRuleRelation->discount_type,
    ] : null;
    $product = unsetAccordingToType($product);
    if (checkProductConfig('variations')) {
        $products[] = $product;
        $product = getProductWithVariations($products, $lang);
        $product = $product[0];
    }
    return $product;
}

function getBrandName($product)
{
    $lang = getLang();
    $brand_name = '';
    if (isset($product->brand_id)) {
        $brand = Brands::where('id', $product->brand_id)->first();
        if ($brand) {
            $brand_name = $lang == "en" ? $brand->name_en : $brand->name;
        }
    }
    return $brand_name;
}

// Get User District Id By User Token
function getUserDistrict($token)
{
    $districtId = null;
    $user = \App\User::where('token', '=', $token)->first();
    if ($user) {
        $address = Addresses::where('user_id', $user->id)->where('active', 1)->first();
        if ($address) {
            $districtId = $address->district_id;
        }
    } else {
        return 'false';
    }
    return $districtId;
}

// Getting District Id by check if user logged in or in header district id
function getDistrictId()
{
    $districtId = null;
    $headers = getallheaders();
    if (isset($headers['district_id'])) {
        $districtId = $headers['district_id'];
    } else {
        if (isset($headers['Authorization']) || isset($headers['token'])) {
            $districtId = isset($headers['Authorization']) ? getUserDistrict($headers['Authorization']) : getUserDistrict($headers['token']);
        }
    }
    if (is_null($districtId)) {
        $districtId = 0;
    }
    return $districtId;
}

// Get Warehouse Of District by District Id
function getDistrictWarehouse($districtId)
{
    $warehouse = null;
    $dbWarehouses = Warehouses::get();
    foreach ($dbWarehouses as $dbWarehouse) {
        if (in_array($districtId, json_decode($dbWarehouse->district_id))) {
            $warehouse = $dbWarehouse;
        }
    }
    return $warehouse;
}

// Getting The Stocks Of Product In specific Warehouse
function getStockForProductByDistrict($product, $warehouse)
{
    $productQty = 0;
    if ($warehouse) {
        $warehouseProduct = ItemWarehouse::where('warehouse_id', $warehouse->id)
            ->where('product_id', $product->id)->first();
        if ($warehouseProduct) {
            $productQty = $warehouseProduct->projected_qty;
        }
    }
    return $productQty;
}

// Get Product Stocks
function getApiProductStocks($product)
{
    $districtId = getDistrictId();


    $productQty = getStocks($product, $districtId);
    return $productQty;
}

function getStocks($product, $districtId)
{
    $productQty = 0;
    if ($districtId != 0) {
        $warehouse = getDistrictWarehouse($districtId);
        if (!is_null($warehouse)) {
            $productQty = getStockForProductByDistrict($product, $warehouse);
            return $productQty;
        } else {
            $productQty = getLowestStocks($product);
            return $productQty;
        }
    } else {
        $productQty = getLowestStocks($product);
        return $productQty;
    }
}

// This Function Is Used When There's No User LoggedIn or there's no district_id in header so i get the lowest stock of this product
function getLowestStocks($product)
{
    $productQty = 0;
    if ($product && isset($product->id)) {
        $warehouseProducts = ItemWarehouse::where('product_id', $product->id)->selectRaw('min(projected_qty) as stock_qty')->first();
        if ($warehouseProducts) {
            $productQty = $warehouseProducts->stock_qty;
        }
    }

    return $productQty;
}

// Foods
function getExtraProducts($extras, $lang)
{
    $extra_array = array();
    foreach ($extras as $extra) {
        $extraProduct = \App\Products::where('id', '=', $extra['extra_id'])->select('id', 'name', 'name_en', 'description', 'description_en', 'item_group', 'image', 'standard_rate', 'is_food_extras')->first();
        if ($extraProduct) {
            $extraProduct->name = $lang == 'en' ? $extraProduct->name_en : $extraProduct->name;
            $extraProduct->description = $lang == 'en' ? $extraProduct->description_en : $extraProduct->description;
            $extraProduct->qty = $extra['qty'];
            $extraProduct = handleMultiImages($extraProduct);
            $extra_array[] = $extraProduct;
        }
    }
    return $extra_array;
}

function getEveryOptions($product, $lang)
{
    $products_options = Products::where('parent_variant_id', $product->id)->active()->whereNULL('deleted_at')->get();
    $options = array();
    foreach ($products_options as $option) {
        $options[] = $option->name_en;
    }
    $object = new stdClass;
    if ($product->food_type == 'options') {
        $object->key = 'options';
    } elseif ($product->food_type == 'multi_sizes') {
        $object->key = 'multi_size';
    }
    $object->value = $options;
    return $object;
}

function getProductsWithFoods($products)
{
    $lang = getLang();
    foreach ($products as $product) {
        if ($product->has_variants == 1) {
            $food_option = [];
            if ($lang == "en") {
                $food_options = Products::where('parent_variant_id', $product->id)->select('id', 'name_en as name', 'standard_rate')->get();
            } else {
                $food_options = Products::where('parent_variant_id', $product->id)->select('id', 'name', 'standard_rate')->get();
            }
            foreach ($food_options as $option) {
                $option_extras = $option->foodExtras;
                $extras = getExtraProducts($option_extras, $lang);
            }
            $product->food_options = getEveryOptions($product, $lang);
            $product->food_products = $food_options;
        } else {
            $product->food_options = 'one_size';
            $product_extras = isset($product->foodExtras) ? $product->foodExtras : [];
            // $product->extras = getExtraProducts($product_extras,$lang);
        }
    }

    return $products;
}

function getCartItems($token, $items, $lang)
{
    $returnedproducts = array();
    foreach ($items as $item) {
        if (!isset($item['item_id'])) {
            continue;
        }
        $dbProduct = \App\Products::where('id', '=', $item['item_id'])->select('id', 'name', 'name_en',
         'description', 'description_en', 'item_group', 'standard_rate', 'food_taste_available',
         'food_optional_note', 'image', 'active', 'uom', 'weight', 'item_code', 'is_variant',
          'has_variants', 'parent_variant_id')->first();
        if ($dbProduct == null) {
            continue;
        }
        $dbProduct = getProductObject($dbProduct);
        if (checkProductConfig('foods') && checkProductConfig('maintaining_stocks') == false) {
            if (isset($item->cartExtras)) {
                $extras = $item->cartExtras;
                $extra_array = getExtraProducts($extras, $lang);
                $dbProduct->extras = $extra_array;
            }
        }
        unset($dbProduct->stock_qty);
        $dbProduct->qty = $item->qty;
        $dbProduct = isFavouriteProduct($dbProduct, $token);
        $dbProduct->standard_rate = itemSellingPrice($dbProduct->id);
        $dbProduct->after_discount = $dbProduct->priceRuleRelation ? $item->price : null;
        $dbProduct->discount = $dbProduct->priceRuleRelation ? [
            'price_list' => $dbProduct->priceRuleRelation->itemPrice->priceList->price_list_name,
            'item_price_id' => $dbProduct->priceRuleRelation->itemPrice->id,
            'min_price' => $dbProduct->priceRuleRelation->min_price,
            'max_price' => $dbProduct->priceRuleRelation->max_price,
            'min_qty' => $dbProduct->priceRuleRelation->min_qty,
            'max_qty' => $dbProduct->priceRuleRelation->max_qty,
            'discount_value' => $dbProduct->priceRuleRelation->discount_rate,
            'discount_type' => $dbProduct->priceRuleRelation->discount_type,
        ] : null;
        $returnedproducts[] = $dbProduct;
    }
    $returnedproducts = getProductWithVariations($returnedproducts, 'en');
    return $returnedproducts;
}

// getting total of order item
function itemTotalPrice($item)
{
    $total = 0;
    $total += $item->qty * $item->rate;
    if (checkProductConfig('foods')) {
        $extras = $item->orderExtras;
        foreach ($extras as $extra) {
            $total += $extra->qty * $extra->rate;
        }
    }
    return $total;
}

// This Handles Images If it's Multiple or Single Image when configuration multi_images is true it's multiple image and if it's false single image activated
function handleMultiImages($product)
{
    if (checkProductConfig('multi_images') == true) {
        $image_url = getImages($product->id);
        $product->images = $image_url;
        unset($product->image);
    } else {
        $product_image = Image::where('content_id', $product->id)->where('content_type', 'App\Products')->first();
        if ($product_image) {
            $url = url('/public/imgs/products/' . $product_image->image);
            $product->image = $url;
        } else {
            $product_image = Image::where('content_id', $product->parent_variant_id)
                ->where('content_type', 'App\Products')->first();
            if ($product_image) {
                $url = url('/public/imgs/products/' . $product_image->image);
                $product->image = $url;
            }
        }
    }
    return $product;
}

// Getting Product Images url Array
function getImages($product_id)
{
    $product = Products::where('id', $product_id)->first();
    if ($product) {
        $url = array();
        $product_images = $product->images()->orderBy('image_order')->get();
        foreach ($product_images as $product_image) {
            $url[] = url('/public/imgs/products/' . $product_image->image);
        }
        if ($url == []) {
            $product_images = App\Image::where('content_id', $product->parent_variant_id)
                ->where('content_type', 'App\Products')->get();
            if ($product_images) {
                foreach ($product_images as $product_image) {
                    $url[] = url('/public/imgs/products/' . $product_image->image);
                }
            }
        }
        return $url;
    }
}

// Storing Base 64 Images
function storeImageBase64($base64Image, $type)
{
    $decodedImage = base64_decode($base64Image);

    $imageName = rand(1, 999) . time() . $decodedImage->getClientOriginalExtension();
    $fp = fopen(public_path() . '/imgs/' . $type . '/' . $imageName, 'wb+');
    $fp = fopen(public_path() . '/imgs/' . $type . '/thumb/' . $imageName, 'wb+');
    fwrite($fp, $decodedImage);
    fclose($fp);
    return $imageName;
}

// Check IF Image Is base64>?
function is_base64($base64Image)
{
    return (bool)preg_match('`^[a-zA-Z0-9+/]+={0,2}$`', $base64Image);
}

function getOrderShippingRate($order)
{
    $array = [];
    $shipping_rate = 0;
    if ($order) {
        $user = User::where('id', $order->user_id)->first();
        $address = Addresses::where('id', $order->address_id)->first();
        if ($address) {
            $district = District::where('id', $address->district_id)->first();
            if ($district) {
                $shipping_role = ShippingRule::where('id', $district->shipping_role)->where('disabled', 0)->first();
                if ($shipping_role) {
                    $shipping_rate = $shipping_role->rate;
                }
            }
        }
    }
    if (isset($shipping_rate)) {
        $array['shipping_rate'] = $shipping_rate;
    }

    if (isset($shipping_role)) {
        $array['shipping_role'] = $shipping_role;
    }

    return $array;
}

function orderGrandTotal($order)
{
    $grand_total_amount = 0;
    if ($order && isset($order->orderItems)) {
        $order_items = $order->orderItems;
        if (count($order_items) > 0) {
            foreach ($order_items as $item) {
                $grand_total_amount += $item->rate * $item->qty;
            }
        }
    }
    return $grand_total_amount;
}

// Get discount on order
function orderPromocodeDiscount($order, $grand_total_amount, $shipping_rate)
{
    $total_amount_after_discount = 0;
    $freeShipping = 0;
    $promo_code = '';
    $promocode_msg = '';
    $discount_type = 'false';
    $discount_amount = 0;
    $user = User::where('id', $order->user_id)->first();
    $freeShipping = 0;
    if ($user) {
        $used_promo_code = UsedPromocode::where('user_id', $user->id)->where('order_id', $order->id)->first();
        if ($used_promo_code) {
            $promo_code = $used_promo_code->code;
            if ($promo_code) {
                $promocode = Promocode::where('code', $promo_code)->first();
                if ($promocode) {
                    $promo_code = $promocode->code;
                    $discount_amount = $promocode->reward;

                    if ($promocode->freeShipping == 1) {
                        $grand_total_amount -= $shipping_rate;
                        $freeShipping = 1;
                        $promocode_msg = 'Have Free Shipping &';
                    }
                    if ($promocode->type == 'persentage') {
                        $promo_code_discount = $grand_total_amount * $promocode->reward / 100;
                        $total_amount_after_discount = $grand_total_amount - $promo_code_discount;
                        $promocode_msg = $promocode_msg . ' Discount = ' . $promocode->reward . '%';
                        $total_amount_after_discount = round($total_amount_after_discount, 2);
                        $discount_type = "persentage";
                    } elseif ($promocode->type == 'amount') {
                        $total_amount_after_discount = $grand_total_amount - $promocode->reward;
                        $promocode_msg = $promocode_msg . ' and have discount rate = ' . $promocode->reward;
                        $total_amount_after_discount = round($total_amount_after_discount, 2);
                        $discount_type = "rate";
                    }
                    if ($total_amount_after_discount < 0) {
                        $total_amount_after_discount = 0;
                    }
                }
            }
        } else {
            $total_amount_after_discount = $grand_total_amount;
        }
    }
    $array['discount_amount'] = $discount_amount;
    $array['discount_type'] = $discount_type;
    $array['promocode'] = $promo_code;
    $array['promocode_msg'] = $promocode_msg;
    $array['freeShipping'] = $freeShipping;
    $array['total_amount_after_discount'] = $total_amount_after_discount;
    return $array;
}

function getMaxExpirationDays()
{
    $setting = Settings::first();
    return $setting->expiration_days;
}

function salesOrderDetails($items_list, $grand_total_amount)
{
    $item_details = array();
    $parent_array = array();
    $array = array();
    foreach ($items_list as $item) {
        $item_name = $item->item_name;
        $product = Products::where('id', intval($item->item_id))->first();
        if ($item_name == "" && $product) {
            $item_name = $product->name_en;
        }
        $rate = $item->rate;
        $total_amount = $item->qty * $rate;
        if (checkProductConfig('variations') && isset($product->is_variant) && $product->is_variant == 1) {
            $parent = Products::where('id', $product->parent_variant_id)->first();
            $parent_array[$parent->name_en][] = (object)array('item_id' => $item->item_id, 'item_name' => $item_name, 'qty' => $item->qty, 'rate' => $rate, 'total_amount' => $total_amount);
            $grand_total_amount += $total_amount;
            continue;
        }
        $item_details[] = (object)array('item_id' => $item->item_id, 'item_name' => $item_name, 'qty' => $item->qty, 'rate' => $rate, 'total_amount' => $total_amount);
        $grand_total_amount += $total_amount;
    }
    $array['item_details'] = $item_details;
    $array['grand_total_amount'] = $grand_total_amount;
    $array['parent_array'] = $parent_array;
    return $array;
}

function restoreStocks($order, $type)
{
    $user_id = $order->user_id;
    $user = User::where('id', intval($user_id))->first();
    if ($user) {
        $address = Addresses::where('user_id', $user->id)->first();
        if ($address && isset($address->district_id)) {
            $warehouses = Warehouses::all();
            foreach ($warehouses as $warehouse) {
                if (in_array($address->district_id, json_decode($warehouse->district_id))) {
                    $warehouse_id = $warehouse->id;
                }
            }
        }
    }
    if ($type == 'all') {
        $order_items = $order->orderItems;
        foreach ($order_items as $item) {
            if (isset($warehouse_id)) {
                $item_warehouse = ItemWarehouse::where('product_id', $item->item_id)->where('warehouse_id', $warehouse_id)->first();
                if ($item_warehouse) {
                    $item_warehouse->projected_qty += $item->qty;
                    $item_warehouse->save();
                }
            }
            $item->returned = 1;
            $item->save();
        }
    } else {
        if (isset($warehouse_id)) {
            $item_warehouse = ItemWarehouse::where('product_id', $restored_product->item_id)->where('warehouse_id', $warehouse_id)->first();
            if ($item_warehouse) {
                $item_warehouse->projected_qty += $restored_product->qty;
                $item_warehouse->save();
            }
        }
    }
}

function saveImage($image, $type, $height = 237, $width = 109)
{
    if (!$image) {
        return '';
    }
    $filename = rand(1, 9999) . time() . '.' . $image->getClientOriginalExtension();
    $path = public_path('imgs/' . $type);
    $resizePath = $path . '/thumb';
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
        mkdir($resizePath, 0777, true);
    }
    $img = Image::make($image->getRealPath());
    $img->save($path . '/' . $filename);
    // $img->resize($height, $width);
    $img->resize(210, null, function ($constraint) {
        $constraint->aspectRatio();
    });
    $img->save($resizePath . '/' . $filename);
    return $filename;
}

function reOrderImage()
{
    $item = Input::get('item');
    $type = Input::get('type');
    $imageID = Input::get('imageID');
    // dd($imageID);
    $imageIndex = Input::get('imageIndex');

    $images = DB::table('images')
        ->where('content_id', $item)
        ->where('content_type', $type)
        ->orderBy('image_order', 'ASC')->get();
    // dd($images);
    foreach ($images as $value) {
        return DB::table('images')->where('id', '=', intval($imageID))->update(array('image_order' => $imageIndex));
    }
}

function deleteImageFile($image, $type)
{
    $imgPath = public_path('/imgs/' . $type . '/thumb' . $image->image);
    $imgBeforeEditPath = public_path('/imgs/' . $type . '/' . $image->image);

    if (File::exists($imgPath)) {
        File::delete($imgPath);
    }
    if (File::exists($imgBeforeEditPath)) {
        File::delete($imgBeforeEditPath);
    }
}

function productCartQty($token, $product)
{
    $user = \App\User::where('token', '=', $token)->first();
    $user_cart = Cart::where('user_id', $user->id)->first();
    if ($user_cart) {
        $item_cart = CartItems::where('item_id', $product->id)->where('cart_id', $user_cart->id)->first();
        if ($item_cart) {
            $product->qty = $item_cart->qty;
        } else {
            $product->qty = 0;
        }
    } else {
        $product->qty = 0;
    }
    return $product;
}

function isFavouriteProduct($product, $token)
{
    $user = User::where('token', $token)->first();
    if ($user) {
        if (!$product) {
            return $product;
        }

        $isfavourite = Favorite::where('product_id', $product->id)->where('user_id', $user->id)->first();
        $product->isFavorite = $isfavourite ? true : false;
    }
    return $product;
}

function getLang()
{
    $lang = app('request')->header('lang') ? app('request')->header('lang') : 'en';
    return $lang;
}

function checkProductConfig($key)
{
    $configurations = config('configurations.products');
    return $configurations[$key];
}

// Foods
function extraPrice()
{
    $extra_id = $_GET['extra_id'];
    $price = 0;
    $product_extra = Products::where('id', $extra_id)->where('is_food_extras', 1)->first();
    if ($product_extra) {
        $price = $product_extra->standard_rate;
    }
    return $price;
}

function storeImage($image, $type)
{
    App\Image::create([
        'image' => $image,
        'content_id' => $type->id,
        'content_type' => get_class($type),
        'image_order' => 0,
    ]);
}

/**
 * delete old image and return the new image file name
 * @param $item
 * @param $image
 * @param $type
 * @param int $height
 * @param int $width
 * @return string
 */
function updateImage($item, $image, $type, $height = 237, $width = 109)
{
    $filename = rand(1, 9999) . time() . '.' . $image->getClientOriginalExtension();

    $path = public_path('imgs/' . $type);
    $resizePath = $path . '/thumb';

    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    if (!file_exists($resizePath)) {
        mkdir($resizePath, 0777, true);
    }
    if (file_exists($path . '/' . $item->image)) {
        File::delete($path . '/' . $item->image);
    }

    if (file_exists($resizePath . '/' . $item->image)) {
        File::delete($resizePath . '/' . $item->image);
    }

    $img = Image::make($image->getRealPath());
    $img->save($path . '/' . $filename);

    $img->resize($height, $width);
    $img->save($resizePath . '/' . $filename);

    return $filename;
}


function authUser($with = null)
{
    $token = request()->header('Authorization');
    if ($token == null) {
        $token = request()->header('token');
    }

    return $with ? User::where('token', $token)->with($with)->first() : User::where('token', $token)->first();
}

function authUserId()
{
    $token = request()->header('Authorization');
    if ($token == null) {
        $token = request()->header('token');
    }
    $user = User::where('token', $token)->first();
    return $user_id = $user ? $user->id : null;
}

function calculateShippingRate($district, $items)
{
    $weight = 0;
    foreach ($items as $item) {
        $uom = $item->product && $item->product->uomRelation ? $item->product->uomRelation : null;
        if (!$uom) {
            $weight += (DefaultWeightValue * $item->qty);
        } else {
            $type = trim($uom->type);
            if ($type == 'kg') {
                $weight += ($item->product->weight * $item->qty);
            }
            if ($type == 'gram') {
                $weight += (($item->product->weight * $item->qty) / 1000);
            }
            if ($type == 'ml') {
                $weight += (($item->product->weight * $item->qty) / 1000000);
            }
        }
    }
    $shipping_rule = $district->shipping
        ? $district->shipping()->wherePivot('from_weight', '<=', $weight)
            ->wherePivot('to_weight', '>=', $weight)->first()
        : ShippingRule::where('id', $district->shipping_role)->first();
    if (!$shipping_rule)
        $shipping_rule = ShippingRule::where('id', $district->shipping_role)->first();

    if (!$shipping_rule) {
        if (getLang() == 'en') {
            return Response::json(['Status' => 'Error', 'message' => "Shipping rule not found"], 400);
        }
        return Response::json(['Status' => 'Error', 'message' => "لا يوجد وسيله دفع"], 400);
    }

    $shipping = [
        'shipping_rule' => $shipping_rule->shipping_rule_label,
        'rate' => $shipping_rule->rate,
    ];

    return $shipping;
}
