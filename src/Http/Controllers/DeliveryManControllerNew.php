<?php

namespace App\Http\Controllers;

use App\Models\Delivery_Man;
use App\Http\Requests\AddDeliveryManRequest;
use App\Http\Requests\EditDeliveryManRequest;
use DB;
use File;
use Image;
use Input;
use Session;
use App\Http\Controllers\utilitiesController;
use Yajra\Datatables\Datatables;


class DeliveryManControllerNew extends utilitiesController
{
    public function __construct()
    {


        $path = storage_path('app/cookie.txt');
        define("COOKIE_FILE", $path);

    }

    public function index()
    {
        return view('admin/delivery-man/list');
    }

    public function deliverymanlist()
    {

        $delivery_man = DB::table('delivery__men')->orderBy('id', 'DESC')->get();

        return Datatables::of($delivery_man)->make(true);
    }

    public function create()
    {
        return view('admin.delivery-man.add');
    }


    public function store(AddDeliveryManRequest $request)
    {
        $name = $request->input('name');
        $email = $request->input('mobile').'@gmail.com';
        $gender = $request->input('gender');
        $date_of_birth =  date('Y-m-d', strtotime($request->input('date_of_birth')));
        $date_of_joining =  date('Y-m-d', strtotime($request->input('date_of_joining')));
        $mobile = $request->input('mobile');
        $password = $request->input('password');


        $loginResult = static::erpnextLogin('Store (DeliveryManControllerNew)');
        if ($loginResult === false) {
            $loginResult = 'Curl error: ' . curl_error($loginResult);
            return Response::json($loginResult.'|login3', 401);
        } else {

            $ch9 = curl_init(config('goomla.proderpurl') . "resource/User");
            curl_setopt($ch9, CURLOPT_COOKIEJAR, COOKIE_FILE);
            curl_setopt($ch9, CURLOPT_COOKIEFILE, COOKIE_FILE);

            $arr = array('name' => $name,'first_name'=>$name, 'change_password'=> $password, 'email' => $email, 'phone' => $mobile);
            curl_setopt($ch9, CURLOPT_POSTFIELDS, array('data' => json_encode($arr)));
            curl_setopt($ch9, CURLOPT_RETURNTRANSFER, true);
            //return  curl_exec($ch9);
            $result1 = curl_exec($ch9);

            $ch10 = curl_init(config('goomla.proderpurl') . "resource/User/" . $email);
            curl_setopt($ch10, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch10, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch10, CURLOPT_COOKIEJAR, COOKIE_FILE);
            curl_setopt($ch10, CURLOPT_COOKIEFILE, COOKIE_FILE);
            $arr2 = array('new_password' => $password, 'email' => $email);
            curl_setopt($ch10, CURLOPT_POSTFIELDS, array('data' => json_encode($arr2)));

            $result2 = curl_exec($ch10);

            $arrEmployee = array();
            $arrEmployee['naming_series'] = "EMP/";
            $arrEmployee['status'] = "Active";
            $arrEmployee['company'] = "Gomla.Online";
            $arrEmployee['user_id'] = $email;
            $arrEmployee['employee_name'] = $name;
            $arrEmployee['date_of_joining'] = $date_of_joining;
            $arrEmployee['date_of_birth'] = $date_of_birth;
            $arrEmployee['gender'] = $gender;

            dd($arrEmployee);
            $ch5g = curl_init(config('goomla.proderpurl') . "resource/Employee");

            curl_setopt($ch5g, CURLOPT_COOKIEJAR, COOKIE_FILE);
            curl_setopt($ch5g, CURLOPT_COOKIEFILE, COOKIE_FILE);
            curl_setopt($ch5g, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch5g, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch5g, CURLOPT_POSTFIELDS, array('data' => json_encode($arrEmployee)));
            return curl_exec($ch5g);
                $result5 = curl_exec($ch5g);
            if ($result5 === false) {
                return Response::json(['Status' => 'Erorr', 'message' => 'Server Error'], 500);
            }
            else{
                $employee = json_decode($result5);

                $employeeId = $employee->data->name;

                $salesPersonArr = array(
                    'owner' => 'ahakim@panarab-media.com',
                    'sales_person_name' => $name,
                    'parent' => "Sales Team",
                    'parent_sales_person'=>"Sales Team",
                    'employee'=>$employeeId
                );

                $salesPersonCh = curl_init(config('goomla.proderpurl') . "resource/Sales Person");
                curl_setopt($salesPersonCh, CURLOPT_COOKIEJAR, COOKIE_FILE);
                curl_setopt($salesPersonCh, CURLOPT_COOKIEFILE, COOKIE_FILE);
                curl_setopt($salesPersonCh, CURLOPT_POSTFIELDS, array('data' => json_encode($salesPersonArr)));
                curl_setopt($salesPersonCh, CURLOPT_RETURNTRANSFER, true);
                //return curl_exec($salesPersonCh);
                $salesPerson = curl_exec($salesPersonCh);
                //return $salesPerson;
                if (curl_exec($salesPersonCh) === false) {
                    return Response::json(['Status' => 'Erorr', 'message' => 'Server Error'], 500);
                }
                else{
                    $salesPerson = json_decode($salesPerson);
                    $salesPersonName = $salesPerson->data->name;
                    $delivery_man = new  Delivery_Man;
                    $delivery_man->name = $request->input('name');
                    $delivery_man->email = $request->input('mobile').'@gmail.com';
                    $delivery_man->gender = $request->input('gender');
                    $delivery_man->employeeCode =  $employeeId;
                    $delivery_man->date_of_birth =  date('Y-m-d', strtotime($request->input('date_of_birth')));
                    $delivery_man->date_of_joining =  date('Y-m-d', strtotime($request->input('date_of_joining')));
                    $delivery_man->salesPersonCode =  $salesPersonName;
                    $delivery_man->mobile = $request->input('mobile');
                    $delivery_man->token = md5(rand(000, 9999));
                    $delivery_man->password = md5($request->input('password'));


                    $delivery_man->save();
                }



            }





        }







        return redirect('admin/delivery/man')->with('success','Delivery Man  Created Successfully');
    }


    public function edit($id)
    {
        $delivery_man = Delivery_Man::findOrFail($id);
        //dd(date('d/m/Y',strtotime($delivery_man->date_of_birth)));
        return view('/admin/delivery-man/edit', compact('delivery_man'));

    }

    public function update(EditDeliveryManRequest $request, $id)
    {
        $delivery_man = Delivery_Man::findOrFail($id);
        $employeeArr = array(
            'employee_name' => $request->input('name'),
            'gender' => $request->input('gender'),
            'date_of_joining'=>date('Y-m-d', strtotime($request->input('date_of_joining'))),
            'date_of_birth' => date('Y-m-d', strtotime($request->input('date_of_birth')))
        );

        static::erpnextLogin('update (DeliveryManControllerNew)');
        $employeeId = $delivery_man->employeeCode;
        $employeeCh = curl_init(config('goomla.proderpurl') . "resource/Employee/$employeeId");
        curl_setopt($employeeCh, CURLOPT_COOKIEJAR, COOKIE_FILE);
        curl_setopt($employeeCh, CURLOPT_COOKIEFILE, COOKIE_FILE);
        curl_setopt($employeeCh, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($employeeCh, CURLOPT_POSTFIELDS, array('data' => json_encode($employeeArr)));
        curl_setopt($employeeCh, CURLOPT_RETURNTRANSFER, true);
        //dd(curl_exec($employeeCh));
        $employee = curl_exec($employeeCh);
        //return $employee;
        if (curl_exec($employeeCh) === false) {
            return Response::json(['Status' => 'Erorr', 'message' => 'Server Error'], 500);
        }
        else{
            $employee = json_decode($employee);
            $employeeId = $employee->data->name;

            $salesPersonArr = array(
                //'owner' => 'ahakim@panarab-media.com',
                'owner' => 'ahakim@panarab-media.com',
                'sales_person_name' => $request->input('name'),
                'parent' => "Sales Team",
                'parent_sales_person'=>"Sales Team"
                );

            $salesPersonCode = $delivery_man->salesPersonCode;
            $salesPersonCh = curl_init(config('goomla.proderpurl') . "resource/Sales Person/".$salesPersonCode);
            curl_setopt($salesPersonCh, CURLOPT_COOKIEJAR, COOKIE_FILE);
            curl_setopt($salesPersonCh, CURLOPT_COOKIEFILE, COOKIE_FILE);
            curl_setopt($salesPersonCh, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($salesPersonCh, CURLOPT_POSTFIELDS, array('data' => json_encode($salesPersonArr)));
            curl_setopt($salesPersonCh, CURLOPT_RETURNTRANSFER, true);
            $salesPerson = curl_exec($salesPersonCh);
            if ($salesPerson === false)
            {
                return Response::json(['Status' => 'Erorr', 'message' => 'Server Error'], 500);
            }
            else
            {
                $delivery_man->name = $request->input('name');
                $delivery_man->gender = $request->input('gender');
                $delivery_man->date_of_birth =  date('Y-m-d', strtotime($request->input('date_of_birth')));
                $delivery_man->date_of_joining =  date('Y-m-d', strtotime($request->input('date_of_joining')));
                $delivery_man->token = md5(rand(000, 9999));
                if(!is_empty($request->input('password')) || trim($request->input('password'))!=='')
                $delivery_man->password = md5($request->input('password'));
                $delivery_man->save();
            }
        }
        $delivery_man->save();
        return redirect('admin/delivery/man')->with('success','Delivery Man  Updated Successfully');
    }

    public function Status($id)
    {

        $password = md5(rand(000, 9999));

        $status = Delivery_Man::where('id', $id)->select('status')->first();
       if($status->status == 0)
       {
           Delivery_Man::where('id', $id)->update(['status' => '1']);
           return redirect('admin/delivery/man')->with('Delivery Man  Activated Successfully, Don`t Forget to Generate New Password');

       }else{
           Delivery_Man::where('id', $id)->update(['status' => '0','password' => $password]);

           return redirect('admin/delivery/man')->with('success','Delivery Man  Disabled Successfully');

       }


    }


}
