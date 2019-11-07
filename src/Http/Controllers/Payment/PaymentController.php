<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\utilitiesController;
use App\Models\Pam;
use App\Models\PaymentMethods;
use App\Models\Payment_Method;
use Illuminate\Http\Request;
use Response;
use DB;

use Yajra\Datatables\Datatables;

class PaymentController extends utilitiesController
{
    public function __construct()
    {
        $path = storage_path('app/cookie.txt');
        if(!defined("COOKIE_FILE"))
        define("COOKIE_FILE", $path);

    }


    public function index()
    {
        return view('admin.payment_methods.index');
    }


    public function paymentList() {
        $payments = PaymentMethods::orderBy('id','ASC')->get();
        return Datatables::of($payments)->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.payment_methods.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request){

        $payment = new PaymentMethods;
        $payment->name = $request->name;
        $payment->key = $request->key;
        $payment->type = $request->payment_type;
        $payment->save();

        return redirect('admin/payment-methods')->with('success','Payment Method Created Successfully');

        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
            $payment = PaymentMethods::findOrFail($id);
            return view('/admin/payment_methods/edit', compact('payment'));
        }

        public function update(Request $request, $id) {
        return DB::transaction(function () use ($request,$id){

            $payment = PaymentMethods::find($id);
            $payment->name = $request->name;
            $payment->key = $request->key;
            $payment->type =  $request->payment_type;
            $payment->save();



        return redirect('admin/payment-methods')->with('success','Payment Method Updated Successfully');
        });

        }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $payment = PaymentMethods::findOrFail($id);
        if(!$payment){
            return 'false';
        }
        $payment->delete();
        return 'true';
        // return redirect('admin/payment-methods')->with('success','Payment Method Deleted Successfully');


    }








    public function getTokenFromReq(\Illuminate\Http\Request $request)
    {
        $token = app('request')->header('token');
        if ($token == null) {
            $token = app('request')->header('Authorization');
        }

        return $token;
    }

    private function getPaymentMethods($isRetry)
    {
        $getPaymentMethodsCh = curl_init(config('goomla.proderpurl') . 'resource/Mode of Payment?fields=["mode_of_payment","name","type"]');
        $this->setGetDataCurlOptions($getPaymentMethodsCh);
        $paymentMethodsResult = curl_exec($getPaymentMethodsCh);
        $curlStatus           = curl_getinfo($getPaymentMethodsCh);
        if (($curlStatus['http_code'] == 401 || $curlStatus['http_code'] == 403) && $isRetry) {
            static::erpnextLogin('getPaymentMethods (PaymentController)');
            $this->getPaymentMethods(false);
        } else {
            if (!isset($PAM)) {
                $PAM = new Pam;
            }
            if ($PAM->checkCurlError($getPaymentMethodsCh, $paymentMethodsResult, 'Payment (Payment Methods Curl )') == 1) {
                return 'error';
            } else {
                curl_close($getPaymentMethodsCh);
                return $paymentMethodsResult;
            }

        }

    }
    public function paymentsMethod2(Request $request)
    {
        $lang  = app('request')->header('lang');
        $token = $this->getTokenFromReq($request);
        if ($lang == 'en')
        {
                    $Payments = array('mode_of_payment'=>'Cash','type'=>'Cash','name'=>'Cash');
        }
        else
        {

            $Payments = array('mode_of_payment'=>'نقدي','type'=>'Cash','name'=>'نقدي');
        }
        return Response::json(array($Payments), 200);
    }
    public function paymentsMethod(Request $request)
    {
        $lang  = app('request')->header('lang');
        $token = $this->getTokenFromReq($request);
        if ($token != null) {
            $user = \App\User::where('token', '=', $token)->first();
            if (empty($user)) {
                if ($lang == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to get payments methods, Please login.'], 401);
                } else {
                    return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولأ.'], 401);
                }
            } else {
                static::erpnextLogin('paymentsMethod (PaymentController)');

                $res = $this->getPaymentMethods(false);
                if ($res == 'error') {
                    return Response::json('Expectation Failed', 417);
                }
                if ($lang == 'en') {\App::setLocale('en');} else {\App::setLocale('ar');}
                $paymentmethods = array();
                $rescounter     = 0;
                $res            = json_decode($res);
                //dd($res['data']);
                foreach ($res->data as $myres) {
                    if (strpos($myres->mode_of_payment, 'Cash') !== false) {} else {continue;}
                    $modeofpayment = $myres->mode_of_payment;
                    $name          = $myres->name;
                    $name          = \Lang::get("catalog.$name", array(), "$lang");
                    $modeofpayment = \Lang::get("catalog.$modeofpayment", array(), "$lang");
                    //dd($modeofpayment);
                    $paymentmethods[$rescounter]['mode_of_payment'] = $modeofpayment;
                    $paymentmethods[$rescounter]['type']            = $myres->type;
                    $paymentmethods[$rescounter]['name']            = $name;
                    $rescounter++;
                }
                //dd($paymentmethods);

                $Payments = $paymentmethods;
                //dd(curl_exec($ch1));
                return Response::json($Payments, 200);

            }

        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);

        }

    }

    public function assingnPayment(Request $request)
    {
        $lang  = app('request')->header('lang');
        $token = $this->getTokenFromReq($request);

        if (app('request')->header('token') != null) {
            $user = \App\User::where('token', '=', $token)->first();
            if (empty($user)) {

                if ($lang == 'en') {
                    return Response::json(['Status' => 'Error', 'message' => 'Unauthorized to assign payment method, Please login.'], 401);
                } else {
                    return Response::json(['Status' => 'Error', 'message' => 'يجب عليك تسجيل الدخول اولاً.'], 401);

                }
            } else {
                $reslt = strtotime($user->token_last_renew) - strtotime(\Carbon\Carbon::now());
                if ($request->input('mode_of_payment') != null && $request->input('name') != null && $request->input('type') != null) {

                    $mode_of_payment = $request->input('mode_of_payment');
                    $name            = $request->input('name');
                    $type            = $request->input('type');

                    $duplicate = Payment_Method::where('type', $type)->first();

                    if ($duplicate) {

                        $payment       = Payment_Method::where('id', $duplicate->id)->first();
                        $payment->type = $request->input('type');
                        //  dd($payment);
                        if ($payment) {
                            return Response::json(['Status' => 'Success', 'message' => 'Success.'], 200);
                        }
                    } else {
                        $payment_method = new Payment_Method;

                        $payment_method->mode_of_payment = $mode_of_payment;
                        $payment_method->name            = $name;
                        $payment_method->type            = $type;
                        $payment_method->user_id         = $user->id;

                        $done = $payment_method->save();
                    }
                    if ($done) {
                        return Response::json(['Status' => 'Success', 'message' => 'Success.'], 200);

                    } else {
                        return Response::json(['Status' => 'Erorr', 'message' => 'Something went wrong. Please try again later.'], 500);
                    }

                } else {
                    return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
                }

            }
        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);
        }

    }
}
