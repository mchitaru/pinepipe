<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Iatstuti\Database\Support\NullableFields;

class Category extends Model
{
    protected $fillable = [
        'name',
        'active',
        'order',
        'description'
    ];

    protected $nullable = [
        'description'
	];

    public function events()
    {
        return $this->morphedByMany('App\Event', 'categorizable');
    }

    public function expenses()
    {
        return $this->morphedByMany('App\Expense', 'categorizable');
    }

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if ($user = \Auth::user()) {
                $category->created_by = $user->creatorId();
            }

            $category->slug = Str::of($category->name)->slug('-');
        });

        static::updating(function ($category) {

            $category->slug = Str::of($category->name)->slug('-');
        });
    }
}
