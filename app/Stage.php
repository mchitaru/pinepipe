<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Scopes\CompanyTenantScope;

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

        static::addGlobalScope(new CompanyTenantScope);

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
        $this->total_amount = 0;

        foreach($this->leads as $lead)
        {
            $this->total_amount += $lead->price;
        }
    }

    public function tasksByUserType($project_id)
    {
        if(!empty($project_id))
        {
            return $this->tasks()->where('project_id', '=', $project_id)->orderBy('order');
        }
        else
        {
            if(\Auth::user()->type == 'client')
            {
                return \Auth::user()->client->tasks()->where('tasks.stage_id', '=', $this->id)->orderBy('order');

            }else if(\Auth::user()->type == 'company')
            {
                return $this->tasks()->orderBy('order');

            }else
            {
                return \Auth::user()->staffTasks()->where('tasks.stage_id', '=', $this->id)->orderBy('order');
            }
        }
    }

    public static function taskStagesByUserType($filter, $sort, $dir, $users)
    {
        if(\Auth::user()->type == 'client')
        {
            return Stage::with(['tasks' => function ($query) use($filter, $sort, $dir, $users)
            {
                if(empty($users)) {

                    $query->whereHas('project', function ($query) {

                                $query->where('client_id', '=', \Auth::user()->client_id);
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
                            })
                            ->orderBy($sort?$sort:'order', $dir?$dir:'asc');

                }else{

                    $query->whereHas('project', function ($query) {

                                $query->where('client_id', '=', \Auth::user()->client_id);
                            })
                            ->whereHas('users', function ($query) use($users)
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
                            })
                            ->orderBy($sort?$sort:'order', $dir?$dir:'asc');
                }

            },'tasks.users'])
            ->where('class', Task::class)
            ->orderBy('order', 'ASC');

        }else if(\Auth::user()->type == 'company')
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
            ->orderBy('order', 'ASC');
        }else
        {
            return Stage::with(['tasks' => function ($query) use($filter, $sort, $dir, $users)
            {
                if(empty($users)) {

                    $query->whereHas('users', function ($query)
                    {
                        // tasks with the current user assigned.
                        $query->where('users.id', \Auth::user()->id);

                    })->orWhereHas('project', function ($query) {

                        // only include tasks with projects where...
                        $query->whereHas('users', function ($query) {

                            // ...the current user is assigned.
                            $query->where('users.id', \Auth::user()->id);
                        });
                    })->where(function ($query) use ($filter) {
                        $query->where('title','like','%'.$filter.'%')
                        ->orWhereHas('project', function ($query) use($filter) {

                            $query->where('name','like','%'.$filter.'%');
                        })
                        ->orWhereHas('tags', function ($query) use($filter)
                        {
                            $query->where('tags.name','like','%'.$filter.'%');

                        });
                    })->orderBy($sort?$sort:'order', $dir?$dir:'asc');
                }else {

                    $query->whereHas('users', function ($query) use($users)
                    {
                        // tasks with the current user assigned.
                        $query->whereIn('users.id', $users);

                    })->where(function ($query) use ($filter) {
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
            ->orderBy('order', 'ASC');
        }
    }

    public static function leadStagesByUserType($filter, $sort, $dir, $tag)
    {
        if($tag){
            $status = array(array_search($tag, Lead::$status));
        }else{
            $status = array(array_search('active', Lead::$status));
        }

        if(\Auth::user()->type == 'client')
        {
            return Stage::with(['leads' => function ($query) use($filter, $sort, $dir, $status){

                        $query->whereIn('archived', $status)
                                ->where('leads.client_id', \Auth::user()->client_id)
                                ->where(function ($query) use ($filter) {
                                    $query->where('name','like','%'.$filter.'%')
                                    ->orWhereHas('client', function ($query) use($filter) {
            
                                        $query->where('name','like','%'.$filter.'%');
                                    });
                                })            
                                ->orderBy($sort?$sort:'order', $dir?$dir:'asc');
                    },
                    'leads.client','leads.user'])
                    ->where('class', Lead::class)
                    ->orderBy('order');
        }
        elseif(\Auth::user()->type == 'company')
        {
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
                    ->orderBy('order');

        }else
        {
            return Stage::with(['leads' => function ($query) use($filter, $sort, $dir, $status){

                        $query->whereIn('archived', $status)
                                ->where('leads.user_id', \Auth::user()->id)
                                ->where(function ($query) use ($filter) {
                                    $query->where('name','like','%'.$filter.'%')
                                    ->orWhereHas('client', function ($query) use($filter) {
            
                                        $query->where('name','like','%'.$filter.'%');
                                    });
                                })            
                                ->orderBy($sort?$sort:'order', $dir?$dir:'asc');
                    },
                    'leads.client','leads.user'])
                    ->where('class', Lead::class)
                    ->orderBy('order');
        }
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
