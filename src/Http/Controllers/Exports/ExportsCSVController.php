<?php

namespace App\Http\Controllers\Exports;


use App\Http\Controllers\Controller;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Products;
use Excel;
use DB;

class ExportsCSVController extends Controller
{
    public function stock()
    {
        $products = Products::Variant()->with(['variantStock', 'price', 'variations'])->get();
        $productArray = [];
        $i = 0;
        foreach ($products as $product) {
            if (isset($product->variantStock) && isset($product->variations[0]) && isset($product->variations[1])) {
                $itemCategoryName = (($product->itemGroup) ? $product->itemGroup->name_en : 'No Category');
                foreach ($product->variantStock as $stock) {
                    $color = $product->variations[0]->variationMeta->value;
                    $size = $product->variations[1]->variationMeta->value;

                    $productArray[] = ['ID' => $product->id, 'Name' => $product->name_en, 'Code' => $product->item_code,
                        'Standard Rate' => isset($product->price->rate) ? $product->price->rate : 'N/A',
                        'Warehouse' => $stock->name, 'Stock Qty' => $stock->pivot->projected_qty,
                        'Color' => $color, 'Size' => $size, 'Category Name' => $itemCategoryName
                    ];
                }
            }
            $i++;
        }

        Excel::create('Stock-Reports', function ($excel) use ($productArray){
            $excel->sheet('sheet', function ($sheet) use ($productArray){
                $sheet->fromArray($productArray);
            });
        })->export('csv');

        return;
    }

    public function sales()
    {
        $salesReportsArray = OrderItems::join('products', 'order_items.item_id', 'products.id')
            ->join('categories', 'products.item_group', 'categories.id')
            ->select(DB::raw('SUM(qty) as qty '), DB::raw('DATE(order_items.created_at) as date'), 'item_id',
                'products.name_en as product_name', 'products.cost as cost', 'order_items.rate as price'
                , 'categories.name_en as category_name', 'products.item_code as barcode')
            ->groupBy('date', 'item_id', 'products.name_en', 'categories.name_en', 'products.item_code'
                , 'products.cost', 'order_items.rate')
            ->get();

        Excel::create('Sales-Reports', function ($excel) use ($salesReportsArray){
            $excel->sheet('sheet', function ($sheet) use ($salesReportsArray){
                $sheet->fromArray($salesReportsArray);
            });
        })->export('csv');

        return;
    }

    public function invoice()
    {
        $data = orders::with('user')->get();
        $values = [];
        foreach ($data as $one) {
            if($one->user)
            $values[] = ['Order No.' => $one->id, 'User Name' => $one->user->name];
            // dd($one->user);
        }

        Excel::create('Invoice-Reports', function ($excel) use ($values){
            $excel->sheet('sheet', function ($sheet) use ($values){
                $sheet->fromArray($values);
            });
        })->export('csv');

        return;
    }

}
