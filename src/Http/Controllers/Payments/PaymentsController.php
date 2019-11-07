<?php

namespace App\Http\Controllers\Payments;

use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentsRequest;
use App\Models\ItemPrice;
use App\Models\Payment;
use App\Models\PaymentMethods;
use App\Models\PriceList;
use App\Models\Products;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseOrders;
use App\Models\PurchaseOrdersItems;
use App\Models\PurchaseReceipts;
use App\Models\ShippingRule;
use App\Models\Taxs;
use App\Models\Warehouses;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;

class PaymentsController extends Controller {

	public function __construct() {

	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return view('admin.payments.index');
	}

	public function paymentsList() {
		$payments = Payment::all();
		foreach ($payments as $payment) {
			$payment['purchase_order_id'] = '--';
			if ($payment) {
				$invoice = PurchaseInvoice::where('id', $payment->invoice_id)->first();
				if ($invoice) {
					$receipt = PurchaseReceipts::where('id', $invoice->purchase_receipt_id)->first();
					if ($receipt) {
						$payment['purchase_order_id'] = $receipt->purchase_order_id;
					}
				}

			}
		}

		return Datatables::of($payments)->make(true);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function itemPrice($product_id) {
		$item_rate = 0;
		$item_details = ItemPrice::where('product_id', $product_id)->get();
		if ($item_details) {
			foreach ($item_details as $price) {
				$price_list = PriceList::where('id', $price->price_list_id)->first();
				if ($price_list) {
					foreach (json_decode($price_list->type) as $type) {
						if ($type == 1) {
							$item_rate = $price->rate;
						}
					}
				}
			}
		}
		return $item_rate;
	}

	public function create() {
		$item_details = array();
		$purchase_invoice_id = Input::get('invoice');
		$payment_exist = Payment::where('invoice_id', $purchase_invoice_id)->first();
		if ($payment_exist) {
			return redirect('admin/payments')->withErrors("You already have payment for this invoice");
		}
		$purchase_invoice = PurchaseInvoice::where('id', $purchase_invoice_id)->first();
		if ($purchase_invoice) {
			$purchase_receipt = PurchaseReceipts::where('id', $purchase_invoice->purchase_receipt_id)->first();
			if ($purchase_receipt) {
				$purchase_order = PurchaseOrders::where('id', $purchase_receipt->purchase_order_id)->first();
			}
		}
		$grand_total_amount = $purchase_invoice->grand_total_amount;
		$purchased_items_details = unserialize($purchase_invoice->purchased_item_details);
		$parent_array = array();
		foreach ($purchased_items_details as $key => $value) {
			$product = Products::where('id', $key)->first();
			$rate = 0;
			if ($purchase_order) {
				$purchase_item = PurchaseOrdersItems::where('item_id', $key)->where('purchase_order_id', $purchase_order->id)->first();
				$rate = $purchase_item->item_rate;
			}
			if ($product->is_variant == 1) {
				$parent_product = Products::where('id', $product->parent_variant_id)->first();
				$parent_array[$parent_product->name_en][] = (object) array('product_name' => $product->name, 'item_id' => $key, 'qty' => intval($value), 'rate' => $rate, 'total_amount' => intval($value) * $rate);
				continue;
			}

			// $rate = $this->itemPrice($key);
			$item_details[] = (object) array('product_name' => $product->name, 'item_id' => $key, 'qty' => intval($value), 'rate' => $rate, 'total_amount' => intval($value) * $rate);
		}
		$purchase_receipt = PurchaseReceipts::where('id', $purchase_invoice->purchase_receipt_id)->first();
		$purchase_order_id = $purchase_receipt->purchase_order_id;
		$purchase_order = PurchaseOrders::whereId($purchase_order_id)->first();
		$selected_tax = Taxs::where('id', $purchase_order->tax_and_charges)->first();
		$selected_shipping_rule = ShippingRule::where('id', $purchase_order->shipping_rule)->first();
		$selected_warehouse = Warehouses::where('id', $purchase_order->warehouse_id)->first();
		$selected_company = Company::where('id', $purchase_order->company_id)->first();
		$payment_methods = PaymentMethods::all();

		return view('admin.payments.add', compact('selected_warehouse', 'purchase_invoice', 'grand_total_amount', 'purchase_invoice', 'item_details', 'selected_tax', 'selected_shipping_rule', 'payment_methods', 'selected_company', 'parent_array'));
	}

	public function store(PaymentsRequest $request) {
		$payment_exist = Payment::where('invoice_id', $request->invoice_id)->first();
		if ($payment_exist) {
			return redirect('admin/payments')->withErrors("You already have payment for this invoice");
		}
		$payment = Payment::create($request->all());
		// $purchase_invoice=PurchaseInvoice::where('id',$request->invoice_id)->first();
		// if($purchase_invoice){
		//     $purchase_receipt = PurchaseReceipts::where('id',$purchase_invoice->purchase_receipt_id)->first();
		//     if($purchase_receipt){
		//        $purchase_order = PurchaseOrders::where('id',$purchase_receipt->purchase_order_id)->first();
		//        if($purchase_order){
		//             $purchase_order_items = $purchase_order->purchaseOrderItems;
		//             foreach($purchase_order_items as $item){
		//                 $product = Products::where('id',$item->item_id)->first();
		//                 $item_warehouse = ItemWarehouse::where('product_id',$item->item_id)->where('warehouse_id',$purchase_order->warehouse_id)->first();
		//                 if($item_warehouse){
		//                     $item_warehouse->projected_qty += $item->qty;
		//                 }else{
		//                     // dd($item-);
		//                     $item_warehouse = new ItemWarehouse();
		//                     $item_warehouse->product_id = $item->item_id;
		//                     $item_warehouse->warehouse_id = $purchase_order->warehouse_id;
		//                     $item_warehouse->warehouse = $purchase_order->warehouse_id;
		//                     $item_warehouse->projected_qty = $item->qty;
		//                     $item_warehouse->item_code = $product->item_code;
		//                     $item_warehouse->save();
		//                     // ItemWarehouse::create(['product_id'=>$item->item_id,'warehouse_id'=>$purchase_order->warehouse->id,'warehouse'=>$purchase_order->warehouse_id,'projected_qty'=>$item->qty,'item_code'=>$product->item_code]);
		//                     dd(5632);
		//                 }
		//             }
		//        }
		//     }
		// }

		return redirect($payment->path())->withSucess('Payment Created Successfully');

	}

	public function changeStatus($id, $status) {
		// return $status;
		$payment = Payment::whereId($id)->first();

		if ($status == 2 && $payment->status == 2) {
			return 'false';
		} elseif ($status == 0 && $payment->status == 0) {
			return 'false';
		} else {
			if (Payment::whereId($id)->update(['status' => $status])) {

				return response()->json(true);
			}
		}
		return response()->json(false);

	}

	public function delete($id) {
		$payment = Payment::findOrFail($id);
		if ($payment->status == 0 || $payment->status == 1) {
			$payment->delete();
			return response()->json(true);
		}
		return response()->json(false);
	}
}
