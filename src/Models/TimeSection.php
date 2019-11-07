<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSection extends Model
{
    protected $table = "time_sections";

    protected $fillable = ['name','name_en' ,'from', 'to', 'status'];
      public function adjustments(){
        return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
    }
}
