<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttributes extends Model
{
    protected $fillable=['products_id','attribute_id','value_id'];
    use SoftDeletes;
}


