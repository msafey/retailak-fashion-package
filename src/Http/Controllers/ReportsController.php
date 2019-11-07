<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use App\Models\Categories;
use App\Models\Company;
use App\Models\Delivery_Man;
use App\Models\Delivery_Orders;
use App\Models\District;
use App\Models\ItemPrice;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\PriceList;
use App\Models\SalesReport;
use App\Models\Products;
use App\Models\PurchaseOrders;
use App\Models\PurchaseOrdersItems;
use App\Models\Seasons;
use App\Models\Warehouses;
use App\Models\LogStock;
use Carbon;
use DB;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Auth;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.reports.items');
    }

    public function salesReports()
    {
        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }

        $salesReportsDates = SalesReport::distinct()->orderBy('date', 'desc')->get(['date'])->take(10);

        return view('admin.reports.sales', compact('salesReportsDates'));
    }

    public function salesReportsList()
    {
        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }

        $salesReportsArray = OrderItems::join('products', 'order_items.item_id', 'products.id')
            ->join('categories', 'products.item_group', 'categories.id')
            ->select(DB::raw('SUM(qty) as qty '), DB::raw('DATE(order_items.created_at) as date'), 'item_id',
                'products.name_en as product_name', 'products.cost as cost', 'order_items.rate as price'
                , 'categories.name_en as category_name', 'products.item_code as barcode')
            ->groupBy('date', 'item_id', 'products.name_en', 'categories.name_en', 'products.item_code'
                , 'products.cost', 'order_items.rate')
            ->get();
        return Datatables::of($salesReportsArray)->make(true);
    }

    public function adminStockImports()
    {
        return view('admin.reports.admin-stock-imports');
    }

    public function getadminStockImports()
    {
        $stocks = LogStock::OrderBy('created_at', 'desc')->with('admin')->get();
        return Datatables::of($stocks)->make(true);
    }

    public function dayReportsList($day)
    {
        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }

        $salesReportsArray = OrderItems::join('products', 'order_items.item_id', 'products.id')
            ->join('categories', 'products.item_group', 'categories.id')
            ->select(DB::raw('SUM(qty) as qty '), DB::raw('DATE(order_items.created_at) as date'), 'item_id',
                'products.name_en as product_name', 'categories.name_en as category_name', 'products.item_code as barcode')
            ->whereDate('order_items.created_at', Carbon\Carbon::parse($day)->toDateString())
            ->groupBy('date', 'item_id', 'products.name_en', 'categories.name_en', 'products.item_code')
            ->get();

        return Datatables::of($salesReportsArray)->make(true);
    }

    public function endOfDay()
    {
        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }

        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }

        $from = \request('start_date');
        $to = \request('end_date');

        $dateRange = [Carbon\Carbon::parse($from)->toDateString(), Carbon\Carbon::parse($to)->toDateString()];
        $totalOrders = Orders::whereBetween('orders.created_at', $dateRange)
            ->count();

        $totalDeliveredOrders = Orders::whereStatus('Delivered')
            ->whereBetween('orders.created_at', $dateRange)
            ->count();

        $totalCanceledOrders = Orders::whereStatus('Canceled')
            ->whereBetween('orders.created_at', $dateRange)
            ->count();

        $totalMoney = Orders::join('order_items', 'order_items.item_id', 'orders.id')
            ->select(DB::raw('SUM(rate * qty) as total_money'))
            ->whereBetween('orders.created_at', $dateRange)
            ->Where('status', 'Delivered')
            ->first();

        $totalMoneyPaymentMethods = Orders::join('order_items', 'order_items.item_id', 'orders.id')
            ->select(DB::raw('SUM(rate * qty) as total_money'), 'payment_method')
            ->whereBetween('orders.created_at', $dateRange)
            ->Where('status', 'Delivered')
            ->groupBy('payment_method')
            ->get();


        return view('admin.reports.end_of_day', [
            'totalOrders' => $totalOrders,
            'totalDeliveredOrders' => $totalDeliveredOrders,
            'totalCanceledOrders' => $totalCanceledOrders,
            'totalMoney' => $totalMoney,
            'totalMoneyPaymentMethods' => $totalMoneyPaymentMethods,
        ]);
    }


    public function dailyReportView($day)
    {
        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }

        return view('admin.reports.daily', compact('day'));

    }

    public function stockReports()
    {
        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }

        $brands = Brands::all();

        $categories = Categories::where('status', 1)->get();
        $seasons = Seasons::all();
        $warehouses = Warehouses::where('status', 1)->get();
        $minprice = Products::where('standard_rate', '!=', 0)->min('standard_rate');
        $maxprice = Products::where('standard_rate', '!=', 0)->max('standard_rate');

        return view('admin.reports.stock_reports', compact('brands', 'categories', 'minprice', 'maxprice', 'seasons', 'warehouses'));
    }

    public function stockReportsList()
    {
        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }

        $products = Products::Variant()->with(['variantStock', 'price', 'variations'])->get();
        $productArray = [];
        $i = 0;
        foreach ($products as $product) {
            if (isset($product->variantStock) && isset($product->variations[0]) && isset($product->variations[1])) {
                $itemCategoryName = (($product->itemGroup) ? $product->itemGroup->name_en : 'No Category');
                foreach ($product->variantStock as $stock) {
                    $color = $product->variations[0]->variationMeta->value;
                    $size = $product->variations[1]->variationMeta->value;

                    $productArray[] = ['id' => $product->id, 'name_en' => $product->name_en, 'item_code' => $product->item_code,
                        'standard_rate' => isset($product->price->rate) ? $product->price->rate : 'N/A',
                        'warehouse' => $stock->name, 'stock_qty' => $stock->pivot->projected_qty,
                        'color' => $color, 'size' => $size, 'cat_name' => $itemCategoryName
                    ];
                }
            }
            $i++;
        }
        return Datatables::of($productArray)->make(true);
    }


    public function itemList()
    {
        $products = Products::get();
        $item_objects = [];
        $buying_price_list = PriceList::where('price_list_name', 'Standard Buying')->first();
        $selling_price_list = PriceList::where('price_list_name', 'Standard Selling')->first();
        foreach ($products as $product) {
            $buying_price = 0;
            $selling_price = 0;
            $product_buying_price = PurchaseOrdersItems::where('item_id', $product->id)->get();
            // dd($product_buying_price);
            if (count($product_buying_price) > 0) {
                $last_product_object = collect($product_buying_price)->last();
                if ($last_product_object) {
                    $buying_price = $last_product_object->item_rate;
                }
            } else {

                if ($buying_price_list) {
                    $buying_item_price = ItemPrice::where('product_id', $product->id)->where('price_list_id', $buying_price_list->id)->first();
                    if ($buying_item_price) {
                        $buying_price = $buying_item_price->rate;
                    }

                }
            }
            $product_selling_price = OrderItems::where('item_id', $product->id)->get();
            if (count($product_selling_price) > 0) {
                $last_product_selling_object = collect($product_selling_price)->last();
                if ($last_product_selling_object) {
                    $selling_price = $last_product_selling_object->rate;

                }
            } else {

                if ($selling_price_list) {
                    $selling_item_price = ItemPrice::where('product_id', $product->id)->where('price_list_id', $selling_price_list->id)->first();
                    if ($selling_item_price) {
                        $selling_price = $selling_item_price->rate;
                    }
                }
            }
            $item_objects[] = (Object)array('product_name' => $product->name_en, 'product_id' => $product->id, 'selling_price' => $selling_price, 'buying_price' => $buying_price, 'profit' => $selling_price - $buying_price);
            // $prod
        }
        return Datatables::of($item_objects)->make(true);
    }

    public function getProductsProfitView()
    {
        return view('admin.reports.products_profit');
    }

    public function productsProfitList()
    {
        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }

        $products = Products::active()->isVariant()->get();
        if (isset($_GET['product_id']) && $_GET['product_id'] != "") {
            $products = Products::active()->isVariant()->where('id', $_GET['product_id'])->get();
        }
        foreach ($products as $product) {
            $product['profit'] = $product->standard_rate - $product->cost;
        }
        return Datatables::of($products)->make(true);
    }

    public function getBuyingPriceHistory()
    {
        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }
        if (!isset($_GET['product_id'])) {
            return 'false';
        }
        $product_id = $_GET['product_id'];
        // dd($product_id);
        // return $product_id;
        $selling_price_list = PriceList::where('price_list_name', 'Standard Selling')->first();
        $purchased_product_details = PurchaseOrdersItems::where('item_id', $product_id)->get();
        foreach ($purchased_product_details as $product_detail) {
            $product = Products::where('id', $product_detail->item_id)->first();
            if ($product) {
                $product_detail['product_name'] = $product->name_en . '-' . $product->name;
            } else {
                $product_detail['product_name'] = 'Item Of ID ' . $product_detail->item_id;
            }
            $order_items = OrderItems::where('item_id', $product_detail->item_id)->get();
            // dd($order_items);
            if (count($order_items) > 0) {
                foreach ($order_items as $key => $item) {
                    if ($item->created_at->gt($product_detail->created_at)) {
                        unset($order_items[$key]);
                    }
                }
            }
            if (count($order_items) > 0) {
                $latest_item_selling_price = collect($order_items)->last();
                if ($latest_item_selling_price) {
                    $product_detail['selling_price'] = $latest_item_selling_price->rate;
                }
            } else {
                if ($selling_price_list) {
                    $selling_itme_price = ItemPrice::where('product_id', $product_id)->where('price_list_id', $selling_price_list->id)->first();
                    if ($selling_itme_price) {
                        $product_detail['selling_price'] = $selling_itme_price->rate;
                    } else {
                        $product_detail['selling_price'] = 0;
                    }

                }
            }
            $product_detail['profit'] = $product_detail['selling_price'] - $product_detail['item_rate'];
        }
        $data = view('admin.reports.buying_history_modal', compact('product_id', 'purchased_product_details'))->render();
        return response()->json(['data' => $data]);
        // return $purchased_product_details;
    }

    public function getSellingPriceHistory()
    {
        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }

        if (!isset($_GET['product_id'])) {
            return 'false';
        }
        $product_id = $_GET['product_id'];
        // dd($product_id);
        $buying_price_list = PriceList::where('price_list_name', 'Standard Buying')->first();
        // dd($buying_price_list);
        $sales_order_details = OrderItems::where('item_id', $product_id)->get();
        // dd($sales_order_details);
        foreach ($sales_order_details as $product_detail) {
            $product = Products::where('id', $product_detail->item_id)->first();
            if ($product) {
                $product_detail['product_name'] = $product->name_en . '-' . $product->name;
            } else {
                $product_detail['product_name'] = 'Item Of ID ' . $product_detail->item_id;
            }
            $order_items = PurchaseOrdersItems::where('item_id', $product_detail->item_id)->get();

            // dd($order_items);
            if (count($order_items) > 0) {
                foreach ($order_items as $key => $item) {
                    if ($item->created_at->gt($product_detail->created_at)) {
                        unset($order_items[$key]);
                    }
                }
            }
            if (count($order_items) > 0) {
                $latest_item_buying_price = collect($order_items)->last();
                if ($latest_item_buying_price) {
                    $product_detail['buying_price'] = $latest_item_buying_price->item_rate;
                }
            } else {
                if ($buying_price_list) {
                    $buying_itme_price = ItemPrice::where('product_id', $product_id)->where('price_list_id', $buying_price_list->id)->first();
                    if ($buying_itme_price) {
                        $product_detail['buying_price'] = $buying_itme_price->item_rate;
                    } else {
                        $product_detail['buying_price'] = 0;
                    }

                }
            }
            $product_detail['profit'] = $product_detail['rate'] - $product_detail['buying_price'];
        }
        // return $sales_order_details;
        $data = view('admin.reports.selling_history_modal', compact('product_id', 'sales_order_details'))->render();
        return response()->json(['data' => $data]);
        // return $purchased_product_details;
    }

    public function districtIndex()
    {
        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }

        $districts = District::all();
        return view('admin.reports.districts', compact('districts'));
    }

    public function districtList()
    {
        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }

        $districts = $this->districtsOrders();
        return Datatables::of($districts)->make(true);
    }

    public function brandOrders()
    {
        $brands = Brands::all();
        $total = [];
        $count_of_orders = [];
        foreach ($brands as $brand) {
            $total[$brand->name_en] = 0;
            $orders = DB::table('brands')->where('brands.id', $brand->id)->join('products', 'products.brand_id', 'brands.id')->join('order_items', 'order_items.item_id', 'products.id')->join('orders', 'orders.id', 'order_items.order_id')->where('orders.status', 'Delivered')
                // ->join('districts','districts.id','address.district_id')
                ->select('orders.id as order_id', 'order_items.*', 'brands.id as brand_id', 'brands.name_en as brand_en', 'orders.status')->get();
            // ->select('orders.id as order_id','orders.address_id','order_items.*','districts.shipping_role','brands.id as brand_id','brands.name_en as brand_en','orders.status')->get();

            // $orders =  DB::table('orders')->where('status','Delivered')->join('order_items','orders.id','order_items.order_id')->join('products','products.id','order_items.item_id')->where('products.brand_id',$brand->id)->join('brands','brands.id','products.brand_id')->join('address','orders.address_id','address.id')

            foreach ($orders as $order) {
                if (!isset($total[$brand->name_en])) {
                    $total[$brand->name_en] = 0;
                }
                if (!isset($count_of_orders[$brand->name_en])) {
                    $count_of_orders[$brand->name_en] = 0;
                }
                $total[$brand->name_en] += $order->rate * $order->qty;
            }
        }
        return $total;
    }

    public function companiesOrders()
    {
        $companies = Company::all();
        $total = [];
        $count_of_orders = [];
        foreach ($companies as $company) {
            $total[$company->name_en] = 0;
            $orders = DB::table('companies')->where('companies.id', $company->id)->join('brands', 'companies.id', 'brands.company_id')->join('products', 'products.brand_id', 'brands.id')->join('order_items', 'order_items.item_id', 'products.id')->join('orders', 'orders.id', 'order_items.order_id')->where('orders.status', 'Delivered')->select('products.brand_id', 'order_items.*', 'companies.name_en as company_name', 'orders.status')->get();
            foreach ($orders as $order) {
                if (!isset($total[$order->company_name])) {
                    $total[$order->company_name] = 0;
                }
                if (!isset($count_of_orders[$order->company_name])) {
                    $count_of_orders[$order->company_name] = 0;
                }
                $total[$order->company_name] += $order->rate * $order->qty;
            }
        }
        return $total;
    }

    public function categoriesOrders()
    {
        $categories = Categories::where('status', 1)->get();
        $total = [];
        $count_of_orders = [];
        foreach ($categories as $category) {
            $total[$category->name_en] = 0;
            $table = 'categories';
            $orders = DB::table('categories')->where('categories.status', 1)->where('categories.id', $category->id)
                ->join('products', function ($join) use ($table) {
                    $join->on($table . '.id', '=', 'products.item_group');
                    $join->on($table . '.id', '=', 'products.second_item_group');
                })->join('order_items', 'order_items.item_id', 'products.id')->select('products.item_group', 'products.second_item_group', 'order_items.*', 'categories.name_en as category_name')->get();

            // ->join('products','products.item_group','brands.id')
            foreach ($orders as $order) {
                if (!isset($total[$order->category_name])) {
                    $total[$order->category_name] = 0;
                }
                if (!isset($count_of_orders[$order->category_name])) {
                    $count_of_orders[$order->category_name] = 0;
                }
                $total[$order->category_name] += $order->rate * $order->qty;
            }
        }

        return $total;
    }

    public function districtsOrders()
    {
        $districts = District::all();

        $orders = DB::table('orders')->join('address', 'orders.address_id', 'address.id')
            ->join('districts', 'districts.id', 'address.district_id')
            ->select('orders.id as order_id', 'districts.id as district_id', 'districts.shipping_role')
            ->get();
        $total = [];
        $count_of_orders = [];

        foreach ($orders as $order) {
            if (!isset($total[$order->district_id])) {
                $total[$order->district_id] = 0;
            }
            if (!isset($count_of_orders[$order->district_id])) {
                $count_of_orders[$order->district_id] = 0;
            }
            $order_object = Orders::where('id', $order->order_id)->where('status', 'Delivered')->first();
            if ($order_object) {
                $order_items = $order_object->OrderItems;
                $count_of_orders[$order->district_id] += 1;
                $total_price = 0;
                foreach ($order_items as $order_item) {
                    $total_price += $order_item->rate * $order_item->qty;
                }
                $total[$order->district_id] += $total_price;
            }

        }

        foreach ($districts as $key => $district) {
            $district->count_of_orders = 0;
            $district->total_money = 0;
            if (isset($total[$district->id])) {
                $district->total_money += $total[$district->id];
            }
            if (isset($count_of_orders[$district->id])) {
                $district->count_of_orders += $count_of_orders[$district->id];
            }
            if (isset($_GET['district_id'])) {
                $district_id = $_GET['district_id'];
                if (!$district_id == 0) {
                    if ($district->id != $district_id) {
                        unset($districts[$key]);
                    }
                }

            }
        }

        return $districts;
    }

    public function courierIndex()
    {
        if (!Auth::guard('admin_user')->user()->can('couriers'))
        {
            return view('admin.un-authorized');
        }

        $delivery_men = Delivery_Man::where('status', 1)->get();
        return view('admin.reports.couriers', compact('delivery_men'));
    }

    public function couriersList()
    {
        if (!Auth::guard('admin_user')->user()->can('couriers'))
        {
            return view('admin.un-authorized');
        }
        $delivery_men = Delivery_Man::where('status', 1)->get();
        $delivery_orders = DB::table('delivery__men')
            ->join('delivery__orders', 'delivery__men.id', 'delivery__orders.delivery_id')
            ->join('districts', 'districts.id', 'delivery__men.district_id')
            ->join('shipping_rules', 'districts.shipping_role', 'shipping_rules.id')
            ->select('delivery__men.id as delivery_man_id', 'delivery__men.name', 'shipping_rules.rate as shipping_rate'
                , 'districts.district_en', 'delivery__orders.id as delivery_order_id')
            ->get();

        $total = [];
        $count_of_orders = [];

        foreach ($delivery_orders as $order) {
            if (!isset($total[$order->delivery_man_id])) {
                $total[$order->delivery_man_id] = 0;
            }
            if (!isset($count_of_orders[$order->delivery_man_id])) {
                $count_of_orders[$order->delivery_man_id] = 0;
            }
            $delivery_order_object = Delivery_Orders::where('id', $order->delivery_order_id)->first();
            if ($delivery_order_object) {
                $runsheet_orders = $delivery_order_object->runsheetOrders;
                if (count($runsheet_orders) > 0) {
                    foreach ($runsheet_orders as $runsheet_order) {
                        $order_object = Orders::where('id', $runsheet_order->order_id)->where('status', 'Delivered')
                            ->first();
                        if ($order_object) {
                            $order_items = $order_object->OrderItems;
                            $count_of_orders[$order->delivery_man_id] += 1;
                            $total_price = 0;
                            foreach ($order_items as $order_item) {
                                $total_price += $order_item->rate * $order_item->qty;
                            }

                            $total[$order->delivery_man_id] += $total_price;
                        }
                    }
                }
            }
        }
        foreach ($delivery_men as $key => $courier) {
            $courier->count_of_orders = 0;
            $courier->total_money = 0;
            if (isset($total[$courier->id])) {
                $courier->total_money += $total[$courier->id];
            }
            if (isset($count_of_orders[$courier->id])) {
                $courier->count_of_orders += $count_of_orders[$courier->id];
            }
            if (isset($_GET['courier_id'])) {
                $courier_id = $_GET['courier_id'];
                if (!$courier_id == 0) {
                    if ($courier->id != $courier_id) {
                        unset($delivery_men[$key]);
                    }
                }

            }

        }
        return Datatables::of($delivery_men)->make(true);
    }

    public function reportsDashboard()
    {
        if (!Auth::guard('admin_user')->user()->can('reports'))
        {
            return view('admin.un-authorized');
        }

        $months_to_get = 11;
        $now = Carbon\Carbon::now();
        $current_month = $now->format('m');
        $first_day_of_the_current_month = Carbon\Carbon::today()->startOfMonth();

        $delivery_men = Delivery_Man::where('status', 1)->get();

        for ($j = 0; $j <= $months_to_get; $j++) {
            foreach ($delivery_men as $courier) {
                $total_courier_selling[$courier->name] = 0;
                $total_orders[$courier->name] = 0;
            }

            $delivery_orders = DB::table('delivery__men')
                ->join('delivery__orders', 'delivery__men.id', 'delivery__orders.delivery_id')
                ->join('districts', 'districts.id', 'delivery__men.district_id')
                ->join('runsheet_orders', 'runsheet_orders.delivery_order_id', 'delivery__orders.id')
                ->join('orders', 'orders.id', 'runsheet_orders.order_id')->where('orders.status', 'Delivered')
                ->join('shipping_rules', 'districts.shipping_role', 'shipping_rules.id')->where('delivery__orders.created_at', '>=', $first_day_of_the_current_month->copy()->subMonth($j))->where('delivery__orders.created_at', '<=', $first_day_of_the_current_month->copy()->subMonth($j)->endOfMonth())->select('delivery__men.id as delivery_man_id', 'delivery__men.name', 'shipping_rules.rate as shipping_rate', 'districts.district_en', 'delivery__orders.id as delivery_order_id', 'delivery__orders.created_at', 'runsheet_orders.order_id', 'orders.status', 'delivery__orders.id as delivery_order_id')->get();
            foreach ($delivery_orders as $order) {
                $delivery_man = Delivery_Man::where('id', $order->delivery_man_id)->where('status', 1)->first();
                if ($delivery_man) {
                    if (!array_key_exists($order->name, $total_courier_selling)) {
                        $total_courier_selling[$order->name] = 0;
                    }
                    $total_courier_selling[$order->name] += $this->totalSalesOrderPrice($order->order_id);
                    if (!array_key_exists($order->name, $total_orders)) {
                        $total_orders[$order->name] = 0;
                    }
                    $total_orders[$order->name] += 1;
                }

            }
            $total_monthly_orders[$first_day_of_the_current_month->copy()->subMonth($j)->format('F')] = $total_orders;
            $total_courier_selling_price[$first_day_of_the_current_month->copy()->subMonth($j)->format('F')] = $total_courier_selling;

        }
        $courier = [];
        // return ($total_courier_selling_price);
        foreach ($total_courier_selling_price as $price) {
            foreach ($price as $key => $value) {
                $courier_money[$key][] = $value;
            }
        }

        foreach ($total_monthly_orders as $monthly_orders) {
            foreach ($monthly_orders as $key => $value) {
                $courier_orders[$key][] = $value;
            }
        }
        foreach ($courier_orders as $key => $order) {
            $array_sum = array_sum($order);
            $courier_orders[$key . '-' . $array_sum] = $courier_orders[$key];
            unset($courier_orders[$key]);
        }

        foreach ($courier_money as $key => $order) {
            $array_sum = array_sum($order);
            $courier_money[$key . '-[' . $array_sum . ' L.E]'] = $courier_money[$key];
            unset($courier_money[$key]);
        }

        // PO TOTAL MONEY In Last 12 months
        for ($j = 0; $j <= $months_to_get; $j++) {
            $total_purchase_orders_money = 0;
            $purchase_orders = PurchaseOrders::where('created_at', '>=', $first_day_of_the_current_month->copy()->subMonth($j))->where('created_at', '<=', $first_day_of_the_current_month->copy()->subMonth($j)->endOfMonth())->get();
            foreach ($purchase_orders as $order) {
                $total_purchase_orders_money += $this->totalPurchaseOrderPrice($order->id);
            }
            $purchase_total[$first_day_of_the_current_month->copy()->subMonth($j)->format('F')] = $total_purchase_orders_money;

        }
        $po_keys = array_keys($purchase_total);
        $po_values = array_values($purchase_total);

        for ($i = 0; $i <= $months_to_get; $i++) {
            $total_sales_orders_money = 0;
            $sales_orders = Orders::where('created_at', '>=', $first_day_of_the_current_month->copy()->subMonth($i))->where('created_at', '<=', $first_day_of_the_current_month->copy()->subMonth($i)->endOfMonth())->where('status', 'Delivered')->get();
            // orders this month
            // $orders = Orders::where( DB::raw('MONTH(created_at)'), '=', date('n') )->where('status','Delivered')->get();
            foreach ($sales_orders as $order) {
                $total_sales_orders_money += $this->totalSalesOrderPrice($order->id);
            }

            $total[$first_day_of_the_current_month->copy()->subMonth($i)->format('n') . '-' . $first_day_of_the_current_month->copy()->subMonth($i)->format('Y')] = $total_sales_orders_money;
        }

        $so_keys = array_keys($total);
        $so_values = array_values($total);

        $districts = $this->districtsOrders();
        $brands_total = $this->brandOrders();
        $companies_total = $this->companiesOrders();
        $categories_total = $this->categoriesOrders();
        return view('admin.reports.dashboard', compact('so_keys', 'districts', 'companies_total', 'brands_total', 'so_values', 'courier_orders', 'courier_money', 'total', 'po_values', 'po_keys', 'purchase_total'));
    }

// TOTAL SALES ORDERS PRICE AFTER PROMOCODE IF EXIST ---@ask amir
    public function totalSalesOrderPrice($order_id)
    {
        $total = 0;
        $sales_order = Orders::where('status', 'Delivered')->where('id', $order_id)->first();
        if ($sales_order && isset($sales_order->OrderItems)) {
            $order_items = $sales_order->OrderItems;
            if (count($order_items) > 0) {
                foreach ($order_items as $item) {
                    $total += $item->rate * $item->qty;
                }
            }
            // $address = Addresses::where('id',$sales_order->address_id)->first();
            // if($address){
            //   $district = District::where('id',$address->district_id)->first();
            //   if($district){
            //     $shipping_role = ShippingRule::where('id',$district->shipping_role)->first();
            //     if($shipping_role){
            //       $shipping_rate=$shipping_role->rate;
            //     }else{
            //       $shipping_rate=0;
            //     }
            //   }
            // }
            // $usedPromocode = UsedPromocode::where('order_id',$sales_order->id)->first();
            // if($usedPromocode){
            //   $promocode = Promocode::where('code',$usedPromocode->code)->first();
            //   if($promocode){
            //       if($promocode->type == 'persentage'){
            //           $discount_rate = $total * $promocode->reward/100;
            //           $total -=$discount_rate;
            //       }else{
            //           $discount_rate = $promocode->reward;
            //           $total -=$discount_rate;
            //       }
            //       if($promocode->freeShipping !=1){
            //            if($shipping_role){
            //                $total += $shipping_rate;
            //            }
            //       }
            //   }
            // }else{
            // $total += $shipping_rate;
            // }

            return $total;
        } else {
            return false;
        }
    }

    public function totalPurchaseOrderPrice($purchase_order_id)
    {
        $purchase_order = PurchaseOrders::where('id', $purchase_order_id)->first();
        $total = 0;
        if ($purchase_order) {
            $purchase_receipts = $purchase_order->purchaseReceipt;
            // dd($purchase_receipts);
            foreach ($purchase_receipts as $receipt) {
                $accepted_items = unserialize($receipt->accepted_quantity);
                foreach ($accepted_items as $key => $value) {
                    $purchase_order_item = PurchaseOrdersItems::where('purchase_order_id', $purchase_order_id)->where('item_id', $key)->first();
                    if ($purchase_order_item) {
                        $total += $value * $purchase_order_item->item_rate;
                    }
                }
            }
        }
        return $total;
    }


    public function invoice()
    {
        return Datatables::of(orders::with('user'))->make(true);
    }

    public function getInvoiceDetails($id)
    {
        $orderItems = OrderItems::where('order_id', $id)->get();

        return view('admin.reports.invoice_details', ['items' => $orderItems]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
