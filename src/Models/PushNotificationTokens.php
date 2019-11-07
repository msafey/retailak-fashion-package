<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotificationTokens extends Model
{
    protected $fillable = ['device_token','user_id','push_notification_id'];
}
