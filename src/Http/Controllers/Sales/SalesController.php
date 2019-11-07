<?php

namespace App\Http\Controllers\Sales;

use App\Models\Addresses;
use app\AdminUser;
use App\Models\AramexShipment;
use App\Models\Cart;
use App\Models\CartExtras;
use App\Models\CartItems;
use App\Models\Delivery_Orders;
use App\Models\District;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Promocode\PromocodeController;
use App\Http\Controllers\SalesOrderController;
use App\Models\ItemWarehouse;
use App\Models\Note;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\OrderStatus;
use App\Models\Products;
use App\Models\Promocode;
use App\Models\SalesReport;
use App\Models\ShippingRule;
use App\Models\TimeSection;
use App\Models\UsedPromocode;
use App\Models\User;
use App\Models\Warehouses;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Response;
use Yajra\Datatables\Datatables;

class SalesController extends Controller
{

    protected $salesordercontroller;
    protected $promocodecontroller;

    function __construct(SalesOrderController $salesordercontroller, PromocodeController $promocodecontroller)
    {
        $this->salesordercontroller = $salesordercontroller;
        $this->promocodecontroller = $promocodecontroller;
    }

    public function getItemRate()
    {
        $item = Input::get('item');
        $item_rate = itemSellingPrice($item);
        return Response::json($item_rate);
    }

    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('sales_orders'))
        {
            return view('admin.un-authorized');
        }

        $product_id = 0;
        if (isset($_GET['product_id'])) {
            $product_id = $_GET['product_id'];
        }
        $products = Products::get();
        return view('admin/sales_orders/index', compact('products', 'product_id'));
    }

    public function ordersList()
    {

        if (!Auth::guard('admin_user')->user()->can('sales_orders'))
        {
            return view('admin.un-authorized');
        }

        $query = Orders::with(['user', 'address', 'shipment']);


        $order_status = \request('order_status');
        $product_id = \request('product_id');


        if (\request()->has('order_status') && \request('order_status') != "0") {

            $query->where('status', $order_status);
        }

        if (\request()->has('product_id') && $product_id != "0") {

            $query->whereHas('allOrderItems', function ($query) use ($product_id) {
                $query->where('item_id', $product_id);
            });
        }

        $orders = $query->select('orders.*');

        return Datatables::of($orders)->make(true);

    }

    public function returnOrderProductsModal()
    {
        $order_id = isset($_GET['id']) ? $_GET['id'] : 0;
        $order = Orders::where('id', $order_id)->where('status', '!=', 'Cancelled')->first();
        $max_return_date = getMaxExpirationDays();
        if ($order) {
            $order_date = $order->created_at;
            $expiration_date = $order_date->copy()->addDays($max_return_date);
            if ($order_date->gte($expiration_date)) {
                return 'return_date_expired';
            }
            $order_items = $order->orderItems;
            foreach ($order_items as $item) {
                $item_product = Products::where('id', $item->item_id)->first();
                if ($item_product->is_variant == 1) {
                    $parent = Products::where('id', $item_product->parent_variant_id)->first();
                    $item_name = $parent->name_en . ' ' . $item_product->name_en;
                } else {
                    $item_name = $item_product->name_en;
                }
                $item->item_name = $item_name;
            }
            $order_id = $order->id;
            $data = view('admin.returns.returns', compact('order_items', 'order_id'))->render();
            return response()->json(['data' => $data]);
        } else {
            return 'not_found';
        }
    }

    public function returnOrderProducts(Request $request)
    {
        $order = Orders::where('id', $request->order_id)->first();
        if ($order->status == 'Cancelled') {
            return 'already_cancelled';
        }
        if ($request->target == 'return_selected') {
            $selected_products = $request->product_ids;
            if ($order) {
                if (is_array($selected_products) && count($selected_products) > 0) {
                    foreach ($selected_products as $item_id) {
                        $order_item = OrderItems::where('item_id', $item_id)->where('order_id', $request->order_id)->first();
                        restoreStocks($order_item, 'selected_only');
                        OrderItems::where('item_id', $item_id)->where('order_id', $order->id)->update(['returned' => 1]);
                    }
                    if (count($order->orderItems) == 0) {
                        $order->status = 'Cancelled';
                        $order->save();
                    }
                } else {
                    return 'no_product_selected';
                }

                return 'success';
            } else {
                return 'false';
            }
        } elseif ($request->target == 'return_all') {
            restoreStocks($order, 'all');
            $order->status = 'Cancelled';
            $order->save();
            return 'order_returned_successfully';
        } else {
            return 'false';
        }
    }

    public function details($id)
    {
        if (!Auth::guard('admin_user')->user()->can('sales_orders'))
        {
            return view('admin.un-authorized');
        }

        $runsheets = Delivery_Orders::all();
        $sales_order = Orders::findOrFail($id);

        $orderHistory = OrderStatus::where('order_id', $id)->orderBy('created_at')->get();
        $orderHistoryArr = [];
        if ($orderHistory) {
            foreach ($orderHistory as $orderHistoryItem) {
                $orderHistory = [];
                $orderHistory['status'] = $orderHistoryItem->status;
                $orderHistory['user_type'] = $orderHistoryItem->user_type;
                $orderHistory['user_id'] = $orderHistoryItem->user_id;
                if ($orderHistoryItem->user_type == 'Admin') {
                    $user = AdminUser::find($orderHistoryItem->user_id);
                } else {
                    $user = User::find($orderHistoryItem->user_id);
                }

                $orderHistory['user_name'] = 'NA';
                if ($user) {
                    $orderHistory['user_name'] = $user->name;
                }

                $orderHistory['time'] = $orderHistoryItem->created_at;
                $orderHistoryArr[] = $orderHistory;
            }
        }

        $items_list = isset($sales_order->OrderItems) ? $sales_order->OrderItems : [];
        $orderStatus = OrderStatus::where('order_id', $id)->orderBy('created_at', 'desc')->first();
        $sales_order->status = $orderStatus->status;
        $grand_total_amount = 0;
        $dbDistricts = DB::table('districts')->where('active', true)->select('id', 'district_ar')->get();
        $districts = [];
        foreach ($dbDistricts as $district) {
            $districts[$district->id] = $district->district_ar;
        }
        $user = User::where('id', $sales_order->user_id)->first();
        // $user = $user->name;
        // dd('username',$user);
        $address = Addresses::where('id', $sales_order->address_id)->first();
        $item_details = salesOrderDetails($items_list, $grand_total_amount);
        // dd($item_details);
        $grand_total_amount = $item_details['grand_total_amount'];
        $parent_array = $item_details['parent_array'];
        $item_details = $item_details['item_details'];
        $total_money_of_returned = 0;
        $returned_list = isset($sales_order->returnedOrderItems) ? $sales_order->returnedOrderItems : [];
        $returned_items = salesOrderDetails($returned_list, $total_money_of_returned);
        $total_money_of_returned = $returned_items['grand_total_amount'];
        $returned_parent_array = $returned_items['parent_array'];
        $returned_items = $returned_items['item_details'];
        $shipping_role = ShippingRule::where('id', $sales_order->shipping_role_id)->where('disabled', 0)->first();
        if ($shipping_role) {
            $shipping_rate = $shipping_role->rate;
        } else {
            $shipping_role = getOrderShippingRate($sales_order);
            if (is_array($shipping_role)) {
                if (isset($shipping_role['shipping_rate'])) {
                    $shipping_rate = $shipping_role['shipping_rate'];
                }

                if (isset($shipping_role['shipping_rule'])) {
                    $shipping_role = $shipping_role['shipping_rule'];
                }

            }

        }

        $grand_total_amount += $shipping_rate;
        $grand_total_amount = round($grand_total_amount, 2);
        $orderNotes = Note::where('order_id', $id)->orderBy('id', 'desc')->get();
        $total_amount_after_discount = orderPromocodeDiscount($sales_order, $grand_total_amount, $shipping_rate);
        $total_amount_after_discount = $total_amount_after_discount['total_amount_after_discount'];
        // dd($item_details);
        // dd('orderHistoryArr',$orderHistoryArr);
        return view('admin.sales_orders.details', compact('total_money_of_returned', 'parent_array', 'runsheets', 'districts', 'shipping_role', 'shipping_rate', 'sales_order', 'items_list', 'grand_total_amount', 'item_details', 'user', 'address', 'total_amount_after_discount', 'returned_parent_array', 'returned_items', 'orderHistoryArr', 'orderNotes'));
        // return view('admin.sales_orders.details', compact('total_money_of_returned', 'parent_array', 'runsheets', 'districts', 'shipping_role', 'shipping_rate', 'sales_order', 'items_list', 'grand_total_amount', 'item_details', 'user', 'address', 'promocode', 'total_amount_after_discount', 'returned_parent_array', 'returned_items', 'orderHistoryArr', 'orderNotes'));
    }

    // Foods
    public function getOrderFoodsDetails($items_list, $reference)
    {
        $data = [];
        $grand_total_amount = 0;
        foreach ($items_list as $item) {
            $product = Products::where('id', $item->item_id)->first();
            $extras = $item->orderExtras;
            $extra_key = '';
            $key = '';
            $extra_array = [];
            foreach ($extras as $extra) {
                $extra_object = Products::where('id', $extra->extra_id)->first();
                $extra_key .= $extra_object->name_en;
                $extra_array[] = array('id' => $extra->extra_id, 'extra_name' => $extra_object->name_en, 'qty' => $extra->qty, 'accepted' => 1, 'rate' => $extra->rate);
            }
            $total = itemTotalPrice($item);
            $grand_total_amount += $total;
            if ($product->is_variant == 1) {
                $parent = Products::where('id', $product->parent_variant_id)->first();
                if (strlen($extra_key) > 1) {
                    $key = $parent->name_en . '-' . $product->name_en . '-' . $extra_key;
                } else {
                    $key = $parent->name_en . '-' . $product->name_en;
                }
                $parent_id = $parent ? $parent->id : 0;
                $parent_name = $parent ? $parent->name : '';
                $object = array('order_item' => $item->id, 'parent_id' => $parent_id, 'parent_name' => $parent_name, 'item_rate' => $item->rate, 'qty' => $item->qty, 'option' => $parent_name, 'option_id' => $product->id, 'extras' => $extra_array, 'type' => $parent->food_type, 'total' => $total, 'key' => $key, 'note' => '', 'reference' => 'old', 'method_view' => $reference);
            } else {

                if (strlen($extra_key) > 1) {
                    $key = $product->name_en . '-' . $product->name_en . '-' . $extra_key;
                } else {
                    $key = $product->name_en . '-' . $product->name_en;
                }
                $object = array('order_item' => $item->id, 'parent_id' => $product->id, 'parent_name' => $product->name_en, 'item_rate' => $item->rate, 'qty' => $item->qty, 'option' => 'One Size', 'option_id' => $item->item_id, 'extras' => $extra_array, 'type' => $product->food_type, 'total' => $total, 'key' => $key, 'note' => '', 'reference' => 'old', 'method_view' => $reference);
            }
            $item = $object;
            $data[] = view('admin.sales_orders.cart', compact('item'))->render();
        }
        $array = [];
        $array['food_cart'] = $data;
        $array['grand_total_amount'] = $grand_total_amount;
        return $array;
    }

    public function getItemDetails()
    {
        $item_rate = 0;
        $item = Input::get('item');
        $product = Products::where('id', $item)->first();
        $item_rate = itemSellingPrice($item);
        $product_variations = array();
        $type = '';
        if ($product) {
            if ($product->has_variants == 1) {
                $variation_childs = Products::where('parent_variant_id', $product->id)->get();
                foreach ($variation_childs as $child) {
                    $array_of_extras = [];
                    $child_rate = itemSellingPrice($child->id);
                    if (checkProductConfig('variations') == true) {
                        $type = 'variations';
                        $product_variations[] = (object)array('item_name' => $child->name_en, 'item_id' => $child->id, 'qty' => 1, 'rate' => $child_rate, 'total_amount' => $child_rate);
                    } elseif (checkProductConfig('foods') == true) {
                        $type = $product->food_type;
                        $extras = DB::table('food_extas')
                            ->where('related_product_id', $child->id)
                            ->join('products', 'products.id', 'food_extas.extra_product_id')->select('food_extas.food_extra_price', 'food_extas.is_optional', 'products.name_en as name', 'products.id as extra_id')->get();
                        $product_variations[] = (object)array('item_name' => $child->name_en, 'item_id' => $child->id, 'qty' => 1, 'rate' => $child_rate, 'total_amount' => $child_rate, 'extras' => $extras);
                    }
                }
                return Response::json(['item_rate' => $item_rate, 'product_variations' => $product_variations, 'parent_name' => $product->name_en, 'type' => $type]);
            } else {
                $product_variations = array();
                if (checkProductConfig('foods') == true) {
                    $extras = DB::table('food_extas')->where('related_product_id', $product->id)->join('products', 'products.id', 'food_extas.extra_product_id')->select('food_extas.food_extra_price', 'food_extas.is_optional', 'products.name_en as name', 'products.id as extra_id')->get();
                    return Response::json(['item_rate' => $item_rate, 'product_variations' => $product_variations, 'parent_name' => "", 'type' => 'one_size', 'extras' => $extras]);
                } else {
                    return Response::json(['item_rate' => $item_rate, 'product_variations' => $product_variations, 'parent_name' => "", 'type' => 'normal']);
                }
            }
        }

    }

    public function getFood()
    {
        $item_rate = 0;
        $item = Input::get('item');
        // return($item);
        $type = '';
        $product = Products::where('id', $item)->first();
        $product_variations = array();
        $extras_array = array();
        if ($product) {
            if ($product->has_variants == 1) {
                $variation_childs = Products::where('parent_variant_id', $product->id)->get();
                foreach ($variation_childs as $child) {
                    $child_rate = itemSellingPrice($child->id);
                    $type = $product->food_type;
                    $extras = DB::table('food_extas')
                        ->where('related_product_id', $child->id)
                        ->join('products', 'products.id', 'food_extas.extra_product_id')->select('food_extas.food_extra_price', 'food_extas.is_optional', 'products.name_en as name', 'products.id as extra_id')->get();
                    $product_variations[] = (object)array('item_name' => $child->name_en, 'item_id' => $child->id, 'qty' => 1, 'rate' => $child_rate, 'total_amount' => $child_rate, 'extras' => $extras);
                }
            } else {
                $item_rate = itemSellingPrice($product->id);
                $type = $product->food_type;
                $extras_array = DB::table('food_extas')
                    ->where('related_product_id', $product->id)
                    ->join('products', 'products.id', 'food_extas.extra_product_id')->select('food_extas.food_extra_price', 'food_extas.is_optional', 'products.name_en as name', 'products.id as extra_id')->get();
            }
            $data = view('admin.sales_orders.food-modal', compact('product', 'item_rate', 'product_variations', 'item', 'type', 'extras_array'))->render();
            return response()->json(['data' => $data]);
        }
    }

    public function foodCartItemView()
    {
        $item = Input::get('item');
        if (!isset($item['extras'])) {
            $item['extras'] = [];
        }
        if ($item['type'] == 'one_size') {
            $item['option'] = 'One Size';
        }
        // return $item;
        $data = view('admin.sales_orders.cart', compact('item'))->render();
        // return $data;
        return response()->json(['data' => $data]);
    }

    public function RunsheetList()
    {
        $runsheets = Delivery_Orders::select('id as runsheet_id')->get();
        return Datatables::of($runsheets)->make(true);
    }

    public function assignOrder()
    {
        if (!$_GET['runsheet_id'] || !$_GET['order']) {
            return 'failed';
        } else {
            $runsheet_id = intval(trim($_GET['runsheet_id']));
            $order_id = intval(trim($_GET['order']));
            $runsheet = Delivery_Orders::where('id', $runsheet_id)->first();

            if ($runsheet) {
                $runsheet->createRunsheetOrders($order_id, $runsheet_id);
                $order = Orders::where('id', $order_id)->first();

                $order->update(['status' => 'Assigned']);
                return 'success';
            }
        }
    }

    public function restoreStocks($order)
    {
        $user_id = $order->user_id;
        $user = User::where('id', intval($user_id))->first();
        if ($user) {
            if (isset($order->address_id)) {
                $address = Addresses::find($order->address_id)->first();
            } else {
                $address = Addresses::where('user_id', $user->id)->first();
            }

            if ($address && isset($address->district_id)) {
                $warehouses = Warehouses::all();
                foreach ($warehouses as $warehouse) {
                    if (in_array($address->district_id, json_decode($warehouse->district_id))) {
                        $warehouse_id = $warehouse->id;
                    }
                }
            }
        }
        $order_items = $order->orderItems;
        foreach ($order_items as $item) {
            if (isset($warehouse_id)) {
                $item_warehouse = ItemWarehouse::where('product_id', $item->item_id)->where('warehouse_id', $warehouse_id)->first();
                if ($item_warehouse) {
                    $item_warehouse->projected_qty += $item->qty;
                    $item_warehouse->save();

                }
            }
        }
    }

    public function cancelOrder(Request $request)
    {
        $order = Orders::where('id', $request->caneceled_orders_ids)->first();
        if ($order->status == 'void') {
            return 'is_void';
        } elseif ($order->status == 'cancelled') {
            return 'cancelled';
        } else {
            $order->status = 'Cancelled';
            $order_items = $order->orderItems;
            if (count($order_items) > 0) {
                $this->restoreStocks($order);
            }
            $order->save();
            return 'success';
        }
    }

    public function create()
    {
        $sales_order_request = Input::get('sales_order_request');
        $item_details = array();
        $adminId = Auth::guard('admin_user')->user()->id;
        $parent_array = array();
        $shippingRoles = ShippingRule::get();
        if ($sales_order_request) {
            $address = Addresses::where('user_id', $sales_order_request)->first();
            if ($address) {
                $district = District::where('id', $address->district_id)->first();
                $shipping_role = ShippingRule::where('id', $district->shipping_role)->first();
                $shipping_rate = $shipping_role->rate;
            }
            $userCart = Cart::where('user_id', $sales_order_request)->first();
            if ($userCart) {
                // $productList = unserialize($userCart->productlist);
                $productList = $userCart->CartItems;
                // return $productList
                $array = $this->cartItems($productList);
                $item_details = $array['item_details'];
                $parent_array = $array['parent_array'];
            }
        }
        if (!isset($shipping_rate) || !$shipping_rate) {
            $shipping_rate = 0;
        }
        $time_sections = TimeSection::where('status', 1)->get();
        $products = Products::where('active', 1)->extras()->isVariant()->get();
        $parentProducts = Products::where('active', 1)->where('is_variant', 0)->where('is_deleted', 0)->select('name', 'name_en', 'item_code')->get();

        $users = User::whereActive(1)->get();

        $districts = DB::table('districts')->where('active', true)->get();

        if (checkProductConfig('foods')) {
            return view('admin.sales_orders.food_add_cart', compact('shippingRoles', 'item_details', 'parent_array', 'shipping_rate', 'sales_order_request', 'products', 'users', 'time_sections', 'districts', 'parentProducts', 'adminId'));
        } else {
            return view('admin.sales_orders.add', compact('shippingRoles', 'item_details', 'parent_array', 'shipping_rate', 'sales_order_request', 'products', 'users', 'time_sections', 'districts', 'parentProducts', 'adminId'));
        }
    }

    public function addNote(Request $request, $orderId)
    {
        $noteText = $request->note;
        if (trim($noteText) !== '') {
            $userId = Auth::guard('admin_user')->user()->id;
            $note = new Note;
            $note->note = $noteText;
            $note->order_id = $orderId;
            $note->admin_user_id = $userId;
            $note->save();
            $note = Note::orderBy('id', 'desc')->first();
            return array('noteText' => $noteText, 'created_at' => (string)$note->created_at);
        }
        return '';
    }

    public function getUserShippingRate()
    {
        $shipping_rate = 0;
        $address_id = Input::get('address_id');
        $address = Addresses::where('id', $address_id)->first();
        if ($address) {
            $district = District::where('id', $address->district_id)->first();
            $shipping_role = ShippingRule::where('id', $district->shipping_role)->first();
            if ($shipping_role) {
                $shipping_rate = $shipping_role->rate;
            }
        }
        return Response::json($shipping_rate);
    }

    public function getCurrentUserAddresses(Request $request)
    {
        $id = $request->id;

        $user = User::where('id', $id)->first();

        if (!$user) {
            return 'This User Does not exist in our database';
        }

        $addresses = Addresses::where('user_id', $user->id)->get();
        $userAddresses = array();
        foreach ($addresses as $address) {

            $userAddresses[] = (object)array('id' => $address->id, 'district_id' => $address->district_id, 'address' => $address->building_no . ' ' . $address->street . ' ' . $address->city . ' ' . $address->country);
            //$data['district']=$address->district_id;
            // $data['token']=$user->token;
        }
        if (empty($userAddresses)) {
            return "No addresses Available please add one - Click ->";
        } else {
            return $userAddresses;
        }

    } // Beshir

    public function checkUserExestance($email)
    {
        $user = User::where('email', $email)->first();
        $user ? $user : false;
        return $user;

    } //Beshir

    public function getUserData(Request $request)
    {
        $userId = $request->id;
        $user = User::where('id', $userId)->first()->toArray();
        $user ? $user : false;
        return $user;

    } //Beshir

    // Updated by osama from mail to id
    public function getUserAddress()
    {
        $cart = [];
        $id = Input::get('id');
//        dd($id);
        $order_id = Input::get('order_id');
        if (isset($order_id) && $order_id != 0) {
            $order = Orders::where('id', $order_id)->first();
            $data['selected_address'] = $order->address_id;
        } else {
            $data['selected_address'] = 0;
        }
        $user = User::where('id', (int)$id)->orderBy('id', 'desc')->first();

        if ($user) {
            $user_token = $user->token;
        }
        $arr = [];
        $user = User::where('id', $id)->orderBy('id', 'desc')->first();
        if ($user) {

        } else {
            return 'false';

        }
        $addresses = Addresses::where('user_id', $id)->where('active', 1)->get();
        foreach ($addresses as $address) {
            $data['user_id'] = $user->id;
            $arr[] = (object)array('id' => $address['id'], 'address' => $address['building_no'] . ' ' . $address['street'] . ' ' . $address['city'] . ' ' . $address['country']);
            $data['district'] = $address->district_id;
            $data['token'] = $user_token;
        }
        $data['address'] = $arr;
        if (count($addresses) == 0) {
            $data = [];
        }
        if (!$addresses) {
            $data = [];
        }
        $userCart = Cart::where('user_id', $id)->first();
        if ($userCart) {
            // $productList = unserialize($userCart->productlist);
            $productList = $userCart->CartItems;
            if (count($productList) > 0) {
                if (checkProductConfig('foods') && checkProductConfig('maintaining_stocks') == false) {
                    $data['food_cart'] = $this->foodItems($productList, 'cart');
                } else {
                    $array = $this->cartItems($productList);
                    $data['item_details'] = $array['item_details'];
                    $data['variant_products'] = $array['parent_array'];
                }
            }
        }
        return Response::json($data);
    }

    public function foodItems($productList, $reference)
    {
        $data = [];
        foreach ($productList as $cartitem) {
            $extras = $cartitem->cartExtras;
            $extra_array = [];
            $extra_key = '';
            $total = 0;
            $item = Products::where('id', $cartitem->item_id)->first();
            $item_rate = itemSellingPrice($item->id);
            $total += $cartitem->qty * $item_rate;
            foreach ($extras as $extra) {
                $extra_object = Products::where('id', $extra->extra_id)->first();
                $extra_key .= $extra_object->name_en;
                $rate = itemSellingPrice($extra->extra_id);
                $extra_array[] = array('id' => $extra->extra_id, 'extra_name' => $extra_object->name_en, 'qty' => $extra->qty, 'accepted' => 1, 'rate' => $rate);
                $total += $extra->qty * $rate;
            }
            $item_rate = itemSellingPrice($item->id);
            if ($item->is_variant == 1) {
                $parent = Products::where('id', $item->parent_variant_id)->first();
                $key = $parent->name_en . '-' . $item->name_en . '-' . $extra_key;
                $object = array('parent_id' => $parent->id, 'parent_name' => $parent->name_en, 'item_rate' => $item_rate, 'qty' => $cartitem->qty, 'option' => $item->name_en, 'option_id' => $item->id, 'extras' => $extra_array, 'type' => $parent->food_type, 'total' => $total, 'key' => $key, 'note' => $cartitem->note, 'reference' => 'old', 'method_view' => $reference);
            } else {
                $key = $item->name_en . '-' . $item->name_en . '-' . $extra_key;
                $object = array('parent_id' => $item->id, 'parent_name' => $item->name_en, 'item_rate' => $item_rate, 'qty' => $cartitem->qty, 'option' => 'One Size', 'option_id' => $item->id, 'extras' => $extra_array, 'type' => $item->food_type, 'total' => $total, 'key' => $key, 'note' => $cartitem->note, 'reference' => 'old', 'method_view' => $reference);
            }
            $item = $object;
            // return $item;
            $data[] = view('admin.sales_orders.cart', compact('item'))->render();
        }
        return $data;
    }

    public function cartItems($productList)
    {
        $item_details = array();
        $parent_ids = array();
        $parent_array = array();
        if (count($productList) > 0) {
            foreach ($productList as $order_item) {
                $order_item_object = Products::where('id', $order_item['item_id'])->first();
                if (!$order_item_object) {
                    continue;
                }
                $item_rate = itemSellingPrice($order_item_object->id);

                if ($order_item_object && $order_item_object->is_variant == 1) {
                    $parent_product = Products::where('id', $order_item_object->parent_variant_id)->first();
                    $item_rate = itemSellingPrice($parent_product->id);
                    $parent_ids[] = $parent_product->id;
                    $parent_array[$parent_product->name_en][] = (object)array('parent_name' => $parent_product->name_en, 'parent_id' => $parent_product->id, 'item_name' => $order_item_object->name_en, 'item_id' => $order_item_object->id, 'qty' => $order_item['qty'], 'rate' => $item_rate, 'total_amount' => $order_item['qty'] * $item_rate);
                    continue;
                }
                $item_details[] = (object)array('item_name' => $order_item_object->name_en, 'item_id' => $order_item['item_id'], 'qty' => $order_item['qty'], 'rate' => $item_rate, 'total_amount' => $order_item['qty'] * $item_rate);
            }
        }
        $array['item_details'] = $item_details;
        $array['parent_array'] = $parent_array;
        $array['parent_ids'] = $parent_ids;
        return $array;
    }

    public function cart(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->all();
            $promocode = Input::get('promocode');
            $data['date'] = date('Y-m-d H:i:s');
            if (checkProductConfig('foods')) {
                $items = $data['ItemCart'];
                if (count($items) == 0) {
                    return redirect()->back()->withErrors('please select items');
                }
                foreach ($items as $item) {
                    $extras = [];
                    $item = json_decode($item);
                    if (isset($item->extras) && count($item->extras) > 0) {
                        foreach ($item->extras as $extra) {
                            $extras[] = (object)array('item_id' => $extra->id, 'qty' => $extra->qty);
                        }
                    } else {
                        $extras = [];
                    }
                    $cart[] = (object)array('item_id' => $item->option_id, 'qty' => $item->qty, 'extras' => $extras, 'note' => $item->note);
                }
            } else {
                $item_id = array_filter($request->input('items'));
                $qty = $request->input('quantity');
                foreach ($item_id as $key => $item) {
                    $product = Products::where('id', $item)->first();
                    $item_rate = itemSellingPrice($item);
                    $cart[] = (object)array('id' => $item, 'qty' => $qty[$key], 'rate' => $item_rate, 'item_name' => $product->name_en);
                }
            }
            $data['items_array_object'] = $cart;
            $district_id = intval($request->district_id);
            $note = '';
            if ($request->has('note')) {
                $note = $request->note;
            }

            $token = $request->token;
            $lang = 'en';
            $request_headers = array();
            $request_headers[] = 'lang: ' . $lang;
            $request_headers[] = 'token: ' . $token;
            $request_headers[] = 'district_id: ' . $district_id;
            $request_headers[] = 'cms: ' . 1;
            $str = http_build_query($cart);
            $url = url('/api/cart');
            $crl = curl_init();
            curl_setopt($crl, CURLOPT_URL, $url);
            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($crl, CURLOPT_POST, true);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $str);
            curl_setopt($crl, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($crl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($crl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);
            $rest = curl_exec($crl);
            $rest = json_decode($rest);
            if (isset($rest->Status) && $rest->Status == 'Error') {
                return redirect()->back()->withErrors($rest->message);
            } else {
                return redirect('admin/checkout?token=' . $token . '&promocode=' . $promocode . '&time_section_id=' . $request->timesection_id . '&note=' . $note);
            }

        });
    }

    public function checkoutviewData()
    {
        $code = $_GET['promocode'];
        $token = $_GET['token'];
        $note = $_GET['note'];
        $cartsdata = [];
        $food_data = [];
        $cartsparentdata = [];
        $time_section_id = $_GET['time_section_id'];
        if ($token != null) {
            $user = \App\User::where('token', '=', $token)->first();
        }
        $userCart = Cart::where('user_id', $user->id)->first();
        if (isset($userCart)) {
            $productList = $userCart->CartItems;
            if (checkProductConfig('foods')) {
                $food_data = $this->foodItems($productList, 'checkout');
            } else {
                if ($productList) {
                    foreach ($productList as $product) {
                        if (isset($product['id'])) {
                            $productId = $product['item_id'];
                            $productQty = $product['qty'];
                            $productData = \App\Products::where('id', '=', $productId)->first();
                            if ($productData) {
                                if ($productData->is_variant == 1) {
                                    $parent = Products::where('id', $productData->parent_variant_id)->first();
                                    $productPrice = itemSellingPrice($parent->id);
                                    $cartsparentdata[$parent->name_en][] = (object)array('parent_id' => $parent->id, 'id' => $product['item_id'], 'product_name' => $productData->name, 'qty' => $productQty, 'rate' => $productPrice, 'total_amount' => $productPrice * $productQty);
                                    continue;
                                }
                                $productPrice = itemSellingPrice($productData->id);
                                $cartsdata[] = (object)array('id' => $product['item_id'], 'product_name' => $productData->name, 'qty' => $productQty, 'rate' => $productPrice, 'total_amount' => $productPrice * $productQty);

                            }
                        }
                    }
                }
            }
            $grand_total_amount = $this->promocodecontroller->getOrderTotal($user->id);
            $address = Addresses::where('user_id', $user->id)->first();
            $district = District::where('id', $address->district_id)->first();
            $shipping_role = ShippingRule::where('id', $district->shipping_role)->first();
            $shipping = 0;
            $shipping_rate = 0;
            if ($shipping_role) {
                $shipping = $shipping_role->rate;
                $shipping_rate = $shipping_role->rate;
            }
            $district_id = $district->id;
            $lang = 'en';
            $request1_headers = array();
            $request_headers[] = 'lang: ' . $lang;
            $request_headers[] = 'token: ' . $token;
            $request_headers[] = 'district_id: ' . $district_id;
            $total_amount_after_discount = 0;
            $discount_rate = 0;
            $promocode_valid = null;
            $res['code'] = $code;
            if ($res['code'] != "") {
                $result = json_decode($this->promocodevalidate($res, $request_headers));
                if ($result) {
                    $promocode_object = Promocode::where('code', $res['code'])->first();
                    if ($promocode_object && !isset($result->Status)) {
                        $promocode_valid = $res['code'];
                        if ($promocode_object->type == 'persentage') {
                            $total_amount_after_discount = $result->discount_rate;
                            $total_amount_after_discount = $grand_total_amount - $total_amount_after_discount;
                            $total_amount_after_discount = round($total_amount_after_discount, 2);
                            $discount_rate = $result->discount_rate;
                        } elseif ($promocode_object->type == 'amount') {
                            $discount = $result->discount_rate;
                            $total_amount_after_discount = $grand_total_amount - $discount;
                            $discount_rate = $result->discount_rate;
                        }
                        if ($promocode_object->freeShipping == 1) {
                            // $total_amount_after_discount -=$shipping_rate;
                            $shipping = 0;
                        }
                    }
                }
            }
            $grand_total_amount = $grand_total_amount + $shipping_rate;
            $total_amount_after_discount += $shipping;
            $checkoutdata = (object)array('shipping_rate' => $shipping_rate, 'discount_rate' => $discount_rate, 'grand_total_amount' => $grand_total_amount, 'shipping' => $shipping, 'promocode_valid' => $promocode_valid, 'total_amount_after_discount' => $total_amount_after_discount, 'timesection_id' => $time_section_id, 'address_id' => $address->id, 'token' => $token, 'note' => $note);
            // return $cartsparentdata;
            return view('admin.sales_orders.checkout', compact('food_data', 'cartsparentdata', 'checkoutdata', 'cartsdata'));

        } else {
            return redirect('/admin/sales-orders')->withErrors('Cart is empty');
        }

    }

    public function postcheckout(Request $request)
    {

        if ($request->promocode == null) {
            $requestData = $request->only(['address_id', 'time_section_id', 'shipping', 'note']);
        } else {
            $requestData = $request->only(['address_id', 'time_section_id', 'shipping', 'promocode', 'note']);
        }
        $lang = 'en';
        $requestData['payment_method'] = 'Cash';
        $requestData['date'] = date('Y-m-d H:i:s');
        $request_headers = array();
        $request_headers[] = 'lang: ' . $lang;
        $request_headers[] = 'token: ' . $request['token'];
        $url1 = url('/api/checkout');
        $crl1 = curl_init();
        curl_setopt($crl1, CURLOPT_URL, $url1);
        curl_setopt($crl1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($crl1, CURLOPT_POST, true);
        curl_setopt($crl1, CURLOPT_POSTFIELDS, $requestData);
        // curl_setopt($crl, CURLOPT_HEADER, $request_headers);
        curl_setopt($crl1, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($crl1, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($crl1, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($crl1, CURLOPT_SSL_VERIFYPEER, false);
        dd(curl_exec($crl1));
        $rest1 = curl_exec($crl1);
        $rest1 = json_decode($rest1);

        if (isset($rest1->Status) && $rest1->Status == 'Error') {

            return redirect()->back()->withErrors($rest1->message);
        } else {
            return redirect('admin/sales-orders')->with('success', 'Order Created Successfully');
        }
    }

    public function getPromoCodeDetails(Request $request)
    {
        $code = Input::get('promocode');
        $headers = getallheaders();
        $token = $request->header('token');
        $lang = $request->header('lang');
        $request_headers[] = 'lang: ' . $lang;
        $request_headers[] = 'token: ' . $token;
        $promocode = Promocode::where('code', $code)->first();
        $data['code'] = $code;
        return $this->promocodevalidate($data, $request_headers);
    }

    public function promocodevalidate($data, $request_headers)
    {
        $url1 = url('/api/promocode/validate');
        $crl1 = curl_init();
        curl_setopt($crl1, CURLOPT_URL, $url1);
        curl_setopt($crl1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($crl1, CURLOPT_POST, true);
        curl_setopt($crl1, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($crl, CURLOPT_HEADER, $request_headers);
        curl_setopt($crl1, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($crl1, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($crl1, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($crl1, CURLOPT_SSL_VERIFYPEER, false);
        $rest1 = curl_exec($crl1);
        // dd($rest1);
        return $rest1;
    }

    public function removeFromCart()
    {
        $type = Input::get('type');
        $deleted_from = Input::get('delete_from');
        $token = Input::get('token');
        $user = User::where('token', $token)->first();
        $userCart = Cart::where('user_id', $user->id)->first();
        if (isset($type)) {
            $removed_object = Input::get('removed_object');
            $item_key = [];
            if (isset($removed_object['extras'])) {
                foreach ($removed_object['extras'] as $extra) {
                    $item_key[] = $extra['id'];
                }
            }
            sort($item_key);
            $item_key = json_encode(array_unique($item_key));
            if ($deleted_from == 'cart') {
                $cart_item = CartItems::where('cart_id', $userCart->id)
                    ->where('item_id', $removed_object['option_id'])
                    ->where('key', $item_key)
                    ->first();
                if ($cart_item) {
                    $cart_item->cartExtras()->delete();
                    $cart_item->delete();
                    return 'true';
                } else {
                    return 'false';
                }
            } else {
                $order_item_id = Input::get('order_item');
                $order_item = OrderItems::where('id', $order_item_id)->first();
                if ($order_item) {
                    $order = Orders::where('id', $order_item->order_id)->first();
                    if ($order && $order->status == 'Pending' || $order->status == 'Assigned') {
                        $order_item_extras = $order_item->orderExtras()->delete();
                        $order_item->delete();
                        return 'true';
                    }
                } else {
                    return 'false';
                }
            }
        } else {
            $product_cancelled_ids = Input::get('caneceled_product_ids');
            // return $product_cancelled_ids;
            $removed_ids = [];
            $productList = $userCart->CartItems;
            foreach ($productList as $product) {
                if (isset($product['item_id'])) {
                    $productId = $product['item_id'];
                    if (!in_array($productId, $product_cancelled_ids)) {
                        $remained_product_in_cart[] = $product;
                    } else {
                        $removed_ids[] = $product['item_id'];
                    }
                }
            }

            if (isset($remained_product_in_cart)) {
                // $userCart->productList=serialize([]);
                // $userCart->save();
                $userCart->CartItems()->delete();
                $address = Addresses::where('user_id', $user->id)->first();
                $district = District::where('id', $address->district_id)->first();
                $district_id = $district->id;
                $lang = 'en';
                $request_headers = array();
                $request_headers[] = 'lang: ' . $lang;
                $request_headers[] = 'token: ' . $token;
                $request_headers[] = 'district_id: ' . $district_id;
                $str = http_build_query($remained_product_in_cart);
                $url = url('/api/cart');
                $crl = curl_init();
                curl_setopt($crl, CURLOPT_URL, $url);
                curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($crl, CURLOPT_POST, true);
                curl_setopt($crl, CURLOPT_POSTFIELDS, $str);
                curl_setopt($crl, CURLOPT_HTTPHEADER, $request_headers);
                curl_setopt($crl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($crl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);
                $rest = curl_exec($crl);
            } else {
                $userCart->CartItems()->delete();
                $userCart->delete();
            }
            if ($removed_ids) {
                return $removed_ids;
            } else {
                return 'false';
            }
        }

    }

    public function show($id)
    {
    }

    public function edit($id)
    {

        if (!Auth::guard('admin_user')->user()->can('sales_orders'))
        {
            return view('admin.un-authorized');
        }

        $time_sections = TimeSection::where('status', 1)->get();
        $users = User::all();
        $shippingRoles = ShippingRule::get();
        $order = Orders::find($id);
        $promo_code = null;

        if ($order->status == 'Cancelled' || $order->status == 'Delivered') {
            return redirect()->back()->withErrors('this order is ' . $order->status . ' can\'t update it');
        }
        $shipping_rate = 0;
        $grand_total_amount = 0;
        $total_amount_after_discount = 0;
        $order_items = $order->OrderItems;
        $new_order_items = array();
        foreach ($order_items as $order_item) {
            $currentOrderItem = new \StdClass();
            $currentOrderItem->item_id = $order_item->item_id;
            $currentOrderItem->qty = $order_item->qty;
            $currentOrderItem->rate = $order_item->rate;
            $orderItemProduct = Products::find($order_item->item_id);
            if ($orderItemProduct) {
                $currentOrderItem->id = $orderItemProduct->id;
                $currentOrderItem->name = $orderItemProduct->name_en;
                $currentOrderItem->standard_rate = $orderItemProduct->standard_rate;
            }
            $currentOrderItem->ratee = 'tsst';
            $new_order_items[] = $currentOrderItem;

        }
        $order_items = $new_order_items;

        $freeShipping = 0;
        $discount_type = 'false';
        $discount_amount = 0;
        if (checkProductConfig('foods') && checkProductConfig('maintaining_stocks') == false) {
            $products = Products::where('active', 1)->extras()->isVariant()->get();
            $food_items = $this->getOrderFoodsDetails($order_items, 'edit_cart');
            $grand_total_amount = $food_items['grand_total_amount'];
            $food_data = $food_items['food_cart'];
        } else {
            $products = Products::where('active', 1)->get();
            if (count($order_items) > 0) {
                foreach ($order_items as $order_item) {
                    $grand_total_amount += $order_item->rate * $order_item->qty;

                    $item_details[] = (object)array('id' => $order_item->id, 'name' => $order_item->name, 'item_id' => $order_item->item_id, 'qty' => $order_item->qty, 'rate' => $order_item->rate, 'total_amount' => $order_item->qty * $order_item->rate);
                }
            } else {
                $item_details = [];
            }
        }

        $selected_user_address = Addresses::where('id', $order->address_id)->first();

        if ($selected_user_address) {

            $district = District::where('id', $selected_user_address->district_id)->first();
            if ($district) {
                $shipping_role = ShippingRule::where('id', $district->shipping_role)->where('disabled', 0)->first();

                if ($shipping_role) {
                    $shipping_rate = $shipping_role->rate;
                }
                $selected_user_address_id = $selected_user_address->id;
            }

        } else {
            $selected_user_address_id = 0;
        }
        if (!is_null($order->shipping_role_id)) {
            $shppingRole = ShippingRule::find($order->shipping_role_id);
            if ($shppingRole) {
                $shipping_rate = $shppingRole->rate;
            }

        }

        $user = User::where('id', $order->user_id)->first();

        if ($user) {
            $used_promo_code = UsedPromocode::where('user_id', $user->id)->where('order_id', $id)->first();
        }


        if (isset($used_promo_code)) {
            $promo_code = null;
            $promocode = Promocode::where('code', $used_promo_code->code)->first();
            if ($promocode) {
                $promo_code = $promocode->code;
                $discount_amount = $promocode->reward;
                if ($promocode->type == 'amount') {
                    $promo_code_discount = $grand_total_amount * $promocode->reward / 100;
                    $total_amount_after_discount = $grand_total_amount - $promo_code_discount;
                    $total_amount_after_discount = round($total_amount_after_discount, 2);
                    $discount_type = "amount";
                } elseif ($promocode->type == 'persentage') {
                    $total_amount_after_discount = $grand_total_amount - $promocode->reward;
                    $total_amount_after_discount = round($total_amount_after_discount, 2);
                    $discount_type = "rate";
                }
                if ($promocode->freeShipping == 0) {
                    $grand_total_amount += $shipping_rate;
                    $total_amount_after_discount += $shipping_rate;
                } else {
                    $freeShipping = 1;
                }
                if ($total_amount_after_discount < 0) {
                    $total_amount_after_discount = 0;
                }
            }
        } else {
            $grand_total_amount += $shipping_rate;
        }
        $parentProducts = Products::where('active', 1)->where('is_variant', 0)->where('is_deleted', 0)->select('name', 'name_en', 'item_code')->get();

        if (isset($shipping_role)) {
            return view('admin.sales_orders.edit_food', compact('discount_type', 'discount_amount', 'freeShipping', 'promo_code', 'selected_user_address_id', 'users', 'order', 'shipping_rate', 'grand_total_amount', 'total_amount_after_discount', 'time_sections', 'item_details', 'products', 'shippingRoles', 'shipping_role', 'parentProducts'));

        }
        return view('admin.sales_orders.edit_food', compact('discount_type', 'discount_amount', 'freeShipping', 'promo_code', 'selected_user_address_id', 'users', 'order', 'shipping_rate', 'grand_total_amount', 'total_amount_after_discount', 'time_sections', 'item_details', 'products', 'shippingRoles', 'parentProducts'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('sales_orders'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($request, $id) {
            if (is_null($request->items) || empty($request->items)) {
                return redirect()->back()->withErrors(['The Order must have at least one item']);
            }

            $promo_code = Input::get('promocode');
            $data['date'] = date('Y-m-d H:i:s');
            $order = Orders::where('id', $id)->first();
            $order->user_id = $request->user_id;
            $order->shipping_role_id = (int)$request->shipping_role_id;

            $order->timesection_id = $request->timesection_id;

            $orderItems = $order->OrderItems;
            foreach ($orderItems as $OrderItem) {

                $oldOrderAddressId = $order->address_id;
                $warehouse = getAddressWarehouses($oldOrderAddressId);
                if ($warehouse) {
                    $itemWarehouse = ItemWarehouse::where('product_id', $OrderItem->item_id)->where('warehouse_id', $warehouse->id)->orderBy('id', 'desc')->first();
                    if ($itemWarehouse) {
                        $itemWarehouse->projected_qty += $OrderItem->qty;
                        $itemWarehouse->save();
                    }
                }
            }

            $order->address_id = $request->address_id;

            $order->deleteOrderItem($order->id);
            if (checkProductConfig('foods') && checkProductConfig('maintaining_stocks') == false) {
                $items = $request->ItemCart;
                if (count($items) == 0) {
                    return redirect()->back()->withErrors('please select items');
                }

                foreach ($items as $item) {
                    $item = json_decode($item, true);
                    $rate = itemSellingPrice($item['option_id']);

                    $OrderAddressId = $request->address_id;
                    $warehouse = getAddressWarehouses($OrderAddressId);
                    if ($warehouse) {
                        $itemWarehouse = ItemWarehouse::where('product_id', $item['option_id'])->where('warehouse_id', $warehouse->id)->orderBy('id', 'desc')->first();
                        if ($itemWarehouse) {
                            $itemWarehouse->projected_qty -= $OrderItem->qty;
                            $itemWarehouse->save();
                        }
                    }

                    $order_item = $order->OrderItem(intval($item['option_id']), intval($order->id), intval($item['qty']), floatval($rate), $item['option']);
                    if (isset($item['extras']) && count($item['extras']) > 0) {
                        foreach ($item['extras'] as $extra) {
                            if (isset($extra->extra_id)) {
                                $extra_rate = itemSellingPrice($extra->extra_id);
                                $order_item->OrderExtra($order_item->id, $item['option_id'], $extra->extra_id, $extra->qty, $extra_rate);
                            }
                        }
                    }

                }

            } else {
                $item_id = array_filter($request->input('items'));
                $qty = $request->input('quantity');
                $rate = $request->input('rate');

                foreach ($item_id as $key => $item) {
                    $product = Products::where('id', intval($item))->first();
                    if ($product) {
                        $item_name = $product->name_en;
                    } else {
                        $item_name = '';
                    }
                    $order->OrderItem((int)$item, (int)$order->id, (int)$qty[$key], (int)$rate[$key], $item_name);
                    $OrderAddressId = $request->address_id;
                    $warehouse = getAddressWarehouses($OrderAddressId);
                    if ($warehouse) {
                        $itemWarehouse = ItemWarehouse::where('product_id', $item)->where('warehouse_id', $warehouse->id)->orderBy('id', 'desc')->first();
                        if ($itemWarehouse) {
                            $itemWarehouse->projected_qty -= (int)$qty[$key];
                            $itemWarehouse->save();
                        }
                    }
                }
            }
            if ($request->note) {
                $order->note = $request->note;
            }

            if ($request->reciept_id) {
                $order->external_reciept_id = $request->reciept_id;
            }

            $order->save();

            $orderStatus = new OrderStatus;
            $orderStatus->order_id = $order->id;
            $orderStatus->user_id = Auth::guard('admin_user')->user()->id;
            $orderStatus->user_type = "Admin";
            $orderStatus->status = $order->status;
            $orderStatus->save();

            $user = User::where('id', $request->user_id)->first();
            $usedPromocode = UsedPromocode::where('code', $promo_code)->where('order_id', $order->id)->first();
            if (!$usedPromocode) {
                $codevalidation = $this->salesordercontroller->validateCode($request->promocode, $user->id);
                // return $codevalidation;
                if (isset($codevalidation)) {
                    if ($codevalidation == 1) {
                        $promocode = Promocode::where('code', $promo_code)->first();
                        if (!is_null($promocode->userscount)) {
                            $promocode->userscount = $promocode->userscount - 1;
                            $promocode->save();
                        }
                        $usedPromocode = UsedPromocode::create(['code' => $promocode->code, 'discount_rate' => $promocode->reward, 'order_id' => $order->id, 'salesorder_id' => "", 'user_id' => $request->user_id]);
                    }
                }
            }

            return redirect('admin/sales-orders')->with('success', 'Order Updated Successfully');
        });
    }

    public function destroy($id)
    {
    }

    public function store(Request $request)
    {
    }

    public function updateOrderStatus(Request $request)
    {

        if (!Auth::guard('admin_user')->user()->can('sales_orders'))
        {
            return view('admin.un-authorized');
        }

        $orderids = $request->orderids;
        $newStatus = $request->newStatus;

        foreach ($orderids as $orderId) {
            $checkOrderExistance = Orders::find($orderId);
            if ($checkOrderExistance) {
                $checkOrderExistance->status = $newStatus;

                $checkOrderExistance->save();

                $orderStatus = new OrderStatus;
                $orderStatus->order_id = $orderId;
                $orderStatus->user_id = Auth::guard('admin_user')->user()->id;
                $orderStatus->user_type = "Admin";
                $orderStatus->status = $newStatus;
                $orderStatus->save();

                if ($newStatus == 'Cancelled') {
                    $this->restoreStocks($checkOrderExistance);
                }

                // send email to me first
                $to_name = $checkOrderExistance->user->name;
                $to_email = $checkOrderExistance->user->email;
                $data = array('name' => $to_name, 'status' => $orderStatus->status, 'number' => $orderId);

                // get all order items
                $order_items = $checkOrderExistance->allOrderItems;
                if (isset($order_items)) {
                    foreach ($order_items as $item) {
                        if ($request->newOrderStatus == 'ReadyToShip') {
                            updateSalesReport($item->item_id, $item->qty);
                        }

                        $image = getImages($item->item_id);
                        $item->image = $image;
                    }
                }
                Mail::send(['html' => 'mail.mailTemplate'], ['body' => $data, 'items' => $order_items], function ($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                        ->subject('Order Status - Khotwh');
                    $message->from('khotwh.retailk@gmail.com', 'khotwh Fashion');
                });
            }
        }
        return redirect()->back()->withSuccess(['Orders Updates Successfully']);
    }

    public function changeOrderStatus(Request $request)
    {

        if (!Auth::guard('admin_user')->user()->can('sales_orders'))
        {
            return view('admin.un-authorized');
        }

        if (is_null($request->orderId) || is_null($request->newOrderStatus)) {
            return redirect()->back();
        }

        $orderId = $request->orderId;
        $checkOrderExistance = Orders::find($orderId);
        if ($checkOrderExistance) {
            $checkOrderExistance->status = $request->newOrderStatus;

            $checkOrderExistance->save();

            $orderStatus = new OrderStatus;
            $orderStatus->order_id = $orderId;
            $orderStatus->user_id = Auth::guard('admin_user')->user()->id;
            $orderStatus->user_type = "Admin";
            $orderStatus->status = $request->newOrderStatus;
            $orderStatus->save();

            if ($request->newOrderStatus == 'Cancelled') {
                $this->restoreStocks($checkOrderExistance);
            }

            // send email to me first
            $to_name = $checkOrderExistance->user->name;
            $to_email = $checkOrderExistance->user->email;
            $data = array('name' => $to_name, 'status' => $orderStatus->status, 'number' => $orderId);

            // get all order items
            $order_items = $checkOrderExistance->allOrderItems;
            if (isset($order_items)) {
                foreach ($order_items as $item) {
                    $image = getImages($item->item_id);
                    $item->image = $image;

                    if ($request->newOrderStatus == 'ReadyToShip') {
                        updateSalesReport($item->item_id, $item->qty);
                    }
                }
            }
            Mail::send(['html' => 'mail.mailTemplate'], ['body' => $data, 'items' => $order_items], function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                    ->subject('Order Status - Khotwh');
                $message->from('khotwh.retailk@gmail.com', 'khotwh Fashion');
            });
        }
        return redirect()->back();
    }

    public function updateExternalReciept(Request $request)
    {
        if (is_null($request->reciept_id) || is_null($request->orderId)) {
            return redirect()->back();
        }

        $order = Orders::find($request->orderId);
        if ($order) {
            $order->external_reciept_id = $request->reciept_id;
            $order->note = $request->note;
            if (!is_null($request->order_status)) {
                $order->status = $request->order_status;
                if ($request->order_status == 'Cancelled') {
                    $this->restoreStocks($order);
                }
            }

            $order->save();
            if (!is_null($request->order_status)) {
                $orderStatus = new OrderStatus;
                $orderStatus->order_id = $order->id;
                $orderStatus->user_id = Auth::guard('admin_user')->user()->id;
                $orderStatus->user_type = "Admin";
                $orderStatus->status = $request->order_status;
                $orderStatus->save();

                // send email to me first
                $to_name = $order->user->name;
                $to_email = $order->user->email;
                $data = array('name' => $to_name, 'status' => $orderStatus->status, 'number' => $order->id);

                // get all order items
                $order_items = $order->allOrderItems;
                if (isset($order_items)) {
                    foreach ($order_items as $item) {
                        $image = getImages($item->item_id);
                        $item->image = $image;
                    }
                }
                Mail::send(['html' => 'mail.mailTemplate'], ['body' => $data, 'items' => $order_items], function ($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                        ->subject('Order Status - Khotwh');
                    $message->from('khotwh.retailk@gmail.com', 'khotwh Fashion');
                });
            }
            return redirect()->back()->with('message', 'Updated successfully');
        } else {
            return redirect()->back();
        }

    }

    public function storeOldOrdersReports()
    {
        $orderItems = OrderItems::get();
        foreach ($orderItems as $orderItem) {
            $order = Orders::find($orderItem->order_id);
            if ($order) {
                $orderStatus = OrderStatus::where('order_id', $order->id)->orderBy('id', 'desc')->first();
                if ($orderStatus && $orderStatus->status == 'ReadyToShip') {
                    $date = date('Y-m-d', strtotime($orderItem->created_at));
                    $itemId = $orderItem->item_id;
                    $product = Products::find($itemId);
                    if ($product && $order && isset($order)) {

                        $salesReportItem = SalesReport::where('product_id', $itemId)->where('date', $date)->first();
                        if ($salesReportItem) {
                            $salesReportItem->qty = $salesReportItem->qty + $orderItem->qty;
                            $salesReportItem->save();
                        } else {
                            $salesReportItem = new SalesReport;
                            $salesReportItem->product_id = $itemId;
                            $salesReportItem->category_id = $product->item_group;
                            $salesReportItem->qty = $orderItem->qty;
                            $salesReportItem->date = $date;
                            $salesReportItem->save();

                        }

                    }
                }
            }

        }
    }

}
