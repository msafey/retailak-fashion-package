<?php

namespace App\Http\Controllers\PriceRules;

use App\Models\Categories;
use App\Http\Controllers\Controller;
use App\Models\ItemPrice;
use App\Models\PriceList;
use App\Models\PriceRules;
use DB;
use App\Models\Products;
use File;
use Image;

use Illuminate\Http\Request;

class PriceRulesController extends Controller
{


    public function priceRuleView($product_id)
    {
        $product = Products::find($product_id);
        $price_lists_ids = ItemPrice::whereProductId($product_id)->distinct()->pluck('price_list_id')->toArray();
        $price_lists = PriceList::whereIn('id', $price_lists_ids)->get();
        return view('admin.price_rules.add', compact('product','product_id', 'price_lists'));
    }

    public function createOrUpdatePriceRule(Request $request, $product_id)
    {
        $this->validate($request, [
            'discount_type' => 'required',
            'discount_rate' => 'required',
            'price_list_id' => 'required',
            'min_qty' => 'required',
            'max_qty' => 'required',
            'price_rule_name' => 'required',
        ]);

        $discount_type = request('discount_type');
        $discount_rate = request('discount_rate');
        $price_list_id = request('price_list_id');
        $min_qty = request('min_qty');
        $max_qty = request('max_qty');
        $price_rule_name = request('price_rule_name');
        $valid_from = date('Y-m-d H:i:s', strtotime($request->valid_from));
        $valid_to = date('Y-m-d H:i:s', strtotime($request->valid_to));

        $item_price = ItemPrice::where('price_list_id',$price_list_id)->where('product_id', $product_id)->firstOrFail();
        $min_price = $discount_type == 'price'
            ? $min_qty*$item_price->rate - $discount_rate : (
                ($min_qty*$item_price->rate) - ($min_qty*$item_price->rate*$discount_rate/100)
            );
        $max_price = $discount_type == 'price'
            ? $max_qty*$item_price->rate - $discount_rate : (
                ($max_qty*$item_price->rate) - ($max_qty*$item_price->rate*$discount_rate/100)
            );

        PriceRules::updateOrCreate(
            ['product_id' => $product_id],
            ['price_rule_name' => $price_rule_name, 'valid_from' => $valid_from,
                'valid_to' => $valid_to, 'discount_type' => $discount_type, 'discount_rate' => $discount_rate,
                'min_qty' => $min_qty, 'max_qty' => $max_qty,
                'min_price' => $min_price , 'max_price' => $max_price,
                'item_price_id' => $item_price->id]
        );

        return redirect('admin/products')->with('message', 'Price Rule Updated Successfully');

    }

}
