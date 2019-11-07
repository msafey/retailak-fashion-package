<?php

namespace App\Http\Controllers\warehouses;

use App\Models\District;
use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseRequest;
use App\Models\ItemWarehouse;
use App\Models\OrderItems;
use App\Models\Products;
use App\Models\PurchaseOrdersItems;
use App\Models\Stocks;
use App\Models\Warehouses;
use App\Models\LogStock;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;

class WarehousesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('warehouses'))
        {
            return view('admin.un-authorized');
        }
        return view('admin.warehouses.index');
    }

    public function warehousesList()
    {
        if (!Auth::guard('admin_user')->user()->can('warehouses'))
        {
            return view('admin.un-authorized');
        }
        $auth_user = Auth::guard('admin_user')->user();
        if ($auth_user->hasRole('store_admin')) {
            $warehouse = $auth_user->warehouse;
            $warehouses = Warehouses::where('id', $warehouse->id)->get();
        } else {
            $warehouses = Warehouses::all();
        }
        foreach ($warehouses as $warehouse) {
            $district_names = [];
            $wareh = json_decode($warehouse->district_id);
            foreach ($wareh as $w) {
                $district = District::where('id', $w)->first();
                if ($district) {
                    $district_names[] = $district->district_en;
                }
            }
            $warehouse['district_id'] = $district_names;
        }
        return Datatables::of($warehouses)->make(true);
    }

    public function details($id)
    {
        if (!Auth::guard('admin_user')->user()->can('warehouses'))
        {
            return view('admin.un-authorized');
        }
        $warehouse = Warehouses::where('id', $id)->first();
        return view('admin.warehouses.details', compact('id', 'warehouse'));
    }

    public function detailsList()
    {
        if (!Auth::guard('admin_user')->user()->can('warehouses'))
        {
            return view('admin.un-authorized');
        }

        $id = $_GET['warehouse_id'];
        $items_warehouse = ItemWarehouse::where('warehouse_id', $id)->orderBy('product_id', 'desc')->get();
        foreach ($items_warehouse as $item) {
            $product = Products::where('id', $item->product_id)->withTrashed()->first();
            $purchase_order_item = PurchaseOrdersItems::where('item_id', $item->product_id)->get();
            if (count($purchase_order_item) > 0) {
                $item['count_of_po'] = count($purchase_order_item);
            } else {
                $item['count_of_po'] = 0;
            }

            $sales_order_item = OrderItems::where('item_id', $item->product_id)->get();
            if (count($sales_order_item) > 0) {
                $item['count_of_so'] = count($sales_order_item);
            } else {
                $item['count_of_so'] = 0;
            }

            if ($product) {
                $item['product_name'] = $product->name_en . '-' . $product->name;
            }
        }
        return Datatables::of($items_warehouse)->make(true);
    }

    public function notSelectedDistricts($warehouse_id)
    {

        $warehouses = Warehouses::all();
        $selected_districts = array();
        foreach ($warehouses as $warehouse) {
            $district_names = [];
            $wareh = json_decode($warehouse->district_id);
            foreach ($wareh as $w) {
                $selected_districts[] = $w;
            }
        }
        // dd($selected_districts);
        $districts = District::where('active', 1)->whereNotIn('id', $selected_districts)->get();
        if ($warehouse_id != 0) {
            $warehouse = Warehouses::where('id', $warehouse_id)->where('status', 1)->first();
            if ($warehouse) {
                $warehouse_districts = json_decode($warehouse->district_id);
                foreach ($warehouse_districts as $dis) {
                    $districts[] = District::where('id', $dis)->first();
                }
            }
        }
        return $districts;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('warehouses'))
        {
            return view('admin.un-authorized');
        }
        $districts = $this->notSelectedDistricts(0);
        // $districts = District::where('active',1)->get();
        return view('admin.warehouses.add', compact('districts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(WarehouseRequest $request)
    {
        if (!Auth::guard('admin_user')->user()->can('warehouses'))
        {
            return view('admin.un-authorized');
        }

        $warehouse = new Warehouses;
        $warehouse->status = 1;
        $warehouse->name = $request->name;
        $warehouse->name_en = $request->name_en;
        $warehouse->district_id = json_encode($request->input('district_id'));
        $warehouse->description = $request->description;
        $warehouse->description_en = $request->description_en;
        $warehouse->warehouse_code = $request->warehouse_code;
        $warehouse->save();

        $user = Auth::guard('admin_user')->user();
        $warehouse->adjustments()->attach($user->id, ['key' => "Warehouse", 'action' => "Added", 'content_name' => $warehouse->name]);
        return redirect($warehouse->path())->with('success', 'Warehouse Created Successfully');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::guard('admin_user')->user()->can('warehouses'))
        {
            return view('admin.un-authorized');
        }

        $warehouse = Warehouses::find($id);
        // $districts = District::where('active',1)->get();
        $districts = $this->notSelectedDistricts($warehouse->id);
        $selectedDistricts = json_decode($warehouse->district_id);
        return view('admin.warehouses.edit', compact('warehouse', 'selectedDistricts', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(WarehouseRequest $request, $id)
    {
        if (!Auth::guard('admin_user')->user()->can('warehouses'))
        {
            return view('admin.un-authorized');
        }

        $warehouse = Warehouses::find($id);
        $requestData = $request->except(['district_id']);
        $warehouse->district_id = json_encode($request->input('district_id'));
        $warehouse->save();

        $warehouse->update($requestData);
        $user = Auth::guard('admin_user')->user();

        $warehouse->adjustments()->attach($user->id, ['key' => "Warehouse", 'action' => "Edited", 'content_name' => $warehouse->name]);

        return redirect($warehouse->path())->with('success', 'Warehouse Updated Successfully');
    }

    public function Status($id)
    {
        if (!Auth::guard('admin_user')->user()->can('warehouses'))
        {
            return view('admin.un-authorized');
        }

        $status = Warehouses::where('id', $id)->select('status')->first();
        if ($status->status == 0) {
            Warehouses::where('id', $id)->update(['status' => '1']);
            return redirect('admin/warehouses')->with('Warehouse Activated Successfully');
        } else {
            Warehouses::where('id', $id)->update(['status' => '0']);
            return redirect('admin/warehouses')->with('success', 'Warehouse  De-Activated Successfully');

        }
    }

    public function importView()
    {
        $warehouses = Warehouses::where('status', 1)->get();
        return view('admin.warehouses.manage', compact('warehouses'));
    }

    public function import(Request $request)
    {
        if ($request->hasFile('import_file')) {
            $results = Excel::load($request->file('import_file')->getRealPath());
            $items_array = [];
            $items = [];
            if (count($results->toArray()) > 0) {
                foreach ($results->toArray() as $key => $row) {
                    if (!array_key_exists('sku', $row) || !array_key_exists('qty', $row)) {
                        continue;
                    } else {
                        $itemCode = trim($row['sku']);
                        $product = Products::where('item_code', $itemCode)->first();
                        if (!$product || $row['qty'] < 0 || $row['qty'] == 0) {
                            continue;
                        }
                        $items_array['name'] = $product->name_en;
                        $items_array['sku'] = $product->item_code;
                        // $items_array['item_id'] = $row['item'];
                        $items_array['qty'] = $row['qty'];
                        $items[] = $items_array;
                    }
                }
            }

            $file = $request->import_file->getClientOriginalName();
            $request->import_file->move(public_path('/admin/uploaded/stocks'), $file);

            LogStock::create(['admin_id' => Auth::guard('admin_user')->user()->id,
                'fileName' => $request->import_file->getClientOriginalName()]);
        }
        return response()->json(['data' => $items]);
    }

    public function importStocks(Request $request)
    {

        return DB::transaction(function () use ($request) {
            $items_data = json_decode($request->items_data);
            $request_type = $request->request_type;
            $warehouse_to = $request->warehouse_to;

            $total = 0;
            $insufficient = [];
            $sufficient = [];
            $failed = 0;
            $imported = 0;
            if ($request_type != 'add') {
                $warehouse_from = $request->warehouse_from;
            }
            if (is_array($items_data) && count($items_data) > 0) {

                foreach ($items_data as $item) {

                    if (!isset($item->item_id)) {
                        $insufficient[$total] = ['error' => 'item id is not given', 'item_id' => 0];
                        $failed += 1;
                        continue;
                    }

                    if (!isset($item->qty)) {
                        $insufficient[$total] = ['error' => 'Qty stock of product with id ' . $item->item_id . ' is not given', 'item_id' => $item->item_id];
                        $failed += 1;
                        continue;
                    }
                    if ($item->qty < 0) {
                        $insufficient[$total] = ['error' => 'Qty stock of product with id ' . $item->item_id . ' is negative value', 'item_id' => $item->item_id];
                        $failed += 1;
                        continue;
                    }
                    $total += 1;
                    $product_exist = Products::where('item_code', $item->item_id)->first();
                    if ($product_exist && $product_exist->has_variants == 1) {
                        $insufficient[$total] = ['error' => 'item code is a parent , should select variations products', 'item_id' => $item->item_id];
                        $failed += 1;
                        continue;
                    }


                    if ($request_type == 'add') {

                        if ($product_exist) {

                            Stocks::create(['stock_qty' => $item->qty, 'product_id' => $item->item_id, 'warehouse_id' => $warehouse_to, 'status' => 'ADDED']);
                            $item_warehouse = ItemWarehouse::where('item_code', $item->item_id)->where('warehouse_id', (int)$warehouse_to)->first();
                            if ($item_warehouse) {

                                $projected_qty = $item_warehouse->projected_qty + $item->qty;
                                $item_warehouse->projected_qty = $projected_qty;
                                $item_warehouse->save();
                            } else {

                                $myProduct = Products::where('item_code', $product_exist->item_code)->first();
                                ItemWarehouse::create(['product_id' => $myProduct->id, 'item_code' => $product_exist->item_code, 'projected_qty' => $item->qty, 'warehouse_id' => $warehouse_to]);
                            }
                            $sufficient[$total] = ['success' => $item->qty . ' Qty of item' . $item->item_id . ' added successfully to warehouse' . $warehouse_to, 'item_id' => $item->item_id];
                            $imported += 1;
                        } else {
                            $insufficient[$total] = ['error' => 'There\'s no product with id of ' . $item->item_id, 'item_id' => $item->item_id];
                            $failed += 1;
                            continue;
                        }
                    } else {

                        if ($product_exist) {

                            $item_warehouse = ItemWarehouse::where('item_code', $item->item_id)->where('warehouse_id', $warehouse_from)->first();
                            if ($item_warehouse) {
                                if ($item_warehouse->projected_qty >= $item->qty) {
                                    $myProduct = Products::where('item_code', $product_exist->item_code)->first();
                                    $destination_item_warehouse = ItemWarehouse::where('product_id', $myProduct->id)->where('warehouse_id', $warehouse_to)->first();
                                    if ($destination_item_warehouse) {
                                        $destination_item_warehouse->projected_qty += $item->qty;
                                    } else {
                                        ItemWarehouse::create(['product_id' => $item->item_id, 'item_code' => $product_exist->item_code, 'projected_qty' => $item->qty, 'warehouse_id' => $warehouse_to]);
                                    }
                                    $item_warehouse->projected_qty -= $item->qty;
                                    $item_warehouse->save();
                                    $imported += 1;
                                    $sufficient[$total] = ['success' => $item->qty . ' Qty of item ' . $item->item_id . ' Transfered successfully from warehouse ' . $warehouse_from . ' to warehouse ' . $warehouse_to, 'item_id' => $item->item_id];

                                } else {
                                    $insufficient[$total] = ['error' => 'The stock of source warehouse of id ' . $item_warehouse->warehouse_id . ' is not enough', 'item_id' => $item->item_id];
                                    $failed += 1;
                                    continue;
                                }
                            }
                        } else {

                            $insufficient[$total] = ['error' => 'There\'s no product with id of ' . $item->item_id, 'item_id' => $item->item_id];
                            $failed += 1;
                            continue;
                        }
                    }
                }
            }
            // store current Admin who Imported the file
            $admin_logs = new LogStock();
            $admin_logs->admin_id = Auth::guard('admin_user')->user()->id;
            $admin_logs->fileName = $request->import_file->getClientOriginalName();
            $check = $admin_logs->save();
            return view('admin.warehouses.imported_results', compact('imported', 'total', 'failed', 'insufficient', 'sufficient'));
        });
    }

    public function importStocksList(Request $request)
    {
        $array = array();
        if (isset($request->items)) {
            $array = json_decode($request->items);
        }
        $array = collect($array);
        // return $array;
        return Datatables::of($array)->make(true);
    }

    public function changeToDefault($id)
    {
        $warehouse = Warehouses::findOrFail($id);
        if ($warehouse) {
            $default = Warehouses::where('default_warehouse', 1)->get();
            if (count($default) > 0) {
                foreach ($default as $exist) {
                    $exist->default_warehouse = 0;
                    $exist->save();
                }
            }
            $warehouse->default_warehouse = 1;
            $warehouse->save();
            $user = Auth::guard('admin_user')->user();
            $warehouse->adjustments()->attach($user->id, ['key' => "Warehouses", 'action' => "Default Warehouse", 'content_name' => $warehouse->name_en]);
            return 'true';
        } else {
            return 'false';
        }
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
