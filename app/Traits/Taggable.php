<?php

namespace App\Traits;

use App\Tag;

trait Taggable
{
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->orderByDesc('id');
    }
}
