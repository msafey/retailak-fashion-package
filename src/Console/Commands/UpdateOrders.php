<?php

namespace App\Console\Commands;

use App\Addresses;
use App\District;
use App\Orders;
use App\ShippingRule;
use Illuminate\Console\Command;

class UpdateOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orders = Orders::orderBy('id','desc')->get();
        foreach ($orders as $order) {
            $address = $order->address ? $order->address : null;
            if (!$address) {
                echo "$order->id failed because of address \n ";
                continue;
            }
            $district = $address->district ?: null;
            if (!$district) {
                echo "$order->id failed because of district with address $address->id \n ";
                continue;
            }
            $discount = $order->usedPromo ? $order->usedPromo->discount_rate : 0;
            $totalAmount = 0;
            $items = $order->items;
            $shipping = calculateShippingRate($district,$items);
            if (!is_array($shipping)) {
                echo "$order->id failed because of shipping rate with district $district->id \n ";
                continue;
            }

            foreach ($items as $item) {
                $totalAmount += ($item->rate * $item->qty);
            }
            $order->update(['total_price' => $totalAmount, /*'shipping_rate' => $shipping['rate'],*/ 'discount' =>$discount ]);
        }

        echo 'Done';
    }
}
