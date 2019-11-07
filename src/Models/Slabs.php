<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slabs extends Model
{
    protected $fillable=['slab_name','min_amount_money','discount_rate','discount_type'];
        protected $table = 'slabs';

    	public function path(){

        	       return 'admin/slabs/';
        }

               public function adjustments(){
                 return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
             }
}
