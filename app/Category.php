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
        'description',
        'category_id'
    ];

    protected $nullable = [
        'description'
	];

    public function events()
    {
        return $this->hasMany('App\Event', 'category_id', 'id');
    }

    public function expenses()
    {
        return $this->hasMany('App\Expense', 'category_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment', 'category_id', 'id');
    }

    public function leads()
    {
        return $this->hasMany('App\Lead', 'category_id', 'id');
    }

    public function children()
    {
        return $this->hasMany('App\Category', 'category_id', 'id');
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
