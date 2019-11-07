<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryProducts extends Model
{
    protected $table = 'category_products';

    protected $fillable = ['product_id', 'category_id'];


}
