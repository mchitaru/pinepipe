<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

use App\Notifications\TaskAssignedAlert;

use App\Traits\Invoiceable;
use App\Traits\Checklistable;
use App\Traits\Commentable;
use App\Traits\Stageable;
use App\Traits\Taggable;
use App\Traits\Actionable;

use App\Scopes\CollaboratorTenantScope;

class Task extends Model implements HasMedia
{
    use NullableFields, HasMediaTrait, Invoiceable, Checklistable, Commentable, Taggable, Stageable, Actionable;

    protected $fillable = [
        'title',
        'priority',
        'description',
        'start_date',
        'due_date',
        'project_id',
        'milestone_id',
        'order',
        'stage_id',
        'user_id',
        'created_by'
    ];

    protected $nullable = [
        'project_id',
        'milestone_id',
        'description',
        'start_date',
        'due_date'
	];

    public static $SEED_PROJECT = 50;
    public static $SEED_FREE = 10;

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CollaboratorTenantScope);

        static::creating(function ($task) {
            if ($user = \Auth::user()) {
                $task->user_id = $user->id;
                $task->created_by = $user->created_by;
            }
        });

        static::updated(function ($task) {

            if(!$task->stage->open) {
                
                $task->timesheets()->each(function($time) {

                    if($time->isStarted()){

                        $time->stop();
                    }
                });    
            }
        });

        static::deleting(function ($task) {

            $task->users()->detach();
            $task->tags()->detach();
    
            $task->comments()->each(function($comment) {
                $comment->delete();
            });

            $task->checklist()->each(function($check) {
                $check->delete();
            });

            $task->invoiceables()->each(function($inv) {
                $inv->delete();
            });

            $task->activities()->delete();
        });
    }

    public function company()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_tasks');
    }

    public function stage()
    {
        return $this->hasOne('App\Stage', 'id', 'stage_id');
    }

    public function milestone()
    {
        return $this->hasOne('App\Milestone','id','milestone_id');
    }

    public function timesheets()
    {
        return $this->hasMany('App\Timesheet', 'task_id', 'id');
    }

    public static function createTask($post)
    {
        $task               = Task::make($post);
        $task->save();

        if(isset($post['users'])){

            $users = $post['users'];
        }else{

            $users = [];
        }

        if(\Auth::user()->type != 'company' && empty($users)){

            $users[] = \Auth::user()->id;
        }

        $task->users()->sync($users);
        $task->syncTags(isset($post['tags'])?$post['tags']:[]);

        Activity::createTask($task);

        return $task;
    }

    public function updateTask($post, $patch)
    {
        $this->update($post);

        if(!$patch) {

            if(isset($post['users']))
            {
                $users = $post['users'];
            }else{

                $users = [];
            }

            if(\Auth::user()->type != 'company' && empty($users)){

                $users[] = \Auth::user()->id;
            }

            $this->users()->sync($users);
            $this->syncTags(isset($post['tags'])?$post['tags']:[]);
        }

        Activity::updateTask($this);
    }

    public function updateOrder($stage, $order)
    {
        $updated = ($this->stage_id != $stage);

        $this->order = $order;
        $this->stage_id = $stage;
        $this->save();

        if($updated) {

            Activity::updateTask($this);
        }

        return $updated;
    }

    public function notifyAssignedUsers($users)
    {
        foreach($users as $user){

            $user = User::find($user);

            if($user && $user->notify_task_assign){

                $user->notify(new TaskAssignedAlert($user, $this));
            }
        }
    }
}
