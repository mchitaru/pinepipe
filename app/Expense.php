<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class Expense extends Model
{
    use NullableFields;

    protected $fillable = [
        'amount',
        'date',
        'category_id',
        'project_id',
        'user_id',
        'description',
        'attachment',
        'created_by'
    ];

    protected $nullable = [
        'project_id',
        'category_id',
        'user_id'
    ];
    
    public static $SEED = 200;

    public function category()
    {
        return $this->hasOne('App\ExpenseCategory','id','category_id');
    }
    
    public function project()
    {
        return $this->hasOne('App\Project','id','project_id');
    }

    public function user()
    {
        return $this->hasOne('App\User','id','user_id');
    }
}
