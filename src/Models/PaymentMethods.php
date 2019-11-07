<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethods extends Model
{

        protected $table = "payment_methods";

        protected $fillable = ['name','type','key'];


          public function adjustments(){
            return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
        }


    //
}
