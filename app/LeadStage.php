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

    public static function stagesByUserType()
    {
        if(\Auth::user()->type == 'client')
        {
            return LeadStage::with(['leads' => function ($query) {

                        $query->where('leads.client_id', \Auth::user()->client_id)
                                ->orderBy('order');
                    },
                    'leads.client','leads.user'])
                    ->where('created_by', '=', \Auth::user()->creatorId())
                    ->orderBy('order');
        }
        elseif(\Auth::user()->type == 'company')
        {
            return LeadStage::with(['leads' => function ($query) {

                        $query->orderBy('order');

                    },'leads.client','leads.user'])
                    ->where('created_by', '=', \Auth::user()->creatorId())
                    ->orderBy('order');

        }else
        {
            return LeadStage::with(['leads' => function ($query) {

                        $query->where('leads.user_id', \Auth::user()->id)
                                ->orderBy('order');
                    },
                    'leads.client','leads.user'])
                    ->where('created_by', '=', \Auth::user()->creatorId())
                    ->orderBy('order');
        }
    }

    public function computeStatistics()
    {
        $this->total_amount = 0;

        foreach($this->leads as $lead)
        {
            $this->total_amount += $lead->price;
        }
    }
}
