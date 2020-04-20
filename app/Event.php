<?php

namespace App;

use App\Lead;
use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;
use App\Traits\Taggable;

class Event extends Model
{
    use NullableFields, Taggable;

    protected $fillable = [
        'active',
        'name',
        'category_id',
        'start',
        'end',
        'busy',
        'notes',
        'user_id',
        'created_by',
    ];

    protected $nullable = [
        'notes'
	];

    public static $SEED = 10;

    public function owner()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo('App\EventCategory');
    }

    public function users()
    {
        return $this->morphedByMany('App\User', 'eventable');
    }

    public function clients()
    {
        return $this->morphedByMany('App\Client', 'eventable');
    }

    public function contacts()
    {
        return $this->morphedByMany('App\Contact', 'eventable');
    }

    public function leads()
    {
        return $this->morphedByMany('App\Lead', 'eventable');
    }

    public static function createEvent($post)
    {
        $post['active']     = true;
        $post['busy']       = true;
        $post['user_id']    = \Auth::user()->id;
        $post['created_by'] = \Auth::user()->creatorId();

        $post['start'] = \Helpers::localToUTC($post['start']);
        $post['end'] = \Helpers::localToUTC($post['end']);

        $event = Event::create($post);

        if(!empty($post['lead_id'])){
            $leads = collect($post['lead_id']);
            $event->leads()->sync($leads);

            $lead = $event->leads->first();
            
            Activity::createLeadEvent($lead, $event);
        }

        $users = collect($post['users']);
        $event->users()->sync($users);

        return $event;
    }

    public function updateEvent($post)
    {
        $post['start'] = \Helpers::localToUTC($post['start']);
        $post['end'] = \Helpers::localToUTC($post['end']);

        $this->update($post);

        if(!empty($post['lead_id']))
        {
            $leads = collect($post['lead_id']);
        }else{

            $leads = collect();
        }

        $this->leads()->sync($leads);

        $users = collect($post['users']);
        $this->users()->sync($users);

        $lead = $this->leads->first();

        if($lead) {
            Activity::updateLeadEvent($lead, $this);
        }
    }

    public function detachEvent()
    {
    }
}
