<?php

namespace App\Http\Controllers\DeliveryOrders;

use App\Models\Addresses;
use App\Models\Delivery_Man;
use App\Models\Delivery_Orders;
use App\Models\District;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SalesOrderController;
use App\Models\ItemWarehouse;
use App\Models\Orders;
use App\Models\Products;
use App\Models\ShippingRule;
use App\Models\User;
use App\Models\Warehouses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;

class DeliveryOrderApiController extends Controller
{
    private $token;

    public function __construct()
    {
        $this->token = new SalesOrderController();
    }

    public function getall(Request $request)
    {
        if (app('request')->header('token') != null) {
            $delivery_man = Delivery_Man::where('token', '=', app('request')->header('token'))->first();
            if (empty($delivery_man)) {
                return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get to Delivered Order, Please login.'], 401);
            } else {
                $delivery_oreders = DB::table('delivery__orders')
                    ->join('delivery__men', 'delivery__orders.delivery_id', '=', 'delivery__men.id')
                    ->join('time_sections', 'delivery__orders.time_section_id', '=', 'time_sections.id')
                    ->where('delivery_id', $delivery_man->id)
                    ->select('delivery__orders.id', 'time_sections.from', 'time_sections.to', 'date')
                    ->orderBy('delivery__orders.id', 'DESC')->get();
                return Response::json($delivery_oreders, 200);
            }
        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
        }
    }

    public function getDeliveryOrder()
    {

        $lang = app('request')->header('lang');
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            if (app('request')->header('token') != null) {
                $delivery_man = Delivery_Man::where('token', '=', app('request')->header('token'))->first();
                if (empty($delivery_man)) {
                    return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get to Delivered Order, Please login.'], 401);
                } else {
                    $delivery_oreders = DB::table('delivery__orders')
                        ->join('delivery__men', 'delivery__orders.delivery_id', '=', 'delivery__men.id')
                        ->where('delivery_id', $delivery_man->id)->where('delivery__orders.id', $id)->first();

                    if ($delivery_oreders) {
                        $orders_ids = unserialize($delivery_oreders->orders_id);

                        $array_order = array();
                        $i = 0;

                        foreach ($orders_ids as $orders_id) {
                            $x = 0;
                            $orders = Orders::findOrFail($orders_id);

                            $array_order[$i]["order_id"] = $orders->id;
                            $array_order[$i]["user_id"] = $orders->user_id;
                            $array_order[$i]["payment_method"] = $orders->payment_method;
                            $array_order[$i]["date"] = $orders->date;
                            $array_order[$i]["created_at"] = $orders->created_at;
                            $salesOrderId = str_replace('SO-', '', $orders->salesorder_id);
                            $array_order[$i]["salesorder_id"] = $salesOrderId;
                            $array_order[$i]["shipping_fee"] = 5;
                            //TODO::
                            $array_order[$i]["status"] = $orders->status;
                            $array_order[$i]["address_id"] = $orders->address_id;
                            $array_order[$i]["date"] = $orders->date;
                            $array_order[$i]["created_at"] = $orders->created_at;
                            $array_order[$i]["receipt_number"] = $orders->salesorder_id;

                            $final_total_price = 0;

                            $user_data = array();
                            $user_data = DB::table('users')->where('id', $orders->user_id)->first();

                            $addresse = array();
                            $addresse = DB::table('address')->where('id', $orders->address_id)->first();

                            $product_order = array();
                            foreach (json_decode($orders->productlist) as $product) {

                                if ($lang == 'en') {
                                    $productArray = DB::table('products')->where('item_code', $product->item_code)->select('name_en as name', 'description_en', 'image', 'item_group', 'min_order_qty', 'item_code', 'max_discount', 'standard_rate', 'created_at', 'updated_at', 'uom')->first();
                                    if (!$productArray) {
                                        $productArray = DB::table('oldproducts')->where('item_code', $product->item_code)->select('name_en as name', 'description_en', 'image', 'item_group', 'min_order_qty', 'item_code', 'max_discount', 'standard_rate', 'created_at', 'updated_at', 'uom')->first();
                                    }
                                } else {
                                    $productArray = DB::table('products')->where('item_code', $product->item_code)->select('name', 'description', 'image', 'item_group', 'min_order_qty', 'item_code', 'max_discount', 'standard_rate', 'created_at', 'updated_at', 'uom')->first();
                                    if (!$productArray) {
                                        $productArray = DB::table('oldproducts')->where('item_code', $product->item_code)->select('name', 'description', 'image', 'item_group', 'min_order_qty', 'item_code', 'max_discount', 'standard_rate', 'created_at', 'updated_at', 'uom')->first();
                                    }
                                }
                                $total_price = $productArray->standard_rate * $product->qty;
                                $product_order[$x] = array_merge((array) $product, (array) $productArray);
                                $product_order[$x]['total_price'] = $total_price;
                                $final_total_price += $total_price;
                                $x++;
                            }

                            $array_order[$i]['cart'] = $product_order;
                            $array_order[$i]['user'] = $user_data;
                            $array_order[$i]['address'] = $addresse;
                            $array_order[$i]['final_total_price'] = $final_total_price;

                            $i++;
                        }

                        return Response::json($array_order, 200);
                    } else {
                        return Response::json(['Status' => 'Erorr', 'message' => 'Thos Delivery Order Not Found In Database'], 412);
                    }
                }
            } else {
                return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
            }
        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
        }
    }

    public function getUserOrders(Request $request) // User History
    {

        $token = getTokenFromReq($request);

        $lang = app('request')->header('lang');
        if ($token != null) {
            $user = User::where('token', '=', $token)->first();
            if (empty($user)) {
                if ($lang == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get to Delivered Order, Please login.'], 401);
                } else {
                    return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولأ.'], 401);
                }
            } else {
                $user_oreders = Orders::where('user_id', $user->id)
                    ->where('status', '!=', 'inactive')->orderBy('id', 'DESC')->get();
                $array_order = array();
                $i = 0;
                foreach ($user_oreders as $order) {
                    $x = 0;
                    $array_order[$i]["order_id"] = $order->id;
                    // $array_order [$i]["user_id"] = $order->user_id;
                    $array_order[$i]["payment_method"] = $order->payment_method;
                    $array_order[$i]["date"] = $order->date;
                    $array_order[$i]["status"] = $order->status;

                    $array_order[$i]["address_id"] = $order->address_id;
                    $array_order[$i]["date"] = $order->date;
                    $final_total_price = 0;

                    $user_data = DB::table('users')->where('id', $order->user_id)->first();
                    $addresse = DB::table('address')->where('id', $order->address_id)->first();
                    $product_order = array();
                    $order_items = $order->OrderItems;
                    // dd($order_items);
                    $orderItemsCount = count($order_items);
                    foreach ($order_items as $product) {
                        if ($lang == 'en') {
                            $productArray = DB::table('products')->where('id', $product->item_id)
                                ->select('name_en as name', 'description_en', 'image', 'id', 'created_at', 'updated_at', 'uom')->first();
                        } else {
                            $productArray = DB::table('products')->where('id', $product->item_id)
                                ->select('name', 'description', 'image', 'item_group', 'id', 'created_at', 'updated_at', 'uom')->first();
                        }

                        $productInstance = Products::find($productArray->id);

                        $product_order[$x] = array_merge((array) $product, (array) $productArray);
                        $product_order[$x]['total_price'] =$productInstance->priceRuleRelation == null ?  $product->rate* $product->qty : $product->rate;
                        // $product_order[$x]['single_price'] = ($product->rate/$product->qty);
                        $product_order[$x]['single_price'] = $productInstance->priceRuleRelation == null ? $product->rate : ($product->rate / $product->qty);
                        $x++;
                    }

                    $array_order[$i]['total_items'] = $orderItemsCount;
                    $array_order[$i]['address'] = $addresse;
                    $shipping_rate = (int) $order->shipping_rate;
                    $array_order[$i]['shipping_rate'] = $shipping_rate;
                    $final_total_price = $order->total_price + $shipping_rate;
                    $final_total_price = $final_total_price - $order->discount;
                    $array_order[$i]['total_price'] = $order->total_price;
                    $array_order[$i]['discount'] = $order->discount;
                    $array_order[$i]['final_total_price'] = $final_total_price;

                    $i++;
                    // dd($final_total_price);
                }

                return Response::json($array_order, 200);
            }
        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
        }
    }

    public function changeStatusOrder()
    {
        if (isset($_GET['order_id']) && isset($_GET['delivery_id']) && isset($_GET['status'])) {
            $order_id = $_GET['order_id'];
            $delivery_id = $_GET['delivery_id'];
            $status = $_GET['status'];

            if (app('request')->header('token')) {

                $deliverey_man = Delivery_Man::where('token', '=', app('request')->header('token'))->first();
                if (empty($deliverey_man)) {
                    return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get to Delivered Order, Please login.'], 401);
                } else {
                    $orders_delivered = Delivery_Orders::where('id', $delivery_id)
                        ->where('delivery_id', $deliverey_man->id)->first();

                    $orders_array = unserialize($orders_delivered->orders_id);

                    if (in_array($order_id, $orders_array)) {
                        if ($status == "Delivered") {
                            $delivered = Orders::where('id', $order_id)->update(['status' => 'DeliveredNotCompleted']);
                            if ($delivered) {
                                return Response::json(['Status' => 'Erorr', 'message' => 'Delivery Deliverd Successfully'], 200);
                            }
                        } else if ($status == "Cancelled") {

                            $added = Orders::where('id', $order_id)->update(['status' => 'CancelledNotCompleted']);
                            if ($added) {
                                return Response::json(['Status' => 'Success', 'message' => 'Delivery Cancelled Successfully'], 200);
                            }
                        } else if ($status == "Assigned") {

                            $Assigned = Orders::where('id', $order_id)->update(['status' => 'Assigned']);
                            if ($Assigned) {
                                return Response::json(['Status' => 'Success', 'message' => 'Delivery Assigned Successfully'], 200);
                            }
                        } else {
                            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
                        }
                    } else {
                        return Response::json(['Status' => 'Erorr', 'message' => 'xxxxxx Request'], 400);
                    }
                }
            } else {
                return Response::json(['Status' => 'Erorr', 'message' => 'Badddd Request'], 400);
            }
        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Baddddddddddd Request'], 400);
        }
    }

    public function getUserOrdersDetails(Request $request, $id)
    {

        $token = getTokenFromReq($request);
        $lang = app('request')->header('lang');
        if (!$token) {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
        }

        $user = User::where('token', '=', $token)->first();
        if (!$user) {
            if ($lang == 'en') return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get to Delivered Order, Please login.'], 401);
            return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولأ.'], 401);
        }

        $user_oreders = Orders::where('id', $id)->where('user_id', $user->id)->get();
        if (count($user_oreders) == 0) return Response::json(['Status' => 'Error', 'message' => 'Invalid Order'], 400);
        $array_order = array();
        $i = 0;
        foreach ($user_oreders as $order) {
            $x = 0;
            $array_order["order_id"] = $order->id;
            $array_order["user_id"] = $order->user_id;
            $array_order["payment_method"] = $order->payment_method;
            $array_order["date"] = $order->date;
            $array_order["status"] = $order->status;
            $array_order["address_id"] = $order->address_id;
            $array_order["date"] = $order->date;

            $user_data = DB::table('users')->where('id', $order->user_id)->select('id', 'email', 'name', 'phone')->first();
            $addresse = DB::table('address')->where('id', $order->address_id)->first();

            $product_order = array();
            $order_items = $order->OrderItems;

            foreach ($order_items as $product) {

                if ($lang == 'en') {
                    $productArray = DB::table('products')->where('products.id', $product->item_id)
                        ->leftJoin('uoms', 'products.uom', 'uoms.id')
                        ->select('products.id', 'products.name_en as name', 'products.description_en as description', 'products.item_group', 'products.id', 'products.standard_rate', 'products.created_at', 'products.updated_at', 'uoms.type as uom')->first();
                } else {
                    $productArray = DB::table('products')->where('products.id', $product->item_id)
                        ->leftJoin('uoms', 'products.uom', 'uoms.id')
                        ->select('products.id', 'products.name', 'products.description', 'products.item_group', 'products.id', 'products.standard_rate', 'products.created_at', 'products.updated_at', 'uoms.type as uom')->first();
                }

                $productInstance = Products::find($productArray->id);

                if ($productArray) {
                    $productArray->standard_rate = ($product->rate / $product->qty);
                    $productArray->name = trim($productArray->name);
                    $productArray = handleMultiImages($productArray);
                    $product_order[$x] = array_merge($product->toArray(), (array) $productArray);
                    // $product_order[$x]['total_price'] = $product->rate;
                    $product_order[$x]['total_price'] =$productInstance->priceRuleRelation == null ?  $product->rate* $product->qty : $product->rate;
                    // $product_order[$x]['single_price'] = ($product->rate/$product->qty);
                    $product_order[$x]['single_price'] = $productInstance->priceRuleRelation == null ? $product->rate : ($product->rate / $product->qty);

                    $x++;
                }
            }
            $array_order['cart'] = $product_order;
            $array_order['user'] = $user_data;
            $array_order['address'] = $addresse;
            $shipping_rate = (int) $order->shipping_rate;
            $array_order['shipping_rate'] = $shipping_rate;
            $final_total_price = $order->total_price + $shipping_rate;
            $final_total_price = $final_total_price - $order->discount;
            $array_order['total_price'] = $order->total_price;
            $array_order['discount'] = $order->discount;
            $array_order['final_total_price'] = $final_total_price;

            $i++;
        }

        return Response::json($array_order, 200);
    }

    public function reOrderValidate(Request $request, $id)
    {

        $token = $this->token->getTokenFromReq($request);

        $lang = app('request')->header('lang');
        if ($token != null) {
            $user = User::where('token', '=', $token)->first();
            if (empty($user)) {
                if ($lang == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get to Delivered Order, Please login.'], 401);
                } else {
                    return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولأ.'], 401);
                }
            } else {
                $user_oreders = Orders::where('id', $id)->get();
                $address = Addresses::where('user_id', $user->id)->first();
                $branch = $this->getDistrictBranch($token, $address->district_id);
                // dd($branch);
                $array_order = array();
                $i = 0;
                foreach ($user_oreders as $order) {
                    $x = 0;
                    $array_order["order_id"] = $order->id;
                    $array_order["user_id"] = $order->user_id;
                    $array_order["payment_method"] = $order->payment_method;
                    $array_orde["date"] = $order->date;
                    // $array_order [$i]["salesorder_id"] = $order->salesorder_id;
                    $array_order["status"] = $order->status;
                    // $array_order [$i]["erpnext_status"] = $order->erpnext_status;
                    $array_order["address_id"] = $order->address_id;
                    $array_order["date"] = $order->date;
                    $final_total_price = 0;
                    $user_data = array();
                    $user_data = DB::table('users')->where('id', $order->user_id)->select('name', 'phone', 'id')->first();

                    $addresse = array();
                    $addresse = DB::table('address')->where('id', $order->address_id)->first();
                    $district = District::where('id', $addresse->district_id)->first();
                    $shipping_role = ShippingRule::where('id', $district->shipping_role)->first();


                    $shipping_rate = $shipping_role ? $shipping_role->rate : 0;

                    $product_order = array();
                    $order_items = $order->OrderItems;
                    foreach ($order_items as $product) {
                        if (!is_null($branch)) {
                            // return $branch;
                            $warehouse = $branch->id;
                            $warehouseProducts = ItemWarehouse::where('warehouse_id', $warehouse)->where('product_id', $product->item_id)->first();
                            // dd($warehouseProducts->projected_qty);
                            if ($warehouseProducts->projected_qty < $product->qty) {
                                continue;
                            }
                        }

                        if ($lang == 'en') {
                            $productArray = DB::table('products')->where('products.id', $product->item_id)
                                ->join('uoms', 'products.uom', 'uoms.id')
                                ->select('products.name_en as name', 'products.description_en as description', 'products.item_group', 'products.id', 'products.standard_rate', 'products.created_at', 'products.updated_at', 'uoms.type as uom', 'products.active')->first();
                        } else {
                            $productArray = DB::table('products')->where('products.id', $product->item_id)
                                ->join('uoms', 'products.uom', 'uoms.id')
                                ->select('products.name', 'products.description', 'products.item_group', 'products.id', 'products.standard_rate', 'products.created_at', 'products.updated_at', 'uoms.type as uom', 'products.active')->first();
                        }
                        if ($productArray->active == 0) {
                            continue;
                        }
                        $productArray->standard_rate = $product->rate;
                        $total_price = $product->qty * $productArray->standard_rate;
                        // dd($product->rate);
                        $productArray->name = trim($productArray->name);
                        // unset($product->item_id);
                        $product = handleMultiImages($product);
                        // unset()
                        $product_order[$x] = array_merge($product->toArray(), (array) $productArray);
                        $product_order[$x]['total_price'] = $total_price;
                        $final_total_price += $total_price;
                        $x++;
                    }
                    // $array_order [$i]["cart"] = $product_order;
                    $array_order['cart'] = $product_order;
                    $array_order['user'] = $user_data;
                    $array_order['address'] = $addresse;
                    $array_order['shipping_rate'] = $shipping_rate;
                    $array_order['final_total_price'] = $final_total_price;
                    $i++;
                }

                return Response::json($array_order, 200);
            }
        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
        }
    }

    public function getDistrictBranch($token, $districtId = null)
    {
        if (is_null($districtId) || $districtId < 1) {
            $user = \App\User::where('token', '=', $token)->first();
            if ($user) {
                $address = Addresses::where('user_id', $user->id)->first();
                if ($address) {
                    $districtId = $address->regoin;
                }
            }
        } else {
            $district = District::find($districtId);
            // dd($district)
            // return $district;
            if ($district) {;
            } else {
                $districtId = 1;
            }
        }
        $branch = null;
        $dbBranches = Warehouses::get();
        // return $dbBranches;
        foreach ($dbBranches as $dbBranch) {
            if (in_array($districtId, json_decode($dbBranch->district_id))) {
                $branch = $dbBranch;
            }
        }
        if (is_null($branch)) {
            $districtId = 1;
            foreach ($dbBranches as $dbBranch) {
                if (in_array($districtId, json_decode($dbBranch->district_id))) {
                    $branch = $dbBranch;
                }
            }
        }
        return $branch;
    }
}
