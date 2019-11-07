<?php

namespace App\Models;

use App\Image;
use Illuminate\Database\Eloquent\Model;

class LineHaulBatch extends Model
{
    protected $fillable=['driver_name','car_plate_number','weight','purchase_order_number'];



    public function linesImage($image)
       {
           $attributes = ['image' => $image];
           if (!$this->images()->where($attributes)->exists()) {
               $line_image = $this->images()->create($attributes);
               return $line_image;
           }
       }
          public function adjustments(){
      return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
  }




       public function images(){
           return $this->morphMany(Image::class, 'content');
       }


       public function imgPath($image)
       {
           return url('/public/admin/imgs/line_haul_batch/' . $image);
       }

       public function imgThumbPath($image)
       {
           return url('/public/imgs/line_haul_batch/thumb/' . $image);
       }



}
