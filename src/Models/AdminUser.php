<?php

namespace App\Models;
use App\Notifications\UserAdminResetPasswordNotification;


//in User model namespace use:
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
// below one extra

//then in User Class:
use Zizaco\Entrust\Traits\EntrustUserTrait;

class AdminUser extends Authenticatable
{

    use Notifiable;


    use EntrustUserTrait;

    protected $table = "admin_users";

    protected $fillable = [
        'name', 'email', 'password','warehouse_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserAdminResetPasswordNotification($token));
    }

    public function warehouse(){
        return $this->belongsTo(Warehouses::class,'warehouse_id');
    }

    public function path()
    {
        return 'admin/cmsusers';
    }

}
