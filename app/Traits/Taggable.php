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

        if(isset($names)){

            foreach($names as $name)
            {
                $tags[] = Tag::firstOrCreate(['name' => $name,
                                            'created_by' => \Auth::user()->creatorId()])->id;
            }
        }

        $this->tags()->sync($tags);
    }
}
