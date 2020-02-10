<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadStage extends Model
{
    protected $fillable = [
        'name',
        'created_by',
        'order',
        'color',
    ];


    protected $hidden = [

    ];

    public static $SEED = 4;

    public function leads()
    {
        return $this->hasMany('App\Lead', 'stage_id', 'id');
    }

    public function leadsByUserType()
    {
        if(\Auth::user()->type == 'company')
        {
            return $this->leads()->with(['client', 'user'])->orderBy('order');

        }else
        {
            return $this->leads()->with(['client', 'user'])->where('user_id', '=', \Auth::user()->id)->orderBy('order');
        }
    }
}
