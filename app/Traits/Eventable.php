<?php

namespace App\Traits;

use App\Event;

trait Eventable
{
    public function events()
    {
        return $this->morphToMany(Event::class, 'eventable')->orderByDesc('id');
    }
}
