<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Iatstuti\Database\Support\NullableFields;
use App\Traits\Categorizable;

class Category extends Model
{
    use Categorizable;

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

    public function payments()
    {
        return $this->morphedByMany('App\Payment', 'categorizable');
    }

    public function leads()
    {
        return $this->morphedByMany('App\Lead', 'categorizable');
    }

    public function children()
    {
        return $this->morphedByMany('App\Category', 'categorizable');
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
