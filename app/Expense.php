<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;
use App\Traits\Taggable;

class Expense extends Model
{
    use NullableFields, Taggable;

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
    
    public static $SEED = 10;

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

    public static function expensesByUserType()
    {
        if(\Auth::user()->type == 'client')
        {
            return Expense::with(['user','project'])
                    ->whereHas('project', function ($query)
                    {
                        $query->whereHas('client', function ($query) 
                        {
                            $query->where('id', \Auth::user()->id);                
                        });
                    });

        }
        elseif(\Auth::user()->type == 'company'){
            
            return Expense::with(['user','project']);
        }else{
            
            return Expense::with(['user','project'])
                            ->where(function ($query)  {
                                $query->where('user_id', \Auth::user()->id);
                            });
        }
    }
}
