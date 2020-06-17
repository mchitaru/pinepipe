<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\SynchronizeGoogleCalendars;

use App\Traits\Synchronizable;

class GoogleAccount extends Model
{
    use Synchronizable;

    protected $fillable = ['google_id', 'name', 'token'];
    protected $casts = ['token' => 'json'];

    public static function boot()
    {
        parent::boot();

        // static::created(function ($googleAccount) {
        //     SynchronizeGoogleCalendars::dispatch($googleAccount);
        // });

        static::deleting(function ($account) {

            $account->calendars()->each(function($calendar) {
                $calendar->delete();
             });
        });
    }    

    public function synchronize()
    {
        SynchronizeGoogleCalendars::dispatch($this);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }
}
