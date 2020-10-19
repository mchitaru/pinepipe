<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;
use App\Traits\Taggable;
use App\Traits\Categorizable;
use App\Traits\Invoiceable;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

use App\Scopes\CompanyTenantScope;

class Expense extends Model implements HasMedia
{
    use NullableFields, Taggable, Categorizable, Invoiceable, HasMediaTrait ;

    protected $fillable = [
        'amount',
        'date',
        'category_id',
        'project_id',
        'description',
        'attachment',
        'user_id',
        'created_by'
    ];

    protected $nullable = [
        'project_id',
        'category_id',
        'user_id'
    ];

    public static $SEED = 10;

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyTenantScope);

        static::creating(function ($expense) {
            if ($user = \Auth::user()) {
                $expense->created_by = $user->created_by;
            }
        });

        static::deleting(function ($expense) {

            $expense->invoiceables()->each(function($inv) {
                $inv->delete();
            });
        });
    }

    public function project()
    {
        return $this->hasOne('App\Project','id','project_id');
    }

    public function user()
    {
        return $this->hasOne('App\User','id','user_id');
    }    

    public static function createExpense($post)
    {
        if(isset($post['category_id']) && !is_numeric($post['category_id'])) {

            //new category
            $category = Category::create(['name' => $post['category_id'],
                                            'class' => Expense::class]);
            $post['category_id'] = $category->id;
        }

        $expense = Expense::create($post);

        return $expense;
    }

    public function updateExpense($post)
    {
        if(isset($post['category_id']) && !is_numeric($post['category_id'])) {

            //new category
            $category = Category::create(['name' => $post['category_id'],
                                            'class' => Expense::class]);
            $post['category_id'] = $category->id;
        }

        $this->update($post);
    }
}
