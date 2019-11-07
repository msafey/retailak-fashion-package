<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taxs extends Model
{
    protected $table='taxs';
    protected $fillable = ['type','rate','amount','status'];



      public function adjustments(){
        return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
    }


}
