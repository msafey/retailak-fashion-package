<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjustments extends Model
{
    public function adjustments(){
    	return $this->belongsToMany(AdminUser::class,'adjustments');
    }
}
