<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Bundle;
use App\Models\Promocode;
use App\Models\Sales;
use Maatwebsite\Excel\Facades\Excel;

class AutoReorderController extends Controller {

    public function products(Request $request) {
        $value =$request->value;
        if($value == null){
            $value = 10;
        }
        $AllProducts = Products::select('name', 'stock_qty')->orderBy('stock_qty', 'desc')->where('stock_qty', '<=', $value)->get();
        $allBundles = Bundle::select('code', 'org_qty')->orderBy('org_qty', 'desc')->where('org_qty', '<=', $value)->get();
        return view('admin/reorder/list', compact('AllProducts', 'allBundles'));
    }

}
