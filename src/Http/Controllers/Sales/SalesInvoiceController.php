<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeliveryNote;
use App\Models\Orders;
use App\Models\SalesInvoice;
use App\Models\PriceList;
use App\Models\Products;
use Yajra\Datatables\Datatables;
use App\Models\District;
use App\Models\ShippingRule;
use App\Models\Addresses;
use App\Models\Promocode;
use App\Http\Controllers\utilitiesController;
use App\User;
use App\Models\OrderItems;
use DB;
use App\Models\UsedPromocode;

class SalesInvoiceController extends utilitiesController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = SalesInvoice::all();
        return view('admin.sales_invoices.index',compact('invoices'));
    }

    public function salesInvoicesList(){
        if(isset($_GET['sales_order_id'])){
            $sales_order_id = $_GET['sales_order_id'];
        }else{
            $sales_order_id = 0;
        }
        if($sales_order_id != 0){
            $invoices = SalesInvoice::where('order_id',$sales_order_id)->get();
        }else{
            $invoices = SalesInvoice::orderBy('id','Desc')->get();
        }
        foreach($invoices as $invoice){
            if(isset($invoice->order_id)){
                $sales_order = Orders::where('id',$invoice->order_id)->first();
                if($sales_order){
                    $array = $this->salesInvoiceDetails($sales_order->id);
                    $invoice['grand_total_amount'] = $array['total_on_order'];
                    $invoice['sales_order_id'] = $sales_order->id;
                    $invoice['discount']= isset($array['promo_code_discount'])? $array['promo_code_discount']:0;
                }
            }
        }
        return Datatables::of($invoices)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
