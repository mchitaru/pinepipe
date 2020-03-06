<?php

namespace App\Traits;

use App\Activity;

trait Actionable
{
    public function activities()
    {
        return $this->morphMany(Activity::class, 'actionable')->orderByDesc('id');
    }
}
