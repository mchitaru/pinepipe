<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Scopes\CollaboratorTenantScope;

class Stage extends Model
{
    protected $fillable = [
        'name',
        'class',
        'order',
        'open',
        'user_id',
        'created_by'
    ];

    protected $hidden = [

    ];

    public static $LEAD_SEED = 5;
    public static $TASK_SEED = 4;


    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CollaboratorTenantScope);

        static::creating(function ($stage) {

            if ($user = \Auth::user()) {
                $stage->user_id = $user->id;
                $stage->created_by = $user->created_by;
            }
        });

        static::deleting(function ($stage) {

            $stage->tasks()->each(function($task) {
                $task->delete();
            });

            $stage->leads()->each(function($lead) {
                $lead->delete();
            });
        });
    }

    public function isOpen(){

        return $this->open == 1;
    }

    public function isClosed(){

        return $this->open == 0;
    }

    public function tasks()
    {
        return $this->hasMany('App\Task', 'stage_id', 'id');
    }

    public function leads()
    {
        return $this->hasMany('App\Lead', 'stage_id', 'id');
    }

    public function computeStatistics()
    {
        $this->lead_count = 0;
        $this->lead_total = 0;

        foreach($this->leads as $lead)
        {
            if(\Auth::user()->can('view', $lead)) {

                $this->lead_count++;
                $this->lead_total += $lead->price;
            }            
        }
    }

    public static function filterTaskStages($filter, $sort, $dir, $users, $select)
    {
        return Stage::with(['tasks' => function ($query) use($filter, $sort, $dir, $users)
        {
            if(empty($users)) {
                
                $query->where(function ($query) use ($filter) {
                    $query->where('title','like','%'.$filter.'%')
                    ->orWhereHas('project', function ($query) use($filter) {

                        $query->where('name','like','%'.$filter.'%');
                    })
                    ->orWhereHas('tags', function ($query) use($filter)
                    {
                        $query->where('tags.name','like','%'.$filter.'%');

                    });
                })->orderBy($sort?$sort:'order', $dir?$dir:'asc');

            }else{
                
                $query->whereHas('users', function ($query) use($users)
                        {
                            $query->whereIn('users.id', $users);

                        })
                        ->where(function ($query) use ($filter) {
                            $query->where('title','like','%'.$filter.'%')
                            ->orWhereHas('project', function ($query) use($filter) {

                                $query->where('name','like','%'.$filter.'%');
                            })
                            ->orWhereHas('tags', function ($query) use($filter)
                            {
                                $query->where('tags.name','like','%'.$filter.'%');

                            });
                        })->orderBy($sort?$sort:'order', $dir?$dir:'asc');
            }

        },'tasks.users'])
        ->where('class', Task::class)
        ->where('created_by', $select)
        ->orderBy('order', 'ASC');
    }

    public static function filterLeadStages($filter, $sort, $dir, $tag)
    {
        if($tag){
            $status = array(array_search($tag, Lead::$status));
        }else{
            $status = array(array_search('active', Lead::$status));
        }

        return Stage::with(['leads' => function ($query) use($filter, $sort, $dir, $status){

                    $query->whereIn('archived', $status)
                            ->where(function ($query) use ($filter) {
                                $query->where('name','like','%'.$filter.'%')
                                ->orWhereHas('client', function ($query) use($filter) {
        
                                    $query->where('name','like','%'.$filter.'%');
                                });
                            })    
                            ->orderBy($sort?$sort:'order', $dir?$dir:'asc');

                },'leads.client','leads.user'])
                ->where('class', Lead::class)
                ->where('created_by', \Auth::user()->created_by)
                ->orderBy('order');
    }

    public function updateOrder($order)
    {
        $updated = ($this->order != $order);

        if($updated){

            $this->order = $order;
            $this->save();
        }

        return $updated;
    }

}
