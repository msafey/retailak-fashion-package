<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{

      public function adjustments(){
        return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')
            ->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
    }
}
