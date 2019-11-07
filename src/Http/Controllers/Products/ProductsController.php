<?php

namespace App\Http\Controllers\Products;

use App\Models\Brands;
use App\Models\Categories;
use App\Filters\ProductFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\EditProductRequest;
use App\Models\ItemPrice;
use App\Models\ItemWarehouse;
use App\Models\ProductAttributes;
use App\Models\Products;
use App\Models\ProductVariations;
use App\Models\PurchaseOrders;
use App\Models\PurchaseOrdersItems;
use App\Models\Seasons;
use App\Models\UOM;
use App\Models\VariantOption;
use App\Models\VariantsMeta;
use App\Models\Variations;
use App\Models\Warehouses;
use Auth;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Milon\Barcode\DNS1D;
use Response;
use Validator;
use Yajra\Datatables\Datatables;
use Zipper;

class ProductsController extends Controller
{
    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('products')) {
            return view('admin.un-authorized');
        }

        $categories = Categories::all();
        $categories = getCategories($categories);
        return view('admin2/products/index', compact('categories'));
    }

    public function productsList(ProductFilters $filter)
    {
        if (!Auth::guard('admin_user')->user()->can('products')) {
            return view('admin.un-authorized');
        }
        $products = Products::NotVariant()->with(['image', 'categories', 'price'])
            ->filter($filter)
            ->select('products.*');
        return Datatables::of($products)->make(true);
    }

    public function create()
    {

        if (!Auth::guard('admin_user')->user()->can('products')) {
            return view('admin.un-authorized');
        }

        // Variations
        $variations = Variations::where('status', 1)->where('affecting_stock', 1)->get();
        $var_attributes = Variations::where('status', 1)->where('affecting_stock', 0)->get();
        $variation_data = $this->variationData($variations);

        $query = DB::table('uoms')->get();
        $categories = Categories::where('status', 1)->get();
        $parent_category = getCategories($categories);
        $brands = Brands::all();
        $seasons = Seasons::all();

        return view('admin2.products.add', compact('seasons', 'var_attributes', 'parent_category', 'variation_data', 'brands', 'variations', 'query'));
    }

    public function store(AddProductRequest $request)
    {
        if (!Auth::guard('admin_user')->user()->can('products')) {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($request) {
            if (!$request->hasFile('images')) {
                return redirect()->back()->withErrors('Image Required');
            }

            $requestData = $request->except('_token', 'images');
            if (!checkProductConfig('item_code') || checkProductConfig('foods') || $request->item_code == 0) {
                if (checkProductConfig('barcode')) {
                    $requestData['item_code'] = $this->getProductBarcode($request, 0, 0, $request->season_id);
                } else {
                    $requestData['item_code'] = str_random(10);
                }
            }
            $product = Products::create($requestData);

            $categories_id = [];
            if ($request->item_group) {
                $categories_id[] = $request->item_group;
            }

            if ($request->second_item_group) {
                $categories_id[] = $request->second_item_group;
            }

            if (count($categories_id) > 0) {

                $product->categories()->attach($categories_id);
            }


            $costPrice = ItemPrice::where('price_list_id', 2)->where('product_id', $product->id)->first();
            $sellingPrice = ItemPrice::where('price_list_id', 1)->where('product_id', $product->id)->first();
            if ($costPrice) {
                $costPrice->rate = $request->cost;
                $costPrice->save();
            } else {
                $costPrice = new ItemPrice;
                $costPrice->product_id = $product->id;
                $costPrice->price_list_id = 2;
                $costPrice->rate = (float) $request->cost;
                $costPrice->save();
            }
            if ($sellingPrice) {
                $sellingPrice->rate = $request->standard_rate;
                $sellingPrice->save();
            } else {
                $sellingPrice = new ItemPrice;
                $sellingPrice->product_id = $product->id;
                $sellingPrice->price_list_id = 1;
                $sellingPrice->rate = (float) $request->standard_rate;
                $sellingPrice->save();
            }

            if ($request->hasFile('images')) {
                $images = $request->images;
                if (checkProductConfig('multi_images')) {
                    $image_order = 0;
                    foreach ($images as $image) {
                        $file_name = saveImage($image, "products");
                        $product->productImage($file_name, $image_order);
                        $image_order += 1;
                    }
                } else {
                    $image_order = 0;
                    $file_name = saveImage($images, "products");
                    $product->productImage($file_name, $image_order);
                }
            }

            if (checkProductConfig('variations')) {
                $request = $this->handleVariationRequest($request);
                $this->saveVariations($product, $request);
                if ($request->has('has_attributes')) {
                    $this->addAttributes($request, $product);
                }
            }
            $user = Auth::guard('admin_user')->user();
            $product->adjustments()->attach($user->id, ['key' => "Product", 'action' => "Added", 'content_name' => $product->name]);
            return redirect('admin/products')->with('success', 'Product Created Successfully');
        });
    }

    public function edit($id)
    {
        if (!Auth::guard('admin_user')->user()->can('products')) {
            return view('admin.un-authorized');
        }

        $categories = Categories::where('status', 1)->get();
        $parent_category = getCategories($categories);
        $query = UOM::all();
        $brands = Brands::all();
        $product = Products::findOrFail($id);
        $seasons = Seasons::all();

        // Variations
        if (checkProductConfig('variations')) {
            $product_images = $product->images()->orderBy('image_order')->get();
        } else {
            $product_images = $product->images()->orderBy('image_order')->get();
            if (count($product_images) > 1) {
                $first_image = $product_images[0];
                $product_images = [];
                $product_images[] = $first_image;
            }
        }
        $variations = Variations::where('status', 1)->where('affecting_stock', 1)->get();
        $variations_data = $this->variationData($variations);
        $product_variations = Products::where('is_variant', 1)->where('parent_variant_id', $product->id)->get();
        $product_variations = getProductWithVariations($product_variations, 'en');


        $variants_values = $this->getVariantsValues($product_variations);

        $attributes = Variations::where('status', 1)->where('affecting_stock', 0)->get();
        $seasons = Seasons::all();
        $attribute_array = array();
        $price = 0;
        $cost = 0;
        $itemPrice = ItemPrice::where('price_list_id', 1)->where('product_id', $product->id)->first();
        $itemCost = ItemPrice::where('price_list_id', 2)->where('product_id', $product->id)->first();
        if ($itemPrice)
            $price = $itemPrice->rate;
        if ($itemCost)
            $cost = $itemCost = $itemCost->rate;

        $product_selected_attributes = $product->attributes;
        foreach ($product_selected_attributes as $attribute) {
            $attribute_object = Variations::where('id', $attribute['attribute_id'])->first();
            $attribute_values = VariantsMeta::where('id', $attribute['value_id'])->first();
            $attribute_array[] = (object) array('attribute' => $attribute_object, 'attribute_values' => $attribute_values);
        }
        return view('/admin2/products/edit', compact(
            'attribute_array',
            'cost',
            'price',
            'attributes',
            'parent_category',
            'variants_values',
            'variations_data',
            'product',
            'query',
            'product_images',
            'categories',
            'brands',
            'variations',
            'seasons'
        ));
    }

    public function update(EditProductRequest $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('products')) {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($request, $id) {
            $product = Products::find($id);

            $product->slug_en = SlugService::createSlug(Products::class, 'slug_en', $request->input('name_en'));
            $product->slug_ar = SlugService::createSlug(Products::class, 'slug_en', $request->input('name'));

            if ($request->has('cost')) {
                $itemPrice = ItemPrice::where('price_list_id', 2)->where('product_id', $product->id)->first();
                if ($itemPrice) {
                    $itemPrice->rate = (float) $request->cost;
                    $itemPrice->save();
                } else {
                    $itemPrice = new ItemPrice;
                    $itemPrice->price_list_id = 2;
                    $itemPrice->product_id = $product->id;
                    $itemPrice->rate = (float) $request->cost;
                    $itemPrice->save();
                }
            }

            if ($request->has('standard_rate')) {
                $itemPrice = ItemPrice::where('price_list_id', 1)->where('product_id', $product->id)->first();
                if ($itemPrice) {
                    $itemPrice->rate = (float) $request->standard_rate;
                    $itemPrice->save();
                } else {
                    $itemPrice = new ItemPrice;
                    $itemPrice->price_list_id = 1;
                    $itemPrice->product_id = $product->id;
                    $itemPrice->rate = (float) $request->standard_rate;
                    $itemPrice->save();
                }
            }

            if (!checkProductConfig('item_code') || checkProductConfig('foods')) {
                $requestData['item_code'] = str_random(10);
                $requestData = $request->except('_token', 'images', 'item_code');
            }
            $requestData['has_attributes'] = $request->has('has_attributes') ? 1 : 0;

            $product->update($requestData);

            if (!$request->has('is_bundle')) {
                $product->is_bundle = 0;
                $product->save();
            }

            if (checkProductConfig('variations')) {
                $this->saveVariations($product, $request);
                if ($request->has_attributes == 1) {
                    ProductAttributes::where('products_id', $product->id)->delete();
                    $this->addAttributes($request, $product);
                } else {
                    ProductAttributes::where('products_id', $product->id)->delete();
                }
            }

            $product_images = $product->images()->orderBy('image_order')->get();

            if (checkProductConfig('multi_images')) {

                if ($request->hasFile('images')) {

                    $product_images = $request->images;

                    $product_images_orders = $product->images()->orderBy('image_order', 'DESC')->get();
                    $image_order = isset($product_images_orders[0]) ? $product_images_orders[0]->image_order : 0;

                    $image = saveImage($product_images, "products");
                    $product->productImage($image, $image_order);
                    $image_order += 1;
                }
            } else {
                if ($request->hasFile('images')) {
                    foreach ($product_images as $image) {
                        $image->delete();
                    }
                }
                $image_order = 0;
                $image = saveImage($request->images, "products");
                $product->productImage($image, $image_order);
            }

            $user = Auth::guard('admin_user')->user();

            $product->adjustments()->attach($user->id, ['key' => "Product", 'action' => "Edited", 'content_name' => $product->name]);

            return redirect($product->path())->with('success', 'Product Updated Successfully');
        });
    }


    public function variationTable()
    {
        if (!Auth::guard('admin_user')->user()->can('products')) {
            return view('admin.un-authorized');
        }

        $parent_price = $_GET['standard_rate'];
        $parent_cost = $_GET['cost'];
        $variations_values = json_decode($_GET['variants']);
        $variant_values = json_decode($variations_values);
        $variations_array = [];
        $variations_ids_array = [];
        foreach ($variant_values as $key => $values) {
            $array = [];
            $array_of_ids = [];
            $count_of_values = count($values);
            foreach ($values as $val) {
                $variant_meta = VariantsMeta::where('id', $val)->first();
                if ($variant_meta) {
                    $variation = Variations::where('id', $variant_meta->variant_data_id)->first();
                    if ($variation) {
                        if ($variation->is_size == 1) {
                            $size_key = $key;
                        } elseif ($variation->is_color == 1) {
                            $color_key = $key;
                        } else {
                            $other_key = $key;
                        }
                        $array[$variation->name_en][] = $variant_meta->variation_value_en;
                        $array_of_ids[$variation->id][] = $variant_meta->id;
                        // $variation_request[$variation->id][] = $variation->id.':'.$variant_meta->id;
                    }
                }
            }
            $variations_array[] = $array;
            $variations_ids_array[] = $array_of_ids;
        }
        if (!isset($size_key)) {
            if (isset($color_key)) {
                $size_key = $color_key;
            } else if (isset($other_key)) {
                $size_key = $other_key;
            }
        }
        $size_variation = array_values((array) $variations_array[$size_key]);
        $size_variation = $size_variation[0];
        //        dd($size_variation);

        $size_variation_id = array_values((array) $variations_ids_array[$size_key]);
        $size_variation_id = $size_variation_id[0];
        unset($variations_array[$size_key]);
        unset($variations_ids_array[$size_key]);
        foreach ($variations_array as $key => $value) {
            $variant = array_values((array) $value);
            $variant_ids = array_values((array) $variations_ids_array[$key]);
            $size_variation = $this->mixArrayValues($variant[0], $size_variation);

            $size_variation_id = $this->mixArrayValues($variant_ids[0], $size_variation_id);
        }
        $data = view('admin2.products.variationsTable', compact('size_variation', 'size_variation_id', 'parent_price', 'parent_cost'))->render();
        return response()->json(['data' => $data]);
    }

    public function mixArrayValues($main_array, $array_to_mix)
    {
        //        dd($main_array, $array_to_mix);
        $array = [];
        foreach ($main_array as $key => $value) {
            if (!strpos($value, '-')) {
                if (!is_numeric($value)) {
                    $value = $value[0];
                }
            }
            //            dd($value);

            foreach ($array_to_mix as $k => $v) {
                if (!is_numeric($v)) {
                    $v = ($v[0]);
                }
                //                dd($v , $value);
                $array[] = $value . '-' . $v;
            }
            //            dd($array);
        }
        return $array;
    }

    public function handleVariationRequest($request)
    {
        $requestData = $request->all();
        $array_keys = array_keys($request->all());
        $search_variations = "variation_item";
        // searching for keys that begin with variation_item as keys is unknown
        $keys = $this->searchArrayKeys($array_keys, $search_variations);
        if (count($keys) > 0) {
            foreach ($keys as $key => $array_key) {
                $modified_value = [];
                $request_value = explode('-', $requestData[$array_keys[$array_key]]);
                foreach ($request_value as $val) {
                    $variation_meta = VariantsMeta::where('id', $val)->first();
                    // dd($variation_meta);
                    $modified_value[] = $variation_meta->variant_data_id . ':' . $val;
                }
                $request[$array_keys[$array_key]] = $modified_value;
            }
        }
        return $request;
    }


    // Barcodes
    public function printBarcodeIndex()
    {
        $categories = Categories::all();
        $categories = getCategories($categories);
        return view('admin/products/print_barcode', compact('categories'));
    }

    public function getBarCodes()
    {
        $item_codes = $_GET['item_codes'];
        $product_array = array();
        $images_array = array();
        if (count($item_codes) > 0) {
            // dd($item_codes);
            foreach ($item_codes as $code) {
                $product = Products::where('item_code', $code)->first();
                if ($product) {

                    if (!file_exists(public_path('imgs/products/barcodes/' . $code . '.png'))) {
                        $image = DNS1D::getBarcodePNG($code, "I25");
                        $this->storeBarcodeImageBase64($code, $image, 'products');
                        // $product_bar_code = public_path('imgs/products/barcodes/'.$product->item_code.'.png');

                    }

                    $product_bar_code = $code . '.png';

                    $images_array[] = $product_bar_code;
                    $product_array[] = $product;
                }
            }
        }
        foreach ($product_array as $product) {
            if ($product->is_variant == 1) {
                $parent = Products::where('id', $product->parent_variant_id)->first();
                $product->name_en = $parent->name_en . ' ' . $product->name_en;
            }
            $product->standard_rate = itemSellingPrice($product->id);
        }
        $img_urls = [];
        if (count($images_array) > 0) {
            foreach ($images_array as $key => $img) {
                $img_url['data'] = $product_array[$key];
                $img_url['image'] = public_path('imgs/products/barcodes/' . $img);
                $img_urls[] = $img_url;
            }
        }
        return response()->json(['data' => $img_urls]);
    }

    public function getProductBarcode($request, $size_code, $color_code, $season_code)
    {
        $category_code = Categories::where('id', $request->item_group)->select('item_code')->first();
        $category_code = $category_code ? sprintf('%02d', $category_code->item_code) : sprintf('%02d', 0);
        $size = sprintf('%02d', $size_code);
        $color = sprintf('%02d', $color_code);
        $season = sprintf('%02d', $season_code);
        $product_last_id = Products::orderBy('id', 'desc')->first();
        if ($product_last_id) {
            $product_id = sprintf('%04d', $product_last_id->id + 1);
        } else {
            $product_id = 1;
        }

        $product_bar_code = $category_code . $color . $size . $season . $product_id;
        return $product_bar_code;
    }

    // Store barcode image bygely base64 fa b7wlo le img we asave
    public function storeBarcodeImageBase64($name, $base64Image, $type)
    {
        $decodedImage = base64_decode($base64Image);
        $imageName = $name . '.png';
        $fp = fopen(public_path() . '/imgs/products/barcodes/' . $imageName, 'wb+');
        fwrite($fp, $decodedImage);
        fclose($fp);
        return $imageName;
    }

    public function details($id)
    {
        if (!Auth::guard('admin_user')->user()->can('products')) {
            return view('admin.un-authorized');
        }
        $product = Products::where('id', $id)->first();

        if ($product) {
            if (checkProductConfig('barcode')) {
                $image = DNS1D::getBarcodePNG($product->item_code, "I25");
                $product_bar_code = $this->storeBarcodeImageBase64($product->item_code, $image, 'products');
            }
            $childs_array = array();
            if (isset($product) && $product->is_variant == 1) {
                $product = Products::where('id', $product->parent_variant_id)->first();
            }
            if ($product) {
                $product['selling_price'] = itemSellingPrice($product->id);
                $purchased_orders = PurchaseOrdersItems::where('item_id', $product->id)->get();
                $product['count_of_orders'] = 0;
                $uom = UOM::where('id', $product->uom)->first();
                if ($uom) {
                    $product->uom = $uom->type;
                }
                foreach ($purchased_orders as $order) {
                    $purchase_order = PurchaseOrders::where('id', $order->purchase_order_id)->first();
                    if ($purchase_order) {
                        $product['count_of_orders'] += 1;
                    }
                }
                $childs_images = [];
                if ($product->has_variants == 1) {
                    $count_purchases = 0;
                    $product_children = Products::where('parent_variant_id', $product->id)->get();
                    foreach ($product_children as $child) {
                        $childs_array[$child->id][] = getProductStocks($child->id);
                    }
                }
            }

            foreach ($childs_array as $key => $array) {
                foreach ($array as $k => $arr) {
                    $child = Products::where('id', $key)->first();
                    if (checkProductConfig('barcode')) {
                        if ($child && !file_exists(public_path('imgs/products/barcodes/' . $child->item_code . '.png'))) {
                            $image = DNS1D::getBarcodePNG($child->item_code, "I25");
                            $product_bar_code = $this->storeBarcodeImageBase64($child->item_code, $image, 'products');
                        } else {
                            $product_bar_code = $child->item_code . '.png';
                        }
                        $array[$k]['barcode'] = $product_bar_code;
                    }

                    $array[$k]['selling_price'] = itemSellingPrice($child->id);
                    $array[$k]['images'] = getImages($key);
                }
                $childs_array[$key] = $array;
            }
            $stocks_array = getProductStocks($product->id);
            $product_stock_qty = $stocks_array['product_stock_qty'];
            $stocks_warehouse = $stocks_array['stocks_warehouse'];
            return view('admin2.products.details', compact('childs_images', 'childs_array', 'product', 'stocks_warehouse', 'product_stock_qty'));
        } else {
            return redirect()->back()->withErrors(['Product Does not exist']);
        }
    }

    public function deleteImage(\App\Image $image)
    {
        if (!Auth::guard('admin_user')->user()->can('products')) {
            return view('admin.un-authorized');
        }

        $product_configurations = config('configurations.products');
        if (isset($product_configurations['multi_images']) && $product_configurations['multi_images'] == true) {
            if ($image->delete()) {
                deleteImageFile($image, 'App\Products');
                return "success";
            }
        } else {
            $product = Products::where('id', $image->content_id)->first();
            if ($product) {
                $product_images = $product->images()->get();
                foreach ($product_images as $image) {
                    if ($image->delete()) {
                        deleteImageFile($image, 'App\Products');
                    }
                }
            }
        }

        return "success";
    }

    // Variation Div
    public function addVariationView()
    {
        if (!Auth::guard('admin_user')->user()->can('products')) {
            return view('admin.un-authorized');
        }
        // i get variation id and values in selected_variations
        $selected_variation = isset($_GET['selected_variations']) ? $_GET['selected_variations'] : [];
        $price = isset($_GET['price']) ? $_GET['price'] : 0;
        $cost = isset($_GET['cost']) ? $_GET['cost'] : 0;
        if (count(array_filter($selected_variation)) == 0) {
            return 'false';
        }
        $variations_values = [];
        $has_special_images = 0;
        foreach ($selected_variation as $variant) {
            $variant_meta = VariantsMeta::where('id', $variant)->first();
            if ($variant_meta) {
                $variation = Variations::where('id', $variant_meta->variant_data_id)->first();
                if ($variation) {
                    $variations_values[] = (object) array('variation' => $variation, 'variant_options' => $variant_meta);
                    if ($variation->has_special_images == 1 && $variation->affecting_stock == 1) {
                        $has_special_images = 1;
                    }
                } else {
                    continue;
                }
            }
        }
        if ($has_special_images == 1) {
            $data = view('admin.products.variantion_with_images', compact('variations_values', 'price', 'cost'))->render();
        } else {
            $data = view('admin.products.variation_without_images', compact('variations_values', 'cost', 'price'))->render();
        }
        return response()->json(['data' => $data]);
    }

    public function deleteVariation($id, $is_attribute)
    {
        if ($is_attribute == 0) {
            $product = Products::find($id);
            if ($product) {
                $user = Auth::guard('admin_user')->user();
                Products::where('id', $id)->update(['user_deleted' => $user->id, 'is_deleted' => 1]);
                $parent_product_id = $product->parent_variant_id;
                $parent_product = Products::where('id', $parent_product_id)->where('has_variants', 1)->first();
                $parent_product_childs = Products::where('parent_variant_id', $parent_product_id)->where('is_variant', 1)->where('is_deleted', 0)->get();
                if (count($parent_product_childs) > 0) {
                    $product->delete();
                    return 'success';
                } else {
                    if ($parent_product) {
                        $parent_product->has_variants = 0;
                        $parent_product->save();
                        $product->delete();
                        return 'success';
                    }
                }
            }
        } else {
            $product_variation = ProductVariations::where('id', $id)->first();
            if ($product_variation) {
                $product_variation->delete();
                return 'success';
            }
        }
    }
    //  This Function For Adding Variation Div In product create  & edit

    // Saving Product Variation
    public function saveVariations($product, $request)
    {
        if (!Auth::guard('admin_user')->user()->can('products')) {
            return view('admin.un-authorized');
        }

        $array_keys = array_keys($request->all());
        $search_variations = "variation_item";
        $keys = $this->searchArrayKeys($array_keys, $search_variations);
        if (count($keys) > 0) {
            foreach ($keys as $key => $value_key) {

                $array_key = explode(':', $array_keys[$value_key]);


                $str = $array_key[0];
                $int = (int) filter_var($str, FILTER_SANITIZE_NUMBER_INT);

                $variations = $request[$str];
                if (isset($request['variation_cost' . $int])) {
                    $cost = (float) $request['variation_cost' . $int];
                } elseif ($request->has('cost')) {
                    $cost = $request->cost;
                } else {
                    $cost = 0;
                }
                if (isset($request['variation_price' . $int])) {
                    $price = (float) $request['variation_price' . $int];
                } else if ($request->has('standard_rate')) {
                    $price = (float) $request->standard_rate;
                } else {
                    $price = 0;
                }


                $name_ar = '';
                $name_en = '';
                $size_code = 0;
                $color_code = 0;
                $variant_options_id = [];
                foreach ($variations as $key => $value) {

                    $exp = explode(':', $value);
                    $variation[$exp[0]] = $exp[1];
                    $variant = VariantsMeta::where('id', $exp[1])->first();
                    $variation_object = Variations::where('id', $exp[0])->first();

                    $variant_option = VariantOption::create(
                        ['variant_data_id' => $variation_object->id, 'variant_meta_id' => $variant->id]
                    );


                    if ($variation_object->key == 'sizes' || $variation_object->key == 'colors') {
                        if (!is_null($variant->item_code)) {
                            if ($variation_object->key == 'colors') {
                                $color_code = $variant->item_code;
                            } else {
                                $size_code = $variant->item_code;
                            }
                        }
                    }
                    if ($name_ar === "" && $name_en === "") {
                        $name_en = $product->name_en . '-' . $variant->variation_value_en;
                        $name_ar = $product->name . '-' . $variant->variation_value;
                    } else {
                        $name_en .= "-" . $variant->variation_value_en;
                        $name_ar .= "-" . $variant->variation_value;
                    }


                    $variant_options_id[] = $variant_option->id;
                }

                $product_variation_exist = $this->checkProductVariationExist($product, $variations);
                $parent_product_childs = Products::where('parent_variant_id', $product->id)->orderBy('id', 'desc')->withTrashed()->first();
                if (isset($parent_product_childs->item_code)) {
                    $exp = explode('_', $parent_product_childs->item_code);
                    $count_exp = count($exp);
                    $i = 1;
                    $i += $exp[$count_exp - 1];
                } else {
                    $i = 1;
                }
                $variations_data = json_encode($variations);

                if (checkProductConfig('barcode')) {

                    $product_bar_code = $this->getProductBarcode($request, $size_code, $color_code, 0);
                } else {
                    $product_bar_code = $product->item_code;
                }

                $product_bar_code = substr($product_bar_code, 0, -4);
                $product_last_id = Products::orderBy('id', 'desc')->first();
                $product_new_id = sprintf('%04d', $product_last_id->id + 1);
                $product_bar_code = $product_bar_code . $product_new_id;

                if (!$product_variation_exist) {

                    $variant_product = Products::create(['name' => $name_ar, 'name_en' => $name_en, 'description_en' => $product->description_en, 'description' => $product->description, 'item_group' => $product->item_group, 'item_code' => $product_bar_code, 'brand_id' => $product->brand_id, 'second_item_group' => $product->second_item_group, 'is_variant' => 1, 'parent_variant_id' => $product->id, 'standard_rate' => $price, 'cost' => $cost]);
                    $i++;
                    $product_new_variant = ProductVariations::create(['parent_variant_id' => $product->id, 'products_id' => $variant_product->id, 'variations_data' => $variations_data]);
                    $image_request = 'variation_image' . $int;
                    if ($request->hasFile($image_request)) {
                        $images = $request[$image_request];
                        $image_order = 0;
                        foreach ($images as $image) {
                            $file_name = saveImage($image, "products");
                            $variant_product->productImage($file_name, $image_order);
                            $image_order += 1;
                        }
                    }
                    $costPrice = ItemPrice::where('price_list_id', 2)->where('product_id', $variant_product->id)->first();
                    if ($costPrice) {
                        $costPrice->rate = $cost;
                        $costPrice->save();
                    } else {
                        $costPrice = new ItemPrice;
                        $costPrice->product_id = $variant_product->id;
                        $costPrice->price_list_id = 2;
                        $costPrice->rate = (float) $cost;
                        $costPrice->save();
                    }

                    $sellingPrice = ItemPrice::where('price_list_id', 1)->where('product_id', $variant_product->id)->first();
                    if ($sellingPrice) {
                        $sellingPrice->rate = (float) $price;
                    } else {
                        $sellingPrice = new ItemPrice;
                        $sellingPrice->product_id = $variant_product->id;
                        $sellingPrice->price_list_id = 1;
                        $sellingPrice->rate = (float) $price;
                        $sellingPrice->save();
                    }

                    $variant_product->variations()->attach($variant_options_id);
                } else {

                    $variant_product = Products::where('id', $product_variation_exist->products_id)->first();
                    if (isset($request['variation_cost' . $int])) {
                        $cost = (float) $request['variation_cost' . $int];
                    }
                    $variant_product->cost = $cost;
                    $costPrice = ItemPrice::where('price_list_id', 2)->where('product_id', $variant_product->id)->first();
                    if ($costPrice) {
                        $costPrice->rate = $cost;
                        $costPrice->save();
                    } else {
                        $costPrice = new ItemPrice;
                        $costPrice->product_id = $variant_product->id;
                        $costPrice->price_list_id = 2;
                        $costPrice->rate = (float) $cost;
                        $costPrice->save();
                    }
                    if (isset($request['variation_price' . $int])) {
                        $price = (float) $request['variation_price' . $int];
                    }

                    $sellingPrice = ItemPrice::where('price_list_id', 1)->where('product_id', $variant_product->id)->first();
                    if ($sellingPrice) {
                        $sellingPrice->rate = (float) $price;
                        $sellingPrice->save();
                    } else {
                        $sellingPrice = new ItemPrice;
                        $sellingPrice->product_id = $variant_product->id;
                        $sellingPrice->price_list_id = 1;
                        $sellingPrice->rate = (float) $price;
                        $sellingPrice->save();
                    }

                    $variant_product->standard_rate = $price;
                    $variant_product->save();
                    $image_request = 'variation_image' . $int;
                    if ($request->hasFile($image_request)) {
                        $product_images = $request[$image_request];
                        $product_images_orders = $variant_product->images()->orderBy('image_order', 'DESC')->get();
                        $image_order = isset($product_images_orders[0]) ? $product_images_orders[0]->image_order : 0;
                        foreach ($product_images as $product_image) {
                            $image = saveImage($product_image, "products");
                            $variant_product->productImage($image, $image_order);
                            $image_order += 1;
                        }
                    }
                }
            }
        }
        $is_product_has_childs = Products::where('parent_variant_id', $product->id)->get();
        if (count($is_product_has_childs) > 0) {
            $product->has_variants = 1;
            $product->save();
            return true;
        } else {
            return false;
        }
    }

    // variations select in add and edit
    public function variationData($variations)
    {
        $variation_data = [];
        foreach ($variations as $variation) {
            $variations_meta = isset($variation->variantsMeta) ? $variation->variantsMeta : [];
            $variation_data[] = (object) array('variation_id' => $variation->id, 'variation' => $variation->name_en, 'variation_options' => $variations_meta);
        }
        return $variation_data;
    }

    public function getVariantsValues($product_variations)
    {
        $variants_values = [];
        if (count($product_variations) > 0) {
            foreach ($product_variations as $variant) {
                $has_special_images = 0;
                $product_variant = ProductVariations::where('products_id', $variant->id)->first();
                $variation_data = isset($product_variant->variations_data) ? json_decode($product_variant->variations_data) : [];
                $variant_data = [];
                $variant_data_id = [];
                foreach ($variation_data as $data) {
                    $exp = explode(':', $data);
                    $variation = Variations::where('id', $exp[0])->first();
                    if ($variation->has_special_images) {
                        $has_special_images = 1;
                    }
                    $variant_meta = VariantsMeta::where('id', $exp[1])->first();
                    if ($variation && $variant_meta && isset($variation->name_en) && isset($variant_meta->variation_value_en)) {
                        $variant_data[$variation->name_en] = $variant_meta->variation_value_en;
                        $variant_data_id[$variation->id] = $variant_meta->id;
                    } else {
                        continue;
                    }
                }
                $variant_images = $variant->images()->orderBy('image_order', 'desc')->get();
                $variants_values[] = (object) array('variant_product_id' => $variant->id, 'variation' => $variant_data, 'variant_data_id' => $variant_data_id, 'variant_images' => $variant_images, 'existed_variations' => $variant_data_id, 'has_special_images' => $has_special_images, 'cost' => $variant->cost, 'price' => $variant->standard_rate);
            }
        }
        return $variants_values;
    }

    public function variationsStock($parent_id)
    {
        $product = Products::where('id', $parent_id)->first();
        $childs_array = array();
        if ($product->has_variants == 1) {
            $count_purchases = 0;
            $product_children = Products::where('parent_variant_id', $product->id)->get();
            foreach ($product_children as $child) {
                $childs_array[$child->id][] = getProductStocks($child->id);
            }
        }
        return view('admin.products.variations_details', compact('childs_array', 'product'));
    }

    // This Function used to check if 2 arrays are similar
    public function checkIfArraysAreSimilar($array1, $array2)
    {
        $n = count($array1); // = B.length
        $array_1_count = count($array1);
        $array_2_count = count($array2);
        if ($array_1_count == $array_2_count) {
            $c = count(array_intersect($array1, $array2));
            if ($c == $array_1_count) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // I use it to check if variation already exist?
    public function checkProductVariationExist($product, $variations)
    {
        $product_variations_exist = ProductVariations::where('parent_variant_id', $product->id)->get();
        foreach ($product_variations_exist as $exist) {
            $product_child = Products::where('id', $exist->products_id)->first();
            if ($product_child) {
                $db_array = json_decode($exist->variations_data);
                $result = $this->checkIfArraysAreSimilar($db_array, $variations);
            } else {
                continue;
            }
            if ($result == true) {
                return $exist;
            } else {
                continue;
            }
        }
    }

    public function getSecondItemgroup()
    {
        $item_group = Input::get('item_group');
        $second_items_group = Categories::where('id', '!=', $item_group)->get();
        $parent_category = getCategories($second_items_group);
        if ($parent_category) {
            return $parent_category;
        }
        return false;
    }

    function searchArrayKeys($array_keys, $search_variations)
    {
        $keys = [];
        foreach ($array_keys as $key => $value) {
            $value = preg_replace('/[0-9]+/', '', $value);
            if (stristr($value, $search_variations) === false) {
                $values[] = $value;
                continue;
            } else {
                $keys[] = $key;
            }
        }
        return $keys;
    }

    function addAttributes($request, $product)
    {
        $attributes_keys = $request->attributes_keys;
        $attributes_values = $request->attributes_value;
        if ($attributes_keys && $attributes_keys) {

            foreach ($attributes_keys as $key => $value) {
                if ($attributes_values[$key] != "") {
                    $product->addAttribute($attributes_values[$key], $value);
                }
            }
        }
    }

    public function attributes()
    {
        $key = $_GET['key'];
        $values = array_filter($_GET['values']);
        $variant_meta = VariantsMeta::where('variant_data_id', $key)->whereNotIn('id', $values)->get();
        return response()->json(['attributes_values' => $variant_meta]);
    }

    // Reorder View Of Products
    public function getProductsOrderPage($id)
    {
        $category_id = $id;
        $products = Products::where('item_group', $id)->get();
        return view('admin.products.order', compact('category_id', 'products'));
    }

    // (Ajax Function To Change Products Orders)
    public function reOrder()
    {
        if (!Auth::guard('admin_user')->user()->can('products')) {
            return view('admin.un-authorized');
        }

        $rowID = Input::get('rowID');
        $rowIndex = Input::get('rowIndex');
        $item_group = Input::get('parent');
        $products = Products::where('id', $rowID)->get();
        foreach ($products as $value) {
            DB::table('products')->where('id', '=', $rowID)->update(array('sorting_no' => $rowIndex));
        }
        $products = Products::where('item_group', $item_group)->orderBy('sorting_no', 'ASC')->get();
        if ($products) {
            return $products;
        }
        return false;
    }

    public function getProductReodering()
    {
        $warehouse_id = isset($_GET['warehouse_id']) ? $_GET['warehouse_id'] : 0;
        $quantity_filter = isset($_GET['quantity_filter']) ? $_GET['quantity_filter'] : 0;
        $warehouses = Warehouses::all();
        return view('admin/products/products_reordering', compact('warehouses', 'quantity_filter', 'warehouse_id'));
    }

    // DataTable Of Reordering View
    public function productReorderingList()
    {

        $warehouse = isset($_GET['warehouse']) ? $_GET['warehouse'] : 0;
        $quantity_filter = isset($_GET['quantity_filter']) ? $_GET['quantity_filter'] : 0;
        if ($quantity_filter != 0 && $warehouse != 0) {
            if ($quantity_filter == 1) {
                $products = ItemWarehouse::where('warehouse_id', $warehouse)->where('projected_qty', 0)->orderBy('projected_qty', 'asc')->get();
            } elseif ($quantity_filter == 2) {
                $products = ItemWarehouse::where('warehouse_id', $warehouse)->whereBetween('projected_qty', array(0, 10))->orderBy('projected_qty', 'asc')->get();
            } elseif ($quantity_filter == 3) {
                $products = ItemWarehouse::where('warehouse_id', $warehouse)->whereBetween('projected_qty', array(10, 50))->orderBy('projected_qty', 'asc')->get();
            } elseif ($quantity_filter == 4) {
                $products = ItemWarehouse::where('warehouse_id', $warehouse)->whereBetween('projected_qty', array(50, 100))->orderBy('projected_qty', 'asc')->get();
            } else {
                $products = ItemWarehouse::where('warehouse_id', $warehouse)->whereBetween('projected_qty', array(100, 1000))->orderBy('projected_qty', 'asc')->get();
            }
        } elseif ($quantity_filter == 0 && $warehouse != 0) {
            $products = ItemWarehouse::where('warehouse_id', $warehouse)->orderBy('projected_qty', 'asc')->get();
        } elseif ($quantity_filter != 0 && $warehouse == 0) {
            if ($quantity_filter == 1) {
                $products = ItemWarehouse::orderBy('projected_qty', 'asc')->where('projected_qty', 0)->get();
            } elseif ($quantity_filter == 2) {
                $products = ItemWarehouse::orderBy('projected_qty', 'asc')->whereBetween('projected_qty', array(0, 10))->get();
            } elseif ($quantity_filter == 3) {
                $products = ItemWarehouse::orderBy('projected_qty', 'asc')->whereBetween('projected_qty', array(10, 50))->get();
            } elseif ($quantity_filter == 4) {
                $products = ItemWarehouse::orderBy('projected_qty', 'asc')->whereBetween('projected_qty', array(50, 100))->get();
            } else {
                $products = ItemWarehouse::orderBy('projected_qty', 'asc')->whereBetween('projected_qty', array(100, 1000))->get();
            }
        } else {
            $products = ItemWarehouse::orderBy('projected_qty', 'asc')->get();
        }
        if ($products) {
            foreach ($products as $product) {
                $item = Products::where('id', $product['product_id'])->first();
                if ($item) {
                    $product['product_name'] = $item->name;
                    $product['product_code'] = $item->item_code;
                } else {
                    $product['product_name'] = '';
                    $product['product_code'] = '';
                }
            }
        }
        return Datatables::of($products)->make(true);
    }

    public function Status($id)
    {
        $status = Products::where('id', $id)->select('active')->first();
        if ($status->active == 0) {
            Products::where('id', $id)->update(['active' => '1']);
            return 'success';
        } else {
            Products::where('id', $id)->update(['active' => '0']);
            return 'success';
            return redirect('admin/products')->with('success', 'Product  De-Activated Successfully');
        }
        return 'false';
    }

    public function importProductsImagesView()
    {
        return view('admin2.products.importStep1');
    }

    public function importProductsImagesForExcel(Request $request)
    {

        $file = $request->file('imagesZipFile');
        $randPathName = mt_rand(10000000, 99999999);
        $destinationPath = public_path('files/' . $randPathName . '/');
        $file->move($destinationPath, $file->getClientOriginalName());
        $Path = $destinationPath . $file->getClientOriginalName();

        \Zipper::make($Path)->extractTo('public/files/' . $randPathName);
        $directoryPath = public_path('files/' . $randPathName);
        $finalImageFiles = array();
        if ($handle = opendir("$directoryPath")) {
            $files = scandir("$directoryPath");

            foreach ($files as $file) {
                if (is_dir("$directoryPath" . "/" . "$file")) {
                    if ($file !== '.' && $file !== '..' && $file !== '__MACOSX') {
                        $this->deleteDirectory("$directoryPath" . "/" . "$file");
                    }
                } else {
                    if (is_array(getimagesize("$directoryPath" . "/" . "$file"))) {
                        $finalImageFiles[] = $directoryPath . "/" . $file;
                        $image = true;
                    } else {
                        if ($file !== '.' && $file !== '..' && $file !== '__MACOSX') {
                            unlink("$directoryPath" . "/" . "$file");
                        }
                    }
                }
            }
        } else {
            return redirect()->back()->withErrors(['Zip File was corrupted']);
        }
        if (empty($finalImageFiles)) {
            return redirect()->back()->withErrors(['No Images Found In The ZipFile']);
        }

        return redirect(url('admin/import/products/step2/' . $randPathName))->withSuccess($finalImageFiles);
    }

    function deleteDirectory($dir)
    {
        system('rm -rf ' . escapeshellarg($dir), $retval);
        return $retval == 0; // UNIX commands return zero on success
    }

    public function importProductsExcelView($pathNo)
    {
        //$pathNo; // Todo Read Uploaded Images Names

        return view('admin2.products.importStep2', compact('pathNo'));
    }

    public function validateExcelRow($row)
    {
        $rowIssues = array();
        if ($row->name_en == null || empty($row->name_en)) {
            $rowIssues[] = "The Row with the ID ($row->id) is missing Name (En)";
        } elseif ($row->name_ar == null || empty($row->name_ar)) {
            $rowIssues[] = "The Row with the ID ($row->id) is missing Name (Ar)";
        } elseif ($row->desc_en == null || empty($row->desc_en)) {
            $rowIssues[] = "The Row with the ID ($row->id) is missing Desc (En)";
        } elseif ($row->desc_ar == null || empty($row->desc_ar)) {
            $rowIssues[] = "The Row with the ID ($row->id) is missing Desc (Ar)";
        } elseif ($row->category_id == null || empty($row->category_id)) {
            $rowIssues[] = "The Row with the ID ($row->id) is missing Category Id";
        } elseif ($row->brand_id == null || empty($row->brand_id)) {
            $rowIssues[] = "The Row with the ID ($row->id) is missing Brand Id";
        }

        $category = Categories::find($row->category_id);
        if (!$category || is_null($category)) {
            $rowIssues[] = "The Row with the ID ($row->id) has the Category Id ($row->category_id) which doesn't exist in our Database";
        }

        $brand = Brands::find($row->brand_id);
        if (!$brand || is_null($brand)) {
            $rowIssues[] = "The Row with the ID ($row->id) has the Brand Id ($row->brand_id) which doesn't exist in our Database";
        }

        return $rowIssues;
    }

    public function validateExcelSheet($rows)
    {
        $issuesArray = array();
        $noOfRecordsNoId = 0;
        foreach ($rows as $key => $row) {
            $rowIssues = array();
            if ($row->id == null || empty($row->id)) {
                $noOfRecordsNoId++;
                continue;
            } else {

                $rowIssues = $this->validateExcelRow($row);

                if (is_array($rowIssues) && !empty($rowIssues)) {
                    foreach ($rowIssues as $rowIssue) {
                        $issuesArray[] = $rowIssue;
                    }
                }
            }
        }
        if ($noOfRecordsNoId == 0) {
            $issuesArray[] = "There Is ($noOfRecordsNoId) record(s) in the excel sheet that doesn't have an id";
        }

        if (count($issuesArray) > 0) {
            return $issuesArray;
        }

        return true;
    }

    public function getProductRowImagesForExcel($Product, $randPathName)
    {
        $productImages = array();
        if (!empty($Product->images) && $Product->images !== null) {
            if (strpos($Product->images, ',')) {
                $productImages = explode(',', $Product->images);
            } else {
                $productImages = array($Product->images);
            }
        }
        $productObjectImages = array();
        if (count($productImages) > 0) {
            foreach ($productImages as $productImage) {
                $imagePath = public_path('files/' . $randPathName . '/' . $productImage);
                $imageUrl = url('public/files/' . $randPathName . '/' . $productImage);
                $productObjectImages[] = $productImage;
            }
        }
        return $productObjectImages;
    }

    public function generateProductTreeForExcel($rows, $randPathName)
    {

        $parentProducts = array();
        $childProducts = array();
        foreach ($rows as $key => $value) {
            if (is_null($value->id)) {
                continue;
            }
            if ($value->parent_id_for_variant == null || empty(trim($value->parent_id_for_variant))) {
                $parentProducts[$value->id] = $value;
            } else {
                if ($value->color_code == null || empty(trim($value->color_code)) || $value->size_code == null || empty(trim($value->size_code))) {
                    continue;
                }

                $childProducts[$value->parent_id_for_variant][] = $value;
            }
        }

        if (empty($parentProducts)) {
            return array();
        } else {
            $productsArray = array();

            foreach ($parentProducts as $parentProduct) {

                $productObject = [];
                // Get Product Images
                $productObjectImages = $this->getProductRowImagesForExcel($parentProduct, $randPathName);
                $productObject['name_en'] = $parentProduct->name_en;
                $productObject['name_ar'] = $parentProduct->name_ar;
                $productObject['desc_en'] = $parentProduct->desc_en;
                $productObject['desc_ar'] = $parentProduct->desc_ar;

                $productObject['selling_price'] = $parentProduct->selling_price;
                $productObject['cost_price'] = $parentProduct->cost_price;
                if ($category = Categories::find($parentProduct->category_id)) // Check If category exists in database
                {
                    $productObject['category_id'] = $category->id;
                    $productObject['category_name'] = $category->name;
                } else {
                    continue;
                }

                if ($brand = Brands::find($parentProduct->brand_id)) // Check If category exists in database
                {
                    $productObject['brand_id'] = $brand->id;
                    $productObject['brand_name'] = $brand->name;
                } else {
                    continue;
                }
                $productObject['size_item_code'] = 0;
                $productObject['color_item_code'] = 0;
                $seasonId = null;
                if (isset($parentProduct->season_code) && $season = Seasons::where('name_en', $parentProduct->season_code)->first()) {
                    $seasonId = $season->id;
                }
                $productObject['season_id'] = $seasonId;

                $productObject['images'] = $productObjectImages;

                $childsArray = array();
                if (isset($childProducts) && isset($childProducts[$parentProduct->id]) && is_array($childProducts[$parentProduct->id]) && count($childProducts[$parentProduct->id]) > 0) {

                    foreach ($childProducts[$parentProduct->id] as $childProduct) {

                        $childProductObject = [];
                        $childProductObjectImages = $this->getProductRowImagesForExcel($childProduct, $randPathName);
                        $childProductObject['name_en'] = $childProduct->name_en;
                        $childProductObject['name_ar'] = $childProduct->name_ar;
                        $childProductObject['desc_en'] = $childProduct->desc_en;
                        $childProductObject['desc_ar'] = $childProduct->desc_ar;
                        $childProductObject['selling_price'] = $childProduct->selling_price;
                        $childProductObject['cost_price'] = $childProduct->cost_price;
                        $childProductObject['size_code'] = $childProduct->size_code;
                        $sizeVatiationMeta = VariantsMeta::where('variant_code', $childProduct->size_code)->first();
                        if ($sizeVatiationMeta) {
                            $childProductObject['size_item_code'] = $sizeVatiationMeta->item_code;
                        } else {
                            $childProductObject['size_item_code'] = 0;
                        }

                        $seasonId = null;
                        if (isset($childProduct->season_code) && $season = Seasons::where('name_en', $childProduct->season_code)->first()) {
                            $seasonId = $season->id;
                        }
                        $childProductObject['season_id'] = $seasonId;

                        $childProductObject['color_code'] = $childProduct->color_code;
                        $colorVatiationMeta = VariantsMeta::where('variant_code', $childProduct->color_code)->first();
                        if ($colorVatiationMeta) {
                            $childProductObject['color_item_code'] = $colorVatiationMeta->item_code;
                        } else {
                            $childProductObject['color_item_code'] = 0;
                        }
                        if ($category = Categories::find($childProduct->category_id)) // Check If category exists in database
                        {
                            $childProductObject['category_id'] = $category->id;
                            $childProductObject['category_name'] = $category->name;
                        }

                        if ($brand = Brands::find($childProduct->brand_id)) // Check If category exists in database
                        {
                            $childProductObject['brand_id'] = $brand->id;
                            $childProductObject['brand_name'] = $brand->name;
                        }
                        $childProductObject['images'] = $childProductObjectImages;
                        $childsArray[] = $childProductObject;
                    }
                    $productObject['variants'] = $childsArray;
                }
                $productsArray[] = $productObject;
            }
        }

        return $productsArray;
    }

    public function importProductsExcel(Request $request, $pathNo)
    {

        if (!Auth::guard('admin_user')->user()->can('products')) {
            return view('admin.un-authorized');
        }

        $validator = Validator::make(
            [
                'file' => $request->excelFile,
                'extension' => strtolower($request->excelFile->getClientOriginalExtension()),
            ],
            [
                'file' => 'required',
                'extension' => 'required|in:doc,csv,xlsx,xls,docx,ppt,odt,ods,odp',
            ]
        );
        $issuesArray = array();
        $successArray = array();
        $randPathName = $pathNo;
        $file = $request->file('excelFile');

        $destinationPath = public_path('files/' . $randPathName . '/');
        $file->move($destinationPath, $file->getClientOriginalName());

        $Path = $destinationPath . $file->getClientOriginalName();
        $rows = Excel::load($Path, function ($reader) { })->get();
        $parentProducts = array();
        $childProducts = array(); // Prooduct Variants
        $productsImagesImport = $productsImages = array();
        $directoryPath = public_path('files/' . $randPathName);
        $files = scandir($directoryPath);

        foreach ($files as $file) {
            if (is_dir("$directoryPath" . "/" . "$file")) {
                if ($file !== '.' && $file !== '..' && $file !== '__MACOSX') {
                    $this->deleteDirectory("$directoryPath" . "/" . "$file");
                }
            } else {
                if (is_array(getimagesize("$directoryPath" . "/" . "$file"))) {
                    $filePath = $directoryPath . "/" . $file;
                    $fileNameToArray = explode('.', $file);
                    $fileExtention = end($fileNameToArray);
                    $randNewFileName = rand(10000000, 99999999) . '.' . $fileExtention;
                    $newFilePath = public_path('imgs/products') . "/" . $randNewFileName;

                    rename($filePath, $newFilePath);
                    $thumbFilePath = public_path('imgs/products') . "/thumb/" . $randNewFileName;

                    $thumb = Image::make($newFilePath);
                    $thumb->resize(210, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $thumb->save($thumbFilePath);
                    $productsImagesImport[$file] = array("newPath" => $newFilePath, "newName" => $randNewFileName);
                    $image = true;
                } else {
                    if ($file !== '.' && $file !== '..' && $file !== '__MACOSX') {
                        unlink("$directoryPath" . "/" . "$file");
                    }
                }
            }
        }
        if (!empty($rows) && $rows->count()) {

            if (is_array($this->validateExcelSheet($rows))) {
                $issuesArray = $this->validateExcelSheet($rows);
            }

            $productsArray = $this->generateProductTreeForExcel($rows, $randPathName);
            //return $issuesArray;
        }
        //return $productsArray;
        if (is_array($productsArray)) {
            foreach ($productsArray as $productVals) {
                $sizeItemCode = 0;
                if (isset($productVals['size_item_code'])) {
                    $sizeItemCode = $productVals['size_item_code'];
                }

                $colorItemCode = 0;
                if (isset($productVals['color_item_code'])) {
                    $colorItemCode = $productVals['color_item_code'];
                }
                $product = new Products;
                $product->name = $productVals['name_ar'];
                $product->name_en = $productVals['name_en'];
                $product->description = $productVals['desc_ar'];
                $product->description_en = $productVals['desc_en'];
                $product->item_group = $productVals['category_id'];
                $request = new \stdClass();
                $request->item_group = $productVals['category_id'];
                $seasonCodeId = 0;
                if (isset($productVals['season_id']) && $productVals['season_id'] !== null) {
                    $seasonCodeId = $productVals['season_id'];
                }

                $Pcode = $this->getProductBarcode($request, $sizeItemCode, $colorItemCode, $seasonCodeId);
                $product->item_code = $Pcode;
                $product->standard_rate = $productVals['selling_price'];
                $product->uom = 2;
                $product->weight = 1;
                $product->active = 1;
                $product->stock_qty = null;
                $product->is_bundle = 0;
                $product->sorting_no = 0;
                if (isset($productVals['variants']) && is_array($productVals['variants']) && count($productVals['variants']) > 0) {
                    $product->has_variants = 1;
                } else {
                    $product->has_variants = 0;
                }

                $product->is_variant = 0;
                $product->parent_variant_id = 0;
                $product->user_deleted = null;
                $product->has_attributes = 0;
                $product->is_deleted = 0;
                $product->image = null;
                $product->cost = $productVals['cost_price'];
                $product->brand_id = $productVals['brand_id'];
                if (isset($productVals['season_id']) && $productVals['season_id'] !== null) {
                    $product->season_id = $productVals['season_id'];
                }

                $product->save();

                $itemPrice = new ItemPrice;
                $itemPrice->product_id = $product->id;
                $itemPrice->price_list_id = 1;
                $itemPrice->rate = $productVals['selling_price'];
                $itemPrice->currency = "EGP";
                $itemPrice->save();

                $productOrgImgs = $productVals['images'];
                if (isset($productsImagesImport) && count($productsImagesImport) > 0 && isset($productVals['images']) && count($productVals['images']) > 0) {
                    foreach ($productOrgImgs as $productOrgImg) {
                        if (isset($productsImagesImport[$productOrgImg])) {

                            $newFileName = $productsImagesImport[$productOrgImg]['newName'];
                            \App\Image::create([
                                'image' => $newFileName,
                                'content_id' => $product->id,
                                'content_type' => get_class($product),
                                'image_order' => 0,
                            ]);
                        }
                    }
                }

                $pname = $productVals['name_en'];
                $successArray[] = "Item ($pname) was added successfully.";
                if (isset($productVals['variants']) && is_array($productVals['variants']) && count($productVals['variants']) > 0) {
                    $variantCounter = 1;
                    foreach ($productVals['variants'] as $variantProduct) {
                        if (!$this->variantAvailable($variantProduct['color_code'], $variantProduct['size_code'])) {
                            continue;
                        }
                        $colorVariationMeta = VariantsMeta::where('variant_code', $variantProduct['color_code'])->first();
                        $sizeVariationMeta = VariantsMeta::where('variant_code', $variantProduct['size_code'])->first();
                        $productVariant = new Products;
                        $productVariant->name = $variantProduct['name_ar'] . '-' . $colorVariationMeta->variation_value . '-' . $sizeVariationMeta->variation_value;
                        $productVariant->name_en = $variantProduct['name_en'] . '-' . $colorVariationMeta->variation_value_en . '-' . $sizeVariationMeta->variation_value_en;
                        $productVariant->description = $variantProduct['desc_ar'];
                        $productVariant->description_en = $variantProduct['desc_en'];
                        $productVariant->item_group = $variantProduct['category_id'];
                        $request = new \stdClass();
                        $request->item_group = $variantProduct['category_id'];
                        $Pcode2 = $Pcode . $variantProduct['color_item_code'] . $variantProduct['size_item_code'];
                        $productVariant->item_code = $Pcode2;
                        $productVariant->standard_rate = $variantProduct['selling_price'];
                        $productVariant->uom = 2;
                        $productVariant->weight = 1;
                        $productVariant->active = 1;
                        $productVariant->stock_qty = null;
                        $productVariant->is_bundle = 0;
                        $productVariant->sorting_no = 0;
                        $productVariant->has_variants = 0;
                        $productVariant->is_variant = 1;
                        $productVariant->parent_variant_id = $product->id;
                        $productVariant->user_deleted = null;
                        $productVariant->has_attributes = 0;
                        $productVariant->is_deleted = 0;
                        $productVariant->image = null;
                        $productVariant->cost = $variantProduct['cost_price'];
                        $productVariant->brand_id = $variantProduct['brand_id'];
                        if (isset($variantProduct['season_id']) && $variantProduct['season_id'] !== null) {
                            $productVariant->season_id = $variantProduct['season_id'];
                        }

                        $productVariant->save();

                        $itemPrice = new ItemPrice;
                        $itemPrice->product_id = $productVariant->id;
                        $itemPrice->price_list_id = 1;
                        $itemPrice->rate = $variantProduct['selling_price'];
                        $itemPrice->currency = "EGP";
                        $itemPrice->save();

                        $childProductOrgImgs = $variantProduct['images'];
                        if (isset($productsImagesImport) && count($productsImagesImport) > 0 && isset($variantProduct['images']) && count($variantProduct['images']) > 0) {
                            foreach ($childProductOrgImgs as $childProductOrgImg) {
                                if (isset($productsImagesImport[$childProductOrgImg])) {

                                    $newFileName = $productsImagesImport[$childProductOrgImg]['newName'];
                                    \App\Image::create([
                                        'image' => $newFileName,
                                        'content_id' => $productVariant->id,
                                        'content_type' => get_class($product),
                                        'image_order' => 0,
                                    ]);
                                }
                            }
                        }

                        $productVariation = new ProductVariations;
                        $productVariation->products_id = $productVariant->id;
                        $productVariation->parent_variant_id = $product->id;
                        $sizeCode = $variantProduct['size_code'];
                        $colorCode = $variantProduct['color_code'];
                        $productVariation->variations_data = json_encode($this->getVariationsData($colorCode, $sizeCode));
                        $productVariation->save();

                        $pname = $variantProduct['name_en'];
                        $successArray[] = "Item ($pname) was added successfully.";
                        $variantCounter++;
                        //$productVariation->variations_data = array($product->id);

                    }
                }
            }
        }

        $allArrays['successArray'] = $successArray;
        $allArrays['issuesArray'] = $issuesArray;

        return view('admin2.products.importResults', compact('allArrays'));
    }

    public function variantAvailable($colorCode, $sizeCode)
    {
        if (isset($colorCode) && $colorCode !== null && isset($sizeCode) && $sizeCode !== null) {
            $colorVariant = VariantsMeta::where('variant_code', $colorCode)->first();
            $sizeVariant = VariantsMeta::where('variant_code', $sizeCode)->first();

            if ($colorVariant && $sizeVariant) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function getVariationsData($colorCode, $sizeCode)
    {
        $variationData = array();
        $colorVariant = VariantsMeta::where('variant_code', $colorCode)->first();
        $sizeVariant = VariantsMeta::where('variant_code', $sizeCode)->first();
        $variationData[] = $colorVariant->variant_data_id . ':' . $colorVariant->id;
        $variationData[] = $sizeVariant->variant_data_id . ':' . $sizeVariant->id;
        return $variationData;
    }

    public function getProductVariants($itemcode)
    {
        $products[] = Products::where('item_code', $itemcode)->first();
        if (count($products) > 0) {
            $product = getProductWithVariations($products, 'en');
            //checking if the product has Variants return those Variants

            if (isset($product[0]->variants_products) && count($product[0]->variants_products) > 0) {
                return $product[0]->variants_products;
            } else {
                return $product[0];
            }
        }
    }
}
