<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seasons extends Model
{
    protected $fillable=['name','name_en','description','description_en'];
    protected $table = 'seasons';

	public function path()
	{
		return 'admin/seasons/';
	}
	public function adjustments(){
		return $this->belongsToMany(AdminUser::class,'adjustments','content_id','user_id')->withPivot('key','action')->withTimestamps()->latest('pivot_updated_at');
	}
}
