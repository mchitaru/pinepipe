<?php

namespace App\Traits;

use App\Activity;

trait Actionable
{
    public function activities()
    {
        return $this->morphMany(Activity::class, 'actionable')->limit(20)->orderByDesc('id');
    }
}
