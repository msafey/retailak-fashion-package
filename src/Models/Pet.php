<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{

    protected $fillable = ['name_en', 'name', 'breed_name_en', 'breed_name', 'gender', 'species', 'pet_type', 'age', 'weight'];

}
