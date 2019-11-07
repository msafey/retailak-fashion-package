<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class contactus extends Model
{
    //
    protected $table='contactu_us';
    protected $fillable = ['id' , 'name' , 'message'];
    public $timestamps = 'true';
}
