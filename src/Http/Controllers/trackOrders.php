<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class trackOrders extends Controller
{
    //

    public function index()
    {
        // get all order
        $orders = Orders::select('id')->orderBy('created_at','desc')->get();
        return view('admin.track orders.index' , compact('orders'));
    }

    public function getStatus(Request $request)
    {
        // if isset id
        if(isset($request->id))
        {
            $order = 'invalid';
            $specific_order = Orders::find($request->id);
            $order_status = OrderStatus::where('order_id',$request->id)->orderBy('created_at')->get();
            if(isset($order_status))
            {
                $order = ["id"=>$specific_order->id , "date"=>$specific_order->date ,"name"=>$specific_order->user->name];
                $order[] = $order_status;
            }
            return $order;
        }
    }
}
