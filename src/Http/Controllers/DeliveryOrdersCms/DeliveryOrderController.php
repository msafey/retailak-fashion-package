<?php

namespace App\Http\Controllers\DeliveryOrdersCms;

use App\Models\Addresses;
use App\Models\Adjustments;
use App\Models\DeliveryNote;
use App\Models\Delivery_Man;
use App\Models\Delivery_Orders;
use App\Models\District;
use App\Http\Controllers\utilitiesController;
use App\Mail\SalesOrderMail;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\OrderStatus;
use App\Models\PaymentMethods;
use App\Models\Products;
use App\Models\RunsheetOrders;
use App\Models\SalesInvoice;
use App\Models\SalesOrderPayment;
use App\Models\Settings;
use App\Models\ShippingRule;
use App\Models\TimeSection;
use App\Models\User;
use App\Models\Warehouses;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use PDF;
use Response;
use Yajra\Datatables\Datatables;

class DeliveryOrderController extends utilitiesController
{

    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet'))
        {
            return view('admin.un-authorized');
        }

        $couriers = Delivery_Man::where('status', 1)->get();
        $warehouses = Warehouses::where('status', 1)->get();
        return view('admin/delivery-orders/list', compact('couriers', 'warehouses'));
    }

    public function DeliveryOrdersList()
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet'))
        {
            return view('admin.un-authorized');
        }

        $courier_id = -1;
        $branch_id = -1;
        if (isset($_GET['courier_id'])) {
            $courier_id = $_GET['courier_id'];
        }
        if (isset($_GET['branch_id'])) {
            $branch_id = $_GET['branch_id'];
            $warehouse = Warehouses::where('id', $branch_id)->first();
            if ($warehouse) {
                $district_ids = json_decode($warehouse->district_id);
            }
        }

        $query = DB::table('delivery__orders')
            ->join('delivery__men', 'delivery__orders.delivery_id', '=', 'delivery__men.id')
            ->join('time_sections', 'delivery__orders.time_section_id', '=', 'time_sections.id')
            ->select('delivery__orders.*', 'time_sections.from', 'time_sections.to', 'delivery__men.name', 'delivery__men.district_id')
            ->orderBy('delivery__orders.id', 'DESC');

        if ($courier_id != -1 && $branch_id != -1) {
            $delivery_oreders = $query->where('delivery__orders.delivery_id', $courier_id)->get();
            foreach ($delivery_oreders as $key => $value) {
                if (!in_array($value->district_id, $district_ids)) {
                    $delivery_oreders->forget($key);
                }
            }
        } elseif ($courier_id != -1 && $branch_id == -1) {
            $delivery_oreders = $query->where('delivery__orders.delivery_id', $_GET['courier_id'])->get();
        } elseif ($courier_id == -1 && $branch_id != -1) {
            $delivery_oreders = $query->get();
            foreach ($delivery_oreders as $key => $value) {
                // dd($district_ids);
                if (!in_array($value->district_id, $district_ids)) {
                    $delivery_oreders->forget($key);
                }
            }
        } else {
            $delivery_oreders = $query->get();
        }

        if (isset($_GET['not_completed']) && $_GET['not_completed'] != 0) {
            foreach ($delivery_oreders as $key => $order) {
                // $orders_id = unserialize($order->orders_id);
                $orders_id = [];
                if (isset($order->runsheetOrders)) {
                    $runsheet_orders = $order->runsheetOrders;
                    foreach ($runsheet_orders as $runsheet_order) {
                        $orders_id[] = $runsheet_order->order_id;
                    }
                }
                foreach ($orders_id as $key => $value) {
                    $order_data = Orders::where('id', intval($value))->first();
                    if ($order_data->status != 'Pending') {
                        // $order->status = $order_data->status;
                        $delivery_oreders->forget($key);
                    }
                }
            }
        }
        // dd($delivery_oreders);
        if (count($delivery_oreders) > 0) {
            foreach ($delivery_oreders as $del) {
                $count_of_pending = 0;
                $delivery_orders = RunsheetOrders::where('delivery_order_id', $del->id)->get();

                if ($delivery_orders) {
                    foreach ($delivery_orders as $del_order) {
                        // dd($del_order->order_id);
                        $order_item = Orders::where('id', $del_order->order_id)->first();
                        // dd($order_item);
                        if ($order_item) {
                            if ($order_item->status == 'Assigned') {
                                $count_of_pending += 1;
                            }
                        }

                    }

                    $del->count_of_orders = count($delivery_orders);
                    if (count($delivery_orders) <= 0) {
                        $del->count_of_orders = 0;
                    }
                }
                if (!isset($del->count_of_orders)) {
                    $del->count_of_orders = 0;
                }
                $del->count_of_pending = $count_of_pending;
            }
        }

        return Datatables::of($delivery_oreders)->make(true);
    }

    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet'))
        {
            return view('admin.un-authorized');
        }

        $delivery_man = Delivery_Man::where('status', 1)->get();
        $districts = District::all();
        $timesection = TimeSection::all();
        return view('admin.delivery-orders.add', compact('delivery_man', 'districts', 'timesection'));
    }

    public function DelOrdersList()
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet'))
        {
            return view('admin.un-authorized');
        }

        $orders = [];
        $time_left = '';
        $setting = Settings::where('max_delivery_time', 24)->first();
        $date = new \DateTime();
        $date_after = new \DateTime();
        $date_after->modify('+' . $setting->min_delivery_time . ' hours');
        $date_after_format = $date_after->format('Y-m-d H:i:s');
        $time = $setting->max_delivery_time - $setting->min_delivery_time;
        $date->modify('-' . $time . ' hours');
        $formatted_date = $date->format('Y-m-d H:i:s');

        if (isset($_GET['delivery_time_left']) && $_GET['delivery_time_left'] != 0) {
            $time_left = $_GET['delivery_time_left'];
            if ($time_left == 'less_than_6hrs_left') {
                $orders = DB::table('orders')->orderBy('orders.id', 'DESC')->where('orders.created_at', '>=', $formatted_date)->where('orders.created_at', '<=', $date_after_format)->leftJoin('address', 'orders.address_id', '=', 'address.id')->where('orders.status', 'Added')
                    ->select('orders.*', 'address.street', 'address.district_id', 'address.city', 'address.country')
                    ->get();
                // dd($orders);
            } elseif ($time_left == 'time_out') {
                $orders = DB::table('orders')->orderBy('orders.id', 'DESC')->where('orders.created_at', '<', $formatted_date)->join('address', 'orders.address_id', '=', 'address.id')
                    ->where('orders.status', 'Added')
                    ->select('orders.*', 'address.street', 'address.district_id', 'address.city', 'address.country')
                    ->get();
            }
        }

        if (isset($_GET['order_id']) && $_GET['order_id'] != 0) {
            if (isset($_GET['district_id']) && $_GET['district_id'] != 0) {
                $orders = DB::table('orders')->where('orders.id', $_GET['order_id'])->join('address', 'orders.address_id', '=', 'address.id')
                    ->where('status', 'Added')->where('address.district_id', $_GET['district_id'])
                    ->select('orders.*', 'address.street', 'address.district_id', 'address.city', 'address.country')
                    ->get();

            } else {
                $orders = DB::table('orders')->where('orders.id', $_GET['order_id'])->join('address', 'orders.address_id', '=', 'address.id')
                    ->where('status', 'Added')
                    ->select('orders.*', 'address.street', 'address.district_id', 'address.city', 'address.country')
                    ->get();
            }
        } elseif (isset($_GET['district_id']) && $_GET['district_id'] != 0) {
            $orders = DB::table('orders')->orderBy('id', 'DESC')->join('address', 'orders.address_id', '=', 'address.id')
                ->where('status', 'Added')->where('address.district_id', $_GET['district_id'])
                ->select('orders.*', 'address.street', 'address.district_id', 'address.city', 'address.country')
                ->get();

        } else {
            $orders = DB::table('orders')->orderBy('id', 'DESC')->join('address', 'orders.address_id', '=', 'address.id')
                ->where('status', 'Added')
                ->select('orders.*', 'address.street', 'address.district_id', 'address.city', 'address.country')
                ->get();
        }

        foreach ($orders as $order) {
            $order_record = Orders::where('id', $order->id)->first();
            $district = District::where('id', $order->district_id)->first();
            if ($district && $order) {
                $address = $order->street . ' ' . $district->name . ' ' . $order->city . ' ' . $order->country;
            }
            // $address = $order->street . ' ' . $district->name . ' ' . $order->city . ' ' . $order->country;
            $productlist = '';
            // var_dump($address);
            $order->address = $address;

            $i = 1;
            $item_list = OrderItems::where('order_id', $order->id)->get();
            // $count_of_orders = count($item_list);
            $order->count_of_orders = count($item_list);
            foreach ($item_list as $list) {
                $productlist = $list->qty . ' ' . $list->item_name;
                if ($i < count($item_list)) {
                    $productlist .= ' , ';
                }

                $i++;

            }
        }

        return Datatables::of($orders)->make(true);
    }

    public function store(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet'))
        {
            return view('admin.un-authorized');
        }

        $this->validate($request, [
            'orders' => 'required',
            'delivery_man' => 'required',
            'time' => 'required',
        ]);
        $order = array_filter($request->input('orders'));
        if (count($order) == 0) {
            return redirect()->back()->withErrors('Select Order');
        }

        $orders = explode(",", $request->input('orders')[0]);
        foreach ($orders as $order_id) {
            $order = Orders::where('id', $order_id)->update(['status' => 'Assigned']);
            if ($order && isset($order->id)) {
                $orderStatus = new OrderStatus;
                $orderStatus->order_id = $order->id;
                $orderStatus->user_id = Auth::guard('admin_user')->user()->id;
                $orderStatus->user_type = 'Admin';
                $orderStatus->status = "Assigned";
                $orderStatus->save();
            }

        }
        $delivery_order = new Delivery_Orders;

        $delivery_order->delivery_id = $request->input('delivery_man');

        $delivery_order->orders_id = serialize($orders);

        $time = $request->input('time');
        if ($time == 1) {

            date_default_timezone_set("Africa/Cairo");
            $myvalue = date("h:i");
            $results = DB::select(DB::raw("SELECT * FROM time_sections ORDER BY ABS( `from` - '$myvalue') LIMIT 1"));
            $delivery_order->time_section_id = $results[0]->id;

            $delivery_order->date = date("Y/m/d");

        } elseif ($time == 2) {
            $delivery_order->time_section_id = $request->input('time_section');
            if ($request->input('date') != null) {
                $delivery_order->date = $request->input('date');
            } else {

                $delivery_order->date = date("Y/m/d");

            }

        }

        $delivery_order->save();
        if ($request->has('orders')) {
            $orders_id = $orders;
            foreach ($orders_id as $order) {
                $delivery_order->createRunsheetOrders(intval($order), $delivery_order->id);
            }
        }
        $user = Auth::guard('admin_user')->user();

        $delivery_order->adjustments()->attach($user->id, ['key' => "DeliveryOrder", 'action' => "Added", 'content_name' => $delivery_order->id]);

        return redirect('admin/runsheet')->with('Orders  Created Successfully');
    }

    public function show($id)
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet'))
        {
            return view('admin.un-authorized');
        }

        $orders = Orders::find($id);
        if ($orders) {
            $address_user = $orders->address;
            // dd($address_user->district_id);
            $district = District::where('id', $address_user->district_id)->first();
            $shipping_role = ShippingRule::where('id', $district->shipping_role)->first();
            if (!is_null($orders->shipping_role_id))
                $shipping_role = ShippingRule::where('id', $orders->shipping_role_id)->first();
            $user_data = $orders->user;
            $order_time = $orders->time;
            $final_total_price = 0;
            $products = array();
            $x = 0;
            if ($shipping_role) {
                $final_total_price = $shipping_role->rate;
                $shipping_rate = $shipping_role->rate;
            } else {
                $shipping_rate = 0;
            }

            $item_list = OrderItems::where('order_id', $id)->get();
            foreach ($item_list as $product) {
                $productsArray = DB::table('products')->where('id', $product->item_id)->first();
                // $rate = $this->itemPrice($productsArray->id);
                $rate = $product->rate;
                $product_order['total_price'] = $product->qty * $rate;
                $product_order['item_name'] = $product->name_en;
                $products[$x] = array_merge((array)$product_order, (array)$productsArray);
                $products[$x]['rate'] = $rate;
                $final_total_price += $product_order['total_price'];
                $product_order['rate'] = $rate;
                $x++;

            }

            return view('admin.delivery-orders.show', compact('total_price_shipping', 'shipping_rate', 'final_total_price', 'products', 'user_payment', 'delivery_man', 'order_time', 'orders', 'item_list', 'address_user', 'user_data'));

        }
    }

    public function edit($id, Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet'))
        {
            return view('admin.un-authorized');
        }

        $delivery_orders = Delivery_Orders::findOrFail($id);

        $delivery_man = Delivery_Man::all();
        $time_section = TimeSection::all();

        $runsheet_orders = $delivery_orders->runsheetOrders;
        foreach ($runsheet_orders as $runsheet_order) {
            $orders_ids[] = $runsheet_order->order_id;
        }
        // $orders_ids = unserialize($delivery_orders->orders_id);

        $timesection = TimeSection::all();

        $orders = DB::table('orders')->join('address', 'orders.address_id', '=', 'address.id')
            ->select('orders.*', 'address.street', 'address.district_id', 'address.city', 'address.country')
            ->paginate(4);
        $urlshow = url('/admin/runsheet/');

        $html = '';

        foreach ($orders as $order) {
            $productlist = '';
            $address = '';

            $i = 0;
            $district = District::where('id', $order->district_id)->first();
            $order_district = $district->name;
            $item_list = OrderItems::where('order_id', $order->id)->get();
            if ($item_list) {
                foreach ($item_list as $product) {
                    $address = $order->street . ' ' . $order_district . ' ' . $order->city . ' ' . $order->country;
                    $productlist .= $product->qty . ' ' . $product->item_name;
                    if ($i < count($item_list)) {
                        $productlist .= ' , ';
                    }

                    $i++;
                }
            }

            if (in_array($order->id, $orders_ids)) {
                $html .= '<tr  class="table" id="' . $order->id . '">' . '<td>' . '<input  type="checkbox" class="checkbox" id="' . $order->id . '" name="orders[]"  checked  value="' . $order->id . '"/> ' . '<td>' . $order->id . '</td>' . '<td>' . $productlist . '<td>' . $address . '</td>' . '<td>' . '</td>' . '<td>' . $order->payment_method . '</td>' . '<td>' . '<a class="btn btn-primary" href="' . $urlshow . '/' . $order->id . '">' . '<i class="fa fa-eye" aria-hidden="true" >' . '</i>' . '</a>' . '</td>' . '</tr>';

            } else {
                $html .= '<tr  class="table" id="' . $order->id . '">' . '<td>' . '<input  type="checkbox" class="checkbox" id="' . $order->id . '" name="orders[]"    value="' . $order->id . '"/> ' . '<td>' . $order->id . '</td>' . '<td>' . $productlist . '<td>' . $address . '</td>' . '<td>' . '</td>' . '<td>' . $order->payment_method . '</td>' . '<td>' . '<a class="btn btn-primary" href="' . $urlshow . '/' . $order->id . '">' . '<i class="fa fa-eye" aria-hidden="true" >' . '</i>' . '</a>' . '</td>' . '</tr>';

            }
        }
        if ($request->ajax()) {
            return $html;
        }

        return view('/admin/delivery-orders/edit', compact('delivery_orders', 'delivery_man', 'time_section', 'orders'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet'))
        {
            return view('admin.un-authorized');
        }

        $this->validate($request, [

            'orders' => 'required',
            'delivery_man' => 'required',
        ]);

        $delivery_order = Delivery_Orders::findOrFail($id);

        // array of order ids
        if ($delivery_order) {
            $runsheet_orders = $delivery_order->runsheetOrders;
        }
        foreach ($runsheet_orders as $runsheet_order) {
            $order_ids[] = $runsheet_order->order_id;
        }
        $orders_ids_new = $request->input('orders');

        $orders_Added = array_diff($order_ids, $orders_ids_new);
        foreach ($orders_Added as $order_Added_Id) {
            Orders::where('id', $order_Added_Id)->update(['status' => 'Added']);
            OrderStatus::where('order_id', $order_Added_Id)->delete();
            $orderStatus = new OrderStatus;
            $orderStatus->order_id = $order_Added_Id;
            $orderStatus->status = "Added";
            $orderStatus->save();
        }

        foreach ($request->input('orders') as $order_id) {
            Orders::where('id', $order_id)->update(['status' => 'Assigned']);
            $orderStatus = new OrderStatus;
            $orderStatus->order_id = $order_id;
            $orderStatus->status = "Assigned";
            $orderStatus->save();
        }

        $delivery_order = Delivery_Orders::findOrFail($id);

        $delivery_order->delivery_id = $request->input('delivery_man');
        // $delivery_order->orders_id = serialize($request->input('orders'));
        $delivery_order->time_section_id = $request->input('time_section');
        $delivery_order->date = $request->input('date');

        $delivery_order->save();
        if ($request->has('orders')) {
            $orders_id = $request->input('orders');
            foreach ($orders_id as $order) {
                $delivery_order->createRunsheetOrders(intval($order), $delivery_order->id);
            }
        }

        $user = Auth::guard('admin_user')->user();

        $delivery_order->adjustments()->attach($user->id, ['key' => "DeliveryMan", 'action' => "Edited", 'content_name' => $delivery_order->id]);

        return redirect('admin/runsheet/')->with('Delivery Order Updated Successfully');
    }

    public function details($id)
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet'))
        {
            return view('admin.un-authorized');
        }

        $order_delivery_id = $id;
        $orders_ids = [];
        $delivery_orders = Delivery_Orders::findOrFail($id);
        $delivery_man_id = $delivery_orders->delivery_id;
        $delivery_man_object = Delivery_Man::where('id', $delivery_man_id)->first();
        $delivery_man = $delivery_man_object->name;
        $runsheet_orders = $delivery_orders->runsheetOrders;
        foreach ($runsheet_orders as $runsheet_order) {
            $orders_ids[] = $runsheet_order->order_id;
        }
        // $orders_ids = unserialize($delivery_orders);
        $array_order = array();
        $product_order = array();
        $i = 0;
        if ($orders_ids) {
            foreach ($orders_ids as $order_id) {
                $x = 0;
                $orders = Orders::findOrFail($order_id);

                $array_order[$i]["id"] = $orders->id;
                $array_order[$i]["user_id"] = $orders->user_id;
                $array_order[$i]["payment_method"] = $orders->payment_method;
                $array_order[$i]["date"] = $orders->date;
                $array_order[$i]["salesorder_id"] = $orders->salesorder_id;
                $array_order[$i]["status"] = $orders->status;
                $array_order[$i]["erpnext_status"] = $orders->erpnext_status;
                $final_total_price = 0;
                $array_order[$i] = $orders;
                $product_order = array();
                $item_list = OrderItems::where('order_id', $order_id)->get();
                foreach ($item_list as $product) {
                    $productArray = DB::table('products')->where('id', $product->item_id)->first();

                    $rate = $product->rate;
                    $total_price = $rate * $product->qty;
                    $product_order[$x] = array_merge((array)$product, (array)$productArray);
                    $product_order[$x]['total_price'] = $total_price;
                    $product_order[$x]['rate'] = $rate;
                    $final_total_price += $total_price;
                    $x++;
                }
                $array_order[$i]['productlist'] = $product_order;
                $array_order[$i]['final_total_price'] = $final_total_price;

                $i++;
            }
        }

        $total_money = 0;
        $count_of_delivered_orders = 0;
        $count_of_pending_orders = 0;
        $count_of_void_orders = 0;
        $count_of_cancelled_orders = 0;

        foreach ($array_order as $arr_order) {
            $item_list = OrderItems::where('order_id', $arr_order->id)->get();
            $qty_of_items = 0;
            $total = 0;
            $count_of_items = 0;

            if ($arr_order->status == 'Delivered') {
                $count_of_delivered_orders += 1;
            } elseif ($arr_order->status == 'Cancelled') {
                $count_of_cancelled_orders += 1;
            } elseif ($arr_order->status == 'Void') {
                $count_of_void_orders += 1;
            } elseif ($arr_order->status == 'Assigned') {
                $count_of_pending_orders += 1;
            }
            foreach ($item_list as $list) {
                $qty_of_items += $list->qty;
                $count_of_items += 1;
                $total += $list->qty * $list->rate;
            }
            $arr_order->count_of_items = $count_of_items;
            $arr_order->qty_of_items = $qty_of_items;
            $arr_order->total = $total;
            $total_money += $total;
        }
        $run_sheets = Delivery_Orders::all();
        $warehouses = Warehouses::where('status', 1)->get();
        $total_orders = count($array_order);

        $activities = [];
        $adjustments = Adjustments::where('content_name', 'DeliveryOrder-' . $id)->get();
        foreach ($adjustments as $adj) {
            $user = \App\AdminUser::where('id', $adj->user_id)->first();
            if ($user) {
                $username = $user->name;
            } else {
                $username = '';
            }
            $activities[] = (object)array('order_id' => $adj->content_id, 'status' => $adj->action, 'created_at' => $adj->created_at, 'user' => $username);
        }

        return view('admin.delivery-orders.details', compact('activities', 'count_of_delivered_orders', 'count_of_pending_orders', 'count_of_void_orders', 'count_of_cancelled_orders', 'total_money', 'total_orders', 'product_order', 'delivery_man', 'total', 'count_of_items', 'qty_of_items', 'warehouses', 'run_sheets', 'array_order', 'order_delivery_id', 'orders_ids'));
    }

    public function orderDetailsList()
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet'))
        {
            return view('admin.un-authorized');
        }

        $data = array();
        if (isset($_GET['orders'])) {
            $orders = $_GET['orders'];
            if (count($orders) > 0) {
                foreach ($orders as $order) {
                    $order_details = Orders::where('id', $order)->first();
                    if ($order_details) {
                        $customer = $order_details->user;

                        $order_items = $order_details->OrderItems;
                        // $product_details=[];
                        foreach ($order_items as $item) {
                            $product = Products::where('id', $item->item_id)->first();
                            $name = '';
                            if ($product) {
                                $name = $product->name_en;
                            }
                            // $product_details[]= $item->qty.' Of  '.$name;
                            $data[] = (object)array('order_id' => $order, 'product_name' => $name, 'qty' => $item->qty, 'rate' => $item->rate, 'total_amount' => $item->rate * $item->qty, 'customer' => $customer->name, 'package' => '--');
                        }
                    }
                }
            }
            // dd($data);
        }

        return Datatables::of($data)->make(true);
    }

// Dispatching Filter
    public function orders()
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet'))
        {
            return view('admin.un-authorized');
        }
        $array_order = [];
        if (isset($_GET['courier_id'])) {
            $courier_id = $_GET['courier_id'];
        }
        if (isset($_GET['run_sheet_id'])) {
            $run_sheet_id = $_GET['run_sheet_id'];
        }
        if ($courier_id != 0 && $run_sheet_id != 0) {

            $courier = Delivery_Man::where('id', $courier_id)->first();
            if ($courier) {
                $orders_ids = DB::table('delivery__men')
                    ->where('delivery__men.id', intval($courier_id))
                    ->join('delivery__orders', 'delivery__men.id', '=', 'delivery__orders.delivery_id')
                    ->where('delivery__orders.id', $run_sheet_id)
                    ->join('runsheet_orders', 'runsheet_orders.delivery_order_id', '=', 'delivery__orders.id')
                    ->select('runsheet_orders.order_id')
                    ->get();
                if (count($orders_ids) > 0) {
                    $array_order = $this->delivered_orders($orders_ids);
                }
            } else {
                return 'Courier Does Not Exist!';
            }
        } elseif ($courier_id != 0 && $run_sheet_id == 0) {
            $courier_id = $_GET['courier_id'];
            // dd($courier_id);
            // $run_sheet_id = $_GET['run_sheet_id'];

            $courier = Delivery_Man::where('id', $courier_id)->first();
            if ($courier) {
                $orders_ids = DB::table('delivery__men')
                    ->where('delivery__men.id', intval($courier_id))
                    ->join('delivery__orders', 'delivery__men.id', '=', 'delivery__orders.delivery_id')
                    ->join('runsheet_orders', 'runsheet_orders.delivery_order_id', '=', 'delivery__orders.id')
                    ->select('runsheet_orders.order_id')
                    ->get();
                if (count($orders_ids) > 0) {
                    $array_order = $this->delivered_orders($orders_ids);
                }
            } else {
                return 'Courier Does Not Exist!';
            }
        } elseif ($courier_id == 0 && $run_sheet_id != 0) {
            // $courier = Delivery_Man::where('id',$courier_id)->first();
            $delivery_order = Delivery_Orders::where('id', $run_sheet_id)->first();
            if ($delivery_order) {
                $orders_ids = $delivery_order->runsheetOrders;
            }

            if (count($orders_ids) > 0) {
                $array_order = $this->delivered_orders($orders_ids);
            }
        }

        if (isset($_GET['warehouse_id']) && $_GET['warehouse_id'] != 0) {
            // return 326236;
            $warehouse = Warehouses::where('id', $_GET['warehouse_id'])->first();
            if ($warehouse) {
                $districts_id = json_decode($warehouse->district_id);
            } else {
                $districts_id = [];
            }
            if ($courier_id == 0 && $run_sheet_id == 0) {
                $orders_ids = Orders::select('id as order_id')->get();
                // return($orders_ids);
                if (count($orders_ids) > 0) {
                    $array_order = $this->delivered_orders($orders_ids);
                }
            }
            foreach ($array_order as $key => $arr_order) {
                $address = Addresses::where('id', $arr_order->address_id)->first();
                if ($address) {
                    $district_id = $address->district_id;
                    if (!in_array($district_id, $districts_id)) {
                        unset($array_order[$key]);
                    }
                }
            }
        }

        foreach ($array_order as $arr_order) {
            $item_list = OrderItems::where('order_id', $arr_order->id)->get();
            $qty_of_items = 0;
            $total = 0;
            $count_of_items = 0;
            foreach ($item_list as $list) {
                $qty_of_items += $list->qty;
                $count_of_items += 1;
                $total += $list->qty * $list->rate;
            }
            $arr_order->count_of_items = $count_of_items;
            $arr_order->qty_of_items = $qty_of_items;
            $arr_order->total = $total;
        }

        // return 'no';
        return $array_order;
    }

    public function delivered_orders($orders_id)
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet'))
        {
            return view('admin.un-authorized');
        }

        $i = 0;
        $array_order = array();
        $product_order = array();
        foreach ($orders_id as $order_id) {
            $x = 0;
            $orders = Orders::findOrFail($order_id->order_id);
            $array_order[$i]["id"] = $orders->id;
            $array_order[$i]["user_id"] = $orders->user_id;
            $array_order[$i]["payment_method"] = $orders->payment_method;
            $array_order[$i]["date"] = $orders->date;
            $array_order[$i]["salesorder_id"] = $orders->salesorder_id;
            $array_order[$i]["status"] = $orders->status;
            $array_order[$i]["erpnext_status"] = $orders->erpnext_status;
            $final_total_price = 0;

            $array_order[$i] = $orders;
            $product_order = array();

            $item_list = OrderItems::where('order_id', $order_id->order_id)->get();
            foreach ($item_list as $product) {
                $productArray = DB::table('products')->where('id', $product->item_id)->first();

                $rate = $product->rate;
                $total_price = $rate * $product->qty;
                $product_order[$x] = array_merge((array)$product, (array)$productArray);
                $product_order[$x]['total_price'] = $total_price;
                $product_order[$x]['rate'] = $rate;
                $final_total_price += $total_price;
                $x++;
            }
            $array_order[$i]['productlist'] = $product_order;
            $array_order[$i]['final_total_price'] = $final_total_price;

            $i++;
        }
        return $array_order;
    }

    public function runSheetActions()
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet'))
        {
            return view('admin.un-authorized');
        }
        $couriers = Delivery_Man::all();
        $warehouses = Warehouses::where('status', 1)->get();
        $run_sheets = Delivery_Orders::all();
        return view('admin.delivery-orders.runsheet_actions', compact('array_order', 'array_order', 'couriers', 'warehouses', 'run_sheets'));
    }

    public function filters()
    {

    }

    public function getSalesInvoicePage()
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet')) {
            return view('admin.un-authorized');
        }

        $sales_order_id = Input::get('order_id');
        $delivery_order_id = Input::get('order_delivery_id');
        $orderdetails = Orders::where('id', $sales_order_id)->first();
        if ($orderdetails->status != 'Delivered') {
            return 'Deliver Order First';
        }

        $date = date('Y-m-d H:i:s');
        $address = $orderdetails->address;
        $district = District::where('id', $address->district_id)->first();
        $selected_shipping_role = ShippingRule::where('id', $district->shipping_role)->first();
        $shipping_rate = $selected_shipping_role ? $selected_shipping_role->rate : 0;
        $shipping_roles = ShippingRule::all();
        $user = User::where('id', $orderdetails->user_id)->first();
        $sales_invoice = SalesInvoice::where('order_id', $sales_order_id)->first();
        $products = array();
        $x = 0;
        $final_total_price = 0;
        $productlist = OrderItems::where('order_id', $sales_order_id)->get();
        foreach ($productlist as $product) {
            $productsArray = DB::table('products')->where('id', $product->item_id)->first();
            if (isset($productsArray)) {
                $rate = $product->rate;
            } else {
                $rate = 0;
            }
            $product_order['total_price'] = $product->qty * $rate;
            $product_order['rate'] = $rate;
            $products[$x] = array_merge((array)$product_order, (array)$productsArray);
            $final_total_price += $product_order['total_price'];

            $x++;
        }

        $promocode_array = orderPromocodeDiscount($orderdetails, $final_total_price, $shipping_rate);
        $final_price_after_discount = $promocode_array['total_amount_after_discount'];
        $promocode_msg = $promocode_array['promocode_msg'];
        $code = $promocode_array['promocode'];

        $final_total_price += $shipping_rate;
        $data = view('admin.delivery-orders.sales_invoice_modal', compact('delivery_order_id', 'final_total_price', 'user', 'products', 'sales_invoice', 'orderdetails', 'productlist', 'date', 'address', 'district', 'selected_shipping_role', 'sales_order_id', 'shipping_roles', 'shipping_rate', 'final_price_after_discount', 'code', 'promocode_msg'))->render();
        return response()->json(['data' => $data]);
    }

    public function emailSalesInvoice(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet'))
        {
            return view('admin.un-authorized');
        }

        $delivery_order_id = $request->delivery_order_id;
        $user_id = $request->user_id;
        if ($request->has('body')) {
            $body = $request->body;
        } else {
            $body = '';
        }
        $products = $request->products;
        $final_total_price = $request->final_total_price;
        foreach ($products as $key => $item) {
            $product = Products::where('id', $item['id'])->first();
            $item_rate = $item['rate'];
            $item_details[] = (object)array('item_name' => $product->name_en, 'qty' => $item['qty'], 'rate' => $item_rate, 'total_amount' => $item['qty'] * $item_rate);
        }
        $user = User::where('id', $user_id)->first();
        if ($user) {
            $user_name = $user->name;
        } else {
            $user_name = 'Customer Name';
        }
        $order_id = $request->order_id;
        $shipping_rate = $request->shipping_rate;
        $final_total_price += $shipping_rate;

        $promocode_msg = '';
        if ($request->has('final_price_after_discount')) {
            $final_price_after_discount = $request->final_price_after_discount;
            $promocode_msg = $request->promocode_msg;
        }

        $pdf = PDF::loadView('admin.delivery-orders.pdf', compact('item_details', 'order_id', 'user_name', 'body', 'final_total_price', 'final_price_after_discount', 'promocode_msg', 'shipping_rate'));
        $fileName = 'sales_order' . time();
        $location = public_path('pdfs/' . $fileName . '.pdf');

        $pdf->save($location);
        Mail::send(new SalesOrderMail($location, $body, $user_name));
        if ($delivery_order_id == 0) {
            // this to get that the request coming from dispatching page not details
            return 'true++';
        } else {
            return 'true';
        }
    }

    public function postSalesInvoice($id, Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet')) {
            return view('admin.un-authorized');
        }

        $runsheet = 0;
        if ($request->has('sales_invoice_exist')) {
            $payment_exist = SalesOrderPayment::where('order_id', $id)->first();
            if ($payment_exist && $payment_exist->status == 2) {
                return 'cancel payment first';
            }
            SalesInvoice::where('id', $request->sales_invoice_exist)->update(['status' => 0]);
            return 'deactivated';
        } else {
            $delivery_note = DeliveryNote::where('order_id', $id)->first();
            $orderdetails = Orders::where('id', $id)->first();
            $productlist = OrderItems::where('order_id', $id)->get();
            $address = $orderdetails->address;
            $district = District::where('id', $address->district_id)->first();
            $delivery_order_id = $request->delivery_order_id;
            if ($delivery_order_id == 0) {
                $runsheet_order = RunsheetOrders::where('order_id', $id)->first();
                if ($runsheet_order) {
                    $delivery_order_id = $runsheet_order->delivery_order_id;
                    $runsheet = 1;
                }
            }
            SalesInvoice::updateOrCreate(['order_id' => $id, 'delivery_note_id' => $delivery_note->id], ['productlist' => json_encode($productlist), 'date' => date('Y-m-d H:i:s'), 'shipping_role_id' => $district->shipping_role, 'user_id' => $orderdetails->user_id, 'status' => 2, 'delivery_order_id' => $delivery_order_id]);

            $date = date('Y-m-d H:i:s');
            $user_id = $orderdetails->user_id;
            $user = User::where('id', $user_id)->first();
            $user = $user->email;
            $products = array();
            $x = 0;
            $final_total_price = 0;
            foreach ($productlist as $product) {
                $productsArray = DB::table('products')->where('id', $product->item_id)->first();

                if (isset($productsArray)) {
                    $rate = $product->rate;
                } else {
                    $rate = 0;
                }
                $product_order['total_price'] = $product->qty * $rate;
                $product_order['rate'] = $rate;
                $product_order['qty'] = $product->qty;
                $products[$x] = array_merge((array)$product_order, (array)$productsArray);
                $final_total_price += $product_order['total_price'];

                $x++;
            }
            $promocode_msg = $request->promocode_msg;
            $shipping_rate = $request->shipping_rate;
            $data['products'] = $products;
            $data['final_total_price'] = $final_total_price;
            $data['final_price_after_discount'] = $request->final_price_after_discount;
            $data['promocode_msg'] = $promocode_msg;
            $data['shipping_rate'] = $shipping_rate;
            $data = view('admin.delivery-orders.email-modal', compact('user', 'id', 'date', 'products', 'final_price_after_discount', 'final_total_price', 'runsheet', 'shipping_rate', 'promocode_msg', 'delivery_order_id'))->render();
            return response()->json(['data' => $data]);
        }
    }

    public function statusOrder(Request $request)
    {

        if (!Auth::guard('admin_user')->user()->can('runsheet')) {
            return view('admin.un-authorized');
        }

        $data = $request->all();
        $orders_id = $data['orders_ids'];
        foreach ($orders_id as $key => $dat) {
            $orderdetails = Orders::where('id', $dat)->first();
            $productlist = OrderItems::where('order_id', $dat)->get();
            $date = date('Y-m-d H:i:s');
            $address = $orderdetails->address;
            $district = District::where('id', $address->district_id)->first();
            $shipping_role = ShippingRule::where('id', $district->shipping_role)->first();
            $delivery_note = DeliveryNote::updateOrCreate(['order_id' => $orderdetails->id, 'shipping_role_id' => $district->shipping_role, 'user_id' => $orderdetails->user_id], ['productlist' => json_encode($productlist), 'date' => $date]);
            Orders::where('id', $dat)->update(['status' => 'Delivered']);
        }
    }

    public function updateStatus()
    {
        if (request()->has('delivered')) {
            $activities = [];
            $delivered_orders = array_filter(request('delivered'));
            foreach ($delivered_orders as $key => $dat) {
                $orderdetails = Orders::where('id', $dat)->first();
                if ($orderdetails) {
                    $productlist = OrderItems::where('order_id', $dat)->get();
                    $date = date('Y-m-d H:i:s');
                    $address = $orderdetails->address;
                    $district = District::where('id', $address->district_id)->first();
                    $shipping_role = ShippingRule::where('id', $district->shipping_role)->first();
                    $delivery_note = DeliveryNote::updateOrCreate(['order_id' => $orderdetails->id, 'shipping_role_id' => $district->shipping_role, 'user_id' => $orderdetails->user_id], ['productlist' => json_encode($productlist), 'date' => $date]);
                    $order_status = Orders::where('id', $dat)->first();
                    if ($order_status->status != 'Delivered') {
                        $activities['delivered'][] = $dat;
                    }
                    Orders::where('id', $dat)->update(['status' => 'Delivered']);
                    $orderStatus = new OrderStatus;
                    $orderStatus->order_id = $dat;
                    $orderStatus->status = "Delivered";
                    $orderStatus->save();
                }
            }
        }
        // dd($activities);
        if (request()->has('cancelled')) {
            $cancelled_orders = array_filter(request('cancelled'));
            foreach ($cancelled_orders as $key => $id) {
                $order = Orders::where('id', $id)->first();
                if ($order) {
                    if ($order->status != 'Cancelled') {
                        $activities['cancelled'][] = $id;
                    }

                    if ($order->status == 'Delivered') {
                        $payment_exist = SalesOrderPayment::where('order_id', $order->id)->where('status', 2)->first();
                        $sales_invoice_exist = SalesInvoice::where('order_id', $order->id)->where('status', 2)->first();
                        if ($sales_invoice_exist && $payment_exist) {
                            return 'cancel sales invoice and payment first';
                        }
                        if ($sales_invoice_exist) {
                            return 'cancel sales invoice first';
                        }
                        DeliveryNote::where('order_id', $order->id)->update(['status' => 0]);
                        $order->status = 'Cancelled';
                        $order->save();
                        $orderStatus = new OrderStatus;
                        $orderStatus->order_id = $order->id;
                        $orderStatus->status = "Cancelled";
                        $orderStatus->save();

                    } elseif ($order->status == 'Assigned') {
                        $order->status = 'Cancelled';
                        $order->save();
                        $orderStatus = new OrderStatus;
                        $orderStatus->order_id = $order->id;
                        $orderStatus->status = "Cancelled";
                        $orderStatus->save();
                    }
                    restoreStocks($order, 'all');
                }
            }
        }

        if (request()->has('void')) {
            $void_orders = array_filter(request('void'));
            foreach ($void_orders as $key => $id) {
                $order = Orders::where('id', $id)->first();
                if ($order) {
                    $order->status = 'Void';
                    $order->save();

                    $orderStatus = new OrderStatus;
                    $orderStatus->order_id = $order->id;
                    $orderStatus->status = "Void";
                    $orderStatus->save();
                }
            }
        }
        $user = Auth::guard('admin_user')->user();
        $activities['user'] = $user->name;

        foreach ($activities as $key => $value) {
            if ($key != 'user') {
                $activities[$key] = array_unique($value);
                foreach ($activities[$key] as $k) {
                    $delivery_order_items = RunsheetOrders::where('order_id', intval($k))->first();
                    if ($delivery_order_items) {
                        $order = Orders::where('id', intval($k))->first();
                        $delivery_order_id = $delivery_order_items->delivery_order_id;
                        $delivery_order = Delivery_Orders::where('id', $delivery_order_id)->first();
                        if ($order) {
                            // dd(32);
                            $order->adjustments()->attach($user->id, ['key' => "DeliveryOrder", 'action' => $key, 'content_name' => 'DeliveryOrder-' . $delivery_order_id]);
                        }
                    }

                }
            }

        }

        return $activities;
    }

    public function getPaymentEntry()
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet')) {
            return view('admin.un-authorized');
        }

        $id = Input::get('id');
        $sales_order_id = Input::get('order_id');
        $delivery_order_id = intval(Input::get('order_delivery_id'));
        // dd($delivery_order_id);
        $sales_invoice_exist = SalesInvoice::where('order_id', $sales_order_id)->first();
        if (!$sales_invoice_exist) {
            return 'no sales invoice';
        }
        if ($sales_invoice_exist && $sales_invoice_exist->status != 2) {
            return 'no sales invoice submitted';
        }
        $payment_exist = SalesOrderPayment::where('order_id', $sales_order_id)->first();
        $orders = Orders::where('id', $sales_order_id)->first();
        $payment_methods = PaymentMethods::all();
        $address_user = $orders->address;
        $user_data = $orders->user;
        $order_time = $orders->time;
        $productsArray = array();
        $x = 0;
        $final_total_price = 0;
        if ($orders) {
            $user = User::where('id', $orders->user_id)->first();
            $address = Addresses::where('id', $orders->address_id)->first();
            $district = District::where('id', $address->district_id)->first();
            $shipping_role = ShippingRule::where('id', $district->shipping_role)->where('disabled', 0)->first();
            if ($shipping_role) {

                $shipping_rate = $shipping_role->rate;
            } else {
                $shipping_rate = 0;
            }
            $final_total_price += $shipping_rate;
            $final_total_price = round($final_total_price, 2);
        }
        $productlist = OrderItems::where('order_id', $sales_order_id)->get();
        foreach ($productlist as $product) {
            $productsArray = DB::table('products')->where('id', $product->item_id)->first();

            if (isset($productsArray)) {
                $rate = $product->rate;
            } else {
                $rate = 0;
            }
            $product_order['total_price'] = $product->qty * $rate;
            $product_order['rate'] = $rate;

            $products[$x] = array_merge((array)$product_order, (array)$productsArray);
            $final_total_price += $product_order['total_price'];
            $x++;
        }
        $promocode_array = orderPromocodeDiscount($orders, $final_total_price, $shipping_rate);
        $total_amount_after_discount = $promocode_array['total_amount_after_discount'];
        $promocode_msg = $promocode_array['promocode_msg'];
        $promocode = $promocode_array['promocode'];

        if (!isset($products)) {
            $products = [];
        }
        $data = view('admin.delivery-orders.payment-modal', compact('sales_order_id', 'delivery_order_id', 'payment_exist', 'shipping_rate', 'promocode', 'total_amount_after_discount', 'total_price_shipping', 'final_total_price', 'products', 'user_payment', 'delivery_man', 'payment_methods', 'order_time', 'orders', 'address_user', 'user_data', 'promocode_msg'))->render();
        return response()->json(['data' => $data]);
    }

    public function postPaymentEntry($id, Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet')) {
            return view('admin.un-authorized');
        }

        $delivery_order_id = $request->delivery_order_id;
        if ($request->has('payment_exist')) {
            SalesOrderPayment::where('id', $request->payment_exist)->update(['status' => 0]);
            return 'cancelled';
        } else {
            if (!$request->has('payment_mode_id') || !$request->has('paid_amount')) {
                return 'false';
            }
            $requestData = $request->except(['_token']);
            if ($request->paid_amount < $request->final_total_amount_after_discount) {
                return 'check paid amount';
            }
            $sales_invoice = SalesInvoice::where('order_id', $id)->first();
            $payment_entry = SalesOrderPayment::updateOrCreate(
                ['order_id' => $id, 'sales_invoice_id' => $sales_invoice->id]
                , ['payment_mode_id' => $request->payment_mode_id, 'date' => date('Y-m-d:h:i:s', strtotime($request->date)), 'paid_amount' => $request->paid_amount, 'unallocated_amount' => $request->unallocated_amount, 'final_total_amount' => $request->final_total_amount, 'final_total_amount_after_discount' => $request->final_total_amount_after_discount, 'status' => 2]
            );
            if ($delivery_order_id == 0) {
                return 'true++';
            } else {
                return 'true';
            }
        }
    }

// Unassign
    public function changeStatus(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet')) {
            return view('admin.un-authorized');
        }

        $id = $request->unassign_order_id;
        // dd($id);
        $delivery_order_id = $request->delivery_order_id;
        // dd($delivery_order_id);
        $status = Orders::where('id', $id)->select('status')->first();
        if ($status->status == 'Assigned') {
            Orders::where('id', $id)->update(['status' => 'Added']);
            OrderStatus::where('order_id', $id)->delete();
            $orderStatus = new OrderStatus;
            $orderStatus->order_id = $id;
            $orderStatus->status = "Added";
            $orderStatus->save();
            $runsheet_order = RunsheetOrders::where('delivery_order_id', $delivery_order_id)->where('order_id', $id)->first();
            if ($runsheet_order) {
                $runsheet_order->delete();
            }
        } else {
            $delivery_order = Delivery_Orders::findOrFail($delivery_order_id);
            $run_sheet_orders = $delivery_order->runsheetOrders->where('order_id', $id)->first();
            $run_sheet_orders->delete();
        }
        return Response::json('true');
    }

    public function destroy($id)
    {
        if (!Auth::guard('admin_user')->user()->can('runsheet')) {
            return view('admin.un-authorized');
        }

        $delivery_order = Delivery_Orders::find($id);
        $delivery_order->delete();
        return redirect('admin/runsheet')->with('Delivery-Order Deleted Successfully');
    }

    public function printRecipte()
    {

    }
}
