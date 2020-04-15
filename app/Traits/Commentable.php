<?php

namespace App\Traits;

use App\Comment;

trait Commentable
{
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->orderByDesc('id');
    }
}
