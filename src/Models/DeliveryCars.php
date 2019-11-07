<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryCars extends Model
{
    protected $fillable = ["title","car_model","car_plate"];


      public function adjustments(){
        return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
    }

}
