<?php

namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $fillable = ['name', 'description', 'display_name'];


    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }


    public function path()
    {
        return 'admin/permissions';
    }
}
