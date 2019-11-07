<?php

namespace App\Http\Controllers\Purchase;

use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Models\ItemPrice;
use App\Models\ItemWarehouse;
use App\Models\Payment;
use App\Models\PriceList;
use App\Models\Products;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseOrders;
use App\Models\PurchaseOrdersItems;
use App\Models\PurchaseReceipts;
use App\Models\ShippingRule;
use App\Models\Stocks;
use App\Models\Taxs;
use App\Models\Warehouses;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PDF;
use Response;
use Yajra\Datatables\Datatables;

class PurchaseReceiptController extends Controller
{

     public function manageReceipts($purchase_order_id){

       return view('admin.purchase_receipts.index',compact('purchase_order_id'));
    }

    public function purchaseReceiptsList($purchase_order_id)
    {
        $data = PurchaseReceipts::where('purchase_order_id',$purchase_order_id)->get();
        return DataTables::of($data)->make(true);
    }

    public function pendingPurchaseReceiptsList()
    {
        $data = PurchaseReceipts::where('status',0)->get();
        return DataTables::of($data)->make(true);
    }




    public function index()
    {
        return view('admin.purchase_receipts.index');
    }

    public function purchaseReceiptDetails($id){
            $purchase_receipt = PurchaseReceipts::find($id);

            $item_details = $parent_array = [];
            if($purchase_receipt){

                $accepted_qty = unserialize($purchase_receipt->accepted_quantity);
                $purchase_order = PurchaseOrders::where('id',$purchase_receipt->purchase_order_id)->first();
                if($purchase_order){
                    $purchase_order_id = $purchase_order->id;
                    $company_selected = Company::where('id',$purchase_order->company_id)->where('status',1)->first();
                    $selected_tax = Taxs::where('id',$purchase_order->tax_and_charges)->first();
                    $selected_shipping_rule = ShippingRule::where('id',$purchase_order->shipping_rule)->first();
                    $required_by_date = $purchase_order->required_by_date;
                    $order_items = $purchase_order->purchaseOrderItems;
                    $parent_array = array();
                    foreach($order_items as $item){
                        $product = Products::where('id',$item->item_id)->where('active',1)->first();
                        if($product && $product->is_variant == 1){
                            $parent_product = Products::where('id',$product->parent_variant_id)->first();
                            $parent_array[$parent_product->name_en][]=(object)array('product_name'=>$product->name_en,'item_id'=>$item->item_id,'qty'=>$accepted_qty[$item->item_id]);
                            continue;
                        }
                        if($product){
                            $item_details[]=(object)array('product_name'=>$product->name,'item_id'=>$item->item_id,'qty'=>$accepted_qty[$item->item_id]);
                        }
                    }
                }
            }
            return view('admin.purchase_receipts.details',compact('parent_array','purchase_receipt','purchase_order_id','company_selected','selected_tax','selected_shipping_rule','required_by_date','purchase_order','order_items','item_details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $shipping_rules = ShippingRule::where('disabled',0)->get();
        $shipping_rule_rate=0;
        $total_discount = 0;
        $discount_per_unit=0;
        $count =0;
        $item_details = array();
        $taxs = Taxs::where('status',1)->get();
        $companies = Company::all();
        $purchase_order_id = Input::get('purchase_order_id');
        $order = PurchaseOrders::find($purchase_order_id);
        $grand_total_amount = 0;
        $order_items = $order->purchaseOrderItems;
        $parent_ids = array();
        $parent_array = array();
        $purchase_receipt_exist = PurchaseReceipts::where('purchase_order_id',$purchase_order_id)->get();
        $qty_of_all_items = 0;
        $total_order=0;
        foreach($order_items as $order_item){
            $total_order += $order_item->qty*$order_item->item_rate;
            $qty_of_all_items += $order_item->qty;
            $sum_of_accepted_quantity=0;
            $remained_qty=$order_item->qty;
            if($purchase_receipt_exist){
                foreach($purchase_receipt_exist as $exist){
                    if(isset($exist->accepted_quantity) && $exist->accepted_quantity !=""){
                        foreach(unserialize($exist->accepted_quantity) as $k =>$value){
                            if($k == $order_item->item_id){
                                $sum_of_accepted_quantity+=$value;
                            }
                        }
                    }
                }
            }
            $remained_qty -= $sum_of_accepted_quantity;
            $product = Products::where('id',$order_item->item_id)->first();

            $order_item_object = Products::where('id',$order_item->item_id)->first();
            $rate = $order_item->item_rate;
            if($order_item_object && $order_item_object->is_variant == 1){
                $parent_product = Products::where('id',$order_item_object->parent_variant_id)->first();
                $parent_ids[] = $parent_product->id;
                $grand_total_amount += $remained_qty*$rate;
                $parent_array[$parent_product->name_en][]=(object)array('product_name'=>$order_item_object->name,'item_id'=>$order_item_object->id,'qty'=>$order_item->qty,'remained_qty'=>$remained_qty,'rate'=>$rate,'total_amount'=>$remained_qty * $rate);
                if($remained_qty != 0){
                   $count +=1;
                }
                $total_order += $order_item->qty*$order_item->item_rate;
                continue;
            }
            // $rate = $this->itemPrice($order_item->item_id);
            // dd($order_item);
            // $rate = $this->itemPrice($order_item->item_id);
            $grand_total_amount += $remained_qty*$rate;

             $item_details[]=(object)array('product_name'=>$product->name,'item_id'=>$order_item->item_id,'qty'=>$order_item->qty,'remained_qty'=>$remained_qty,'rate'=>$rate,'total_amount'=>$remained_qty * $rate);
             if($remained_qty != 0){
                $count +=1;
             }
        }

        $discount = $order->discount;
        $discount_type = $order->discount_type;
        $total_discount = $total_order;

        $total_after_discount = $this->totalAfterDiscount($discount_type,$discount,$total_order);
        $total_discount -= $total_after_discount;
        // dd($qty_of_all_items);
        $discount_per_unit = $total_discount / $qty_of_all_items;
        // dd($discount_per_unit);
        $selected_tax = Taxs::where('id',$order->tax_and_charges)->first();
        if($selected_tax){
            if($selected_tax->type == 'Actual'){
                $taxs_amount=$selected_tax->amount;
                $grand_total_amount+= $taxs_amount;
                $taxs_rate=0;
            }else{
                $taxs_rate = $selected_tax->rate;
                $total = $grand_total_amount/100 * $taxs_rate;
                $grand_total_amount +=$total;
                $taxs_amount=0;
            }
        }else{
             $taxs_rate=0;
             $taxs_amount=0;
        }
        $selected_shipping_rule = ShippingRule::where('id',$order->shipping_rule)->first();
        if($selected_shipping_rule){

            $shipping_rule_rate=$selected_shipping_rule->rate;
            $grand_total_amount += $shipping_rule_rate;
        }
        if($count>0){
            return view('admin.purchase_receipts.add',compact('parent_array','parent_ids','total_discount','discount_per_unit','shipping_rules','shipping_rule_rate','grand_total_amount','taxs','companies','order','order_items','item_details','taxs_amount','taxs_rate'));
        }else{
            return redirect()->back()->withErrors('Sorry This Order Quantities Is Done, Purchase New Order');
        }

        // foreach($order_items as $item){
        //       $rate[]=  $item->getProductRate($item->item_id);
        // }
    }


    public function totalAfterDiscount($discount_type,$discount,$total){

        if($discount_type =='persentage'){
            $discount = $discount/100;
            $discount *=$total;
            $total -=$discount;
        }elseif($discount_type =='amount'){
            $total -=$discount;
        }
        return $total;
    }

    public function itemPrice($product_id){
        $item_rate=0;
        $item_details= ItemPrice::where('product_id',$product_id)->get();
        if($item_details){
            foreach($item_details as $price){
                $price_list = PriceList::where('id',$price->price_list_id)->first();
                if($price_list){
                    foreach(json_decode($price_list->type) as $type){
                        if($type ==1){
                            $item_rate = $price->rate;
                        }
                    }
                }
            }
        }
               return $item_rate;
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
            $item_id = array_filter($request->input('item_id'));
            $purchase_order_id = Input::get('purchase_order_id');
            $accepted_qty = $request->input('accepted_qty');
            $taxs_rate=0;
            $shipping_rate=0;
            $item_quantity_array = array_combine($item_id, $accepted_qty);
            $order= PurchaseOrders::where('id',$purchase_order_id)->first();
            $total_discount_qty =0;
            $total=0;

            // check that rate and total amount and grand total is correct and there's nothing wrong with them
            if(!isset($item_id)){
                return redirect()->back()->withErrors("There is no items to purchase a receipt");
            }

                foreach ($item_id as $key => $item){
                    $product = Products::where('id',$item)->first();
                    if($product){
                        $order_item = PurchaseOrdersItems::where('item_id',$product->id)->where('purchase_order_id',$order->id)->first();
                        $item_rate = $order_item->item_rate;
                        $item_details[]=(object)array('item_name'=>$product->name_en,'accepted_qty'=>$accepted_qty[$key],'rate'=>$item_rate,'total_amount'=>$accepted_qty[$key]*$item_rate);
                    }

                    $sum_of_accepted_quantity=0;
                    $required_qty = PurchaseOrdersItems::where('item_id',$item)->where('purchase_order_id',$purchase_order_id)->first();
                    $total_discount_qty += $required_qty->qty;
                    // // check if you already made a purchase receipt for this item before
                    $purchase_receipt_exist = PurchaseReceipts::where('purchase_order_id',$purchase_order_id)->where('status','2')->get();
                    if(count($purchase_receipt_exist)>0){
                        foreach($purchase_receipt_exist as $exist){
                            foreach(unserialize($exist->accepted_quantity) as $k =>$value){
                                if($k == intval($item)){
                                    $sum_of_accepted_quantity+=$value;
                                }
                            }
                        }
                    }
                    $sum_of_accepted_quantity +=$accepted_qty[$key];
                    if($sum_of_accepted_quantity > $required_qty->qty){
                        $sum_of_accepted_quantity -=$required_qty->qty;
                        return redirect()->back()->withErrors('Quantity accepted is more than quantity required by '.$sum_of_accepted_quantity);
                    }
                    if($item_rate){
                        $total += $item_rate * $accepted_qty[$key];
                    }

                }
                $total_po=0;
                $discount = $order->discount;
                $discount_type = $order->discount_type;
                $total_qty_of_required_items=0;
                $total_for_discount = $order->purchaseOrderItems;
                foreach($total_for_discount as $total_items){
                    $total_po += $total_items->qty * $total_items->item_rate;
                    $total_qty_of_required_items += $total_items->qty;
                }
                $total_after_discount = $this->totalAfterDiscount($discount_type,$discount,$total_po);
                $discount_rate = $total_po - $total_after_discount ;
                $price_per_unit = $discount_rate / $total_qty_of_required_items;
                $total_accepted_qty = array_sum($accepted_qty);
                // $price_per_unit = $discount/$total_discount_qty;
                $discount_rate = $total_accepted_qty * $price_per_unit;
                $total -= floatval($discount_rate);
                if($request->has('tax_and_charges')){
                    $tax = Taxs::where('id',$request->tax_and_charges)->first();
                    if($tax->type == 'Actual'){
                        $taxs_rate =  $tax->amount;
                        $total += $tax->amount;
                    }else{
                        $taxs_rate =  ($tax->rate/100 * $total);
                        $total += ($tax->rate/100 * $total);
                    }
                }

                if($request->has('shipping_rule')){
                    $shipping_rule = ShippingRule::where('id',$request->shipping_rule)->first();
                    if($shipping_rule){
                        $shipping_rate = $shipping_rule->rate;
                        $total += $shipping_rate;
                    }else{
                        $shipping_rate = 0;
                    }
                }
                $total = round($total, 2);
                $grand_total_amount = round($request->grand_total_amount, 2);
                if($request->has('grand_total_amount')){
                    if(floatval($grand_total_amount) != $total){
                        return redirect()->back()->withErrors('Grand Total amount is in-correct');
                    }
                }

            $serialized_item_quantity_accepted = serialize($item_quantity_array);
            // $purchase_receipt = PurchaseReceipts::create(['purchase_order_id'=>$purchase_order_id,'accepted_quantity'=>$serialized_item_quantity_accepted,'status'=>1,'grand_total_amount'=>$total]);

            $purchase_receipt = new PurchaseReceipts;
            $purchase_receipt->accepted_quantity = $serialized_item_quantity_accepted;
            $purchase_receipt->purchase_order_id = $purchase_order_id;
            $purchase_receipt->status = 1;
            $purchase_receipt->grand_total_amount = $total;
            $purchase_receipt->save();
            $purchase_receipt_id = $purchase_receipt->id;
            if($purchase_receipt){
                $purchase_invoice = PurchaseInvoice::create(['purchased_item_details' => $serialized_item_quantity_accepted,'purchase_receipt_id'=>$purchase_receipt_id,'status'=>1,'grand_total_amount'=>$total]);
            }
            $purchase_order = PurchaseOrders::where('id',$purchase_order_id)->first();
            $company = Company::where('id',$purchase_order->company_id)->first();
            $company_name = $company ? $company->name_en : "";
            $required_by_date = $purchase_order->required_by_date;
            $pdf = PDF::loadView('admin.purchase_receipts.pdf',compact('item_details','purchase_receipt_id','required_by_date','company_name','shipping_rate','taxs_rate','total','purchase_order_id'));

            $fileName = 'PR-'.$purchase_receipt_id;
            $location =public_path('pdfs/'.$fileName.'.pdf');
            $pdf->save($location);
            return redirect($purchase_receipt->path($purchase_order_id))->with('success','Receipt Created Successfully');
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


         public function downloadFile($id)
        {
            $purchase_receipt = PurchaseReceipts::where('id',$id)->first();
            if($purchase_receipt){
                $myFile =public_path().'/pdfs/PR-'.$id.'.pdf';
                $headers = ['Content-Type: application/pdf'];
                $newName = 'PR-'.$id.'-'.time().'.pdf';
            }
            return response()->download($myFile, $newName, $headers);
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

    public function changeStatus($id , $status)
        {
            if($status==0){
                $purchase_invoice = PurchaseInvoice::where('purchase_receipt_id',$id)->first();
                if($purchase_invoice){
                    if($purchase_invoice->status !=2){
                        $payment=Payment::where('invoice_id',$purchase_invoice->id)->first();
                        if($payment){
                            $payment->status = 0;
                            $payment->save();
                        }
                        $purchase_invoice->status = 0;
                        $purchase_invoice->save();
                        // $purchase_invoice->delete();
                    }else{
                        return 'not allowed';
                    }
                }
            }
            $purchase_receipt = PurchaseReceipts::where('id',$id)->first();
            if($status == 0 && $purchase_receipt->status == 0){
                return 'already_cancelled';
            }elseif($status ==2 && $purchase_receipt->status == 2){
                return 'already_submitted';
            }
            if (PurchaseReceipts::whereId($id)->update(['status' => $status]))
            {
                $purchase_order_items_details = unserialize($purchase_receipt->accepted_quantity);
                $purchase_order_id = $purchase_receipt->purchase_order_id;
                $purchase_order =PurchaseOrders::where('id',$purchase_order_id)->first();
                $warehouse = Warehouses::where('id',$purchase_order->warehouse_id)->first();
                if($status == 2){
                    foreach($purchase_order_items_details as $key=>$value){
                        $stock =Stocks::where('product_id',$key)->where('purchase_order_reference_id',$purchase_receipt->purchase_order_id)->first();
                        if($stock){
                            $stock->active=1;
                            $stock->stock_qty += $value;
                            $stock->save();
                        }else{
                            $stock = Stocks::create(['purchase_order_reference_id' => $purchase_order_id,'warehouse_id'=>$purchase_order->warehouse_id,'product_id'=>$key,'stock_qty'=>$value,'status'=>'ADDED','active'=>1,'destination_warehouse'=>null]);
                            // $stock->destination_warehouse = null;
                            if($warehouse){
                                $stock->destination_warehouse =$warehouse->name;
                                $stock->save();
                            }
                        }

                        $item_warehouse = ItemWarehouse::where('product_id',$key)->where('warehouse_id',$purchase_order->warehouse_id)->first();

                        if($item_warehouse){
                            $item_warehouse->projected_qty += $value;
                            $item_warehouse->save();
                        }else{
                            $product = Products::where('id',$key)->first();
                            $item_warehouse = ItemWarehouse::create(['product_id' => $key,'warehouse_id'=>$purchase_order->warehouse_id,'projected_qty'=>$value,'warehouse'=>$purchase_order->warehouse_id,'item_code'=>$product->item_code]);
                        }
                    }
                }else{
                    foreach($purchase_order_items_details as $key=>$value){
                        // dd($purchase_receipt);
                        $stock =Stocks::where('product_id',$key)->where('purchase_order_reference_id',$purchase_receipt->purchase_order_id)->first();
                    // dd($stock);
                        // dd($stock);
                        if($stock && $stock->active !=0){
                            $stock->stock_qty -= $value;
                            $stock->save();
                            if($stock->stock_qty == 0){
                                $stock->active=0;
                                $stock->save();
                            }
                            $item_warehouse = ItemWarehouse::where('product_id',$key)->where('warehouse_id',$purchase_order->warehouse_id)->first();
                            if($item_warehouse){
                                $item_warehouse->projected_qty -= $value;
                                $item_warehouse->save();
                            }
                        }
                    }
                }
                return response()->json(true);
            }
            return response()->json(false);
        }

public function delete($id){

    $purchase_invoice = PurchaseInvoice::where('purchase_receipt_id',$id)->first();
        if($purchase_invoice->status != 2 ){
              $payment=Payment::where('invoice_id',$purchase_invoice->id)->first();
              if($payment){
                $payment->delete();
              }
              $purchase_invoice->delete();
        }else{
                return 'not allowed';
        }
        $purchase_receipt = PurchaseReceipts::where('status','!=',2)->where('id',$id)->first();
        if($purchase_receipt){
            $purchase_receipt->delete();
            return 'true';
        }else{
            return response()->json(false);
        }
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
