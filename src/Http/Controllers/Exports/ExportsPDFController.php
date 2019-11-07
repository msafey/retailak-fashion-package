<?php

namespace App\Http\Controllers\Exports;


use App\Http\Controllers\Controller;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Products;
use DB;
use Excel;
use PDF;

class ExportsPDFController extends Controller
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

                    $productArray[] = ['id' => $product->id, 'name' => $product->name_en, 'code' => $product->item_code,
                        'rate' => isset($product->price->rate) ? $product->price->rate : 'N/A',
                        'warehouse' => $stock->name, 'qty' => $stock->pivot->projected_qty,
                        'color' => $color, 'size' => $size, 'category' => $itemCategoryName
                    ];
                }
            }
            $i++;
        }

        request()->merge(['productArray' => $productArray]);
        $pdf = PDF::loadView('admin.PDF.stockReports', $productArray);


        return $pdf->download('Stock-Reports.pdf');

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

        request()->merge(['sales' => $salesReportsArray]);
        $pdf = PDF::loadView('admin.PDF.sales', $salesReportsArray);


        return $pdf->download('Sales-Reports.pdf');
    }

    public function invoice()
    {
        $data = orders::with('user')->get();
        $values = [];
        foreach ($data as $one) {

            if ($one->user)
                $values[] = ['order_id' => $one->id, 'user_name' => $one->user->name];
        }

        request()->merge(['invoice' => $values]);

        $pdf = PDF::loadView('admin.PDF.invoice', $values);

        return $pdf->stream('Invoice-Reports.pdf');
    }
}
