<?php
namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class RolePermission extends EntrustPermission
{
    protected $table = 'permission_role';
    protected $fillable = ['role_id', 'permission_id'];
}
