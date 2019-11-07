<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\ProductBundle;
use App\Models\ProductBundleItem;
use App\Models\Products;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;
use Yajra\Datatables\Datatables;

class ProductBundleController extends Controller
{
     public function manageProductBundles($product){
        $product_data = Products::find($product);
        if($product_data->is_bundle == 0){
            return redirect()->back()->withErrors('This product is not bundle product');
        }
       return view('admin.products.bundle_index',compact('product'));
    }

    public function itemsList($product)
    {
        $data = ProductBundle::where('product_id',$product)->get();
        return DataTables::of($data)->make(true);
    }



    public function getItems(){
        $item_group = Input::get('item_group');
        $items = Products::where('item_group',$item_group)->where('is_bundle',0)->get();
        if(!$items){
            $items = [];
        }
        return Response::json($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createProductBundleView()
    {
        $products = Products::where('active',1)->where('is_bundle',0)->get();

        $product = Input::get('product');
        if(isset($product)){
            $product_data = Products::find($product);
            if($product_data->is_bundle == 0){
                return redirect()->back()->withErrors('This product is not bundle product');
            }
            $bundle_data = ProductBundle::where('product_id',$product)->first();
            if($bundle_data){
                    $bundle_meta = ProductBundleItem::where('bundle_id',$bundle_data->id)->get();
            }
            // dd($bundle_meta);
            if(!$bundle_data){
                $bundle_data = new ProductBundle;
                $bundle_data->product_id = $product;
                $bundle_data->save();
                $bundle_meta = [];
            }
            $bundle_id = $bundle_data->id;
        }
            // $products = [];
        return view('admin.products.addbundle',compact('bundle_data','bundle_meta','products','bundle_id'));
    }


    public function create()
    {
        // $item_groups = Categories::all();
        // // $products = Products::where('is_bundle',1)->get();
        // if(!$item_groups){
        //     $item_groups=[];
        // }
        //     $products = [];
        // return view('admin.products.addbundle',compact('item_groups','products','bundle_d'));
    }

    public function updateproductbundle(Request $request,$bundle_id){

        return DB::transaction(function () use ($request,$bundle_id) {
            $product = Products::find($request->product);
            if($product == null){
                return redirect()->back()->withErrors('You Assign Stock To Product Does Not Exist');
            }
            if($product->is_bundle == 0 ){
                return redirect()->back()->withErrors('This Product is not bundle product');
            }
            $bundle =ProductBundle::updateOrCreate(['id'=>$bundle_id,'product_id'=>$request->product],['name'=>$request->name,'description'=>$request->description]);
            // $bundle->name = $request->name;
            // $bundle->description = $request->description;
            // $bundle->item_group = $request->item_group;
            // $bundle->product_id = $request->product;
            // $bundle->save();
            if($request->has('items')){
                $item_id = array_filter($request->input('items'));
                $qty = $request->input('qty');
            }else{
                $bundle->deattachBundle($bundle_id);
            }

            if(isset($item_id)){
                foreach ($item_id as $key => $item) {
                    $bundle->deattachBundle($bundle_id);
                    $bundle->bundle($item_id[$key], $bundle_id, $qty[$key]);
                }
            }
           return redirect('admin/products')->with('success','Bundle Created Successfully');
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            return DB::transaction(function () use ($request) {
                $product = Products::find($request->product);
                if($product == null){
                    return redirect()->back()->withErrors('You Assign Stock To Product Does Not Exist');
                }
                if($product->is_bundle == 0 ){
                    return redirect()->back()->withErrors('This Product is not bundle product');
                }
                $bundle =ProductBundle::updateOrCreate(['product_id'=>$request->product],['name'=>$request->name,'description'=>$request->description]);
                // $bundle->name = $request->name;
                // $bundle->description = $request->description;
                // $bundle->item_group = $request->item_group;
                // $bundle->product_id = $request->product;
                // $bundle->save();
                $item_id = array_filter($request->input('items'));
                $qty = $request->input('qty');
                $bundle_id = $bundle->id;
                if(isset($item_id)){
                    foreach ($item_id as $key => $item) {
                        $bundle->bundle($item_id[$key], $bundle_id, $qty[$key]);
                    }
                }
                // dd(1);
               return redirect($bundle->path($request->product))->with('success','Bundle Created Successfully');
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
        $item_groups = Categories::all();
        $products = Products::where('is_bundle',0)->get();
        $bundle = ProductBundle::find($id);
        $bundle_item = $bundle->productBundleItems;
        return view('admin.products.editbundle',compact('bundle','bundle_item','item_groups','products'));
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



    public function delete($id){
        return DB::transaction(function () use ($id) {
            $bundle = ProductBundle::find($id);
            if($bundle){
                $bundle->productBundleItems()->delete();
            }

            $bundle->delete();
            return 'success';
        });
    }

}
