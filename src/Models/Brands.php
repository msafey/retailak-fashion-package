<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    use Sluggable;
    protected $fillable=['name','name_en','description','description_en','company_id'];
    protected $table = 'brands';

    	public function path()
    	   {
    	       return 'admin/brands/';
    	   }

           public function adjustments(){
             return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
         }
     public function brandImage($image)
        {
            $attributes = ['image' => $image];
            if (!$this->images()->where($attributes)->exists()) {
                $brand_image = $this->images()->create($attributes);
                return $brand_image;
            }
        }


        public function images(){
            return $this->morphMany(Image::class, 'content');
        }

        public function imgPath($image)
          {
              return url('/public/admin/imgs/brands/' . $image);
          }

          public function imgThumbPath($image)
          {
              return url('/public/imgs/brands/thumb/' . $image);
          }

          public function sluggable() {
            return [
                'slug_en' => [
                    'source' => 'name_en',
                    'separator' => '-',
                ],
                'slug_ar' => [
                    'source' => 'name',
                    'separator' => '-',
                ]
            ];
        }
}
