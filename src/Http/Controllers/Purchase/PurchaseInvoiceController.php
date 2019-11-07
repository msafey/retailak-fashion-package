<?php

namespace App\Http\Controllers\Purchase;

use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Models\ItemPrice;
use App\Mail\PurchaseInvoiceMail;
use App\Models\Payment;
use App\Models\PriceList;
use App\Models\Products;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseOrders;
use App\Models\PurchaseOrdersItems;
use App\Models\PurchaseReceipts;
use App\Models\ShippingRule;
use File;
use App\Taxs;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use PDF;
use Yajra\Datatables\Datatables;


class PurchaseInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = PurchaseInvoice::all();
        return view('admin.purchase_invoices.index2');
    }

    public function purchaseInvoicesList(){
        $purchase_order_id = 0;
        $purchase_receipt_id = 0;
        $purchase_invoice_id = 0;

        $search_filter = $_GET['search_filter'];
        ${$search_filter} = isset($_GET['search_id']) ? intval($_GET['search_id']) : 0;
        $pending = isset($_GET['pending']) ? $_GET['pending'] : 0;
        $completed = isset($_GET['completed'])? $_GET['completed'] : 0;
        if($pending == 1 && $completed==1){
            $invoices = PurchaseInvoice::orderBy('id','Desc')->get();
        }elseif($pending == 1 && $completed==0){
            $invoices = PurchaseInvoice::where('status','!=',2)->orderBy('id','Desc')->get();
        }elseif($pending == 0 && $completed==1){
            $invoices = PurchaseInvoice::where('status',2)->orderBy('id','Desc')->get();
        }else{
            $invoices = PurchaseInvoice::orderBy('id','Desc')->get();
        }
        if($purchase_order_id != 0){

            $purchase_order = PurchaseOrders::where('id',$purchase_order_id)->first();
            if(!$purchase_order){
                $invoices = array();
                return Datatables::of($invoices)->make(true);
            }
            $invoices = $this->getPurchaseInvoices($purchase_order_id,$invoices,'purchase_order');
        }
        if($purchase_receipt_id !=0){
            $purchase_invoice = PurchaseInvoice::where('purchase_receipt_id',$purchase_receipt_id)->first();
            if(!$purchase_invoice){
                $invoices = array();
                return Datatables::of($invoices)->make(true);
            }
            $invoices = $this->getPurchaseInvoices($purchase_receipt_id,$invoices,'purchase_receipt');
        }

        if($purchase_invoice_id !=0){
            $purchase_invoice = PurchaseInvoice::where('id',$purchase_invoice_id)->first();
            if(!$purchase_invoice){
                $invoices = array();
                return Datatables::of($invoices)->make(true);
            }
                $invoices = $this->getPurchaseInvoices($purchase_invoice_id,$invoices,'purchase_invoice');
        }

        if(count($invoices)>0){
            foreach($invoices as $invoice){
                if(isset($invoice->purchase_receipt_id)){
                    $purchase_receipt = PurchaseReceipts::where('id',$invoice->purchase_receipt_id)->first();
                    if($purchase_receipt){
                        $purchase_order = PurchaseOrders::where('id',$purchase_receipt->purchase_order_id)->first();
                        if($purchase_order){
                            $invoice['purchase_order_id'] = $purchase_order->id;
                        }
                    }
                }
            }
        }

        return Datatables::of($invoices)->make(true);
    }

    // Filter
    public function getPurchaseInvoices($purchase_id,$invoices,$key){
        if($key == "purchase_order"){
            $purchase_receipt =PurchaseReceipts::where('purchase_order_id',$purchase_id)->get();
            $purchase_invoice = array();
            $count = 0;
            $inv = array();
            foreach($purchase_receipt as $reciept){
               $purchaseInvoice = PurchaseInvoice::where('purchase_receipt_id',$reciept->id)->first();
               foreach($invoices as $key => $invoice){
                    if($invoice->id === $purchaseInvoice->id){
                        $inv[] = $invoice;
                    }
               }

            }
            return $inv;
        }elseif($key == "purchase_invoice"){
            $invoice = PurchaseInvoice::where('id',intval($purchase_id))->first();
            $invoices =  $this->unsetInvoices($invoices,$invoice);
        }elseif($key == "purchase_receipt"){
            $invoice = PurchaseInvoice::where('purchase_receipt_id',$purchase_id)->first();
            $invoices =  $this->unsetInvoices($invoices,$invoice);
        }
        return $invoices;
    }

    public function purchaseInvoiceDetails($id){
            $taxs = Taxs::where('status',1)->get();
            $shipping_rules = ShippingRule::where('disabled',0)->get();
            $companies = Company::all();

            $purchase_receipt_invoice = PurchaseInvoice::find($id);
            $purchase_receipt = PurchaseReceipts::where('id',$purchase_receipt_invoice->purchase_receipt_id)->first();
            $grand_total_amount = $purchase_receipt->grand_total_amount;
            $purchaseItemsDetails = $this->purchaseItemsDetails($purchase_receipt);
            $item_details = $purchaseItemsDetails['item_details'];
            $qty_of_all_accepted_items =$purchaseItemsDetails['qty_of_all_accepted_items'];
            $purchase_order= $purchaseItemsDetails['purchase_order'];
            $discount = $purchase_order->discount;
            $discount_type = $purchase_order->discount_type;
            $total=0;
            $total_qty_of_required_items=0;
            $total_for_discount = $purchase_order->purchaseOrderItems;
            foreach($total_for_discount as $total_items){
                $total += $total_items->qty * $total_items->item_rate;
                $total_qty_of_required_items += $total_items->qty;
            }
            $total_after_discount = $this->totalAfterDiscount($discount_type,$discount,$total);
            $discount_rate = $total - $total_after_discount ;
            $discount_per_unit = $discount_rate / $total_qty_of_required_items;
            $purchase_order_id = $purchase_receipt->purchase_order_id;
            $purchase_order = PurchaseOrders::whereId($purchase_order_id)->first();
            $taxs_result = selectedTaxs($purchase_order);
            $taxs_rate = $taxs_result['taxs_rate'];
            $taxs_amount = $taxs_result['taxs_amount'];
            $purchase_receipt_id=$purchase_receipt->id;
            $shipping_rule_rate = 0;
            return view('admin.purchase_invoices.details',compact('shipping_rule_rate','shipping_rules','grand_total_amount','taxs','companies','purchase_order','purchase_receipt','item_details','taxs_amount','taxs_rate','purchase_receipt_invoice','purchase_receipt_id','discount_rate','discount_per_unit'));

    }


    public function unsetInvoices($invoices,$purchase_invoice){
        if($purchase_invoice){
            foreach($invoices as $key => $value){
                if($value->id != $purchase_invoice->id){
                    unset($invoices[$key]);
                    continue;
                }
            }
        }
        return $invoices;
    }

    public function createInvoice($id){
        $purchase_receipt = PurchaseReceipts::where('id',$id)->first();
        $purchase_receipt_invoice = PurchaseInvoice::where('purchase_receipt_id',$id)->first();
        if($purchase_receipt_invoice){
            return redirect('admin/purchase-invoices?purchase_order_id='.$purchase_receipt->purchase_order_id);
        }
        $qty_of_all_accepted_items=0;
        $shipping_rules = ShippingRule::where('disabled',0)->get();
        $taxs = Taxs::where('status',1)->get();
        $companies = Company::all();
        $purchase_receipt = PurchaseReceipts::where('id',$id)->first();
        $purchase_order = PurchaseOrders::where('id',$purchase_receipt->purchase_order_id)->first();
        $grand_total_amount = $purchase_receipt->grand_total_amount;
        $purchased_items_details = unserialize($purchase_receipt->accepted_quantity);
        foreach($purchased_items_details as $key => $value){
            $qty_of_all_accepted_items += $value;
            $rate = 0;
            $product = Products::where('id',$key)->first();
            // $rate = $this->itemPrice($key);
            $purchase_item = PurchaseOrdersItems::where('item_id',$key)->where('purchase_order_id',$purchase_order->id)->first();
            if($purchase_item){
                $rate = $purchase_item->item_rate;
            }
            // $rate = $product->standard_rate;
            $item_details[]=(object)array('product_name'=>$product->name,'item_id'=>$key,'qty'=>intval($value),'rate'=>$rate,'total_amount'=>intval($value) * $rate);
        }

        $discount = $purchase_order->discount;
        $discount_type = $purchase_order->discount_type;
        $total=0;
        $total_qty_of_required_items=0;
        $total_for_discount = $purchase_order->purchaseOrderItems;
        foreach($total_for_discount as $total_items){
            $total += $total_items->qty * $total_items->item_rate;
            $total_qty_of_required_items += $total_items->qty;
        }
        $total_after_discount = $this->totalAfterDiscount($discount_type,$discount,$total);
        $discount_rate = $total - $total_after_discount ;
        $discount_per_unit = $discount_rate / $total_qty_of_required_items;
        $purchase_order_id = $purchase_receipt->purchase_order_id;
        $purchase_order = PurchaseOrders::whereId($purchase_order_id)->first();
        $taxs_result = selectedTaxs($purchase_order);
        $taxs_rate = $taxs_result['taxs_rate'];
        $taxs_amount = $taxs_result['taxs_amount'];

        $purchase_receipt_id=$purchase_receipt->id;
        $shipping_rule_rate = 0;
        // dd($purchase_receipt->id);
        return view('admin.purchase_invoices.add',compact('shipping_rule_rate','shipping_rules','grand_total_amount','taxs','companies','purchase_order','purchase_receipt','item_details','taxs_amount','taxs_rate','purchase_receipt_id','discount_rate','discount_per_unit'));

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }


     public function downloadFile($id)
    {
        $purchase_invoice = PurchaseInvoice::where('id',$id)->first();
        if($purchase_invoice){
            $myFile =public_path().'/pdfs/PI-'.$id.'.pdf';
            if(File::exists($myFile)){
                $headers = ['Content-Type: application/pdf'];
                $newName = 'PI-'.$id.'-'.time().'.pdf';
            }else{
                $headers = ['Content-Type: application/pdf'];
                $array = $this->createPdf($purchase_invoice);
                $newName = 'PI-'.$id.'-'.time().'.pdf';
                $pdf = $array['pdf'];
                return $pdf->download($newName.'.pdf');
            }
        }
        return response()->download($myFile, $newName, $headers);
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



    public function postPurchaseInvoice(Request $request)
    {
        return DB::transaction(function () use ($request){
            $purchase_receipt_id = Input::get('purchase_receipt_id');
            $purchase_receipt_invoice = PurchaseInvoice::where('purchase_receipt_id',$purchase_receipt_id)->first();
            if($purchase_receipt_invoice){
                return response()->json(['data' => 'purchase_invoice_already_exist']);
            }
                $item_id = array_filter($request->input('items'));
                $qty = $request->input('qty');
                $sum_qty = array_sum($qty);
                $item_quantity_array = array_combine($item_id, $qty);
                $total=0;


                $purchase_receipt =PurchaseReceipts::where('id',$purchase_receipt_id)->first();
                if($purchase_receipt){
                    $purchase_order = PurchaseOrders::where('id',$purchase_receipt->purchase_order_id)->first();
                }

                foreach ($item_id as $key => $item){
                    if($purchase_order){
                        $purchase_item = PurchaseOrdersItems::where('item_id',$item)->where('purchase_order_id',$purchase_order->id)->first();
                        if($purchase_item){
                            $rate = $purchase_item->item_rate;
                            $total += $qty[$key] * $rate;
                        }
                    }else{
                        $rate=0;
                    }

                }



                $discount = $purchase_order->discount;
                $discount_type = $purchase_order->discount_type;
                $total_po=0;
                $total_qty_of_required_items=0;
                $total_for_discount = $purchase_order->purchaseOrderItems;
                foreach($total_for_discount as $total_items){
                    $total_po += $total_items->qty * $total_items->item_rate;
                    $total_qty_of_required_items += $total_items->qty;
                }
                $total_after_discount = $this->totalAfterDiscount($discount_type,$discount,$total_po);
                $discount_rate = $total_po - $total_after_discount ;
                $discount_per_unit = $discount_rate / $total_qty_of_required_items;
                $discount_to_reduce = $sum_qty*$discount_per_unit;

                $total -= floatval($discount_to_reduce);
                if($request->has('tax')){
                    $tax = Taxs::where('id',$request->tax)->first();
                    if($tax->type == 'Actual'){
                        $total += $tax->amount;
                    }else{
                      $total += ($tax->rate/100 * $total);
                    }
                }

                if($request->has('shipping_rule')){
                    $shipping_rule = ShippingRule::where('id',$request->shipping_rule)->first();
                    if($shipping_rule){
                        $total += $shipping_rule->rate;
                    }
                }
                $total = round($total, 2);
                $grand_total_amount = round($request->grand_total_amount,2);
                if($request->has('grand_total_amount')){
                    if($grand_total_amount != $total){
                return response()->json(['data' => 'grand_total_incorrect']);
                    }
                }
                // $purchase_reciept  = PurchaseReceipts::where('id',$purchase_receipt_id)->first();
                // dd($purchase_reciept);
                // $purchase_order = PurchaseOrders::where('id',$purchase_reciept->purchase_order_id)->first();
                $serialized_purchased_items_details = serialize($item_quantity_array);
                $purchase_invoice = new PurchaseInvoice;
                $purchase_invoice->purchased_item_details = $serialized_purchased_items_details;
                $purchase_invoice->purchase_receipt_id = $purchase_receipt_id;
                $purchase_invoice->status = 1;
                $purchase_invoice->grand_total_amount = $total;
                $purchase_invoice->save();
                $company = Company::where('id',$purchase_order->company_id)->first();

                $company_name = $company ? $company->name_en : '' ;

                $date = date('Y-m-d:h:i:s');
                $data= view('admin.purchase_invoices.email-modal',compact('company_name','purchase_receipt_id','date'))->render();
                return response()->json(['data' => $data]);
               });
    }



   public function store(Request $request){
   }

    public function sendEmailModal(Request $request){
        $purchase_receipt_id = $request->purchase_receipt_id;
        $purchase_receipt =PurchaseReceipts::where('id',$purchase_receipt_id)->first();
        if($purchase_receipt){
            $purchase_order = PurchaseOrders::where('id',$purchase_receipt->purchase_order_id)->first();
            $company = Company::where('id',$purchase_order->company_id)->first();
            $company_name = $company ? $company->name_en : '' ;
        }
        $date = date('Y-m-d:h:i:s');
        $data= view('admin.purchase_invoices.email-modal',compact('company_name','purchase_receipt_id','date'))->render();
            return response()->json(['data' => $data]);
    }



    public function sendEmail(Request $request){
                $purchase_receipt_id = $request->purchase_receipt_id;
                $email=$request->email;
                $company_name = $request->company_name;
                $body = $request->body;
                $purchase_invoice = PurchaseInvoice::where('purchase_receipt_id',$purchase_receipt_id)->first();

               $array =  $this->createPdf($purchase_invoice);
                $pdf = $array['pdf'];
                $purchase_invoice_id = $array['purchase_invoice_id'];

                $fileName = 'PI-'.$purchase_invoice_id;
                $location =public_path('pdfs/'.$fileName.'.pdf');
                $pdf->save($location);
                Mail::send(new PurchaseInvoiceMail($location,$email,$body));
                return 'true';
                // view('admin.purchase-orders.pdf',compact('item_details'));
    }



public function createPdf($purchase_invoice){
    $purchase_reciept = PurchaseReceipts::where('id',$purchase_invoice->purchase_receipt_id)->first();
    $purchase_order = PurchaseOrders::where('id',$purchase_reciept->purchase_order_id)->first();
    // $purchase_invoice = PurchaseInvoice::where('purchase_receipt_id',$purchase_receipt_id)->first();
    $purchase_invoice_id = $purchase_invoice->id;
    $taxs_rate=0;
    $shipping_rate=0;
    $required_by_date= $purchase_order->required_by_date;
    $shipping_rule= ShippingRule::where('id',$purchase_order->shipping_rule)->first();
    if($shipping_rule){
    $shipping_rate = $shipping_rule->rate;

    }
    $tax = Taxs::where('id',$purchase_order->tax_and_charges)->first();

    $accepted_quantity_items = unserialize($purchase_reciept->accepted_quantity);
    $total =0;

    foreach($accepted_quantity_items as $key => $qty){
        $item_rate=0;
        $product = Products::where('id',$key)->first();
        if($product){
            $item_object = PurchaseOrdersItems::where('item_id',$key)->where('purchase_order_id',$purchase_order->id)->first();
        if($item_object){
            $item_rate= $item_object->item_rate;
        }
            $item_details[]=(object)array('item_name'=>$product->name_en,'qty'=>$qty,'rate'=>$item_rate,'total_amount'=>$qty*$item_rate);
            $total += $qty*$item_rate;
        }

    }
    if($tax){
        if($tax->type == 'Actual'){
            $taxs_rate = $tax->amount;
            $total += $tax->amount;
        }else{
            $taxs_rate = ($tax->rate/100 * $total);
            $total += ($tax->rate/100 * $total);
        }
    }
    $total +=$shipping_rate;

    $pdf = PDF::loadView('admin.purchase_invoices.pdf',compact('item_details','purchase_invoice_id','required_by_date','company_name','shipping_rate','taxs_rate','total'));
    $array['pdf'] = $pdf;
    $array['purchase_invoice_id'] = $purchase_invoice_id;
    return $array;

}

    public function changeStatus($id , $status)
    {

                $payment = Payment::where('invoice_id',$id)->first();
                if($status == 0){
                    if($payment){
                        if($payment->status == 2){
                            return 'not allowed';
                        }
                    }
                }
                $purchase_invoice =PurchaseInvoice::whereId($id)->first();
                $purchase_receipt = PurchaseReceipts::where('id',$purchase_invoice->purchase_receipt_id)->first();
                if($status == 2){
                        if($purchase_receipt->status !=2){
                            return 'PR not submitted';
                        }
                }
                if (PurchaseInvoice::whereId($id)->update(['status' => $status]))
                {
                    return 'true';
                }else{
                    return 'false';
                }
    }



    public function delete($id){
        $purchase_invoice = PurchaseInvoice::where('id',$id)->first();

        if($purchase_invoice){
            $payment = Payment::where('invoice_id',$id)->first();
            if($payment){
                if($payment->status != 2){
                    $payment->delete();
                }else{
                    return 'not allowed';
                }
            }
            $purchase_invoice->delete();
            return response()->json(true);
        }
        return response()->json(false);
    }



    public function destroy($id)
    {
    }



    public function purchaseItemsDetails($purchase_receipt){
        $purchase_order = PurchaseOrders::where('id',$purchase_receipt->purchase_order_id)->first();
        $qty_of_all_accepted_items = 0;
        $purchased_items_details = unserialize($purchase_receipt->accepted_quantity);
        foreach($purchased_items_details as $key => $value){
            $qty_of_all_accepted_items += $value;
            $rate = 0;
            $product = Products::where('id',$key)->first();
            $purchase_item = PurchaseOrdersItems::where('item_id',$key)->where('purchase_order_id',$purchase_order->id)->first();
            if($purchase_item){
                $rate = $purchase_item->item_rate;
            }
            $item_details[]=(object)array('product_name'=>$product->name,'item_id'=>$key,'qty'=>intval($value),'rate'=>$rate,'total_amount'=>intval($value) * $rate);
        }
        $array['item_details'] = $item_details;
        $array['qty_of_all_accepted_items'] = $qty_of_all_accepted_items;
        $array['purchase_order'] = $purchase_order;
        return $array;
    }

}
