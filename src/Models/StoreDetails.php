<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreDetails extends Model
{
    protected $fillable=['store_name','store_address','user_id','shop_type_id','tax_card','commercial_register'];

    public function imgPath($image)
    {
        return url('/public/imgs/store_details/' . $image);
    }

    public function imgThumbPath($image)
    {
        return url('/public/imgs/store_details/thumb/' . $image);
    }
}
