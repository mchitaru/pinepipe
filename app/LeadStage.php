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

    public function leads()
    {
        return $this->hasMany('App\Lead', 'stage_id', 'id')->orderBy('item_order');
    }

    public function user_leads(){
//        return LeadStage::select('leads.*','leadstages.name as stage_name' )->leftjoin('leads','leads.stage','=','leadstages.id')->where('leads.user_id','=',\Auth::user()->id)->get();
        return Lead::where('stage','=',$this->id)->where('user_id','=',\Auth::user()->id)->get();
    }
}
