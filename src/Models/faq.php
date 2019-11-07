<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class faq extends Model
{
    protected $table="faq";
    public $timestamps = true;
    protected $fillable = ['id','title' , 'created_at','updated_at'];

    public function details()
    {
        return $this->hasMany('App\faq_details' , 'faq_id' , 'id');
    }
}
