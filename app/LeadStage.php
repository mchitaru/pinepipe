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

    public function leadsByUserType($client_id)
    {
        if(!empty($client_id))
        {
            if(\Auth::user()->type == 'company')
            {
                return $this->leads()->where('client_id','=',$client_id)->orderBy('order');

            }else
            {
                return \Auth::user()->leads()->where('stage_id', '=', $this->id)->where('client_id','=',$client_id)->orderBy('order');
            }
        }
        else
        {
            if(\Auth::user()->type == 'company')
            {
                return $this->leads()->orderBy('order');

            }else
            {
                return \Auth::user()->leads()->where('stage_id', '=', $this->id)->orderBy('order');
            }
        }
    }
}
