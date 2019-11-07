<?php

namespace App\Http\Controllers\Products;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Transformers\ProductsTransformer;
use App\Models\ProductAttributes;
use App\Models\Products;
use App\Models\ProductVariations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class FilterProductsApiController extends Controller
{
    //
    public function filterProducts(Request $request)
    {
        $filters = $request->filters;
        $category = $request->category;
        $lang = getLang();

        if ((!$request->has('category')) || (!is_array($request->filters)) || (!$request->has('filters'))) {
            return Response::json(['Status' => 'Error', 'message' => 'Bad Request'], 400);
        }
        if ((int) $category > 0) {
            $categoryObject = Categories::where('id', $category)->first();
        } else {
            $categoryObject = Categories::where('slug_' . $lang, $category)->first();
        }
        $keys_id = $values_id = array();

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
                $query->where('categories.id', $categoryObject->id)
                    ->orWhere('categories.parent_item_group', $categoryObject->id);
            })
            ->select(
                'products.id',
                'parent_variant_id',
                getLang() == 'en' ? 'slug_en as slug' : 'slug_ar as slug',
                getLang() == 'en' ? 'name_en as name' : 'name',
                getLang() == 'en' ? 'description_en as description' : 'description',
                'season_id'
            )->get()
            ->with('variantsProducts.variations', 'favourite', 'images')
            ->get();
        $product_transformer = new ProductsTransformer;
        $products_array = count($products) > 0 ? $product_transformer->transformCollection($products->toArray()) : [];

        return $products_array;
    }
}
