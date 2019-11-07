<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class faq_details extends Model
{
    protected $table="faq_details";
    public $timestamps = true;
    protected $fillable = ['id','created_at','updated_at','question' , 'answer'];

}
