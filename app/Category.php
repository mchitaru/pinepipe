<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Iatstuti\Database\Support\NullableFields;
use App\Traits\Categorizable;

use App\Scopes\TenantScope;

class Category extends Model
{
    use Categorizable;

    protected $fillable = [
        'name',
        'class',
        'order',
        'description',
        'category_id',
        'user_id',
        'created_by'
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

        static::addGlobalScope(new TenantScope);

        static::creating(function ($category) {
            if ($user = \Auth::user()) {
                $category->user_id = $user->id;
                $category->created_by = $user->created_by;
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

            $category->children()->update(['category_id' => null]);
            $category->leads()->update(['category_id' => null]);
            $category->articles()->update(['category_id' => null]);
            $category->expenses()->update(['category_id' => null]);
            $category->payments()->update(['category_id' => null]);
        });
    }

    public function children()
    {
        return $this->hasMany('App\Category', 'category_id', 'id');
    }

    public function leads()
    {
        return $this->hasMany('App\Lead', 'category_id', 'id');
    }

    public function articles()
    {
        return $this->hasMany('App\Article', 'category_id', 'id');
    }

    public function expenses()
    {
        return $this->hasMany('App\Expense', 'category_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment', 'category_id', 'id');
    }
}
