<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportErrorLogs extends Model
{
    protected $fillable = ['index','error','phone'];
}
