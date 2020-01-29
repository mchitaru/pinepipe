<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stage_id',
        'user_id',
        'client_id',
        'source_id',
        'created_by',
        'notes'
    ];
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function client()
    {
        return $this->hasOne('App\User', 'id', 'client_id');
    }

    public function stage()
    {
        return $this->hasOne('App\LeadStage', 'id', 'stage_id');
    }

    public function removeProjectLead()
    {
        return Project::where('lead_id','=',$this->id)->update(array('lead_id' => 0));
    }

    public function sources()
    {
        return $this->hasOne('App\Leadsource', 'id', 'source_id');
    }
}
