<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use App\Models\ItemWarehouse;
use App\Models\Note;
use App\Models\Orders;
use App\Models\OrderStatus;
use App\Models\PriceList;
use App\Models\Promocode;
use App\Models\ShippingRule;
use App\Models\UsedPromocode;
use App\Models\User;
use App\Models\UserOrderPayment;
use App\Models\Warehouses;
use Carbon\Carbon;
use DB;
use Psr\Log\InvalidArgumentException;
use Mail;

class CheckoutController extends Controller
{

    public function index()
    {
        return DB::transaction(function () {
            list($order_items, $totalPrice, $shipping, $warehouse) = $this->validateRequest();

            $token = getTokenFromReq(request());
            $user = User::whereToken($token)->first();
            $promocode = null;
            $shippingrate = $shipping['rate'];
            $deliveryRole = $shipping['shipping_rule'];
            $discount = 0;
            if (request()->promocode) {
                $promocode = Promocode::whereCode(request()->promocode)->fisrt();
                $this->validatePromocode($promocode, $user->id, $totalPrice);
                if ($promocode->freeShipping) {
                    $shippingrate = 0;
                    $deliveryRole = 'Free Shipping';

                    $discount = $promocode->type == 'amount'
                        ? $promocode->reward
                        : (($promocode->reward * ($totalPrice + $shippingrate)) / 100);

                }
            }

            $status = strtolower(request('payment_method')) != 'cash' ? 'inactive' : 'Pending';
            $payment_order_id = strtolower(request('payment_method')) == 'cash' ? null : request('payment_order_id');

            $order = Orders::create([
                'user_id' => $user->id,
                'shipping_rate' => $shippingrate,
                'payment_method' => request('payment_method'),
                'date' => date('Y-m-d H:i:s'),
                'address_id' => request('address_id'),
                'salesorder_id' => "",
                'status' => $status,
                'device_os' => request()->header('deviceOs'),
                'device_id' => request()->header('deviceId'),
                'app_version' => request()->header('applicationVersion'),
                'shipping_role_id' => null,
                'note' => request()->has('note') ? request()->note : null,
                'payment_order_id' => $payment_order_id,
                'total_price' => $totalPrice,
                'shipping_rule' => $deliveryRole,
                'discount' => $discount,
            ]);

            if (request()->has('note') && request()->has('admin')) {

                $noteText = request()->note;
                $note = new Note;
                $note->note = $noteText;
                $note->admin_user_id = request()->admin;
                $note->order_id = $order->id;
                $note->save();

            }

            if (isset(request()->admin) && request()->admin !== null) {
                $userType = 'Admin';
                $admin_id = request()->admin;
            } else {
                $userType = 'Customer';
            }

            $orderStatus = new OrderStatus;
            $orderStatus->order_id = $order->id;
            $orderStatus->user_id = (isset($admin_id)) ? $admin_id : $user->id;
            $orderStatus->user_type = $userType;
            $orderStatus->status = 'Pending';
            $orderStatus->save();

            foreach ($order_items as $list) {
                $order->OrderItem(intval($list['id']), intval($order->id), intval($list['qty']),
                    $list['rate'], $list['item_name']);

                $itemWarehouseName = $warehouse->id;
                $itemInWarehouse = ItemWarehouse::where('product_id', $list['id'])
                    ->where('warehouse_id', $itemWarehouseName)->first();
                if ($itemInWarehouse) {
                    $pqty = $itemInWarehouse->projected_qty;
                    $itemInWarehouse->projected_qty = (int)$pqty - $list['qty'];
                    $itemInWarehouse->save();
                }
            }

            if (request()->promocode) {
                UsedPromocode::create(['code' => $promocode->code,
                    'discount_rate' => $discount, 'order_id' => $order->id, 'salesorder_id' => "",
                    'user_id' => $user->id]);
            }

            if (strtolower(request('payment_method')) == 'cash') {
                $user->cart->items()->delete();

                try {
                    $orderId = $order->id;
                    $to_name = $order->user->name;
                    $to_email = $order->user->email;
                    $data = array('name' => $to_name, 'status' => $orderStatus->status, 'number' => $orderId);

                    foreach ($order_items as $key => $item) {
                        $image = getImages($item['id']);
                        $item['image'] = $image;
                        $item['item_id'] = $item['id'];
                        $order_items[$key] = (object)($item);
                    }

                    Mail::send(['html' => 'mail.mailTemplate'], ['body' => $data, 'items' => $order_items],
                        function ($message) use ($to_name, $to_email) {
                            $message->to($to_email, $to_name)
                                ->subject('Order Status - Khotwh');
                            $message->from('khotwh.retailk@gmail.com', 'khotwh Fashion');
                        });

                } catch (Exception $exception) {

                }

            } else {
                UserOrderPayment::create([
                    'user_id' => $user->id,
                    'order_id' => $payment_order_id,
                    'sales_order_id' => $order->id,
                    'status' => "Pending",
                ]);
            }

            return response()->json(['status' => '200', 'message' => 'Success', 'order_id' => $order->id], 200);

        });
    }


    private function validateRequest()
    {
        $request = request()->all() + [
            'token' => request()->header('token'),
            'authorization' => request()->header('Authorization'),
            'deviceOs' => request()->header('deviceOs'),
            'deviceId' => request()->header('deviceId'),
            'applicationVersion' => request()->header('applicationVersion'),
            ];
        $lang = getLang();

        $validator = \Illuminate\Support\Facades\Validator::make($request, [
            'token' => 'nullable|required_without:authorization|exists:users,token',
            'authorization' => 'nullable|required_without:token|exists:users,token',
            'deviceOs' => 'required|in:WEB,Android,Ios,ANDROID,IOS,ios,android',
            'deviceId' => 'required|max:200',
            'applicationVersion' => 'required|max:15',
            'address_id' => 'required|exists:address,id',
            'payment_method' => 'required|in:CASH,cash,Credit,credit',
            'promocode' => 'nullable|exists:promocodes,code'
        ], [
            'token.required_without' => $lang == 'en' ? 'Unauthorized to add to cart, Please login.' : 'يجب عليك تسجيل الدخول اولا',
            'token.exits' => $lang == 'en' ? 'Unauthorized to add to cart, Please login.' : 'يجب عليك تسجيل الدخول اولا',
            'authorization.required_without' => $lang == 'en' ? 'Unauthorized to add to cart, Please login.' : 'يجب عليك تسجيل الدخول اولا',
            'authorization.exits' => $lang == 'en' ? 'Unauthorized to add to cart, Please login.' : 'يجب عليك تسجيل الدخول اولا',
            'deviceOs.required' => 'Missing Headers',
            'deviceOs.in' => 'Invalid device os header',
            'deviceId.required' => 'Missing Headers',
            'applicationVersion.required' => 'Missing Headers',
            'address_id.required' => 'Missing Data',
            'address_id.exits' => $lang == 'en' ? 'Address not found' : 'خطأ في العنوان',
            'payment_method.required' => $lang == 'en' ? 'Invalid Payment Method' : 'خطأ في طريقة الدفع',
            'payment_method.in' => $lang == 'en' ? 'Invalid Payment Method' : 'خطأ في طريقة الدفع',
            'promocode.exists' => $lang == 'en' ? 'Invalid Promocode' : 'هذا الرمز غير صحيح',
        ]);

        throw_if($validator->fails(), new InvalidArgumentException($validator->errors()->first()));

        $address = Addresses::find(request()->address_id);
        return $this->validateAddress($address);
    }

    private function validateAddress($address)
    {
        $lang = getLang();
        $error_msg = $lang == 'en' ? 'Invalid District' : 'خطأ في المنطة';
        throw_if($address->district == null, new InvalidArgumentException($error_msg));

        $token = getTokenFromReq(request());
        $user = User::whereToken($token)->first();
        return $this->validateCart($user, $address->district);
    }


    private function validateCart($user, $district)
    {
        $lang = getLang();
        $cart = $user->cart;
        $error_msg = $lang == 'en' ? 'The Cart is empty.' : 'سله الشراء فارغه.';
        throw_if(empty($cart) || empty($cart->items)  || $cart->items == null
            || $cart->items()->count() == 0, new InvalidArgumentException($error_msg));

        return $this->validateWarehouse($district, $cart->items);
    }

    private function validateWarehouse($district, $cart_items)
    {
        $lang = getLang();
        $existBoolean = false;
        $error_msg = $lang == 'en' ? 'Your district is not covered yet , Try again Later' : 'لم يتم تغطية منطقتك';
        $warehouses = Warehouses::where('district_id', 'LIKE', "%{$district->id}%")->get();
        foreach ($warehouses as $warehouse) {
            if (in_array($district->id, json_decode($warehouse->district_id))) {
                $existBoolean = true;
                break;
            }
        }

        throw_if(!$existBoolean, new InvalidArgumentException($error_msg));

        return $this->validateShippingRate($district, $cart_items, $warehouse);
    }

    private function validateShippingRate($district, $cart_items, $warehouse)
    {
        $lang = getLang();

        $weight = 0;
        foreach ($cart_items as $item) {
            $uom = $item->product && $item->product->uomRelation ? $item->product->uomRelation : null;
            if (!$uom) {
                $weight += (DefaultWeightValue * $item->qty);
            } else {
                $type = trim($uom->type);
                if ($type == 'kg') {
                    $weight += ($item->product->weight * $item->qty);
                }
                if ($type == 'gram') {
                    $weight += (($item->product->weight * $item->qty) / 1000);
                }
                if ($type == 'ml') {
                    $weight += (($item->product->weight * $item->qty) / 1000000);
                }
            }
        }
        $shipping_rule = $district->shipping
            ? $district->shipping()->wherePivot('from_weight', '<=', $weight)
                ->wherePivot('to_weight', '>=', $weight)->first()
            : ShippingRule::where('id', $district->shipping_role)->first();

        if (!$shipping_rule) {
            $shipping_rule = ShippingRule::where('id', $district->shipping_role)->first();
        }

        $error_msg = $lang == 'en' ? 'Shipping rule not found' : 'لا يوجد وسيله دفع';

        throw_if(!$shipping_rule, new InvalidArgumentException($error_msg));

        $shipping = [
            'shipping_rule' => $shipping_rule->shipping_rule_label,
            'rate' => $shipping_rule->rate,
        ];

        return $this->validateProducts($cart_items, $warehouse, $shipping);
    }

    private function validateProducts($cart_items, $warehouse, $shipping)
    {
        $lang = getLang();
        $error_msg = $lang == 'en' ? 'Invalid products' : 'خطأ في المنتجات';
        $products = [];
        $order_items = [];
        $totalPrice = 0;
        foreach ($cart_items as $item) {
            $product = $item->product;
            if ($product == null || $product->active == 0) {
                continue;
            }
            $qty = $this->validateProductStockByDistrict($product, $warehouse);

            $product_name = $lang == 'en' ? $product->name_en : $product->name;

            $error_msg = $lang == 'en'
                ? "Sorry There is only ($product_name) quantit(ies) left from the product ($qty) in stock please update your cart to proceed"
                : " عذرا المتاح عدد ($qty)  من المنتج ($product_name) يرجى تعديل سلة الشراء لأتمام العملية ";

            throw_if($item->qty > $qty, new InvalidArgumentException($error_msg));

            $product->qty = $item->qty;
            $product->price = $item->price == null ? itemSellingPrice($product->id) : $item->price;
            $products[] = $product;
            $order_items[] = [
                'id' => $product->id,
                'rate' => $product->price,
                'item_name' => $product->name_en,
                'item_code' => $product->item_code,
                'qty' => $product->qty,
                'warehouse_id' => $warehouse->id,
            ];

            if ($item->price != null) {
                $totalPrice += $item->price;
            } else {
                $list = $item->is_autoship ? PriceList::where('price_list_name', 'Autoship')->first()
                    : PriceList::where('price_list_name', 'Standard Selling')->first();

                $productPrice = $item->product ? $item->product->priceList()
                    ->where('price_list_id', $list->id)->latest()->first() : null;

                if ($productPrice != null) {
                    $totalPrice += ($productPrice->rate * $item->qty);

                }
            }
        }

        throw_if($products == [], new InvalidArgumentException($error_msg));
        return array($order_items, $totalPrice, $shipping, $warehouse);
    }


    private function validateProductStockByDistrict($product, $warehouse)
    {
        $lang = getLang();
        $warehouseProduct = ItemWarehouse::where('warehouse_id', $warehouse->id)
            ->where('product_id', $product->id)->first();

        throw_if($warehouseProduct == null, new InvalidArgumentException('Invalid Warehouse'));

        $qty = $warehouseProduct->projected_qty;

        $error_msg = $lang == 'en' ? 'Product is out of stock' : 'يوجد منتج منتهي';
        throw_if($qty == null || $qty == 0, new InvalidArgumentException($error_msg));

        return $qty;
    }


    private function validatePromocode($promocode, $user_id, $product_price)
    {
        $lang = getLang();
        $error_msg = '';
        $usedPromoForUser = UsedPromocode::where('user_id', $user_id)
            ->where('code', $promocode->code)->count();

        $totalUsed = UsedPromocode::where('code', $promocode->code)->count();
        $now_time = Carbon::now()->setTimezone('GMT+2')->format('Y-m-d H:i:s');

        if (!$promocode->active) {
            $error_msg = $lang == 'en' ? 'Invalid Promocode' : 'هذا الرمز غير صحيح';
        } elseif ($promocode->userscount <= $totalUsed
            || $promocode->sfrom > $now_time || $promocode->sto < $now_time) {
            $error_msg = $lang == 'en' ? 'This promocode has been expired' : 'هذا الرمز منتهي';
        } elseif ($usedPromoForUser >= $promocode->usage_per_user) {
            $error_msg = $lang == 'en' ? 'Sorry you\'ve used this promocode before' : 'هذا الرمز تم استخدامه من قبل';
        } elseif ($product_price < $promocode->min_required_price
            || $product_price > $promocode->max_required_price) {
            $error_msg = $lang == 'en'
                ? 'Sorry, Your total order must be more than ' .
                $promocode->min_required_price . ' EGP to apply this promo code.'
                : 'عفوا، يجب ان يكون إجمالي طلبك أكثر من ' .
                $promocode->min_required_price . ' لإستخدام كود الخصم هذا';
        }

        throw_if($error_msg != '', new InvalidArgumentException($error_msg));
    }

}


