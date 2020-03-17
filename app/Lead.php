<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class Lead extends Model
{
    use NullableFields;

    protected $fillable = [
        'name',
        'price',
        'stage_id',
        'order',
        'user_id',
        'client_id',
        'contact_id',
        'source_id',
        'created_by',
        'notes'
    ];

    protected $nullable = [
        'client_id',
        'contact_id',
        'notes'
	];

 
    public static $SEED = 10;
    
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }

    public function stage()
    {
        return $this->belongsTo('App\LeadStage');
    }

    public function removeProjectLead()
    {
        return Project::where('lead_id','=',$this->id)->update(array('lead_id' => 0));
    }

    public function sources()
    {
        return $this->hasOne('App\Leadsource', 'id', 'source_id');
    }

    public static function createLead($post)
    {
        $stage = LeadStage::find($post['stage_id']);
        
        $post['order']   = $stage->leads->count();

        if(\Auth::user()->type != 'company')
        {
            $post['user_id'] = \Auth::user()->id;
        }

        $lead                = Lead::make($post);
        $lead->created_by    = \Auth::user()->creatorId();
        $lead->save();

        return $lead;
    }

    public function updateLead($post)
    {
        $this->update($post);
    }

    public function detachLead()
    {
        $this->removeProjectLead();
    }
}
