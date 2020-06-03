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
        'class',
        'order',
        'description',
        'category_id'
    ];

    protected $nullable = [
        'category_id',
        'description'
	];

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if ($user = \Auth::user()) {
                $category->user_id = $user->id;
                $category->created_by = $user->creatorId();
            }
        });

        static::created(function ($category) {

            $category->slug = Str::of($category->name.' '.$category->id)->slug('-');
            $category->save();
        });

        static::updating(function ($category) {

            $category->slug = Str::of($category->name.' '.$category->id)->slug('-');
        });

        static::deleting(function ($category) {

            $category->children()->detach();
        });
    }

    public function children()
    {
        return $this->hasMany('App\Category', 'category_id', 'id');
    }
}
