<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $table = "users";

    protected $fillable = ['name', 'email', 'facebook_id', 'token', 'phone', 'password', 'device_id', 'device_os', 'app_version', 'active', 'type'];

    public function address()
    {
        return $this->hasMany('App\Addresses', 'user_id', 'id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id', 'id');
    }

    public function adjustments()
    {
        return $this->belongsToMany('App\AdminUser', 'adjustments', 'content_id', 'user_id')->withPivot('key', 'action')->withTimestamps()->latest('pivot_updated_at');
    }


    public function orders()
    {
        return $this->hasMany('App\Orders', 'user_id', 'id');
    }


    public function promocodes()
    {
        return $this->belongsToMany(
            'App\Promocode',
            'user_promocode',
            'user_id',
            'promocode_id'
        );
    }

    public function addNew($input)
    {
        $check = static::where('facebook_id', $input['facebook_id'])->first();


        if (is_null($check)) {
            return static::create($input);
        }


        return $check;
    }

    public function district()
    {
        return $this->hasOne('App\Addresses', 'user_id', 'id')
            ->where('active', 1)
            ->select('district_id');
    }

    public function favourites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }


}
