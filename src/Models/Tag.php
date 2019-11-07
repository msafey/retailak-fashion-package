<?php

namespace App\Models;

use App\Post;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false; //remove default timestamp

    protected $fillable = [
        'name'
    ];
    protected $table = 'tag';
    protected $hidden = array();
    public function post()
    {
        return $this->hasMany(Post::class);
    }
}
