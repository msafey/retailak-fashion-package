<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushNorification extends Model
{
    protected $table ="push_notifications";

    protected $primaryKey = "id";

    protected $fillable = ['push_title','push_message','push_os','push_time'];

    //we men el user, wel ip, we taba3 anhy country w city w url
}
