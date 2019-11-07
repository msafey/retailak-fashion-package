<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class salaries_drawing extends Model
{
    //
    protected $fillable = ['id', 'admin_id', 'employee','cost','type','Reason' ,'created_at' ,'updated_at'];

    protected $table='salaries_drawing';
    public $timestamps = true;

    public function admin()
    {
        return $this->hasOne('App\AdminUser' ,'id' ,'admin_id');
    }


}
