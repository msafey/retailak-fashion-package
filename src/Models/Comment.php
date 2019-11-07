<?php

namespace App\Models;

use App\Post;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function posts()
    {
        return $this->belongsTo(Post::class, 'post_comments');
    }
}
