<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UOM extends Model
{
    protected $fillable=['type'];
    protected $table = 'uoms';

    	public function path()
    	   {

    	       return 'admin/uom/';
    	   }

           public function adjustments(){
             return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
         }


}
