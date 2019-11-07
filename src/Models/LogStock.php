<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\AdminUser;

class LogStock extends Model
{
    //
    protected $table='stock_logs';
    protected $guarded =['id'];

    public function admin () {
        return $this->belongsTo(AdminUser::class , 'admin_id' , 'id');
    }
}
