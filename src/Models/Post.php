<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Tag;
use App\Comment;

class Post extends Model
{
    //
    protected $fillable = [
        'title', 'slug', 'body', 'thumbnailURL',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_comments');
    }

    public function relatedPostsByTag($slug)
    {
        $post = Post::where('slug', '=', $slug)->first();

        $related = Post::whereHas('tags', function ($q) use ($post) {
            $q->whereIn('name', $post->tags->pluck('name'));
        })
            ->where('id', '<>', $post->id) // So you won't fetch same post
            ->get();

        return $related;
    }
}
