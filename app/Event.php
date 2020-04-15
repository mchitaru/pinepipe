<?php

namespace App;

use App\Lead;
use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class Event extends Model
{
    use NullableFields;

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

    public function user()
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
        $post['user_id']    = \Auth::user()->id;
        $post['active']     = true;
        $post['busy']       = true;
        $post['created_by'] = \Auth::user()->creatorId();

        $event = Event::create($post);

        if(isset($post['lead_id']))
        {
            $leads = collect($post['lead_id']);
            $event->leads()->sync($leads);
        }

        // Activity::createContact($contact);

        return $event;
    }

    public function updateEvent($post)
    {
        $this->update($post);

        if(isset($post['lead_id']))
        {
            $leads = collect($post['lead_id']);
        }else{

            $leads = collect();
        }

        $this->leads()->sync($leads);
    }

    public function detachEvent()
    {
    }
}
