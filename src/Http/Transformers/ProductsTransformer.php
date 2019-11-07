<?php

namespace App\Http\Transformers;

use App\Products;

class ProductsTransformer extends Transformer
{
    public function transform($product, $filter = 1)
    {
        $variants = $variant_products = [];

        if (isset($product['variants_products'])) {
            list($variants, $variant_products) = $this->getVariantsProducts($product['variants_products']);
        }
        $pro = Products::find($product['id']);
        if ((count($variants) > 0) && count($variant_products) > 0) {
            return [
                'id' => $product['id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'description' => $product['description'],
                'favourite' => isset($product['favourite']['product_id']) ? true : false,
                'images' => $this->getImages($product['images']),
                'variants' => $variants,
                'variants_products' => $variant_products,
                'discount' => $pro->priceRuleRelation ? [
                    'price_list' => $product->priceRuleRelation->itemPrice->priceList->price_list_name,
                    'item_price_id' => $product->priceRuleRelation->itemPrice->id,
                    'min_price' => $product->priceRuleRelation->min_price,
                    'max_price' => $product->priceRuleRelation->max_price,
                    'min_qty' => $product->priceRuleRelation->min_qty,
                    'max_qty' => $product->priceRuleRelation->max_qty,
                    'discount_value' => $product->priceRuleRelation->discount_rate,
                    'discount_type' => $product->priceRuleRelation->discount_type,
                ] : null,
            ];
        } else {
            return [];
        }
    }

    private function getVariantsProducts($variantProducts)
    {
        $variants = $variants_keys = $variantsProductsArray = [];

        foreach ($variantProducts as $variantProduct) {

            $stock = isset($variantProduct['stock'][0]['projected_qty']) &&
            $variantProduct['stock'][0]['projected_qty'] > 0 ? $variantProduct['stock'][0]['projected_qty']
             : false;

            if (!$stock) {
                if (strpos(request()->url(), 'filter') !== false) {
                    $variants = $variant_products = [];
                }
                continue;
            }

            list($variation_options, $variation_codes) =
            $this->getVariationOptions($variantProduct['variations']);

            list($variants, $variants_keys) =
            Products::getVariants($variantProduct['variations'], $variants, $variants_keys);

            $price = $this->getPrice($variantProduct);
            $product = Products::findOrFail($variantProduct['id']);
            $slug_parent = Products::findOrFail($variantProduct['parent_variant_id']);
            $variantsProductsArray[] = [
                'id' => $variantProduct['id'],
                'parent_id' => $variantProduct['parent_variant_id'],
                'parent_slug' => getLang() == 'en'? $slug_parent->slug_en : $slug_parent->slug_ar,
                'name' => $variantProduct['name'],
                'description' => $variantProduct['description'],
                'stock_qty' => $stock,
                'season' => $variantProduct['season_id'],
                'price' => $price,
                'images' => $this->getImages($variantProduct['images']),
                'variant_option' => $variation_options,
                'variant_code' => $variation_codes,
                'discount' => $product->priceRuleRelation ? [
                    'price_list' => $product->priceRuleRelation->itemPrice->priceList->price_list_name,
                    'item_price_id' => $product->priceRuleRelation->itemPrice->id,
                    'min_price' => $product->priceRuleRelation->min_price,
                    'max_price' => $product->priceRuleRelation->max_price,
                    'min_qty' => $product->priceRuleRelation->min_qty,
                    'max_qty' => $product->priceRuleRelation->max_qty,
                    'discount_value' => $product->priceRuleRelation->discount_rate,
                    'discount_type' => $product->priceRuleRelation->discount_type,
                ] : null,
            ];
        }
        return array($variants, $variantsProductsArray);
    }

    public function getVariationOptions($variations)
    {
        $variation_codes = $variation_options = $variants_value_id = $variants_key_id = [];
        $x = 0;
        if (isset($variations)) {

            foreach ($variations as $variation) {

                $key_id = $variation['variation_data']['id'];
                $value_id = $variation['variation_meta']['id'];
                $key = $variation['variation_data']['key'];
                $value = $variation['variation_meta']['value'];

                $variation_options[$x]['key_id'] = $key_id;
                $variation_options[$x]['value_id'] = $value_id;
                $variation_options[$x]['value'] = $value;
                $variation_options[$x]['key'] = $key;

                $variation_codes[] = $value;

                $x++;


            }
        }
        return array($variation_options, $variation_codes);

    }


// Getting Product Images url Array

    public
    function getImages($images)
    {
        $urls = [];
        foreach ($images as $image) {
            $image_url = url('/public/imgs/products/' . $image['image']);
            $urls[] = $image_url;
        }
        return $urls;

    }

    /**
     * @param $product
     * @return |null
     */
    public
    function getPrice($product)
    {
        return isset($product['price']['rate']) ? $product['price']['rate'] : null;
    }

}
