<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\SynchronizeGoogleEvents;
use App\Traits\Synchronizable;

class Calendar extends Model
{
    use Synchronizable;

    protected $fillable = ['google_id', 'name', 'color', 'timezone', 'user_id', 'created_by'];

    public static function boot()
    {
        parent::boot();

        // static::created(function ($calendar) {
        //     SynchronizeGoogleEvents::dispatch($calendar);
        // });

        static::deleting(function ($calendar) {

            $calendar->events()->each(function($event) {
                $event->delete();
             });
        });
    }    

    public function synchronize()
    {
        SynchronizeGoogleEvents::dispatch($this);
    }    

    public function googleAccount()
    {
        return $this->belongsTo(GoogleAccount::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }    
}
