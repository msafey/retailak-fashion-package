<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AramexShipment extends Model
{
    protected $fillable = ['order_id','shipment_id', 'response', 'has_error', 'transaction', 'notification', 'label_url', 'shipment_track','status'];

    protected $table = 'aramex_shipment';
}
