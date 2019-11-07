<?php

namespace App\Http\Controllers\Purchase;

use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddPurchaseOrderRequest;
use App\Models\ItemPrice;
use App\Mail\PurchaseOrderMail;
use App\Models\Payment;
use App\Models\PriceList;
use App\Models\Products;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseOrders;
use App\Models\PurchaseOrdersItems;
use App\Models\PurchaseReceipts;
use App\Models\ShippingRule;
use App\Models\AdminUser;
use App\Models\Adjustments;
use App\Models\Taxs;
use App\Models\Warehouses;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use PDF;
use Response;
use Yajra\Datatables\Datatables;


class PurchaseOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!Auth::guard('admin_user')->user()->can('purchase_orders')) {
            return view('admin.un-authorized');
        }

        $product_id = 0;
        if (isset($_GET['product_id'])) {
            $product_id = $_GET['product_id'];
        }
        $products = Products::where('is_bundle', 0)->where('active', 1)->get();
        // dd($products);

        return view('admin/purchase-orders/index', compact('products', 'product_id'));
    }

    public function purchaseOrdersList()
    {
        if (!Auth::guard('admin_user')->user()->can('purchase_orders')) {
            return view('admin.un-authorized');
        }
        $product_id = 0;
        if (isset($_GET['product_id'])) {
            $product_id = $_GET['product_id'];
        }
        if (isset($_GET['creation_date'])) {
            $creation_date = $_GET['creation_date'];
        }
        if ($creation_date != '') {
            // Filter for creation date
            $orders = PurchaseOrders::orderBy('id', 'DESC')->whereDate('created_at', '>=', date('Y-m-d:h:i:s', strtotime($creation_date)))->where('is_deleted', 0)->get();
        } else {
            $orders = PurchaseOrders::orderBy('id', 'DESC')->where('is_deleted', 0)->get();
        }
        foreach ($orders as $key => $order) {
            if ($order) {
                $purchase_receipts = PurchaseReceipts::where('purchase_order_id', $order->id)->where('is_deleted', 0)->get();
                /*This For To get count of purchase receipts in specific PO  -> PR(number)*/
                $order['count_purchase_receipts'] = count($purchase_receipts);
                $company = Company::where('id', $order->company_id)->first();
                if ($company) {
                    $order['company'] = $company->name_en;
                } else {
                    $order['company'] = '';
                }
                if ($product_id != 0) {
                    $product_item = Products::where('id', $product_id)->first();
                    if ($product_item && $product_item->has_variants) {
                        $product_childs = Products::where('parent_variant_id', $product_item->id)->get();
                        foreach ($product_childs as $child) {
                            $order_items = PurchaseOrdersItems::where('item_id', $child->id)->where('purchase_order_id', $order->id)->first();
                        }
                        if (!isset($order_items)) {
                            unset($orders[$key]);
                            continue;
                        }

                    } else {
                        $order_items = PurchaseOrdersItems::where('item_id', $product_id)->where('purchase_order_id', $order->id)->first();
                        if (!$order_items) {
                            unset($orders[$key]);
                        }
                    }

                }
            }
        }
        return Datatables::of($orders)->make(true);
    }

    // to get rate of the product via ajax request
    public function getVariant()
    {
        $item_rate = 0;
        $item = Input::get('item');
        $product = Products::where('id', $item)->first();

        if ($product) {
            $item_rate = itemSellingPrice($item);
            return Response::json(['item_name' => $product->name_en, 'item_id' => $product->id, 'item_rate' => $item_rate, 'product_variations' => array(), 'qty' => 1]);
        }

    }

    public function getItemDetails()
    {

        $item_rate = 0;
        $item = Input::get('item');
        $product = Products::where('id', $item)->first();
        if (isset($_GET['sales_order']) && $_GET['sales_order'] == 1) {
            $item_rate = itemSellingPrice($item);
        } else {
            $item_rate = itemBuyingPrice($item);
        }
        $product_variations = array();
        if ($product) {
            if ($product->has_variants == 1) {
                $variation_childs = Products::where('parent_variant_id', $product->id)->get();
                foreach ($variation_childs as $child) {
                    $rate = 0;
                    if (isset($_GET['sales_order']) && $_GET['sales_order'] == 1) {
                        $rate = itemSellingPrice($child->id);
                    } else {
                        $rate = itemBuyingPrice($child->id);
                    }
                    $product_variations[] = (object)array('item_name' => $child->name_en, 'item_id' => $child->id, 'qty' => 1, 'rate' => $rate, 'total_amount' => $rate);
                }
                return Response::json(['item_rate' => $item_rate, 'product_variations' => $product_variations, 'parent_name' => $product->name_en]);
            } else {
                return Response::json(['item_rate' => $item_rate, 'product_variations' => $product_variations, 'parent_name' => ""]);
            }
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('purchase_orders'))
        {
            return view('admin.un-authorized');
        }

        $warehouses = Warehouses::where('status', 1)->get();
        $item_rate = 0;
        $shipping_rules = ShippingRule::where('disabled', 0)->get();
        $taxs = Taxs::where('status', 1)->get();
        $variation_childs = array();
        $product_configurations = config('configurations.products');
        if (isset($_GET['product']) && $_GET['product'] != 0) {
            $product_id = $_GET['product'];
            $item = Products::where('id', $product_id)->first();
            if ($item) {
                if ($item->active == 0) {
                    return redirect()->back()->withErrors('This Product is in-active');
                }

                if (isset($product_configurations['variations']) && $product_configurations['variations'] == true) {
                    if ($item->has_variants == 1) {
                        $variation_childs = Products::where('is_variant', 1)->where('parent_variant_id', $item->id)->get();
                    }

                    $products = Products::isVariant()->where('active', 1)->get();
                } else {
                    $products = Products::isVariant()->hasNoVariants()->where('active', 1)->get();
                }

            }
            $item_rate = $this->itemPrice($product_id);
        } else {
            if (isset($product_configurations['variations']) && $product_configurations['variations'] == true) {
                $products = Products::isVariant()->where('active', 1)->get();
            } else {
                $products = Products::isVariant()->hasNoVariants()->where('active', 1)->get();
            }
        }
        // dd($products);

        if (!isset($item)) {
            $item = new \stdClass();
            $item->id = -1;
            $item->standard_rate = -1;
        }
        $companies = Company::all();
        return view('admin.purchase-orders.add', compact('variation_childs', 'warehouses', 'companies', 'item_rate', 'products', 'item', 'shipping_rules', 'taxs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    public function purchaseOrderDetails($id)
    {
        if (!Auth::guard('admin_user')->user()->can('purchase_orders'))
        {
            return view('admin.un-authorized');
        }

        $products = Products::where('active', 1)->get();
        $order = PurchaseOrders::where('id', $id)->first();
        $total = 0;
        if ($order) {
            $selected_company = Company::where('id', $order->company_id)->first();
            $company_name = $selected_company->name_en;
            $selected_warehouse = Warehouses::where('id', $order->warehouse_id)->first();
            $grand_total_amount = 0;
            $total_after_discount = 0;
            $array = $this->orderItems($order, $grand_total_amount);
            $parent_array = $array['parent_array'];
            $grand_total_amount = $array['grand_total_amount'];
            $item_details = $array['item_details'];
            $order_items = $array['order_items'];
            $total_on_order = $grand_total_amount;
            $discount_rate = $array['discount_rate'];
            $total_after_discount = $array['total_after_discount'];
            $taxs_result_for_after_discount = taxes($order, $total_after_discount);
            $total_after_discount = $taxs_result_for_after_discount['grand_total_amount'];
            $taxs_result = taxes($order, $grand_total_amount);
            $taxs_amount = $taxs_result['taxs_amount'];
            $taxs_rate = $taxs_result['taxs_rate'];
            $grand_total_amount = $taxs_result['grand_total_amount'];
            $shipping_result_for_after_discount = shippingRule($order, $total_after_discount);
            $total_after_discount = $shipping_result_for_after_discount['grand_total_amount'];
            $shipping_rule_result = shippingRule($order, $grand_total_amount);
            $grand_total_amount = $shipping_rule_result['grand_total_amount'];
            $shipping_rule_rate = $shipping_rule_result['shipping_rule_rate'];
            $selected_tax = Taxs::where('id', $order->tax_and_charges)->first();
            $shipping_rule = ShippingRule::where('id', $order->shipping_rule)->first();

            $activites = Adjustments::where('content_id', $order->id)->where('key', 'PurchaseOrder')->get();
            foreach ($activites as $activity) {
                $user = AdminUser::where('id', $activity->user_id)->first();
                $activity->user_name = $user->name;
            }
            // $total_after_discount = $grand_total_amount - $discount_rate;
            $purchase_receipts = PurchaseReceipts::where('purchase_order_id', $order->id)->get();
            /*This For To get count of purchase receipts in specific PO  -> PR(number)*/
            $order['count_purchase_receipts'] = count($purchase_receipts);
            return view('admin.purchase-orders.details', compact('parent_array', 'total_on_order', 'company_name', 'discount_rate', 'total_after_discount', 'activites', 'selected_tax', 'shipping_rule', 'selected_company', 'selected_warehouse', 'grand_total_amount', 'item_rate', 'shipping_rules', 'taxs', 'companies', 'products', 'order', 'order_items', 'shipping_rule_rate', 'item_details', 'taxs_amount', 'taxs_rate'));
        }
    }

    public function totalAfterDiscount($discount_type, $discount, $total)
    {

        if ($discount_type == 'persentage') {
            $discount = $discount / 100;
            $discount *= $total;
            $total -= $discount;
        } elseif ($discount_type == 'amount') {
            $total -= $discount;
        }
        return $total;
    }

    public function postPurchaseOrder(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('purchase_orders'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($request) {
            $item_id = array_filter($request->input('items'));
            $qty = $request->input('quantity');
            $item_rate = $request->input('rate');
            $total = 0;
            // check that rate and total amount and grand total is correct and there's nothing wrong with them
            if (!isset($item_id)) {
                return response()->json(['data' => 'no_items']);
            }
            foreach ($item_id as $key => $item) {
                $product = Products::where('id', $item)->first();
                if ($product->has_variants == 1) {
                    unset($item_id[$key]);
                    unset($qty[$key]);
                    unset($item_rate[$key]);
                    continue;
                }
                if ($qty[$key] == 0) {
                    continue;
                }
                $product_name = '';
                if ($product) {
                    $product_name = $product->name_en;
                }
                if ($item_rate[$key] != 0) {
                    $total += $item_rate[$key] * $qty[$key];
                    if ($request->has('total_amount')) {
                        if ($request->total_amount[$key] != $qty[$key] * $item_rate[$key]) {
                            return response()->json(['data' => 'total_amount_incorrect']);
                        }
                    }
                    $item_details[] = (object)array('item_name' => $product_name, 'qty' => $qty[$key], 'rate' => $item_rate[$key], 'total_amount' => $qty[$key] * $item_rate[$key]);
                }
            }


            $discount_type = $request->discount_type;
            $discount = floatval($request->discount);
            $total = $this->totalAfterDiscount($discount_type, $discount, $total);

            if ($request->has('tax_and_charges')) {
                $tax = Taxs::where('id', $request->tax_and_charges)->first();
                if ($tax) {
                    if ($tax->type == 'Actual') {
                        $total += $tax->amount;
                    } else {
                        $total += ($tax->rate / 100 * $total);
                    }
                }
            }
            if ($request->has('shipping_rule')) {
                $shipping_rule = ShippingRule::where('id', $request->shipping_rule)->first();
                if ($shipping_rule) {
                    $total += $shipping_rule->rate;
                }
            }
            $total = round($total, 2);
            $grand_total_amount = round($request->grand_total_amount, 2);
            // Validate Grand Total Amount
            if ($request->has('grand_total_amount')) {
                if ($grand_total_amount != $total) {
                    return response()->json(['data' => 'grand_total_amount_incorrect']);
                }
            }
            // Validate Required By Date
            if (strtotime("now") > strtotime($request->required_by_date)) {
                return response()->json(['data' => 'required_by_date_incorret']);
            }

            $purchase_order = new PurchaseOrders;
            $purchase_order->company_id = $request->company_id;
            $purchase_order->required_by_date = date('Y-m-d:h:i:s', strtotime($request->required_by_date));
            $purchase_order->warehouse_id = $request->warehouse_id;
            $purchase_order->shipping_rule = $request->shipping_rule;
            $purchase_order->tax_and_charges = $request->tax_and_charges;
            $purchase_order->discount = $discount;
            $purchase_order->discount_type = $discount_type;
            $purchase_order->status = 'ADDED';
            $purchase_order->grand_total_amount = $total;
            $purchase_order->save();


            $purchase_order_id = $purchase_order->id;
            // Creating PurchaseOrderItems
            foreach ($item_id as $key => $item) {
                $data[] = $purchase_order->purchaseOrder($item_id[$key], $purchase_order_id, $qty[$key], $item_rate[$key]);
            }
            // Activities - Adjustments
            $user = Auth::guard('admin_user')->user();
            $purchase_order->adjustments()->attach($user->id, ['key' => "PurchaseOrder", 'action' => "Added", 'content_name' => $purchase_order->id]);

            $company = Company::where('id', $purchase_order->company_id)->first();
            if ($company) {
                $company_name = $company->name_en;
            } else {
                $company_name = '';
            }
            $date = date('Y-m-d:h:i:s');
            $product_id = $request->product_id;
            // dd($product_id);
            $data = view('admin.purchase-orders.email-modal', compact('product_id', 'company_name', 'purchase_order_id', 'date'))->render();
            return response()->json(['data' => $data]);
        });
    }

    public function sendEmail(Request $request)
    {
        $purchase_order_id = $request->purchase_order_id;
        $email = $request->email;
        if (!$email) {
            return 'false';
        }
        $body = $request->body;
        $company_name = $request->company_name;
        $query = 0;
        $array = $this->purchaseOrderPdf($purchase_order_id, $company_name, $query);
        $location = $array['location'];
        Mail::send(new PurchaseOrderMail($location, $email, $body));
        return 'true';
    }

    public function purchaseOrderPdf($purchase_order_id, $company_name, $query)
    {

        $purchase_order = PurchaseOrders::where('id', $purchase_order_id)->where('is_deleted', 0)->first();
        $taxs_rate = 0;
        $shipping_rate = 0;
        $required_by_date = $purchase_order->required_by_date;
        $shipping_rule = ShippingRule::where('id', $purchase_order->shipping_rule)->first();
        if ($shipping_rule) {
            $shipping_rate = $shipping_rule->rate;
        }
        $tax = Taxs::where('id', $purchase_order->tax_and_charges)->first();
        // dd($purchase_order->tax_and_charges);
        $warehouse = Warehouses::where('id', $purchase_order->warehouse_id)->first();
        $warehouse_name = $warehouse->name;
        $purchase_order_items = $purchase_order->purchaseOrderItems;
        $total = 0;

        foreach ($purchase_order_items as $item) {
            $product = Products::where('id', $item->item_id)->first();
            if ($product) {
                // $item_rate= $this->itemPrice($item->item_id);
                $item_details[] = (object)array('item_name' => $product->name_en, 'qty' => $item->qty, 'rate' => $item->item_rate, 'total_amount' => $item->qty * $item->item_rate);
                $total += $item->qty * $item->item_rate;
            }

        }
        $total_on_order = $total;
        $discount = $purchase_order->discount;
        $discount_type = $purchase_order->discount_type;
        $total_after_discount = $this->totalAfterDiscount($discount_type, $discount, $total);
        $discount_rate = $total - $total_after_discount;
        if ($tax) {
            if ($tax->type == 'Actual') {
                $taxs_rate = $tax->amount;
                $total_after_discount += $tax->amount;
            } else {
                $taxs_rate = ($tax->rate / 100 * $total_after_discount);
                $total_after_discount += ($tax->rate / 100 * $total_after_discount);
            }
        }
        $total_after_discount += $shipping_rate;
        $pdf = PDF::loadView('admin.purchase-orders.pdf', compact('discount', 'discount_type', 'total_after_discount', 'discount_rate', 'item_details', 'purchase_order_id', 'required_by_date', 'company_name', 'shipping_rate', 'taxs_rate', 'total', 'total_on_order', 'query'));
        $fileName = 'PO-' . $purchase_order_id;
        $location = public_path('pdfs/' . $fileName . '.pdf');
        $pdf->save($location);
        $array['location'] = $location;
        $array['filename'] = $fileName;
        return $array;
    }

    public function shippingruleRate()
    {
        $shipping_rule = Input::get('shipping_rule');
        $rule = ShippingRule::where('id', $shipping_rule)->first();
        return Response::json($rule);
    }


    public function downloadFile($id)
    {

        $purchase_order = PurchaseOrders::where('id', $id)->where('is_deleted', 0)->first();
        if ($purchase_order) {
            $company = Company::where('id', $purchase_order->company_id)->first();
        }
        $query = 0;
        if (isset($_GET['query'])) {
            $query = 1;
        };
        $array = $this->purchaseOrderPdf($id, $company->name_en, $query);
        $location = $array['location'];
        $filename = $array['filename'];
        $myFile = $location;
        $headers = ['Content-Type: application/pdf'];
        $newName = 'PO-' . $id . '-' . time() . '.pdf';
        $user = Auth::guard('admin_user')->user();
        if ($query == 1) {
            $purchase_order->adjustments()->attach($user->id, ['key' => "PurchaseOrder", 'action' => "DownloadedPO-WithoutPrices", 'content_name' => $purchase_order->id]);
        } elseif ($query == 0) {
            $purchase_order->adjustments()->attach($user->id, ['key' => "PurchaseOrder", 'action' => "DownloadedPO", 'content_name' => $purchase_order->id]);

        }

        return response()->download($myFile, $newName, $headers);
    }


    public function taxsValue()
    {
        $tax = Input::get('tax');
        $tax_object = Taxs::where('id', $tax)->first();
        return Response::json($tax_object);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function itemPrice($product_id)
    {
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

    public function orderItems($order, $grand_total_amount)
    {
        $grand_total_amount_after_discount = 0;
        $order_items = $order->purchaseOrderItems;
        $item_details = array();
        $parent_ids = array();
        $parent_array = array();
        if (count($order_items) > 0) {
            foreach ($order_items as $order_item) {
                $order_item_object = Products::where('id', $order_item->item_id)->first();
                if ($order_item_object && $order_item_object->is_variant == 1) {
                    $parent_product = Products::where('id', $order_item_object->parent_variant_id)->first();
                    $parent_ids[] = $parent_product->id;
                    $parent_array[$parent_product->name_en][] = (object)array('parent_id' => $parent_product->id, 'item_name' => $order_item_object->name_en, 'item_id' => $order_item_object->id, 'qty' => $order_item->qty, 'rate' => $order_item->item_rate, 'total_amount' => $order_item->qty * $order_item->item_rate);
                    $grand_total_amount += $order_item->item_rate * $order_item->qty;
                    continue;
                }
                $grand_total_amount += $order_item->item_rate * $order_item->qty;
                $item_details[] = (object)array('item_id' => $order_item->item_id, 'qty' => $order_item->qty, 'rate' => $order_item->item_rate, 'total_amount' => $order_item->qty * $order_item->item_rate);
            }

            $discount = $order->discount;
            $discount_type = $order->discount_type;
            $grand_total_amount_after_discount = $this->totalAfterDiscount($discount_type, $discount, $grand_total_amount);

        } else {
            $item_details = [];
        }
        $array['grand_total_amount'] = $grand_total_amount;
        $array['item_details'] = $item_details;
        $array['order_items'] = $order_items;
        $array['total_after_discount'] = $grand_total_amount_after_discount;
        $array['discount_rate'] = $grand_total_amount - $grand_total_amount_after_discount;
        $array['parent_array'] = $parent_array;
        $array['parent_ids'] = $parent_ids;
        return $array;

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::guard('admin_user')->user()->can('purchase_orders'))
        {
            return view('admin.un-authorized');
        }

        $warehouses = Warehouses::where('status', 1)->get();
        $shipping_rules = ShippingRule::where('disabled', 0)->get();
        $taxs = Taxs::where('status', 1)->get();
        $companies = Company::all();
        $products = Products::isVariant()->where('active', 1)->get();
        $order = PurchaseOrders::where('id', $id)->first();
        // dd($order);
        $grand_total_amount = 0;

        $array = $this->orderItems($order, $grand_total_amount);
        $grand_total_amount = $array['grand_total_amount'];
        $item_details = $array['item_details'];
        $order_items = $array['order_items'];
        $parent_array = $array['parent_array'];
        $parent_ids = $array['parent_ids'];


        $taxs_result = taxes($order, $grand_total_amount);
        $taxs_amount = $taxs_result['taxs_amount'];
        $taxs_rate = $taxs_result['taxs_rate'];
        $grand_total_amount = $taxs_result['grand_total_amount'];


        $shipping_rule_result = shippingRule($order, $grand_total_amount);
        $grand_total_amount = $shipping_rule_result['grand_total_amount'];
        $shipping_rule_rate = $shipping_rule_result['shipping_rule_rate'];

        return view('admin.purchase-orders.edit', compact('parent_ids', 'parent_array', 'shipping_rule', 'warehouses', 'selected_tax', 'grand_total_amount', 'item_rate', 'shipping_rules', 'taxs', 'companies', 'products', 'order', 'order_items', 'shipping_rule_rate', 'item_details', 'taxs_amount', 'taxs_rate'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddPurchaseOrderRequest $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('purchase_orders'))
        {
            return view('admin.un-authorized');
        }

        return DB::transaction(function () use ($request, $id) {
            $purchase_order = PurchaseOrders::find($id);
            if ($purchase_order->is_deleted == 1) {
                return redirect('/admin/purchase-orders');
            }
            if ($request->has('items')) {
                $item_id = array_filter($request->input('items'));
            } else {
                return redirect()->back()->withErrors('Select at least one item');
            }

            $qty = $request->input('quantity');
            $rate = $request->input('rate');
            $total = 0;
            // check that rate and total amount and grand total is correct and there's nothing wrong with them
            if (!isset($item_id)) {
                return redirect()->back()->withErrors("There is no items to purchase a receipt");
            }
            foreach ($item_id as $key => $item) {
                // $item_rate= $this->itemPrice($item);
                if (!$qty[$key]) {
                    $qty[$key] = 1;
                }
                $total += $rate[$key] * $qty[$key];

                if ($request->has('total_amount')) {
                    if ($request->total_amount[$key] != $qty[$key] * $rate[$key]) {
                        return redirect()->back()->withErrors('Total amount is in-correct');
                    }
                }

            }
            $discount = intval($request->discount);
            $discount_type = $request->type;
            $total = $this->totalAfterDiscount($discount_type, $discount, $total);

            if ($request->has('tax_and_charges')) {
                $tax = Taxs::where('id', $request->tax_and_charges)->first();
                if ($tax->type == 'Actual') {
                    $total += $tax->amount;
                } else {
                    $total += ($tax->rate / 100 * $total);
                }
            }
            if ($request->has('shipping_rule')) {
                $shipping_rule = ShippingRule::where('id', $request->shipping_rule)->first();
                $total += $shipping_rule->rate;
            }
            $total = round($total, 2);
            if ($request->has('grand_total_amount')) {
                if ($request->grand_total_amount != $total) {
                    return redirect()->back()->withErrors('Grand Total amount is in-correct');
                }
            }

            // dd($purchase_order);
            $purchase_order->company_id = $request->company_id;
            $purchase_order->discount_type = $request->type;
            $purchase_order->discount = $request->discount;
            $purchase_order->warehouse_id = $request->warehouse_id;
            $purchase_order->required_by_date = date('Y-m-d:h:i:s', strtotime($request->required_by_date));
            $purchase_order->shipping_rule = $request->shipping_rule;
            $purchase_order->tax_and_charges = $request->tax_and_charges;
            // $purchase_order->status = 'ADDED';
            $purchase_order->grand_total_amount = $total;
            $purchase_order->save();
            $purchase_order_id = $purchase_order->id;
            $purchase_order->deattachPurchaseOrder($purchase_order_id);
            foreach ($item_id as $key => $item) {
                // $item_rate = $this->itemPrice($item_id[$key]);
                $purchase_order->purchaseOrder($item_id[$key], $purchase_order_id, $qty[$key], $rate[$key]);
            }

            $user = Auth::guard('admin_user')->user();
            $purchase_order->adjustments()->attach($user->id, ['key' => "PurchaseOrder", 'action' => "Edited", 'content_name' => $purchase_order->id]);


            return redirect('admin/purchase-orders')->with('success', 'Order Created Successfully');
        });
    }


    public function delete($id)
    {
        if (!Auth::guard('admin_user')->user()->can('purchase_orders'))
        {
            return view('admin.un-authorized');
        }

        $purchase_order = PurchaseOrders::where('id', $id)->where('is_deleted', 0)->first();
        if ($purchase_order) {
            $purchase_receipts = PurchaseReceipts::where('purchase_order_id', $purchase_order->id)->get();
            foreach ($purchase_receipts as $purchase_receipt) {
                if ($purchase_receipt && $purchase_receipt->status == 2) {
                    return 'cancel purchase receipt';
                } else {
                    $purchase_invoice = PurchaseInvoice::where('purchase_receipt_id', $purchase_receipt->id)->first();
                    if ($purchase_invoice) {
                        $payment = Payment::where('invoice_id', $purchase_invoice->id)->first();
                        if ($payment) {
                            $payment->update(['is_deleted' => 1]);
                        }
                        $purchase_invoice->update(['is_deleted' => 1]);
                    }
                    $purchase_receipt->update(['is_deleted' => 1]);
                }
            }

            $purchase_order->is_deleted = 1;
            $purchase_order->save();
            // dd($purchase_order->is_deleted);
            return response()->json(true);
        }
        return response()->json(false);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
