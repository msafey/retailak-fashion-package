<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable=['name','name_en','description','description_en','logo','status'];
    protected $table='companies';

    public function path()
    {
        return 'admin/companies/';
    }


    public function imgPath($image)
    {
        return url('/public/admin/imgs/companies/' . $image);
    }

    public function imgThumbPath($image)
    {
        return url('/public/imgs/companies/thumb/' . $image);
    }

}
