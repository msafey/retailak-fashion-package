<?php

namespace App\Http\Controllers\BrandApi;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Brands;
use App\Models\Addresses;
use App\Events\ErrorEmail;
use App\Models\PriceList;
use App\Models\ItemPrice;
use App\Models\Image;
use App\Models\Warehouses;
use App\Models\ItemWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;

class BrandApiController extends ApiController
{
    //TO GET ALL EPISODES =>LATEST API

    public function getProducts()
    {
        try {
            $lang    = getLang();
            $token = app('request')->header('token');
            if ($token == null) {
                $token = app('request')->header('Authorization');
            }

            if (isset($_GET['id'])) {
                $brandId = trim($_GET['id']);
                // $address = Addresses::where('user_id',$user->id)->first();
                $brand =  Brands::where('id', $brandId)->first();
                $AllProducts = DB::table('products')->where('brand_id', $brandId)->where('active', 1)->orderBy('sorting_no', 'asc')->get();
                $array = array();

                foreach ($AllProducts as $key => $product) {
                    $product->standard_rate = itemSellingPrice($product->id);
                    $stock_qty = getApiProductStocks($product);
                    if ($product->standard_rate != 0 && !$stock_qty == 0) {
                        if ($lang == 'en') {
                            $product->name = $product->name_en;
                            $product->brand_name = trim($brand->name_en);
                        } else {
                            $product->brand_name = trim($brand->name);
                        }
                        $product = handleMultiImages($product);
                        $product->stock_qty = getApiProductStocks($product);
                        $array[] = $product;
                    }
                }
                $AllProducts = getProductWithVariations($array, $lang);
                if (count($AllProducts)) {
                    return Response::json($AllProducts, 200);
                } else {
                    return Response::json($array, 200);
                }
            } else if (isset($_GET['slug'])) {
                $brand_slug = $brandId = trim($_GET['slug']);
                $lang = getLang();
                $brand =  Brands::where('slug_' . $lang, $brand_slug)->first();
                $AllProducts = DB::table('products')->where('brand_id', $brand->id)->where('active', 1)->orderBy('sorting_no', 'asc')->get();
                $array = array();

                foreach ($AllProducts as $key => $product) {
                    $product->standard_rate = itemSellingPrice($product->id);
                    $stock_qty = getApiProductStocks($product);
                    if ($product->standard_rate != 0 && !$stock_qty == 0) {
                        if ($lang == 'en') {
                            $product->name = $product->name_en;
                            $product->brand_name = trim($brand->name_en);
                        } else {
                            $product->brand_name = trim($brand->name);
                        }
                        $product = handleMultiImages($product);
                        $product->stock_qty = getApiProductStocks($product);
                        $array[] = $product;
                    }
                }
                $AllProducts = getProductWithVariations($array, $lang);
                if (count($AllProducts)) {
                    return Response::json($AllProducts, 200);
                } else {
                    return Response::json($array, 200);
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

    public function brands()
    {
        $lang    = app('request')->header('lang');
        $brands = Brands::all();
        if (!$brands) {
            $brands = [];
        }
        foreach ($brands as $key => $brand) {
            $count_product[$brand->id] = 0;
            $products = DB::table('products')->where('active', 1)->where('products.brand_id', $brand->id)
                ->join('item_prices', function ($join) {
                    $item_list = PriceList::where('price_list_name', 'Standard Selling')->first();
                    $join->on('products.id', '=', 'item_prices.product_id')
                        ->where('item_prices.price_list_id', '=', $item_list->id);
                })->select('products.*', 'item_prices.rate as standard_rate')->get();
            // $products[$brand->id] = $product
            // $brand['products'] = $products;
            $count_product[$brand->id] = count($products);
            if ($count_product[$brand->id] == 0) {
                unset($brands[$key]);
                continue;
            }
            // $brand['count_product'] = $count_product[$brand->id];
            $brand_image = Image::where('content_id', $brand->id)->where('content_type', 'App\Brands')->first();
            if ($brand_image) {
                $brand['logo'] = url('public/imgs/brands') . '/' . $brand_image->image;
            }
            // $brand->name_ar = $brand->name;

            if ($lang == 'en') {
                $brand->name = trim($brand->name_en);
                $brand->description = trim($brand->description_en);
            } else {
                $brand->name = trim($brand->name);
                $brand->description = trim($brand->description);
            }
            unset($brand->description_en);
            unset($brand->name_en);
        }
        return Response::json($brands);
    }
}
