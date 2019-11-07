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

class AnalyticController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //   $Orders = Orders::select('productlist')->get();
    }

    public function date(Request $request) {
        if ($request->input('from') == null) {
            $from = "2017-02-30";
        } else {
            $from = date('Y-m-d', strtotime($request->input('from')));
        }
        if ($request->input('to') == null) {
            $to = date('Y-m-d');
        } else {
            $to = date('Y-m-d', strtotime($request->input('to')));
        }

//        $from = '2017-10-19';
//        $to = '2017-10-19';
        return [$from, $to];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request) {
        $Orders = Orders::select('productlist', 'id', 'user_id', 'date', 'salesorder_id', 'updeted')->where('updeted', 0)->get();

        $totalPrice = 0;
        $bundlesQty = [];
        $PRODUCTLIST = [];
        foreach ($Orders as $key => $value) {
            // dd($value->id);
            $items = json_decode($value->productlist, true);
            foreach ($items as $item) {
                $myproduct = Products::where('item_code',$item['item_code'])->first();
                if(is_null($myproduct))
                    $myproduct = DB::table('oldproducts')->where('item_code',$item['item_code'])->first();

                //  dd($myproduct);
                //if ($myproduct->is_bundle == true) {
                //dd($myproduct);
                $Bundle = Bundle::where('code', $item['item_code'])->first();
                if (!is_null($Bundle))
                    $orgQty = $Bundle->org_qty;
                //                 $PRODUCTLIST[$myproduct->id]['sold'] = $PRODUCTLIST[$myproduct->id]['sold'] + $item['qty'];
                //     dd($orgQty);
                if (isset($orgQty))
                    $PRODUCTLIST[$myproduct->id]['org_qty'] = $orgQty;


                //   dd($PRODUCTLIST[$myproduct->id]['org_qty']);
                $neworder_id = new Sales;
                $neworder_id->order_Id = $value->id;
                $neworder_id->user_Id = $value->user_id;
                $neworder_id->order_date = $value->date;
                $neworder_id->SoID = $value->salesorder_id;
                $neworder_id->item_name = $item['item_name'];
                $neworder_id->item_code = $item['item_code'];
                //   $totalPrice += $item->standard_rate * $item->qty;
                $neworder_id->qty = $item['qty'];
                $neworder_id->standard_rate = $myproduct->standard_rate;
                if ($myproduct->is_bundle == 1) {
                    $neworder_id->bunddle = 1;
                } else {
                    $neworder_id->bunddle = 0;
                }
                //          $neworder_id->org_qty = $PRODUCTLIST[$myproduct->id]['org_qty'];
                //   $neworder_id->sold = $PRODUCTLIST[$myproduct->id]['sold'];

                if ($neworder_id->save() == true) {
                    $orderuploaded = Orders::find($value->id);
                    // dd($orderuploaded);
                    $orderuploaded->updeted = 1;
                    $orderuploaded->save();
                    //  dd($orderuploaded);
                }



//
            }
        }
        return redirect('admin/analytics');
    }

    public function analytics(Request $request) {

        /*         * *****request********** */
        if ($request->input('from') == null) {
            $from = "2017-02-30";
        } else {
            $from = date('Y-m-d', strtotime($request->input('from')));
        }
        if ($request->input('to') == null) {
            $to = date('Y-m-d');
        } else {
            $to = date('Y-m-d', strtotime($request->input('to')));
        }

        /* if($to == null){

          } */
        /* $test = DB::table('orders')->whereBetween('date', ['2017-09-05', '2017-09-05'])->get();
          dd($test); */
        $active = $request->active;
        /*         * ******total Orders*********** */
        $Orderscount = DB::table('orders')->whereBetween('date', [$from, $to])->count('id');
//        dd($Orderscount);
        /*         * ******total money*********** */
        $get = [];
        $bundlesQty = [];
        $bundles = [];
        $arraytop = [];
        $counter = 0;

        /*         * **************not buddle****************** */
        $products = Products::where('is_bundle', 0)->get()->toArray();
        $ProductsArray = array();
        foreach ($products as $product) {
            //$ass = array();
            $code = $product["item_code"];
            $ProductsArray[$code] = $product["active"];
        }
        //dd($ProductArrayss);
        $productslists = Orders::whereBetween('date', [$from, $to])->pluck('productlist');
//         dd($productslists);
        foreach ($productslists as $productList) {
            $encodedProductList = json_decode($productList, TRUE);
            //print_r($arr);die;
            foreach ($encodedProductList as $ListProduct) {
                //  print_r($x["id"]);die;
                if (isset($arraytop[$ListProduct["item_code"]])) {

                    if (isset($ProductsArray[$ListProduct["item_code"]])) {
                        if ($ProductsArray[$ListProduct["item_code"]][1] == 0 || $ProductsArray[$ListProduct["item_code"]][1] == 1) {
                            $arraytop[$ListProduct["item_code"]] = $arraytop[$ListProduct["item_code"]] + $ListProduct['qty'];
                        }
                    }
                } else {
                    if (isset($ProductsArray[$ListProduct["item_code"]])) {
                        if ($ProductsArray[$ListProduct["item_code"]][1] == 0 || $ProductsArray[$ListProduct["item_code"]][1] == 1) {
                            $arraytop[$ListProduct["item_code"]] = $ListProduct['qty'];
                        }
                    }
                }
            }
        }

        //dd($arraytop);

        /*         * ******** Best seller qty***************** */
        //   dd($arraytop);
        asort($arraytop);
        $arrss = array_reverse($arraytop);
        $Best_seller_product_qty = array_slice($arrss, 0, 5);
        // dd($Best_seller_product_qty);
        $bestSellingProductsnames = [];
        $i = 0;
        foreach ($Best_seller_product_qty as $code => $qty) {
            // dd($code);
            $Pname = Products::where('item_code', $code)->first();
            if(empty($Pname)){
                $Pname = DB::table('oldproducts')->where('item_code', $code)->first();
            }
            if (!empty($Pname)) {
                $name = $Pname->name;
                $bestSellingProductsnames[$i] = $name;
                $i++;
                // echo "Name : ". $name."<br /> Qty :".$qty."<br /> <br />";
            }
            //   dd($bestSellingProductsnames);
        }
//        var_dump($bestSellingProductsnames);
//        dd($Best_seller_product_qty);
        /*         * ********end Best seller code***************** */

        /*         * ************Lowest Selling qty***************** */
        $Values2 = array_keys($Best_seller_product_qty);
        //dd($Values2);
        $valuessss = Products::whereIn('item_code', $Values2)->select('name')->get();
        $resultss = array_keys($arraytop);
        asort($arraytop);
        $Lowest_Selling_Product_qty = array_slice($arraytop, 0, 5);
        /*         * ************end Lowest Selling qty***************** */
        /*         * ************Lowest Selling code***************** */


        $lowestSellingProductsname = [];
        $i = 0;
        foreach ($Lowest_Selling_Product_qty as $code => $qty) {
            // dd($code);
            $Pname = Products::where('item_code', $code)->first();
            if(empty($Pname))
                $Pname = DB::table('oldproducts')->where('item_code', $code)->first();
            if (!empty($Pname)) {
                $name = $Pname->name;
                $lowestSellingProductsname[$i] = $name;
                // echo "Name : ". $name."<br /> Qty :".$qty."<br /> <br />";
            }
            $i++;
        }
        /*         * *******end Lowest Selling code***************** */
        /*         * *end not buddle************ */
        /*         * *is buddle************ */
        $arraytop2 = [];
        $productCodes2 = Products::where('is_bundle', 1)->get()->toArray();
        $ProductArrayss2 = array();
        foreach ($productCodes2 as $productCode2) {
            $ass = array($productCode2["active"], $productCode2["is_bundle"]);
            $code = $productCode2["item_code"];
            $ProductArrayss2[$code] = $ass;
        }

        //dd($ProductArrayss);
        $productslists2 = Orders::whereBetween('date', [$from, $to])->pluck('productlist');
        // dd($productslists2);


        foreach ($productslists2 as $arrs) {
            $arr = json_decode($arrs, TRUE);
            //print_r($arr);die;
            foreach ($arr as $x) {
                //  print_r($x["id"]);die;
                if (isset($arraytop2[$x["item_code"]])) {
                    if (isset($ProductArrayss2[$x["item_code"]])) {
                        if ($ProductArrayss2[$x["item_code"]][1] == 1 || $ProductArrayss2[$x["item_code"]][1] == 0) {
                            $arraytop2[$x["item_code"]] = $arraytop2[$x["item_code"]] + $x['qty'];
                        }
                    }
                } else {
                    if (isset($ProductArrayss2[$x["item_code"]])) {
                        if ($ProductArrayss2[$x["item_code"]][1] == 1 || $ProductArrayss2[$x["item_code"]][1] == 0) {
                            $arraytop2[$x["item_code"]] = $x['qty'];
                        }
                    }
                }
            }
        }
        //  dd($arraytop2);
//   dd($x["item_code"]);
        /*         * ******** Best seller qty***************** */

        //dd($arraytop2);
        asort($arraytop2);
        $arrss = array_reverse($arraytop2);
        $best_Selling_bunddle_qty = array_slice($arrss, 0, 5);
//        dd($best_Selling_bunddle_qty);

        /*         * ********end Best seller qty***************** */
        /*         * ************Best seller code***************** */
        $BestSellingProducts = [];
        $i = 0;
        foreach ($best_Selling_bunddle_qty as $code => $qty) {
            // dd($code);
            $Pname = Products::where('item_code', $code)->first();
            if(empty($Pname))
                $Pname = DB::table('oldproducts')->where('item_code', $code)->first();
            if (!empty($Pname)) {
                $name = $Pname->name;
                $BestSellingProducts[$i] = $name;
                // echo "Name : ". $name."<br /> Qty :".$qty."<br /> <br />";
            }
            $i++;
        }


        /*         * ********end Best seller code***************** */
        /*         * ************Lowest Selling qty***************** */
        //   dd($arraytop2['qty']);
        $resultss = array_keys($arraytop2);
        asort($arraytop2);
        $Lowest_Selling_bunddle_qty = array_slice($arraytop2, 0, 5);
        /*         * ************end Lowest Selling qty***************** */
        /*         * ************Lowest Selling code***************** */

        $lowestSellingBunddlesname = [];
        $i = 0;
        foreach ($Lowest_Selling_bunddle_qty as $code => $qty) {
            // dd($code);
            $Pname = Products::where('item_code', $code)->first();
            if(empty($Pname))
                $Pname = DB::table('oldproducts')->where('item_code', $code)->first();
            if (!empty($Pname)) {
                $name = $Pname->name;
                $lowestSellingBunddlesname[$i] = $name;
            }
            $i++;
        }

        /*         * *********************** */
        $totalPrice = 0;
        $productCodes2 = Products::get()->toArray();
        $ProductArrayss2 = array();
        foreach ($productCodes2 as $productCode2) {
            $ProductArrayss2 = $productCode2['standard_rate'];
        }

        // dd($ProductArrayss2);
        $productslists2 = Orders::whereBetween('date', [$from, $to])->select('productlist','shipping_rate')->get();
       // $productslists3 = Orders::whereBetween('date', [$from, $to])->pluck('shipping_rate');
   //   dd($productslists2);


        foreach ($productslists2 as $arrs) {
            $arr = json_decode($arrs['productlist'], TRUE);

             //dd($arrs['shipping_rate']);
            //print_r($arr);die;
            foreach ($arr as $x) {
               //  dd($x['item_code']);
                $getProductPrice = \App\Products::select('standard_rate')->where('item_code', $x['item_code'])->first();


                if(empty($getProductPrice))
                 $getProductPrice =  DB::table('oldproducts')->select('standard_rate')->where('item_code', $x['item_code'])->first();
                $totalPrice += $getProductPrice->standard_rate * $x['qty'];
            }
          $totalPrice = $totalPrice + $arrs['shipping_rate'];
        }
        $totalPrices = $totalPrice;
      //   dd($totalPrices);
        /*         * *************************** */
        /*         * *end is buddle************ */
     //   $totalPrice = 0;
        $sales = DB::table('sales')
                ->select('qty', 'item_name', 'standard_rate', 'org_qty', DB::raw('sum(qty) as total'))
                ->groupBy('item_code')
                ->whereBetween('order_date', [$from, $to])
                ->where('bunddle', 1)
                ->get();
        $arrsold = [];
        $qtySum = [];
        /* $sales2 = DB::table('sales')
          ->select('qty', 'item_name', 'standard_rate', 'org_qty')

          ->whereBetween('order_date', [$from, $to])
          ->where('bunddle', 1)
          ->get(); */
//        foreach ($sales as $sale) {
//            //dd($sale->qty);
//            $totalPrice += $sale->standard_rate * $sale->qty;
//        }
        // $totalPrices = $totalPrice;
        //  dd($totalPrices);
        /*         * *************total active Product*************** */
        $totalActiveProducts = DB::table('products')->where('active', true)->count('id');
        // dd($totalActive);
        /*         * **************************** */
        /*         * *************total active bunddle*************** */
        $totalActiveBunddles = DB::table('products')->where('is_bundle', true)->count('id');
        // dd($totalActive);
        /*         * **************************** */
        /*         * *************total active user*************** */
        $totalActiveUsers = DB::table('users')->count('id');
        /*         * **************************** */
        /*         * *************total deleviry user*************** */
        $deliveryMen = DB::table('delivery__men')->count('id');
        /*         * **************************** */
        /*         * *************total deleviry user*************** */
        $timeSections = DB::table('time_sections')->where('status', true)->count('id');
        /*         * **************************** */
        /*         * *************total promocodes*************** */
        $promocodes = DB::table('promocodes')->where('active', true)->count('id');
        /*         * **************************** */
        /*         * *************table promocodes*************** */
        $tablePromo = Promocode::select('userscount', 'code', 'org_qty')->get();
        // dd($tablePromo);
        // $aa = $totalPrice;


        //dd($ordersByUsers);


        return view('admin/analytic/list', compact('Orderscount', 'totalQty', 'lowestSellingBunddlesname', 'totalPrices', 'sales', 'LowestSellingProductsqty', 'lowestSellingProductsname', 'bestSellingProductsnames', 'LowestSellingProducts', 'BestSellingProducts', 'qty', 'BestSellingProduc', 'aa', 'Lowest_seller_bunddle_code', 'Lowest_Selling_bunddle_qty', 'best_Selling_bunddle_qty', 'Best_seller_bunddle_code', 'resultss', 'Best_seller_product_code', 'www', 'Lowest_Selling_Product_Code', 'Best_seller_product_qty', 'valuessss', 'Lowest_Selling_Product_qty', 'Values2', 'keymin2', 'result2', 'resultss2', 'bundles', 'totalActiveProducts', 'totalActiveBunddles', 'totalActiveUsers', 'deliveryMen', 'timeSections', 'promocodes', 'tablePromo', 'from', 'to'));
    }

   public function customerOrders(Request $request)
    {

        if ($request->input('from') == null) {
            $from = "2017-02-30";
        } else {
            $from = date('Y-m-d', strtotime($request->input('from')));
        }
        if ($request->input('to') == null) {
            $to = date('Y-m-d');
        } else {
            $to = date('Y-m-d', strtotime($request->input('to')));
        }

        if ($request->input('nofrom') == null) {
            $nofrom = 0;
        } else {
            $nofrom = $request->input('nofrom');
        }
        if ($request->input('noto') == null) {
            $noto = 100000000000;
        } else {
            $noto = $request->input('noto');
        }

        $productsArray = [];
        $orders2 = DB::table('orders')->whereBetween('orders.created_at', [$from, $to]);
        $orderz = DB::table('orders')->whereBetween('orders.created_at', [$from, $to])->get();
        $territories = [];
        //dd($orders);
        foreach($orderz as $order)
        {
            $orderProductList = json_decode($order->productlist);
            $userEmail = $order->user_id;
            $address = DB::table('address')->find($order->address_id);
            if($address)
            {
                $district = DB::table('districts')->find($address->regoin);
                if($district)
                {
                    if(!isset($territories[$userEmail][$district->district_ar]))
                        $territories[$userEmail][$district->district_ar] = 1;
                    else
                        $territories[$userEmail][$district->district_ar] = $territories[$userEmail][$district->district_ar] + 1;
                }


            }



            $OrderSum = 0;
            foreach($orderProductList as $userOrderProduct)
            {
                $productCode = $userOrderProduct->item_code;
                $product = DB::table('products')->where('item_code',$productCode)->first();

                if($product == null)
                    $product = DB::table('oldproducts')->where('item_code',$productCode)->first();


                if($product!== null)
                {
                    $OrderSum += $product->standard_rate*$userOrderProduct->qty ;


                }

            }
            if(!isset($userOrderSum[$order->user_id]))
                $userOrderSum[$order->user_id] = $OrderSum;
            else
                $userOrderSum[$order->user_id] =  $userOrderSum[$order->user_id] + $OrderSum;

        }

        $ordersByUsers2 = $orders2->join('users', 'orders.user_id', '=', 'users.id');
        /*foreach($ordersByUsers2 as $orderByUser2)
        {
            $userOrderProductList = unserialize($orderByUser2->productlist);
            $OrderSum = 0;
            foreach($userOrderProductList as $userOrderProduct)
            {
                $productId = $userOrderProduct['id'];
                $product = DB::table('products')->find($productId);
                if(!$product) DB::table('oldproducts')->find($productId);
                $OrderSum += $product->standard_rate;
            }
            if(!isset($userOrders[$orderByUser2->user.id]))
            {

                $userOrders[$orderByUser2->user.id] =
            }


        }*/

        $ordersByUsers = $ordersByUsers2->select(DB::raw('count(*) as orders_count, orders.user_id'),'users.name as username','users.phone as phone','users.id as usrid')->groupBy('user_id')->get();
        //dd($noto);
        foreach($ordersByUsers as $key => $userOrders)
        {
            if($userOrders->orders_count < $nofrom || $userOrders->orders_count > $noto)
            unset($ordersByUsers[$key]);
        }
        //dd($territories);
        //dd($userOrderSum);
        //dd($ordersByUsers);

        //$ordersByUsers = $ordersByUsers3->whereBetween('orders_count', [$nofrom, $noto])->get();
        return view('admin/analytic/userOrders',compact('ordersByUsers','userOrderSum','territories'));
    }
    public function excel(Request $request) {

        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name,
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.
//       $asd = $this->analytics($from);
//       dd($asd);
        if ($request->input('from') == null) {
            $from = "2017-02-30";
        } else {
            $from = date('Y-m-d', strtotime($request->input('from')));
        }
        if ($request->input('to') == null) {
            $to = date('Y-m-d');
        } else {
            $to = date('Y-m-d', strtotime($request->input('to')));
        }
        //  dd($from);
        $payments = [];
        $productCodes2 = Products::get()->toArray();
        $ProductArrayss2 = array();
        foreach ($productCodes2 as $productCode2) {
            $ass = array($productCode2["active"], $productCode2["is_bundle"]);
            $code = $productCode2["item_code"];
            $ProductArrayss2[$code] = $ass;
        }

        //dd($ProductArrayss);
        $productslists2 = Orders::whereBetween('date', [$from, $to])->pluck('productlist');
        // dd($productslists2);

        foreach ($productslists2 as $arrs) {
            $arr = json_decode($arrs, TRUE);
            //print_r($arr);die;
            foreach ($arr as $x) {
                //  $getProductPrice = \App\Products::select('name')->where('item_code', $x['item_code'])->first();
                if (isset($payments[$x["item_code"]])) {
                    $payments[$x["item_code"]] = $payments[$x["item_code"]] + $x['qty'];
                } else {
                    $payments[$x["item_code"]] = $x['qty'];
                }
            }
        }

//              $paymentss = Sales::select('id', 'item_name', DB::raw('sum(qty) as total'))->groupBy('item_code')->whereBetween('order_date', [$from, $to])->get();
//      dd($paymentss);
        $alldata = [];
        $i = 0;

        foreach ($payments as $code => $qty) {
            // dd($qty);
            $Pname = Products::where('item_code', $code)->first();

            $name = $Pname->name;
            $id = $Pname->id;
            //dd($id);
            $alldata[$i]['id'] = $id;
            $alldata[$i]['name'] = $name;
            $alldata[$i]['qty'] = $qty;
            $i++;
        }
        //  dd($alldata);

        $paymentsArray = [];

        // Define the Excel spreadsheet headers
        $paymentsArray[] = ['Id', 'Name', 'Quantity'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.

        foreach ($alldata as $payment) {
            //dd($payment);
            $paymentsArray[] = $payment;
        }
        //   dd($paymentsArray);
        // Generate and return the spreadsheet
        // dd($form);
        Excel::create('from ' . $from . ' to ' . $to, function($excel) use ($paymentsArray) {
//dd($paymentsArray);
            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Product');
            $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Product file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($paymentsArray) {
                $sheet->fromArray($paymentsArray, null, 'A1', false, false);
            });
        })->store('xls', storage_path('excel/exports'))->export('xls');
    }

}
