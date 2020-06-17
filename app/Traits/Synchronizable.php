<?php

namespace App\Traits;

use App\Synchronization;

trait Synchronizable
{
    // We use Laravel's naming convention "bootNameOfTheTrait"
    // to add boot logic to our synchronizables.
    public static function bootSynchronizable()
    {
        // Start a new synchronization once created.
        static::created(function ($synchronizable) {
            $synchronizable->synchronization()->create();
        });

        // Stop and delete associated synchronization.
        static::deleting(function ($synchronizable) {
            optional($synchronizable->synchronization)->delete();
        });
    }
    
    public function synchronization()
    {
        // We only expect one synchronization model per synchronizable.
        return $this->morphOne(Synchronization::class, 'synchronizable');
    }
    
    abstract public function synchronize();
}