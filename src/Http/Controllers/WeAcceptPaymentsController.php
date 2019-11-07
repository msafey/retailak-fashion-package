<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use App\Models\User;
use App\Models\UserOrderPayment;
use App\Models\Orders;
use App\Models\Cart;
use Mail;
use Illuminate\Support\Facades\DB;

class WeAcceptPaymentsController extends ApiController
{

    public function checkout(\Illuminate\Http\Request $request)
    {
        $token = getTokenFromReq(request());
        $final_total_price = $request->final_total_price;

        $user = \App\User::where('token', '=', $token)->with('cart')->first();


        if (!isset($user->cart->CartItems) || count($user->cart->CartItems) < 0) {
            return $this->respondWithErorr("Error", "Cart Is Empty");
        }

        $user_id = $user->id;

        $profile = $this->Authentication();

        if (isset($profile->message)) {
            return $this->respondWithErorr("Error", $profile->message);
        }
        if (!isset($profile->token)) {
            return $this->respondWithErorr("Error", "Enable to get user profile!");
        }

        $address = Addresses::find($request->address_id);
        if (!$address) return $this->respondValidationErorr(400, 'Invalid address');


        // 2- Create Order

        if ($request->has('name') && $request->name != '') {
            $name = explode(' ', $request->name);
            $first_name = (isset($name[0])) ? $name[0] : 'NA';
            $last_name = (isset($name[1])) ? $name[1] : 'NA';
        } else {
            $name = explode(' ', $user->name);
            $first_name = (isset($name[0])) ? $name[0] : 'NA';
            $last_name = (isset($name[1])) ? $name[1] : 'NA';
        }

        $merchant_id = $profile->profile->id;
        $auth_token = $profile->token;

        $url = 'https://accept.paymobsolutions.com/api/ecommerce/orders?token=' . $auth_token;

        $order_data = [
            "delivery_needed" => "false",
            "merchant_id" => $merchant_id,  // merchant_id obtained from step 1
            "merchant_order_id" => $user_id . rand(1, 5) . str_random(2),  // unique alpha-numerice value, example: E6RR3
            "amount_cents" => $final_total_price * 100,
            "currency" => "EGP",
            "items" => [],
            "shipping_data" => [
                "first_name" => $first_name,
                "phone_number" => ($request->has('phone') && $request->phone != '') ? $request->phone : $user->phone,
                "last_name" => $last_name,
                "email" => ($request->has('email') && $request->email != '') ? $request->email : $user->email,
                "apartment" => 'NA',
                "floor" => 'NA',
                "street" => 'NA',
                "building" => 'NA',
                "postal_code" => 'NA',
                "country" => 'NA',
                "city" => 'NA'
            ]
        ];


        $request_headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($order_data))
        ];

        $order = curlPostRequest($url, $order_data, $request_headers);

        if (!isset($order->id)) {
            return $this->respondWithErorr("Error", "Enable to create order!");
        }

        $order_id = $order->id;


        // 3- Payment Key Generation

        $url = 'https://accept.paymobsolutions.com/api/acceptance/payment_keys?token=' . $auth_token;

        $payment_data = [
            "amount_cents" => $final_total_price * 100,
            "currency" => "EGP",
            "order_id" => $order_id,  // order_id_from_step_2
            "card_integration_id" => env('CARD_INTEGRATION_ID'),  // card integration_id will be provided upon signing up,
            "billing_data" => [

                "first_name" => $first_name,
                "phone_number" => ($request->has('phone') && $request->phone != '') ? $request->phone : $user->phone,
                "last_name" => $last_name,
                "email" => ($request->has('email') && $request->email != '') ? $request->email : $user->email,
                "apartment" => 'NA',
                "floor" => 'NA',
                "street" => 'NA',
                "building" => 'NA',
                "postal_code" => 'NA',
                "country" => 'NA',
                "city" => 'NA',
                "shipping_method" => "NA",
                "state" => "NA"
            ],
        ];

        $request_headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($payment_data))
        ];

        $payment_key = curlPostRequest($url, $payment_data, $request_headers);

        if (!isset($payment_key->token)) {
            return $this->respondWithErorr("Error", "Enable to do payment!");
        }

        $payment_token = $payment_key->token;

        $iframe_id = env('WE_ACCEPT_IFRAME_ID'); //$iframe->id;

        $iframe_url = 'https://accept.paymobsolutions.com/api/acceptance/iframes/' . $iframe_id . '?payment_token=' . $payment_token;
        $salesOrderController = new SalesOrderController;
        array_merge($request->all(), ['payment_method' => 'Credit']);
        array_merge($request->all(), ['payment_order_id' => $order_id]);

        $checkout = $salesOrderController->checkout($request, 1, $order_id);
        if ($checkout->getContent() && json_decode($checkout->getContent()) && isset(json_decode($checkout->getContent())->Status) && json_decode($checkout->getContent())->Status != '200') {
            return $checkout;
        }
        return $this->respond($iframe_url);
    }

    public function Authentication()
    {
        // 1-  Register

        $data = [
            "username" => env("PAYMENT_USERNAME", "mahmoudsaeed"),
            "password" => env("PAYMENT_PASSWORD","Mah3313652")
        ];

        $payload = json_encode($data);
        $request_headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ];

        $url = 'https://accept.paymobsolutions.com/api/auth/tokens';
        $profile = curlPostRequest($url, $data, $request_headers);
        return $profile;
    }

    public function callback(\Illuminate\Http\Request $request)
    {
        $success = '';
        $orderId = 0;
        if ($request->has('success') && $request->has('order')) {
            $success = $request->success;
            $orderId = $request->order;
        }

        DB::table('payment_logs')->insert([
            'text' => json_encode(request()->all()),
            'order_id' => $orderId,
            'success' => $success,
        ]);
        $amount_cents = $request->amount_cents / 100;

        $order = Orders::withoutGlobalScopes()->where('payment_order_id', $orderId)->latest()->first();

        if (!$order) return $this->returnHtmlResponse($amount_cents, false);

        if ($request['success'] === 'true') {
            if ($order->status != 'Added') {
                $to_name = $order->user->name;
                $to_email = $order->user->email;
                $orderId = $order->id;
                $data = array('name' => $to_name, 'status' => 'Pending', 'number' => $orderId);

                $order_items = $order->allOrderItems;

                if (isset ($order_items)) {
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
                $order->status = 'Pending';
                $order->save();
                $userId = $order->user_id;
                $userCart = Cart::where('user_id', $userId)->latest()->first();
                if ($userCart) {
                    $userCart->CartItems()->delete();
                    $userCart->delete();
                }
            }
            return $this->returnHtmlResponse($amount_cents, true);

        }

        restoreStocks($order, 'all');
        $order->OrderItems()->delete();
        $order->delete();
        return $this->returnHtmlResponse($amount_cents, false);
    }

    public function status()
    {
        $token = getTokenFromReq(request());

        $user = \App\User::where('token', '=', $token)->latest()->first();
        if (!$user) return $this->respondAuthError();

        $user_id = $user->id;
        $user_order = UserOrderPayment::where('user_id', $user_id)->first();
        if (!$user_order) return $this->respondNotFound();

        return $this->respond($user_order->status);
    }

    public function returnHtmlResponse($amount_cents, $status)
    {
        $message = ($status) ? 'Thank you for using the online payment service.You have successfully paid EGP ' . $amount_cents . ' to PAM' :
            'Sorry we were unable to complete the checkout process .. Please try again later';
        return '
 <!doctype html>
 <html lang="en">

<head>
  <meta charset="utf-8">
  <title>khotwh</title>
  <link rel="icon" href="http://163.172.78.65:4031/favicon.ico" type="image/x-icon">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"><style>
html, body{background: #fff;color: #000;font-size: 1rem;overflow-x: hidden;}
.logo{margin-top: 150px;margin-bottom: 50px}
.logo img{width: auto}

.soon{font-size: 1.3rem;color: #5C5C5C;line-height: 1.9;}
.btn-success {padding: 10px 45px;color: #fff;background-color: #213c53;border-color: #213c53;}
.btn-success:hover, .btn-success:focus {box-shadow:none; color: #fff;background-color: #213c53;border-color: #213c53;}
.footer{position: absolute;width: 100%;bottom: 0;height: 50px;}
</style>
</head>

<body>
<div class="col-lg-6 mx-auto text-center pt-2 align-self-center">
<div class="logo">
<img src="http://khotwh.com/assets/images/icon/logo.png">
</div>
  <h4 class="soon">
  ' . $message . '
</h4>

<a class="btn btn-success my-4" href="http://khotwh.com">Continue</a>
</div>

<div class="row mx-0 footer">
    <div class="col-sm-12 text-right pr-lg-5">
      <div class="footer-end">
        <p><i class="fa fa-copyright" aria-hidden="true"></i> © Khotwh powered by 
         <a href="http://retailak.com/" target="_black">Retailak</a>.</p>
      </div>
    </div>
    </div>

</body>
</html>
                ';
    }
}

function curlPostRequest($url, $data, $request_headers = [], $hideError = false)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Set HTTP Header for POST request
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);

    $rest = curl_exec($ch);

    $result = json_decode($rest);

    if (!$hideError) {
        return $result;
    }

}
