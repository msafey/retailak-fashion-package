<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class deviceToken extends Model
{
    protected $table = "device_tokens";

    protected $fillable = ['user_id', 'device_id', 'device_token','device_os','app_version'];
}
