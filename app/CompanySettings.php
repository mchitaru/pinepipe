<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class CompanySettings extends Model implements HasMedia
{
    use NullableFields, HasMediaTrait;

    protected $fillable = [
        'name',
        'email',
        'address',
        'city',
        'state',
        'zipcode',
        'country',
        'phone',
        'invoice',
        'currency',
        'created_by',
    ];

    protected $nullable = [
        'email',
        'address',
        'city',
        'state',
        'zipcode',
        'country',
        'phone',
        'invoice',
    ];

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($settings) {
            if ($user = \Auth::user()) {
                $settings->created_by = $user->creatorId();
            }
        });

        static::deleting(function ($settings) {

        });
    }

    public function company()
    {
        return $this->belongsTo('App\User', 'id', 'created_by');
    }
}
