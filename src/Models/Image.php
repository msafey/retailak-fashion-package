<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'image', 'content_type', 'content_id','image_order'
    ];
    public function content()
    {
        return $this->morphTo();
    }


}
