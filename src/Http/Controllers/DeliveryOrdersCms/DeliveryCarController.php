<?php

namespace App\Http\Controllers\DeliveryOrdersCms;

use App\Models\DeliveryCars;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddDeliveryCarRequest;
use Illuminate\Http\Request;
use Auth;

use Yajra\Datatables\Datatables;


class DeliveryCarController extends Controller
{

    public function index()
    {
        return view('admin.delivery_cars.index');
    }


    public function carsList() {
        $delivery_cars = DeliveryCars::all();
        return Datatables::of($delivery_cars)->make(true);
    }

    public function create()
    {
        return view('admin.delivery_cars.add');
    }


    public function store(AddDeliveryCarRequest $request)
    {
        $delivery_car = DeliveryCars::create($request->all());
        $user = Auth::guard('admin_user')->user();

               $delivery_car->adjustments()->attach($user->id, ['key' =>"DeliveryCar", 'action' =>"Added",'content_name'=>$delivery_car->title]);


        return redirect('admin/delivery/cars')->with('success','Car Created Successfully');
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $delivery_car = DeliveryCars::find($id);
        return view('admin.delivery_cars.edit',compact('delivery_car'));
    }


    public function update(AddDeliveryCarRequest $request, $id)
    {
      $requestData = $request->except(['_method','_token']);
        $delivery_car =DeliveryCars::where('id',$id)->first();
        // dd($delivery_car);
        $delivery_car->update($requestData);
        $user = Auth::guard('admin_user')->user();
        // dd($user);
        $delivery_car->adjustments()->attach($user->id, ['key' =>"DeliveryCar", 'action' =>"Edited",'content_name'=>$delivery_car->title]);
        return redirect('admin/delivery/cars')->with('success','Car Updated Successfully');
    }


    public function delete($id)
    {
        $delivery_car = DeliveryCars::find($id);
        if($delivery_car){
            $user = Auth::guard('admin_user')->user();

                   $delivery_car->adjustments()->attach($user->id, ['key' =>"DeliveryCar", 'action' =>"Deleted",'content_name'=>$delivery_car->title]);


            $delivery_car->delete();
            return response()->json(true);

        }

                return response()->json(false);


    }
}
