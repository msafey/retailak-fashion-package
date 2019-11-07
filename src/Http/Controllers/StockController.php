<?php

namespace App\Http\Controllers;

use App\Models\ItemWarehouse;
use App\Models\Products;
use App\Models\Stocks;
use App\Models\Suppliers;
use App\Models\Warehouses;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;
use Yajra\Datatables\Datatables;


class StockController extends Controller
{
    public function index(){
    }

    public function manageProductItems($product){
        $product_data = Products::find($product);
        if($product_data){
             if($product_data->is_bundle == 1){
                 return redirect()->back()->withErrors('This product is bundle');
             }
            return view('admin.stocks.index',compact('product'));
        }else{
            return redirect('admin/products')->withError("There's no product with this id");
        }
    }

    public function itemsList($product)
    {
        $data = Stocks::where('product_id',$product)->where('active',1)->get();
        foreach($data as $dat){


            if(!$dat['moved_from_warehouse']){
                $dat['moved_from_warehouse'] = '---';
            }


            if(!$dat['destination_warehouse']){
                $dat['destination_warehouse'] = '---';
            }
            if(!$dat['purchase_order_reference_id']){
                $dat['purchase_order_reference_id'] = '---';

            }
        }
        return DataTables::of($data)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Suppliers::all();
        $warehouses = Warehouses::where('status',1)->get();
        return view('admin.stocks.add',compact('suppliers','warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request){
            $stock= new Stocks;
            $product = Products::find($request->product);
            if($product == null){
                return redirect()->back()->withErrors('You Assign Stock To Product Does Not Exist');
            }
            $warehouse = Warehouses::where('id',$request->warehouse_id)->first();
            $stock->stock_qty = $request->stock_qty;
            // $stock->price = $request->price;
            $stock->product_id = $request->product;
            $stock->supplier_id = $request->supplier_id;
            $stock->warehouse_id = $request->warehouse_id;
            $stock->destination_warehouse = $warehouse->name;
            $stock->status = 'ADDED';
            $stock->save();

       $item_warehouse = ItemWarehouse::where('product_id',$product->id)->where('warehouse_id',$request->warehouse_id)->first();
       if($item_warehouse){
        $item_warehouse->projected_qty += $request->stock_qty;
        $item_warehouse->save();
        }else{
            $item_warehouse = new ItemWarehouse;
            $item_warehouse->product_id = $request->product;
            $item_warehouse->item_code = $product->item_code;
            $item_warehouse->projected_qty = $request->stock_qty;
            $item_warehouse->warehouse_id = $request->warehouse_id;
            $item_warehouse->save();
        }


            return redirect($stock->path($request->product))->with('success','Stock Created Successfully');
        });
    }

    // GET TOTAL STOCK QUANTITY AFTER ADDING AND SUBTRACTING THE MOVED STOCKS
    public function totalStockQuantity($warehouse,$product){
        if(isset($warehouse)){
            // $product = Products::find($product);
            // $stocks_added = Stocks::where('warehouse_id',$warehouse)->where('product_id',$product)->where('status','ADDED')->where('active',1)->get();
            // $stock_qty=0;
            // foreach($stocks_added as $add){
            //     $stock_qty += $add->stock_qty;
            // }
            // $stocks_moved = Stocks::where('warehouse_id',$warehouse)->where('product_id',$product)->where('status','MOVED')->where('active',1)->get();
            // foreach($stocks_moved as $move){
            //     if($stock_qty - $move->stock_qty >= 0){
            //         $stock_qty -= $move->stock_qty;
            //     }
            // }
            $item_warehouse = ItemWarehouse::where('product_id',$product)->where('warehouse_id',$warehouse)->first();
            if($item_warehouse){
                $stock_qty = $item_warehouse->projected_qty;
            }else{
                $stock_qty = 0;
            }
        }

        return $stock_qty;
    }


    public function isAllowed($warehouse,$moved_qty,$product){
        $total_stock_quantity = $this->totalStockQuantity($warehouse,$product);
        if($total_stock_quantity >= $moved_qty){
            return 'true';
        }
        return redirect()->back()->withErrors("Total stock quantity is not enough");
    }





    // GET ORIGINAL WAREHOUSE STOCK QUANTITY
    public function warehouses_qty(){
        $warehouse = Input::get('warehouse');
        $product = Input::get('product');
        $stock_qty = $this->totalStockQuantity($warehouse,$product);
        if(!$stock_qty){
            $stock_qty = 0;
        }
        return Response::json($stock_qty);
    }
    // Destination Warehouses
    public function dest_warehouse(){
        $warehouse = Input::get('warehouse');
        $dest_warehouse = Warehouses::where('id','!=',$warehouse)->get();
        return Response::json($dest_warehouse);
    }


    // GET MOVE STOCKS VIEW
    public function moveStocks(){
        $warehouses = Warehouses::all();
        return view('admin.stocks.move',compact('warehouses'));
    }

    //
    public function storeMovedStocks(Request $request){
        // dd($request->all());
        $response = $this->isAllowed($request->warehouse,$request->moved_qty,$request->product);
            if($response == 'true'){
                $product = Products::find($request->product);
                if($product == null){
                    return redirect()->back()->withErrors('You Assign Stock To Product Does Not Exist');
                }
                $current_warehouse = Warehouses::where('id',$request->warehouse)->first();
                $destination_warehouse = Warehouses::where('id',$request->dest_warehouse)->first();

                $stock= new Stocks;
                $stock->stock_qty = $request->moved_qty;
                $stock->product_id = $request->product;
                $stock->warehouse_id = $request->warehouse;
                $stock->moved_from_warehouse = $current_warehouse->name;
                $stock->destination_warehouse = $destination_warehouse->name;
                $stock->status = 'MOVED';
                $stock->save();

                $stock= new Stocks;
                $stock->stock_qty = $request->moved_qty;
                $stock->product_id = $request->product;
                $stock->warehouse_id = $request->dest_warehouse;
                $stock->moved_from_warehouse = $current_warehouse->name;
                $stock->destination_warehouse = $destination_warehouse->name;
                $stock->status = 'ADDED';
                $stock->save();

                $current_item_warehouse =ItemWarehouse::where('product_id',$request->product)->where('warehouse_id',$request->warehouse)->first();
                if($current_item_warehouse){
                    $current_item_warehouse->projected_qty -= $request->moved_qty;
                    $current_item_warehouse->save();

                    $destination_item_warehouse = ItemWarehouse::where('product_id',$request->product)->where('warehouse_id',$request->dest_warehouse)->first();
                    if($destination_item_warehouse){
                        $destination_item_warehouse->projected_qty += $request->moved_qty;
                        $destination_item_warehouse->save();
                    }else{
                        $item_warehouse = new ItemWarehouse;
                        $item_warehouse->product_id = $request->product;
                        $item_warehouse->item_code = $product->item_code;
                        $item_warehouse->projected_qty = $request->moved_qty;
                        $item_warehouse->warehouse_id = $request->dest_warehouse;
                        $item_warehouse->save();
                    }
                }

            }else{
                return $response;
            }

        return redirect($stock->path($request->product))->with('success','Stock MOVED Successfully');

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
     * @param  ProjectData  $project
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        // $survey_item = SurveyItems::find($id);
        // $answers = unserialize($survey_item->answers);
        // if($answers == ""){
        //     $answers=[];
        // }
        // $languages = Language::where('status',1)->get();
        // return view('admin.survey_items.edit',compact('survey_item','answers','languages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EditProjectRequest $request
     * @param  ProjectData  $project
     * @return \Illuminate\Http\Response
     */
    // public function update(SurveyItemRequest $request,$id)
    // {
    //         $survey_item = SurveyItems::find($id);

    //     return DB::transaction(function () use ($request,$survey_item) {

    //         $survey_item->title=$request->title;
    //         $survey_item->type = $request->type;

    //        if($request->type == 1){
    //             $survey_item->answers=serialize("");
    //         }else{
    //             // array_filter This remove nulls from the array
    //         $request->answers=array_filter($request->answers);
    //         // Then check if the request answers is empty?
    //         if(empty($request->answers)){
    //            return redirect()->back()->withErrors('Please write answers to question');
    //         }
    //         $survey_item->answers=serialize($request->answers);
    //         }

    //         $survey_item->status = 1;
    //         $survey_item->survey_id=$request->survey;
    //         $survey_item->save();

    //         $survey_item->adjustments()->attach(Auth::id(), ['key' =>"Survey's Item", 'action' =>"Edited",'content_name'=>$survey_item->title]);

    //         return redirect($survey_item->path($request->survey))->with('success','Question Updated Successfully');
    //     });
    // }




    // public function delete($id){
    //     return DB::transaction(function () use ($id) {
    //         $survey_item = SurveyItems::find($id);

    //         $survey_item->adjustments()->attach(Auth::id(), ['key' =>"Survey's Item", 'action' =>"Deleted",'content_name'=>$survey_item->title]);

    //         $survey_item->delete();
    //         return 'success';
    //     });
    // }
}
