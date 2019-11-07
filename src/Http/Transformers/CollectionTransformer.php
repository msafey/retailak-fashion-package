<?php

namespace App\Http\Transformers;

use App\BundleData;
use App\BundleMeta;
use App\SMSGateway;

class CollectionTransformer extends Transformer
{
    /**
     * @var ProductsTransformer
     */
    private $productsTransformer;

    function __construct()
    {
        $productsTransformer = new ProductsTransformer;
        $this->productsTransformer = $productsTransformer;
    }

    public function transform($collection)
    {
        $items = $this->productsTransformer->transformCollection($collection['products']);

        return [
            'items' => $items,
            "id" => $collection['id'],
            "name" => getLang() == 'en' ? $collection['name'] : $collection['name_ar'],
            "sortno" => $collection['sortno'],
        ];

    }

    /**
     * @return string
     */

}
