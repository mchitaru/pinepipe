<?php

namespace App\Traits;

use App\Category;

trait Categorizable
{
    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable')->orderBy('id', 'asc');
    }

    public function syncCategory($category, $class)
    {
        $categories = [];

        if(!empty($category)){

            $categories[] = Category::firstOrCreate(['name' => $category,
                                                        'class' => $class,
                                                        'created_by' => [0, \Auth::user()->creatorId()]])->id;
        }

        $this->categories()->sync($categories);
    }
}
