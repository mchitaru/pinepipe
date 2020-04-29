<?php

namespace App\Traits;

use App\Category;

trait Categorizable
{
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->orderBy('id', 'asc');
    }
}
