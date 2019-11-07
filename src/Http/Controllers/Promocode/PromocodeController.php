<?php

namespace App\Http\Controllers\Promocode;

use App\Models\Cart;
use App\Models\Categories;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\SalesOrderController;
use App\Models\ItemPrice;
use App\Models\PriceList;
use App\Models\Products;
use App\Models\Promocode;
use App\Models\UsedPromocode;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Response;
use Yajra\Datatables\Datatables;

class PromocodeController extends Controller
{


    public function itemPrice($product_id)
    {
        $item_rate = 0;
        $item_details = ItemPrice::where('product_id', $product_id)->get();
        if ($item_details) {
            foreach ($item_details as $price) {
                $price_list = PriceList::where('id', $price->price_list_id)->first();
                if ($price_list) {
                    foreach (json_decode($price_list->type) as $type) {
                        if ($type == 0) {
                            $item_rate = $price->rate;
                        }
                    }
                }
            }
        }
        return $item_rate;
    }

    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('promocodes'))
        {
            return view('admin.un-authorized');
        }

        return view('admin/promocode/list');
    }

    public function Promocodeslist(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('promocodes'))
        {
            return view('admin.un-authorized');
        }

        $available = 0;
        $unavailable = 0;
        if (isset($_GET['available'])) {
            $available = $_GET['available'];
        }
        if (isset($_GET['unavailable'])) {
            $unavailable = $_GET['unavailable'];
        }

        if ($available == 1 && $unavailable == 1) {

            $promocodes = DB::table('promocodes')->select('id', 'code', 'userscount', 'active', 'sfrom', 'sto', 'created_at', 'updated_at');

        } elseif ($available == 1 && $unavailable == 0) {
            $promocodes = DB::table('promocodes')->select('id', 'code', 'userscount', 'active', 'sfrom', 'sto', 'created_at', 'updated_at')->where('active', 1);

        } elseif ($available == 0 && $unavailable == 1) {
            $promocodes = DB::table('promocodes')->select('id', 'code', 'userscount', 'active', 'sfrom', 'sto', 'created_at', 'updated_at')->where('active', 0);

        } else {
            $promocodes = DB::table('promocodes')->select('id', 'code', 'userscount', 'active', 'sfrom', 'sto', 'created_at', 'updated_at');

        }

        return Datatables::of($promocodes)->make(true);
    }

    public function createPromoCode()
    {
        if (!Auth::guard('admin_user')->user()->can('promocodes'))
        {
            return view('admin.un-authorized');
        }

        return view('admin/promocode/add');
    }

    public function storePromoCode(\Illuminate\Http\Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('promocodes'))
        {
            return view('admin.un-authorized');
        }
        $this->validate($request, [
            'code' => 'required',
            'min_required_price' => 'required|numeric',
            'max_required_price' => 'required|numeric',
            'usage_per_user' => 'required|min:1',
        ]);

        $code = strtolower($request->input('code'));
        $promocode = Promocode::whereCode($code)->first();
        if ($promocode) {
            return redirect()->back()->withErrors("The code ($code) already exists");
        }
        if (!$request->has('rate') && !$request->has('type')) {
            return redirect()->back()->withErrors('Please Select Discount Type');
        }
        $rate = 0;
        if ($request->input('freeshipping') == 'on') {
            $freeShipping = 1;
        } else {
            $freeShipping = 0;
        }

        $promocode = new Promocode;
        $promocode->code = $code;
        if ($request->input('rate')) {
            $promocode->reward = $request->input('rate');
        } else {
            $promocode->reward = 0;
        }

        if ($request->input('type')) {
            $promocode->type = $request->input('type');
        }

        $promocode->freeShipping = $freeShipping;
        $promocode->userscount = $request->input('nofusers');
        $promocode->sfrom = date('Y-m-d H:i:s', strtotime($request->input('sfrom')));
        $promocode->sto = date('Y-m-d H:i:s', strtotime($request->input('sto')));
        if ($request->input('status') != 'inactive') {
            $promocode->active = true;
        } else {
            $promocode->active = false;
        }

        $promocode->min_required_price = $request->min_required_price;
        $promocode->max_required_price = $request->max_required_price;
        $promocode->usage_per_user = $request->usage_per_user;


        $promocode->created_at = \Carbon\Carbon::now();
        $promocode->updated_at = \Carbon\Carbon::now();
        $promocode->save();

        if (!is_null($request->input('user'))) {
            $user = $request->input('user');
            $promocode->users()->sync([intval($user)]);
        }

        $user = Auth::guard('admin_user')->user();

        $promocode->activities()->attach($user->id, ['key' => "Promocode", 'action' => "Added", 'content_name' => $promocode->code]);

        return redirect('admin/promocodes');
    }

    public function edit($id, Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('promocodes'))
        {
            return view('admin.un-authorized');
        }

        $promocode = Promocode::findOrFail($id);
        $promocodeUsers = $promocode->users;

        return view('/admin/promocode/edit', compact('promocode','promocodeUsers'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('promocodes'))
        {
            return view('admin.un-authorized');
        }

        $this->validate($request, [
            'min_required_price' => 'required|numeric',
            'max_required_price' => 'required|numeric',
            'usage_per_user' => 'required|min:1',
        ]);

        $promocode = Promocode::findorfail($id);
        if ($request->input('freeshipping') == 'on') {
            $freeShipping = 1;
        } else {
            $freeShipping = 0;
        }

        if ($request->input('rate')) {
            $promocode->reward = $request->input('rate');
        } else {
            $promocode->reward = 0;
        }

        if ($request->input('type')) {
            $promocode->type = $request->input('type');
        }

        $promocode->freeShipping = $freeShipping;

        $promocode->userscount = $request->input('nofusers');
        $promocode->min_required_price = $request->min_required_price;
        $promocode->max_required_price = $request->max_required_price;
        $promocode->usage_per_user = $request->usage_per_user;

        $promocode->sfrom = date('Y-m-d:h:i:s', strtotime($request->input('sfrom')));
        $promocode->sto = date('Y-m-d:h:i:s', strtotime($request->input('sto')));
        if ($request->input('status') == 'active') {
            $promocode->active = true;
        } elseif ($request->input('status') == 'inactive') {
            $promocode->active = false;
        } else {
            $promocode->active = false;
        }

        $promocode->save();
        if (!is_null($request->input('user'))) {
            $promocode->users()->sync([$request->input('user')]);
        }

        $user = Auth::guard('admin_user')->user();

        $promocode->activities()->attach($user->id, ['key' => "Promocode", 'action' => "Edited", 'content_name' => $promocode->code]);

        return redirect('admin/promocodes');
    }

    public function getTokenFromReq(\Illuminate\Http\Request $request)
    {
        $token = app('request')->header('token');
        if ($token == null) {
            $token = app('request')->header('Authorization');
        }
        return $token;
    }

    public function getOrderTotal($userId)
    {
        $userCart = Cart::where('user_id', $userId)->first();
        $orderTotal = 0;
        if ($userCart) {
            $productList = $userCart->CartItems;
            if ($productList) {
                foreach ($productList as $product) {
                    if (isset($product['item_id'])) {
                        $productId = $product['item_id'];
                        $productQty = $product['qty'];
                        $productData = \App\Products::where('id', '=', $productId)->first();
                        if ($productData) {
                            if (checkProductConfig('foods')) {
                                $product_extras = $product->cartExtras;
                                foreach ($product_extras as $extra) {
                                    $extra_rate = itemSellingPrice($extra->extra_id);
                                    $orderTotal += $extra_rate * $extra->qty;
                                }
                            } else {
                                if ($productData->is_variant == 1) {
                                    $parent = Products::where('id', $productData->parent_variant_id)->first();
                                    $productPrice = itemSellingPrice($parent->id);
                                    $orderTotal += $productPrice * $productQty;
                                    continue;
                                }
                            }

                            $productPrice = itemSellingPrice($productId);
                            $orderTotal += $productPrice * $productQty;
                        }

                    }

                }
            }

        }
        return $orderTotal;
    }

    public function validateCode(Request $request)
    {
        try {
            $finalReward = 0;
            $shippingReward = 0;
            $lang = getLang();
            $token = getTokenFromReq($request);
            $cart_id = \request('cart_id');
            if ($token != null) {
                $user = \App\User::where('token', '=', $token)->first();
            }
            if (empty($user) && !$cart_id) {
                if ($lang == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => 'Unauthorized , Please login.'], 401);
                } else {
                    return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولاًً'], 401);
                }
            } else {
                if (!$token && $cart_id) {
                    $cart = Cart::find($cart_id);
                    if (!$cart) {
                        return Response::json(['Status' => 'Error', 'message' => 'Invalid Cart'], 401);
                    }

                    $user = User::find($cart->user_id);
                    if (empty($user)) {
                        return Response::json(['Status' => 'Error', 'message' => 'Invalid Cart'], 401);
                    }
                }
                $code = $request->input("code");
                $promocode = Promocode::where('code', $code)->first();

                if (!$promocode || !$promocode->active) {
                    if ($lang == 'en') {
                        return response()->json(['Status' => 'Error', 'message' => 'Invalid Promocode'], 501);
                    } else {
                        return response()->json(['Status' => 'Error', 'message' => 'هذا الرمز غير صحيح'], 501);
                    }
                }

                $usedPromoForUser = UsedPromocode::where('user_id', $user->id)->where('code', $code)->count();
                $totalUsed = UsedPromocode::where('code', $code)->count();

                if ($promocode->userscount <= $totalUsed || $promocode->sfrom > date('Y-m-d H:i:s')
                    || $promocode->sto < date('Y-m-d H:i:s')) {
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

                $cart = $user->cart;
                $productsPrice = $cart ? (new SalesOrderController())->calculatePrice($cart) : 0;
                if ($productsPrice !== 0 && $productsPrice < $promocode->min_required_price ||
                    $productsPrice > $promocode->max_required_price) {
                    if ($lang == 'en') {
                        return response()->json(['Status' => 'Error',
                            'message' => 'Sorry, Your total order must be more than ' .
                                $promocode->min_required_price . ' EGP to apply this promo code.'], 501);
                    } else {
                        return response()->json(['Status' => 'Error', 'message' => 'المنتجات المختارة خارج اسعار الرمز'], 501);
                    }
                }

                if ($promocode->freeShipping == 1) {
                    $promocode->freeShipping = true;
                    if ($promocode->type == 'persentage' || $promocode->type == 'percentage') {
                        $finalReward = $promocode->reward;
                    } else {
                        $finalReward = $promocode->reward;
                    }
                } else {
                    $finalReward = $promocode->reward;
                    $promocode->freeShipping = false;
                }

                return response()->json(["discount_type" => $promocode->type, "discount_rate" => intval($finalReward),
                    "has_free_shipping" => $promocode->freeShipping], 200);
            }


        } catch (\Exception $e) {
            $error = array($e->getMessage(), $e->getFile(), $e->getLine());
            //event(new ErrorEmail($error ));
            return $error;
        }
    }

    public function isPromocodeWithinMinMax($user_id, $promocode)
    {
        $usercart = \App\Cart::where('user_id', '=', $user_id)->first();
        $cart = \App\Cart::where('user_id', '=', $user_id)->first();
        $items = \App\CartItems::where('cart_id', '=', $cart->id)->get();
        $totalprice = 0;

        foreach ($items as $item) {

            $product = \App\Products::find(intval($item['item_id']));
            if (!$product) {
                continue;
            }

            $totalprice += $item->qty * $product->standard_rate;
        }
        $promo = Promocode::where('code', $promocode)->first();
        if ($promo && ($totalprice > $promo->max_required_price
                || $totalprice < $promo->min_required_price)) {
            return false;
        } else {
            return true;
        }
        return false;
    }
}
