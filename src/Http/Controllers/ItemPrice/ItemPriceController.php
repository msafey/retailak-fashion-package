<?php

namespace App\Http\Controllers\ItemPrice;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemPriceRequest;
use App\Models\ItemPrice;
use App\Models\PriceList;
use App\Models\Products;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;
use Yajra\Datatables\Datatables;


class ItemPriceController extends Controller
{

	public function index(){

	}


    public function itemPriceView($product){

    	$product_data = Products::find($product);
    	if($product_data){
    	    return view('admin.item_price.index',compact('product_data','product'));
    	}else{
    	    return redirect('admin/products')->withError("There's no product with this id");
    	}
    }


    public function standard_rate_list($product)
    {
        $data = ItemPrice::where('product_id',$product)->get();
        foreach($data as $dat){
           	$price_list = PriceList::where('id',$dat['price_list_id'])->first();
            if($price_list){
        	       $dat['price_list']= $price_list->price_list_name;
            }else{
                $dat['price_list']='';
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
        $price_list = PriceList::all();
        return view('admin.item_price.add',compact('price_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemPriceRequest $request)
    {
    		$requestData=$request->except(['_token']);
            $product = Products::find($request->product_id);
            if($product == null){
                return redirect()->back()->withErrors('You Assign Price To Product Does Not Exist');
            }

            $item_price = ItemPrice::where('price_list_id',$request->price_list_id)->where('product_id',$request->product_id)->first();
            if(!$item_price){
                $item_price = new ItemPrice;
                $item_price->price_list_id = $request->price_list_id;
                $item_price->product_id = $request->product_id;
                $item_price->rate = $request->rate;
                $item_price->save();
            	// $item_price->update($requestData);
            }else{
                return redirect()->back()->withErrors('You Already Have Item Price For This Price List');
            }
       	return redirect('/admin/products/'.$request->product_id.'/standard-rate')->with('success','Item Price Created Successfully');
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
    	$price_list=PriceList::all();
    	$product_id = $_GET['product_id'];
    	$product = Products::find($product_id);
    	if($product == null){
    	    return redirect()->back()->withErrors('You Edit Price To Product Does Not Exist');
    	}

    	$item_price = ItemPrice::find($id);

    	return view('admin.item_price.edit',compact('product_id','item_price','price_list'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EditProjectRequest $request
     * @param  ProjectData  $project
     * @return \Illuminate\Http\Response
     */
    public function update(ItemPriceRequest $request,$id)
    {
            $requestData=$request->except(['_token']);
            $product = Products::find($request->product_id);
            if($product == null){
                return redirect()->back()->withErrors('You Assign Price To Product Does Not Exist');
            }
            $item_price = ItemPrice::where('id',$id)->where('product_id',$request->product_id)->first();
            if($item_price){
                $item_price->price_list_id = $request->price_list_id;
                $item_price->product_id = $request->product_id;
                $item_price->rate = $request->rate;
                $item_price->save();
            }
// ItemPrice::update(['price_list_id'=>$request->price_list_id,'product_id'=>$request->product_id,'rate'=>$request->rate]);
            // $item_price = ItemPrice::where('price_list_id',$request->price_list_id)->where('product_id',$request->product_id)->first();
            // if(!$item_price){
            // }else{
return redirect('admin/products/'.$request->product_id.'/standard-rate')->with('success','ItemPrice Created Successfully');
    }
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

