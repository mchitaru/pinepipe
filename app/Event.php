<?php

namespace App;

use App\Lead;
use App\Project;
use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;
use App\Traits\Taggable;
use App\Traits\Actionable;

use App\Scopes\CollaboratorTenantScope;

class Event extends Model
{
    use NullableFields, Taggable, Actionable;

    protected $with = ['calendar'];

    protected $fillable = [
        'name',
        'start',
        'end',
        'busy',
        'description',
        'user_id',
        'created_by',
        'allday',
        'recurrence',
        'google_id',        
    ];

    protected $nullable = [
        'description',
        'recurrence'
	];

    public static $SEED = 10;

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CollaboratorTenantScope);

        static::creating(function ($event) {
            if ($user = \Auth::user()) {
                $event->user_id = $user->id;
                $event->created_by = $user->created_by;
            }
        });

        static::deleting(function ($event) {

            $event->users()->detach();
            $event->clients()->detach();
            $event->contacts()->detach();
            $event->leads()->detach();

            $event->tags()->detach();

            $event->activities()->delete();
        });
    }

    public function owner()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
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

    public function projects()
    {
        return $this->morphedByMany('App\Project', 'eventable');
    }

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function getStartedAtAttribute($start)
    {
        return $this->asDateTime($start)->setTimezone($this->calendar->timezone);
    }

    public function getEndedAtAttribute($end)
    {
        return $this->asDateTime($end)->setTimezone($this->calendar->timezone);
    }

    public function getDurationAttribute()
    {
        return $this->started_at->diffForHumans($this->ended_at, true);
    }    

    public static function createEvent($post)
    {
        $post['busy']       = true;

        $post['start'] = \Helpers::localToUTC($post['start']);
        $post['end'] = \Helpers::localToUTC($post['end']);

        $event = Event::create($post);

        if(!empty($post['lead_id'])){
            $leads = collect($post['lead_id']);
            $event->leads()->sync($leads);

            $lead = $event->leads->first();
        }

        if(!empty($post['project_id'])){
            $projects = collect($post['project_id']);
            $event->projects()->sync($projects);

            $project = $event->projects->first();
        }

        $users = collect($post['users']);
        $event->users()->sync($users);

        Activity::createEvent($event);

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

        if(!empty($post['project_id']))
        {
            $projects = collect($post['project_id']);
        }else{

            $projects = collect();
        }

        $this->projects()->sync($projects);

        $users = collect($post['users']);
        $this->users()->sync($users);

        Activity::updateEvent($this);
    }
}
