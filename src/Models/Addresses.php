<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    protected $table = "address";
    protected $fillable=[ 'title', 'street' ,'user_id','address_phone','street','building_no','floor_no' , 'apartment_no' ,'city' , 'district_id','nearest_landmark','lat','lng'];

    public function orders()
    {
        return $this->hasOne('App\Orders', 'address_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
