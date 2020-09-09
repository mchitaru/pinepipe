<?php

namespace App\Traits;

use App\Tag;

trait Taggable
{
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->orderByDesc('id');
    }

    public function syncTags($names)
    {
        //tags
        $tags = [];

        foreach($names as $name)
        {
            $tags[] = Tag::firstOrCreate(['name' => $name,
                                        'created_by' => \Auth::user()->created_by])->id;
        }

        $this->tags()->sync($tags);
    }
}
