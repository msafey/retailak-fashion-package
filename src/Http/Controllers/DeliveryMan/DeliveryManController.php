<?php

namespace App\Http\Controllers\DeliveryMan;

use App\Models\DeliveryCars;
use App\Models\Delivery_Man;
use App\Models\District;
use App\Http\Controllers\utilitiesController;
use App\Http\Requests\AddDeliveryManRequest;
use App\Http\Requests\EditDeliveryManRequest;
use DB;
use File;
use Image;
use Input;
use Session;
use Yajra\Datatables\Datatables;


class DeliveryManController extends utilitiesController
{
    public function index()
    {
        return view('admin/delivery-man/list');
    }

    public function deliverymanlist()
    {
        $inactive=0;
        if(isset($_GET['inactive'])){
                $inactive=$_GET['inactive'];
        }

        if($inactive == 1){
            $delivery_man = DB::table('delivery__men')->where('status',0)->orderBy('id', 'DESC')->get();
        }else{
            $delivery_man = DB::table('delivery__men')->where('status',1)->orderBy('id', 'DESC')->get();
        }

        return Datatables::of($delivery_man)->make(true);
    }

    public function create()
    {
        $districts = District::all();
        $delivery_cars = DeliveryCars::all();
        return view('admin.delivery-man.add',compact('districts','delivery_cars'));
    }


        public function store(AddDeliveryManRequest $request)
    {
        // dd($request->all());

                $name = $request->input('name');
                $email = $request->input('mobile').'@gmail.com';
                $gender = $request->input('gender');
                $date_of_birth =  date('Y-m-d', strtotime($request->input('date_of_birth')));
                $date_of_joining =  date('Y-m-d', strtotime($request->input('date_of_joining')));
                $delivery_man = new  Delivery_Man;
                $delivery_man->name = $request->input('name');
                $delivery_man->district_id = $request->input('district_id');
                $delivery_man->route = $request->input('route');
                $delivery_man->email = $request->input('mobile').'@gmail.com';
                $delivery_man->gender = $request->input('gender');
                $delivery_man->delivery_car_id = $request->input('delivery_car_id');
                $delivery_man->date_of_birth =  date('Y-m-d', strtotime($request->input('date_of_birth')));
                $delivery_man->date_of_joining =  date('Y-m-d', strtotime($request->input('date_of_joining')));
                $delivery_man->mobile = $request->input('mobile');
                $delivery_man->token = md5(rand(000, 9999));
                $delivery_man->password = md5($request->input('password'));
                $delivery_man->save();
        return redirect('admin/delivery/man')->with('success','Delivery Man  Created Successfully');
    }

    public function edit($id)
    {
        $delivery_man = Delivery_Man::findOrFail($id);
        $districts = District::all();
        $delivery_cars = DeliveryCars::all();
        return view('/admin/delivery-man/edit', compact('delivery_man','districts','delivery_cars'));

    }

    public function update(EditDeliveryManRequest $request, $id){

        $delivery_man = Delivery_Man::findOrFail($id);
        $delivery_man->name = $request->input('name');
        $delivery_man->gender = $request->input('gender');
        $delivery_man->district_id = $request->input('district_id');
        $delivery_man->delivery_car_id = $request->input('delivery_car_id');
        $delivery_man->route = $request->input('route');
        $delivery_man->date_of_birth =  date('Y-m-d', strtotime($request->input('date_of_birth')));
        $delivery_man->date_of_joining =  date('Y-m-d', strtotime($request->input('date_of_joining')));
        $delivery_man->token = md5(rand(000, 9999));
        if(!empty($request->input('password')) || trim($request->input('password'))!==''){
            $delivery_man->password = md5($request->input('password'));
        }
        $delivery_man->save();
        return redirect('admin/delivery/man')->with('success','Delivery Man  Updated Successfully');

    }


    public function Status($id,$status)
    {

        $password = md5(rand(000, 9999));
        $deliveryMan = Delivery_Man::find($id);
        if($deliveryMan){
            if($status == 0)
            {
                Delivery_Man::where('id', $id)->update(['status' => $status]);
                return 'success';

            }else{
                Delivery_Man::where('id', $id)->update(['status' => $status,'password' => $password]);

                return 'success';

            }
        }

    }


}
