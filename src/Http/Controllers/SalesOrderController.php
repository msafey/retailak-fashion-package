<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use App\Models\Cart;
use App\Models\CartExtras;
use App\Models\CartItems;
use App\Models\District;
use App\Events\ErrorEmail;
use App\Models\FoodExtas;
use App\Models\ItemPrice;
use App\Models\ItemWarehouse;
use App\Models\Note;
use App\Models\Orders;
use App\Models\OrderStatus;
use App\Models\PriceList;
use App\Models\Products;
use App\Models\Promocode;
use App\Models\ShippingRule;
use App\Models\UOM;
use App\Models\UsedPromocode;
use App\Models\UserOrderPayment;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Hash;
use Log;
use Mail;
use Request;
use Response;

class SalesOrderController extends utilitiesController
{

    public function getshipping()
    {
        $addressId = $_GET['address_id'];
        $address = Addresses::find($addressId);

        if (!$address) {
            if (getLang() == 'en') {
                return Response::json(['Status' => 'Erorr', 'message' => "Address not found"], 400);
            }
            return Response::json(['Status' => 'Erorr', 'message' => "العنوان غير موجود"], 400);

        }

        $district_id = $address->district_id;

        $district = District::find($district_id);


        if (!$district) {
            if (getLang() == 'en') {
                return Response::json(['Status' => 'Erorr', 'message' => "District not found"], 400);
            }
            return Response::json(['Status' => 'Erorr', 'message' => "المنطقه غير موجوده"], 400);
        }

        $cart = Cart::with('items.products')
            ->where('user_id', 105)->latest()->first();

        $weight = 0;
        foreach ($cart->items as $item) {
            $uom_id = $item->products->uom;
            $uom = UOM::find($uom_id);

            if (!$uom) {
                $weight = DefaultWeightValue;
            }
            $type = trim($uom->type);

            if ($type == 'kg') {
                $weight += $item->products->weight;
            }
            if ($type == 'gram') {
                $weight += $item->products->weight / 1000;
            }
            if ($type == 'ml') {
                $weight += $item->products->weight / 1000000;
            }
        }
        $shippingRole = $district->shipping_role;


        foreach ($district->shipping as $shipping) {

            if (($shipping->pivot->from_weight <= $weight) && ($weight <= $shipping->pivot->to_weight)) {
                $shippingRole = $shipping->id;
            }
        }


        $shipping_rule = ShippingRule::where('id', $shippingRole)->first();

        if (!$shipping_rule) {
            if (getLang() == 'en') {
                return Response::json(['Status' => 'Erorr', 'message' => "Shipping rule not found"], 400);
            }
            return Response::json(['Status' => 'Erorr', 'message' => "لا يوجد وسيله دفع"], 400);
        }

        $shipping = [
            'shipping_rule' => $shipping_rule->shipping_rule_label,
            'rate' => $shipping_rule->rate,
        ];

        return Response::json($shipping, 200);
    }


    public function getcart(\Illuminate\Http\Request $request)
    {
        try {
            $token = getTokenFromReq($request);
            $lang = getLang();
            $returnedproducts = array();
            if ($token != null) {
                $user = \App\User::where('token', '=', $token)->first();
            }
            if (empty($user)) {
                if ($lang == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get cart, Please login.'], 401);
                } else {
                    return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولاًً'], 401);
                }
            } else {
                $user_id = $user->id;
                $usercart = \App\Cart::where('user_id', '=', $user_id)->first();
                $lang = getLang();
                if (isset($usercart->CartItems) && count($usercart->CartItems) > 0) {
                    $items = $usercart->CartItems;
                    $returnedproducts = getCartItems($token, $items, $lang);
                }
                if (count($returnedproducts) > 0) {
                    return Response::json($returnedproducts, 200);
                } else {
                    return Response::json(array(), 200);
                }
            }
        } catch (\Exception $e) {
            $error = array($e->getMessage(), $e->getFile(), $e->getLine());
            event(new ErrorEmail($error));
            return $error;
        }
    }

    public function addtocart(\Illuminate\Http\Request $request)
    {
        return DB::transaction(function () use ($request) {
            $token = getTokenFromReq($request);
            $lang = getLang();
            if ($token != null) {
                $user = \App\User::where('token', '=', $token)->first();
            } // Checking if Token exists
            if (!isset($user)) // Checking if User exists
            {
                if ($lang == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to add to cart, Please login.'], 401);
                } else {
                    return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولاًً'], 401);
                }
            }
            else {
                if (checkProductConfig('maintaining_stocks') == true) {
                    if (!empty($request->input())) {
                        $requestData = $request->input();

                        $cart = Cart::updateOrcreate(['user_id' => $user->id]);
                        $validate = $this->validateCartRequest($requestData, 'normal');
                        if ($validate != 'true') {
                            return $validate;
                        }

                        foreach ($requestData as $item) {
                            if ($item['qty'] == 0) {
                                $cart_item = CartItems::where('cart_id', $cart->id)->where('item_id', $item['id'])->first();
                                if ($cart_item) {
                                    $cart_item->delete();
                                }
                            } else {
                                $price = null;
                                if (array_key_exists('item_price_id', $item) && isset($item['item_price_id'])
                                    && !empty($item['item_price_id'])) {
                                    $item_price = ItemPrice::where('product_id', $item['id'])
                                        ->where('id',$item['item_price_id'])->first();
                                    $price_rule = $item_price->priceRule;
                                    $price = $item['qty'] * $item_price->rate;
                                    $price -= $price_rule->discount_type == 'price'
                                        ? $price_rule->discount_rate : ($price*$price_rule->discount_rate/100);

                                }

                                $cart_item = CartItems::updateOrcreate([
                                    'cart_id' => $cart->id,
                                    'item_id' => $item['id'],
                                ], ['qty' => $item['qty'], 'price' => $price]);
                            }
                        }

                    }
                    else {
                        return Response::json(['Status' => 'Error', 'message' => 'Bad Request'], 400);
                    }
                    return $this->getcart($request);
                }
                else {
                    return $this->foodAddToCart($request, $user);
                }
            }
        });
    }

    public function checkout(\Illuminate\Http\Request $request, $creditCard = null, $weOrderId = null)
    {
        return DB::transaction(function () use ($request, $creditCard, $weOrderId) {
            $productQtys = [];
            $device_os = $request->header('deviceOs');
            $device_id = $request->header('deviceId');
            $app_version = $request->header('applicationVersion');
            $token = getTokenFromReq($request);
            $lang = getLang();
            $shippingrule = (int)$request->input('shipping');
            $promo_code = $request->input('promocode')
                ? Promocode::where('code', $request->input('promocode'))->first() : null;
            $user = \App\User::where('token', '=', $token)->first();
            if (!$user) {
                if ($lang == 'en') return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to add to cart, Please login.'], 401);
                return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولاًً'], 401);
            }

            if ($user->type == 'GUEST') {
                if (!$request->has('phone')) {
                    if ($lang == 'en') return Response::json(['Status' => 'Error', 'message' => 'Phone Required'], 400);
                    return Response::json(['Status' => 'Error', 'message' => 'رقم التليفون مطلوب'], 400);
                }

                if (!$request->has('name')) {
                    if ($lang == 'en')
                        return Response::json(['Status' => 'Error', 'message' => 'Name Required'], 400);
                    return Response::json(['Status' => 'Error', 'message' => 'الأسم مطلوب'], 400);
                }

                if (!$request->has('email')) {
                    if ($lang == 'en') return Response::json(['Status' => 'Error', 'message' => 'Email Required'], 400);
                    return Response::json(['Status' => 'Error', 'message' => 'البريد الألكترونى مطلوب'], 400);
                }

                $checkMailExistance = \App\User::where('email', $request->email)->first();
                if ($checkMailExistance) {
                    if ($lang == 'en') return Response::json(['Status' => 'Error', 'message' => 'This email already exists '], 400);
                    return Response::json(['Status' => 'Error', 'message' => 'البريد الألكترونى مسجل بالفعل'], 400);
                }

                $user->name = $request->name;
                $phone = $this->validatePhone($request->phone);
                if (!preg_match("/^([\+]2)?((01[0125]\d{8})|((02)?[23]\d{7}))$/", $phone)) {
                    if ($lang == 'en') return Response::json(['Status' => 'Error', 'message' => 'Please Enter Valid Phone Number'], 400);
                    return Response::json(['Status' => 'Error', 'message' => 'من فضلك ادخل رقم تليفون صحيح'], 400);
                }

                $passwrd = str_random(8);
                $password = Hash::make($passwrd);
                $user->email = $request->email;
                $user->phone = $phone;
                $user->password = $password;
                $user->type = 'Customer';
                $user->save();
                $data = ['password' => $passwrd, 'email' => $request->email];
                try {
                    Mail::send('admin.reset-password.senduserandpassword', $data, function ($message) use ($data) {
                        $message->to($data['email'])->from('khotwh.retailk@gmail.com', 'Khotwh Online Store')->subject('Your Khotwh Online Account');
                    });
                } catch (\Exception $exception) {

                }

            }

            $address_id = $request->input('address_id');
            $address = Addresses::find($address_id);
            $user_id = $user->id;
            $usercart = \App\Cart::where('user_id', '=', $user_id)->first();
            $shippingrule = ShippingRule::where('id', $shippingrule)->first();

            if (/*!$request->has('time_section_id') ||*/ !$request->has('address_id') || !$request->has('payment_method')) {
                return Response::json(["Status" => "error", "message" => "Missing Data"], 417);
            }

            if (is_null($address)) {
                return Response::json(['Status' => 'Error', 'message' => 'Your address Id not found , Try again Later'], 400);
            }

            $districtId = $address->district_id;
            $district = District::find($districtId);

            if (!$district) {
                if ($lang == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => 'Invalid District'], 401);
                } else {
                    return Response::json(['Status' => 'Error', 'message' => 'خطأ في المنطقة'], 401);
                }
            }

            if (!$usercart) {
                if ($lang == 'en') return Response::json(['Status' => 'Error', 'message' => 'The Cart is empty.'], 401);
                return Response::json(['Status' => 'Error', 'message' => 'سله الشراء فارغه.'], 401);
            }

            $shipping = calculateShippingRate($district, $usercart->items);

            if (!is_array($shipping)) {
                return $shipping;
            }

            $shippingrate = $promo_code && $promo_code->freeShipping ? 0 : $shipping['rate'];
            $deliveryRole = $promo_code && $promo_code->freeShipping ? 'Free Shipping' : $shipping['shipping_rule'];
            $payment_method = $creditCard == 1 ? 'Credit Card' : 'Cash on delivery';
            $date = date('Y-m-d H:i:s');

            $items = $usercart->CartItems;
            $errors = ($lang == "en") ? "Failed to make the checkout ," : "لا يمكنك اتمام عملية الشراء";
            $productsPrice = $this->calculatePrice($usercart);

            if ($request->input('promocode')) {
                $returnedValue = $this->checkoutPromocode($request->input('promocode'), $user_id, $productsPrice);
                if (!is_bool($returnedValue)) {
                    return $returnedValue;
                }
            }

            if (checkProductConfig('maintaining_stocks') == true) {
                $warehouse = getDistrictWarehouse($districtId);
                if (is_null($warehouse)) {
                    return Response::json(['Status' => 'Error', 'message' => 'Your district is not covered yet , Try again Later'], 400);
                }

                $newitemsarray = [];
                foreach ($items as $item) {
                    if (!isset($item['id'])) continue;

                    $product = \App\Products::find(intval($item['item_id']));
                    if (!$product) continue;

                    $productQty = getStockForProductByDistrict($product, $warehouse);
                    $product->stock_qty = $productQty;
                    if ($product && $product->active == 0) continue;
                    $prodname = $product->name;
                    $prodnamen = $product->name_en;
                    if ($item['qty'] < $product->min_order_qty) {
                        if ($product->stock_qty < 1) {
                            $displayqty = 0;
                        } else {
                            $displayqty = $product->min_order_qty + 1;
                        }

                        if ($lang != 'en')
                            return Response::json(['Status' => 'Error', 'message' => "عذرا لايمكنك شراء أقل من عدد ($displayqty)  من المنتج ($prodname) يرجى تعديل سلة الشراء لأتمام العملية"], 400);
                        return Response::json(['Status' => 'Error', 'message' => "Sorry You can't buy less than ($displayqty) quantit(ies)  from the product ($prodnamen) Please update the cart to proceed"], 400);
                        continue;
                    }

                    if (!is_null($product->max_order_qty)) {
                        if ($item['qty'] > $product->max_order_qty) {
                            if ($productQtys[$product->item_code] < 1) {
                                $displayqty = 0;
                            } else {
                                $displayqty = $product->max_order_qty;
                            }

                            if ($lang != 'en') {
                                //$errors.= " متبقى عدد ($displayqty)  من المنتج ($prodname),";
                                return Response::json(['Status' => 'Error', 'message' => "عذرا لايمكنك شراء أكثر من عدد ($displayqty)  من المنتج ($prodname) يرجى تعديل سلة الشراء لاشراء لأتمام العملية"], 400);
                            } else {
                                return Response::json(['Status' => 'Error', 'message' => "Sorry You can't buy more than ($displayqty) quantit(ies)  from the product ($prodnamen) Please update the cart to proceed"], 400);

                            }
                            // continue;
                        } elseif (isset($product->stock_qty) && $item['qty'] > $product->stock_qty) {
                            if ($product->stock_qty < 1) {
                                $displayqty = 0;


                                $prodname = $product->name;
                                $prodnamen = $product->name_en;
                                if ($lang != 'en') {
                                    //$errors.= " متبقى عدد ($displayqty)  من المنتج ($prodname),";
                                    return Response::json(['Status' => 'Error', 'message' => "عذرا المتاح عدد  ($displayqty)  من المنتج ($prodname) يرجى تعديل سلة الشراء لأتمام العملية"], 400);
                                } else {
                                    return Response::json(['Status' => 'Error', 'message' => "Sorry There is only ($displayqty) quantit(ies) left from the product ($prodnamen) in stock please update your cart to proceed"], 400);

                                }
                                //continue;
                            }
                        }

                    } else {
                        if (isset($product->stock_qty)) {
                            if ($item['qty'] > $product->stock_qty) {
                                if ($product->stock_qty < 1) {
                                    $displayqty = 0;

                                    if ($lang != 'en') {
                                        return Response::json(['Status' => 'Error', 'message' => " عذرا المتاح عدد ($displayqty)  من المنتج ($prodname) يرجى تعديل سلة الشراء لأتمام العملية "], 400);
                                    } else {
                                        return Response::json(['Status' => 'Error', 'message' => "Sorry There is only ($displayqty) quantit(ies) left from the product ($prodnamen) in stock please update your cart to proceed"], 400);
                                    }
                                }
                            }
                        }

                    }
                    $product->qty = $item['qty'];
                    $product->price = $item['price'] == null ? itemSellingPrice($product->id) : $item['price'];
                    $newitemsarray[] = $product;

                }
                if (strlen($errors > 40)) {
                    return Response::json(['Status' => 'Error', 'message' => $errors], 401);
                }

                $finalitemsarray1 = array();
                $finalitemsarray = array();
                $totalprice = 0;
                foreach ($newitemsarray as $newitem) {
                    $finalitemsarray1['id'] = $newitem->id;
                    $finalitemsarray1['rate'] = $newitem->price;
                    $finalitemsarray1['item_name'] = $newitem->name_en;
                    $finalitemsarray1['item_code'] = $newitem->item_code;
                    $finalitemsarray1['qty'] = $newitem->qty;
                    if (isset($warehouse)) {
                        $finalitemsarray1['warehouse_id'] = $warehouse->id;
                    }
                    $totalprice = $totalprice + $newitem->qty * $newitem->standard_rate;
                    $finalitemsarray[] = $finalitemsarray1;
                }

                $productItem = $finalitemsarray;
                //------ ----------
                $cart_items = $usercart->CartItems;
                if (!$creditCard || ($creditCard && $creditCard !== 1)) {
                    foreach ($cart_items as $cart_item) {
                        $cart_item->cartExtras()->delete();
                    }
                    $usercart->CartItems()->delete();
                    $usercart->delete();
                }

                $shippingRuleId = null;
                if ($shippingrule && isset($shippingrule->id))
                    $shippingRuleId = $shippingrule->id;

                $order = \App\Orders::create([
                    'user_id' => $user_id,
                    'shipping_rate' => $shippingrate,
                    'payment_method' => $payment_method,
                    'date' => $date,
                    'address_id' => $address_id,
                    'salesorder_id' => "",
                    'status' => ($creditCard && $creditCard == 1) ? 'inactive' : 'Pending',
                    'device_os' => $device_os,
                    'device_id' => $device_id,
                    'app_version' => $app_version,
                    'shipping_role_id' => $shippingRuleId,
                    'note' => ($request->has('note')) ? $request->note : null,
                    'payment_order_id' => ($creditCard && $creditCard == 1) ? $weOrderId : null,
                    'total_price' => $productsPrice,
                    'shipping_rule' => $deliveryRole
                ]);


                if ($weOrderId !== null) {
                    UserOrderPayment::create([
                        'user_id' => $user_id,
                        'order_id' => $weOrderId,
                        'sales_order_id' => $order->id,
                        'status' => "Pending",
                    ]);
                }

                if ($request->has('note') && $request->has('admin')) {

                    $noteText = $request->note;
                    $note = new Note;
                    $note->note = $noteText;
                    $note->admin_user_id = $request->admin;
                    $note->order_id = $order->id;
                    $note->save();

                }

                if (isset($request->admin) && $request->admin !== null) {
                    $userType = 'Admin';
                    $admin_id = $request->admin;
                } else {
                    $userType = 'Customer';
                }

                $orderStatus = new OrderStatus;
                $orderStatus->order_id = $order->id;
                $orderStatus->user_id = (isset($admin_id)) ? $admin_id : $user_id;
                $orderStatus->user_type = $userType;
                $orderStatus->status = 'Pending';
                $orderStatus->save();

                $checkOrderExistance = \App\Orders::orderBy('id', 'desc')->first();

                // send email to me first
                if ($checkOrderExistance) {
                    $orderId = $checkOrderExistance->id;
                    $to_name = $checkOrderExistance->user->name;
                    $to_email = $checkOrderExistance->user->email;
                    $data = array('name' => $to_name, 'status' => $orderStatus->status, 'number' => $orderId);
                    //return $data;
                    // get all order items
                }


                foreach ($productItem as $list) {
                    $order->OrderItem(intval($list['id']), intval($order->id), intval($list['qty']),
                        $list['rate'], $list['item_name']);
                }

                foreach ($items as $item) {
                    if (!isset($item['item_id'])) {
                        continue;
                    }

                    $product = \App\Products::find((int)$item['item_id']);
                    if (!$product) {
                        continue;
                    }
                    $product->stock_qty = getStockForProductByDistrict($product, $warehouse);
                    if ($product->is_bundle == true) {
                        if (isset($product->stock_qty)) {
                            $productStock = $product->stock_qty;
                        } else {
                            $productStock = $product->stock_qty;
                        }

                        $userQty = $item['qty'];
                        if ($productStock < $userQty) {
                            if ($lang == 'en') {
                                return Response::json(['Status' => 'Error', 'message' => "Order quantity should be equal to or less than $productStock "], 400);

                            } else {
                                return Response::json(['Status' => 'Error', 'message' => "الكمية المطلوبة لابد أن تكون أكثر من أو تساوي $productStock "], 400);
                            }

                        }

                        $product->stock_qty = $productStock - $userQty;
                        $product->save();
                    } else {

                        $itemWarehouseName = $warehouse->id;
                        $itemInWarehouse = ItemWarehouse::where('product_id', $product->id)->where('warehouse_id', $itemWarehouseName)->first();
                        if ($itemInWarehouse) {
                            $pqty = $itemInWarehouse->projected_qty;
                            $itemInWarehouse->projected_qty = (int)$pqty - $item['qty'];
                            $itemInWarehouse->save();
                        }
                    }

                }

                $order_items = $checkOrderExistance->allOrderItems;

                if (isset ($order_items)) {
                    foreach ($order_items as $item) {
                        $image = getImages($item->item_id);
                        $item->image = $image;
                    }
                }
                if (isset($data['name']) && !$creditCard || ($creditCard && $creditCard !== 1)) {
                    try {
                        Mail::send(['html' => 'mail.mailTemplate'], ['body' => $data, 'items' => $order_items],
                            function ($message) use ($to_name, $to_email) {
                            $message->to($to_email, $to_name)
                                ->subject('Order Status - Khotwh');
                            $message->from('khotwh.retailk@gmail.com', 'khotwh Fashion');
                        });
                    } catch (\Exception $exception)
                    {

                    }

                }
            } else {
                $newitemsarray = getCartItems($token, $items, $lang);
                if (strlen($errors > 40)) {
                    return Response::json(['Status' => 'Error', 'message' => $errors], 401);
                }
                if (!$creditCard || ($creditCard && $creditCard != 1)) {
                    $usercart->CartItems()->delete();
                    $usercart->delete();
                }
                $shippingRuleId = null;
                if ($shippingrule)
                    $shippingRuleId = $shippingrule->id;

                $order = \App\Orders::create([
                    'user_id' => $user_id,
                    'shipping_rate' => $shippingrate,
                    'payment_method' => $payment_method,
                    'date' => $date,
                    'address_id' => $address_id,
                    'salesorder_id' => "",
                    'status' => ($creditCard && $creditCard == 1) ? 'inactive' : 'Pending',
                    'device_os' => $device_id,
                    'device_id' => $device_os,
                    'app_version' => $app_version,
                    'shipping_role_id' => $shippingRuleId,
                    'note' => ($request->has('note')) ? $request->note : null,
                    'payment_order_id' => ($creditCard && $creditCard == 1) ? $weOrderId : null,
                    'total_price' => $productsPrice,
                    'shipping_rule' => $deliveryRole
                ]);

                if ($weOrderId !== null) {
                    UserOrderPayment::create([
                        'user_id' => $user_id,
                        'order_id' => $weOrderId,
                        'sales_order_id' => $order->id,
                        'status' => "Pending",
                    ]);
                }

                if ($request->has('note')) {
                    $noteText = $request->note;
                    $note = new Note;
                    $note->note = $noteText;
                    $note->order_id = $order->id;
                    $note->save();

                }
                $orderStatus = new OrderStatus;
                $orderStatus->order_id = $order->id;
                $orderStatus->status = "Pending";
                $orderStatus->save();

                foreach ($newitemsarray as $list) {
                    $item = $order->OrderItem(intval($list['id']), intval($order->id), intval($list['qty']),
                        $list['standard_rate'], $list['name_en']);
                    if (isset($list['extras'])) {
                        foreach ($list['extras'] as $extra) {
                            $item->OrderExtra($item->id, intval($item->item_id),
                                intval($extra['id']), $extra['qty'], $extra['standard_rate']);
                        }
                    }
                }
            }
            if ($promo_code) {
                $discount = $promo_code->type == 'amount'
                    ? $promo_code->reward
                    : (($promo_code->reward * ($data['subTotal'] + $data['shippingRate'])) / 100);
                $data['discount'] = $discount;
                $order->update(['discount' => $discount]);
            }


            if ($promo_code) {
                $promocode = Promocode::where('code', $request->input('promocode'))->first();
                $usedPromocode = UsedPromocode::create(['code' => $request->input('promocode'),
                    'discount_rate' => $discount, 'order_id' => $order->id, 'salesorder_id' => "",
                    'user_id' => $user_id]);
            }

            return Response::json(['status' => '200', 'message' => 'Success', 'order_id' => $order->id], 200);
        });
    }

    public function checkoutPromocode($promocode, $user, $productsPrice)
    {
        $lang = getLang();
        $promocode = Promocode::where('code', $promocode)->first();

        if (!$promocode || !$promocode->active) {
            if ($lang == 'en') {
                return response()->json(['Status' => 'Error', 'message' => 'Invalid Promocode'], 501);
            } else {
                return response()->json(['Status' => 'Error', 'message' => 'هذا الرمز غير صحيح'], 501);
            }
        }

        $usedPromoForUser = UsedPromocode::where('user_id', $user)->where('code', $promocode->code)->count();
        $totalUsed = UsedPromocode::where('code', $promocode->code)->count();

        $now_time = Carbon::now()->setTimezone('GMT+2')->format('Y-m-d H:i:s');

        if ($promocode->userscount <= $totalUsed || $promocode->sfrom > $now_time || $promocode->sto < $now_time) {
            if ($lang == 'en') {
                return response()->json(['Status' => 'Error', 'message' => 'This promocode has been expired'], 501);
            } else {
                return response()->json(['Status' => 'Error', 'message' => 'هذا الرمز منتهي'], 501);
            }
        }

        if ($usedPromoForUser >= $promocode->usage_per_user) {
            if ($lang == 'en') {
                return response()->json(['Status' => 'Error', 'message' => 'Sorry you\'ve used this promocode before'], 501);
            } else {
                return response()->json(['Status' => 'Error', 'message' => 'هذا الرمز تم استخدامه من قبل'], 501);
            }
        }

        if ($productsPrice < $promocode->min_required_price || $productsPrice > $promocode->max_required_price) {
            if ($lang == 'en') {
                return Response::json(['Status' => 'Error', 'message' => 'Sorry, Your total order must be more than ' .
                    $promocode->min_required_price . ' EGP to apply this promo code.'], 501);
            } else {
                return Response::json(['Status' => 'Error', 'message' => 'عفوا، يجب ان يكون إجمالي طلبك أكثر من ' .
                    $promocode->min_required_price . ' لإستخدام كود الخصم هذا '], 501);
            }
        }

        return true;
    }

    public function validateCartRequest($requestData, $type)
    {
        $lang = getLang();
        $array = [];
        $headers = getallheaders();
        foreach ($requestData as $item) {
            if ($type == 'food') {
                $product = Products::where('id', $item['item_id'])->first();
                if (!$item['item_id'] || !$product) {
                    if ($lang == 'en') {
                        return Response::json(['Status' => 'Error', 'message' => 'Item Not Found'], 404);
                    } else {
                        return Response::json(['Status' => 'Error', 'message' => 'المنتج غير موجود'], 404);
                    }
                }
                if ($product->is_food_extra == 1) {
                    if ($lang == 'en') {
                        return Response::json(['Status' => 'Error', 'message' => 'This Item is Topp Found'], 404);
                    } else {
                        return Response::json(['Status' => 'Error', 'message' => 'المنتج غير موجود'], 404);
                    }
                }
                if ($product->has_variants == 1) {
                    if ($lang == 'en') {
                        return Response::json(['Status' => 'Error', 'message' => 'This Item ' . $product->name_en . ' have options, You must select the option of this item'], 400);
                    } else {
                        return Response::json(['Status' => 'Error', 'message' => 'هذا المنتج ' . $product->name . ' لديه خيارات, من فضلك اختر الاختيار الذي تريده'], 400);
                    }
                }

                if (!isset($item['qty'])) {
                    if ($lang == 'en') {
                        return Response::json(['Status' => 'Error', 'message' => 'Please mention needed quantity of product ' . $product->name_en], 412);
                    } else {
                        return Response::json(['Status' => 'Error', 'message' => 'من فضلك ادخل الكمية المطلوبة من المنتج ' . $product->name], 412);
                    }
                }
                $itemsMandatoryExtrasIds = FoodExtas::where('related_product_id', $item['item_id'])->pluck('extra_product_id')->toArray();

                if (isset($item['extras']) && is_array($item['extras'])) {
                    if (count($item['extras']) > 0) {
                        foreach ($item['extras'] as $key => $extra) {
                            // check if extra is not related to the product
                            $extra_item = Products::find($extra['item_id']);
                            if (!$extra_item) {
                                if ($lang == 'en') {
                                    return Response::json(['Status' => 'Error', 'message' => 'Invalid Product Extra'], 400);
                                } else {
                                    return Response::json(['Status' => 'Error', 'message' => 'الاضافة غير صحيحة'], 400);
                                }
                            }
                            if (in_array($extra['item_id'], $itemsMandatoryExtrasIds)) {
                                if (($key = array_search($extra['item_id'], $itemsMandatoryExtrasIds)) !== false) {
                                    unset($itemsMandatoryExtrasIds[$key]);
                                }
                            }
                            $extras_ids[] = $extra['item_id'];
                            if ($extra_item->is_food_extras == 0) {
                                if ($lang == 'en') {
                                    return Response::json(['Status' => 'Error', 'message' => 'The Item (' . $extra['item_id'] . ') Is a topping and can\'t be ordered'], 400);
                                } else {
                                    return Response::json(['Status' => 'Error', 'message' => 'الاضافة غير صحيحة'], 400);
                                }
                            }

                            if (count($itemsMandatoryExtrasIds) > 0) {
                                if ($lang == 'en') {
                                    return Response::json(['Status' => 'Error', 'message' => 'The Mandatory Extras for the item $product->name_en was not sent'], 400);
                                } else {
                                    return Response::json(['Status' => 'Error', 'message' => 'الاضافات للمنتج $product->name غير مكتملة'], 400);
                                }
                            }
                            $check = FoodExtas::where('extra_product_id', $extra['item_id'])->where('related_product_id', $item['item_id'])->first();
                            if (!$check) {
                                if ($lang == 'en') {
                                    return Response::json(['Status' => 'Error', 'message' => 'This Extra ' . $item['item_id'] . ' not related to this product ' . $product->name_en], 412);
                                } else {
                                    return Response::json(['Status' => 'Error', 'message' => 'هذه الاضافة ' . $item['item_id'] . ' ليست متاحة لهذا المنتج ' . $product->name], 412);
                                }
                            }
                        }
                    }
                }
            } elseif ($type == 'normal') {
                // Validating cart request of any other groccery or variation
                $product = Products::where('id', $item['id'])->first();
                if (!$product || !$item['id']) {
                    if ($lang == 'en') {
                        return Response::json(['Status' => 'Error', 'message' => 'Bad Request'], 412);
                    } else {
                        return Response::json(['Status' => 'Error', 'message' => 'المنتج غير موجود'], 412);
                    }
                } else {
                    $pname = $lang == 'en' ? $product->name_en : $product->name;
                    if ($product->has_variants == 1) {
                        if ($lang == 'en') {
                            return Response::json(['Status' => 'Error', 'message' => 'This Product ' . $product->name_en . ' have variants, You must select the variant of this product'], 400);
                        } else {
                            return Response::json(['Status' => 'Error', 'message' => 'هذا المنتج ' . $product->name . ' لديه متغيرات, من فضلك اختر متغير من هذا المنتج'], 400);
                        }
                    }
                    if (!$item['qty']) {
                        if ($item['qty'] != 0) {
                            if ($lang == 'en') {
                                return Response::json(['Status' => 'Error', 'message' => 'Please mention needed quantity of product ' . $product->name_en], 412);
                            } else {
                                return Response::json(['Status' => 'Error', 'message' => 'من فضلك ادخل الكمية المطلوبة من المنتج ' . $product->name], 412);
                            }
                        }
                    } elseif ($item['qty'] < 0) {
                        if ($lang == 'en') {
                            return Response::json(['Status' => 'Error', 'message' => 'The Quantity For Product ($pname) Should be Greater than 0'], 400);
                        } else {
                            return Response::json(['Status' => 'Error', 'message' => 'لابد ان تكون الكمية المطلوبة أكثر من الصفر'], 400);
                        }
                    } else {
                        if (isset($headers['cms'])) {
                            $stockQty = getStocks($product, $headers['district_id']);
                        } else {
                            $stockQty = getApiProductStocks($product);
                        }
                        // return $stockQty;
                        if (isset($stockQty)) {
                            if ($item['qty'] > $stockQty) {
                                if ($lang == 'en') {
                                    if ($stockQty == 0) {
                                        return Response::json(['Status' => 'Error', 'message' => "The requested product ($pname) is out of stock"], 412);
                                    }
                                    return Response::json(['Status' => 'Error', 'message' => "Order quantity for product ($pname) should be less than or equal to $stockQty"], 412);
                                } else {
                                    if ($stockQty == 0) {
                                        return Response::json(['Status' => 'Error', 'message' => "المنتج المطلوب قد نفذت كميته"], 412);
                                    }
                                    return Response::json(['Status' => 'Error', 'message' => "الكمية المطلوبة من المنتج ($pname) لابد أن تكون أقل من أو تساوى $stockQty"], 412);
                                }
                            }
                        }
                    }
                    if (array_key_exists('item_price_id', $item) && isset($item['item_price_id']) && !empty($item['item_price_id'])) {
                        $item_price = $product->itemPrice ? $product->itemPrice()->where('id',$item['item_price_id'])->first() : null;
                        if (!$item_price) {
                            return Response::json(['Status' => 'Error', 'message' => 'Invalid Price List'], 400);
                        }
                        $priceRule = $item_price->priceRule;
                        if (!$item_price->priceRule) {
                            return Response::json(['Status' => 'Error', 'message' => 'Invalid Item Price'], 400);
                        }

                        if ($priceRule->valid_from > date('Y-m-d H:i:s')
                            || $priceRule->valid_to < date('Y-m-d H:i:s')) {
                            if ($lang == 'en') {
                                return Response::json(['Status' => 'Error', 'message' => 'The offer is out of date'], 400);
                            } else {
                                return Response::json(['Status' => 'Error', 'message' => 'العرض غير ساري'], 400);
                            }
                        }

                        if ($priceRule->min_qty > $item['qty']
                            || $priceRule->max_qty < $item['qty']) {
                            if ($lang == 'en') {
                                return Response::json(['Status' => 'Error',
                                    'message' => 'The quantity of the product is out of min and max for the offer'], 400);
                            } else {
                                return Response::json(['Status' => 'Error', 'message' => 'العرض غير علي هذه الكمية'], 400);
                            }
                        }
                    }
                }
            }
        }
        return 'true';
    }

    /* Foods Add To Cart */
    public function foodAddToCart($request, $user)
    {
        $requestData = $request->all();
        if (!empty($requestData)) {
            $cart = Cart::updateOrcreate(['user_id' => $user->id]);
            $result = $this->validateCartRequest($requestData, 'food');
            if ($result != 'true') {
                return $result;
            }
            foreach ($requestData as $item) {
                $item_key = [];
                if (isset($item['extras'])) {
                    foreach ($item['extras'] as $key => $extra) {
                        if (!$extra['item_id'] || is_null($extra['item_id']) || !$extra['qty'] || is_null($extra['qty'])) {
                            unset($item['extras'][$key]);
                            continue;
                        }
                        $item_key[] = $extra['item_id'];
                    }
                } else {
                    $item['extras'] = [];
                }
                sort($item_key);
                $item_key = json_encode(array_unique($item_key));
                if ($item['qty'] == 0) {
                    $cart_item = CartItems::where('cart_id', $cart->id)
                        ->where('item_id', $item['item_id'])
                        ->where('key', $item_key)
                        ->first();
                    if ($cart_item) {
                        $cart_item->cartExtras()->delete();
                        $cart_item->delete();
                    }
                } else {
                    $cart_item = CartItems::updateOrcreate([
                        'cart_id' => $cart->id,
                        'item_id' => $item['item_id'],
                        'key' => $item_key,
                    ], ['qty' => $item['qty']]);
                    if (isset($item['extras'])) {
                        $extras = $item['extras'];
                        $this->addExtras($cart_item->id, $extras, $item['item_id']);
                        // return $extras;
                    } else {
                        CartItems::where('cart_item_id', $cart_item->id)->delete();
                    }
                }
            }
            return $this->getcart($request);
        } else {
            return Response::json(['Status' => 'Error', 'message' => 'Bad Request'], 400);
        }
    }

    public function addExtras($cart_item_id, $extras, $item_id)
    {
        $lang = getLang();
        $extras_ids = [];
        foreach ($extras as $item) {
            $product = Products::whereId($item['item_id'])->isExtra()->first();
            $extras_ids[] = $item['item_id'];
            if ($product) {
                if ($item['qty'] == 0) {
                    CartExtras::where('cart_item_id', $cart_item_id)->where('item_id', $item_id)->where('extra_id', $item['item_id'])->delete();
                } else {
                    CartExtras::updateOrcreate([
                        'cart_item_id' => $cart_item_id,
                        'item_id' => $item_id,
                        'extra_id' => $item['item_id'],
                    ], ['qty' => $item['qty']]);
                }
            }
        }
        CartExtras::where('cart_item_id', $cart_item_id)->where('item_id', $item_id)
            ->whereIn('extra_id', array_diff(CartExtras::where('cart_item_id', $cart_item_id)->pluck('extra_id')->toArray(), $extras_ids))->delete();
    }

    public function validatePhone($phone)
    {
        $phone = str_replace(" ", "", $phone);
        $phone = str_replace("-", "", $phone);
        if (!is_null($phone)) {
            $phone = str_replace('٠', '0', $phone);
            $phone = str_replace('١', '1', $phone);
            $phone = str_replace('٢', '2', $phone);
            $phone = str_replace('٣', '3', $phone);
            $phone = str_replace('٤', '4', $phone);
            $phone = str_replace('٥', '5', $phone);
            $phone = str_replace('٦', '6', $phone);
            $phone = str_replace('٧', '7', $phone);
            $phone = str_replace('٨', '8', $phone);
            $phone = str_replace('٩', '9', $phone);
        }
        return $phone;
    }

    public function checkAddressOrder($id)
    {
        // check for address id
        $check = Addresses::find($id);
        if (!$check) {
            return Response::json(['Status' => 'Error', 'message' => "InCorrect Address ID"], 400);
        }
        // check if address has orders or not
        $has_order = Orders::where('address_id', $check->id)->get();
        if (count($has_order) > 0) {
            return Response::json(true, 200);
        }
        return Response::json(false, 200);
    }

    public function calculatePrice($cart)
    {
        $items = $cart->items;
        $totalPrice = 0;
        foreach ($items as $item) {
            if ($item->price != null ) {
                $totalPrice += $item->price;
                continue;
            }
            $list = $item->is_autoship ? PriceList::where('price_list_name', 'Autoship')->first()
                : PriceList::where('price_list_name', 'Standard Selling')->first();

            $productPrice = $item->product ? $item->product->priceList()
                ->where('price_list_id', $list->id)->latest()->first() : null;

            if ($productPrice === null) continue;

            $totalPrice += ($productPrice->rate * $item->qty);
        }
        return $totalPrice;
    }

}
