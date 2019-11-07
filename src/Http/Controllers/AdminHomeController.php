<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\ItemWarehouse;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Settings;
use App\Models\ShippingRule;
use App\Models\Warehouses;
use Carbon\Carbon;
use Carbon\diffForHumans;
use Carbon\now;
use Carbon\subDay;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{

    public function index()
    {
        $warehouseItems = Warehouses::withCount('productsCount')->get();

        $totalOrders = Orders::count();
        $deliveredOrders = Orders::whereStatus('Delivered')->count();
        $totalMoney = OrderItems::sum(DB::raw('(rate * qty)'));
        $totalSoldProducts = OrderItems::distinct('item_id')->count();

        $month = Carbon::now()->month;
        $day = Carbon::now()->day;

        $totalMonthOrders = Orders::whereMonth('created_at', $month)->count();
        $deliveredMonthOrders = Orders::whereMonth('created_at', $month)->whereStatus('Delivered')->count();
        $totalMonthMoney = OrderItems::whereMonth('created_at', $month)->sum(DB::raw('(rate * qty)'));
        $totalMonthItems = OrderItems::whereMonth('created_at', $month)->distinct('item_id')->count();


        $totalDayOrders = Orders::whereDay('created_at', $day)->count();
        $deliveredDayOrders = Orders::whereDay('created_at', $day)->whereStatus('Delivered')->count();
        $totalDayMoney = OrderItems::whereDay('created_at', $day)->sum(DB::raw('(rate * qty)'));
        $totalDayItems = OrderItems::whereDay('created_at', $day)->distinct('item_id')->count();

        return view("admin/dashboard", compact('warehouseItems', 'totalOrders', 'deliveredOrders', 'totalMoney', 'totalSoldProducts',
            'totalMonthOrders', 'deliveredMonthOrders', 'totalMonthMoney', 'totalMonthItems', 'totalDayOrders', 'deliveredDayOrders', 'totalDayMoney', 'totalDayItems'));
    }

    public function refreshdata()
    {
        return view("admin/refreshdata");
    }
}
