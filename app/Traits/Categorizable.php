<?php

namespace App\Traits;

use App\Category;

trait Categorizable
{
    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable')->orderByAsc('order');
    }
}
