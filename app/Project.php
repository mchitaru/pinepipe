<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

use App\Notifications\ProjectAssignedAlert;

use App\Traits\Actionable;
use App\Traits\Notable;
use App\Traits\Taggable;
use App\Traits\Eventable;

use App\Scopes\CollaboratorTenantScope;

class Project extends Model implements HasMedia
{
    use NullableFields, HasMediaTrait, Actionable, Taggable, Notable, Eventable;

    protected $fillable = [
        'name',
        'description',
        'price',
        'client_id',
        'lead_id',
        'archived',
        'start_date',
        'due_date',
        'user_id',
        'created_by'
    ];

    protected $nullable = [
        'client_id',
        'user_id',
        'lead_id',
        'description',
        'start_date',
        'due_date',
    ];

    protected $hidden = [

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

        static::creating(function ($project) {
            if ($user = \Auth::user()) {
                $project->user_id = $user->id;
                $project->created_by = $user->created_by;
            }
        });

        self::deleting(function ($project) {

            $project->users()->detach();

            $project->expenses()->each(function($expense) {
                $expense->delete();
             });

            $project->milestones()->each(function($milestone) {
                $milestone->delete();
            });

            $project->timesheets()->each(function($timesheet) {
                $timesheet->delete();
            });

            $project->invoices()->each(function($invoice) {
                $invoice->delete();
            });

            $project->tasks()->each(function($task) {
                $task->delete();
            });

            $project->tags()->detach();

            $project->activities()->delete();
        });
    }

    public function company()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task', 'project_id', 'id');
    }

    public function client()
    {
        return $this->hasOne('App\Client', 'id', 'client_id');
    }

    public function lead()
    {
        return $this->hasOne('App\Lead', 'id', 'lead_id');
    }

    public function milestones()
    {
        return $this->hasMany('App\Milestone', 'project_id', 'id');
    }

    public function timesheets()
    {
        return $this->hasMany('App\Timesheet', 'project_id', 'id');
    }

    public function files()
    {
        return $this->hasMany('App\ProjectFile', 'project_id', 'id');
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice', 'project_id', 'id');
    }

    public function expenses()
    {
        return $this->hasMany('App\Expense', 'project_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_projects');
    }

    public function permissions()
    {
        return $this->hasOne('App\ProjectClientPermissions', 'project_id', 'id');
    }


    public function stages($filter, $sort, $dir, $users, $open)
    {
        return Stage::with(['tasks' => function ($query) use ($filter, $sort, $dir, $users, $open)
        {
            if(empty($users)) {

                $query->where('project_id', '=', $this->id)
                        ->whereHas('stage', function ($query) use($open) {

                            $query->whereIn('open', $open);
                        })
                        ->where(function ($query) use ($filter) {
                            $query->where('title','like','%'.$filter.'%')
                            ->orWhereHas('project', function ($query) use($filter) {

                                $query->where('name','like','%'.$filter.'%');
                            });
                        })
                        ->orderBy($sort?$sort:'priority', $dir?$dir:'asc');
            }else {

                $query->where('project_id', '=', $this->id)
                        ->whereHas('stage', function ($query) use($open) {

                            $query->whereIn('open', $open);
                        })
                        ->where(function ($query) use ($filter) {
                            $query->where('title','like','%'.$filter.'%')
                            ->orWhereHas('project', function ($query) use($filter) {

                                $query->where('name','like','%'.$filter.'%');
                            });
                        })
                        ->whereHas('users', function ($query) use($users)
                        {
                            $query->whereIn('users.id', $users);

                        })->orderBy($sort?$sort:'order', $dir?$dir:'asc');
            }

        }], 'tasks.users')
        ->where('class', Task::class)
        ->where('created_by', $this->created_by)
        ->orderBy('order', 'ASC');
    }

    public function computeStatistics()
    {
        $this->progress = 0;
        $this->completed_tasks = 0;

        if(!$this->tasks->isEmpty())
        {
            foreach($this->tasks as $task)
            {
                if($task->stage && $task->stage->isClosed())
                    $this->completed_tasks++;
            }

            $this->progress = intval(($this->completed_tasks / $this->tasks->count()) * 100);
        }
    }

    public static function createProject($post)
    {
        if(isset($post['client_id']) && !is_numeric($post['client_id'])) {

            if(\Auth::user()->hasMaxClients()) return null;

            //new client
            $client = Client::createClient(['name' => $post['client_id']]);
            $post['client_id'] = $client->id;
        }

        if(isset($post['lead_id']) && !is_numeric($post['lead_id'])) {

            //new lead
            $lead = Lead::createLead(['name' => $post['lead_id'],
                                    'stage_id' => \Auth::user()->getFirstLeadStage()->id,
                                    'client_id' => $post['client_id']]);
            $post['lead_id'] = $lead->id;
        }

        $project              = Project::make($post);
        $project->save();

        if(isset($post['users']))
        {
            $users = collect($post['users']);
        }else{

            $users = collect();
        }

        $project->users()->sync($users);

        Activity::createProject($project);

        return $project;
    }

    public function updateProject($post)
    {
        if(isset($post['client_id']) && !is_numeric($post['client_id'])) {

            //new client
            $client = Client::createClient(['name' => $post['client_id']]);
            $post['client_id'] = $client->id;
        }

        if(isset($post['lead_id']) && !is_numeric($post['lead_id'])) {

            //new lead
            $lead = Lead::createLead(['name' => $post['lead_id'],
                                    'stage_id' => \Auth::user()->getFirstLeadStage()->id,
                                    'client_id' => $post['client_id']]);
            $post['lead_id'] = $lead->id;
        }        

        $this->update($post);

        if(isset($post['users']))
        {
            $users = collect($post['users']);
        }else{

            $users = collect();
        }

        $this->users()->sync($users);

        foreach($this->tasks as $task){

            $users = $task->users->intersect($this->users()->get());

            $task->users()->sync($users->pluck('id'));
        }

        Activity::updateProject($this);
    }

    static function humanFileSize($bytes, $decimals = 2) {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    static function translatePriority($priority)
    {
        switch($priority)
        {
            case 2: return __('low');
            case 1: return __('medium');
            default: return __('high');
        }
    }

    public function notifyAssignedUsers($users)
    {
        foreach($users as $user){

            $user = User::find($user);

            if($user && $user->notify_project_assign){

                $user->notify(new ProjectAssignedAlert($user, $this));
            }
        }
    }
    
    //used for filters
    public static $status = [
        'active',
        'archived'
    ];

}
