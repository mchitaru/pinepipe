<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TaskStage extends Model
{
    protected $fillable = [
        'name',
        'color',
        'created_by',
        'order',
    ];


    protected $hidden = [

    ];

    public static $SEED = 4;

    public function tasks()
    {
        return $this->hasMany('App\Task', 'stage_id', 'id');
    }

    public static function stagesByUserType($sort, $dir)
    {
        if(\Auth::user()->type == 'client')
        {
            return TaskStage::with(['tasks' => function ($query) use($sort, $dir)
            {
                $query->WhereHas('project', function ($query) {
                    
                    $query->where('client_id', '=', \Auth::user()->client_id);

                })->orderBy($sort?$sort:'priority', $dir?$dir:'asc');
            },'tasks.users'])
            ->where('created_by', '=', \Auth::user()->creatorId())
            ->orderBy('order', 'ASC');

        }else if(\Auth::user()->type == 'company')
        {
            return TaskStage::with(['tasks' => function ($query) use($sort, $dir)
            {
                $query->orderBy($sort?$sort:'priority', $dir?$dir:'asc');

            },'tasks.users'])
            ->where('created_by', '=', \Auth::user()->creatorId())
            ->orderBy('order', 'ASC');
        }else
        {
            return TaskStage::with(['tasks' => function ($query) use($sort, $dir)
            {
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
                })->orderBy($sort?$sort:'priority', $dir?$dir:'asc');

            },'tasks.users'])
            ->where('created_by', '=', \Auth::user()->creatorId())
            ->orderBy('order', 'ASC');
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

    public static function getChartData()
    {
        $usr     = \Auth::user();
        $m       = date("m");
        $de      = date("d");
        $y       = date("Y");
        $format  = 'Y-m-d';
        $arrDate = [];
        $arrDay  = [];

        for($i = 0; $i <= 7 - 1; $i++)
        {
            $timestamp         = mktime(0, 0, 0, $m, ($de - $i), $y); 
            $date              = date($format, $timestamp);

            $arrDay['label'][] = ($date != date($format))?date('M d', $timestamp):__('Today');
            $arrDate[]         = $date;
        }

        if(\Auth::user()->type == 'client')
        {
            $stages = TaskStage::with(['tasks' => function ($query) use ($arrDate)
            {                
                $query->whereIn(DB::raw("DATE(updated_at)"), $arrDate);
                $query->WhereHas('project', function ($query) {
                    
                    $query->where('client_id', '=', \Auth::user()->client_id);

                });
            }])
            ->where('created_by', '=', \Auth::user()->creatorId())
            ->orderBy('order', 'ASC')
            ->get();

        }else if(\Auth::user()->type == 'company')
        {
            $stages = TaskStage::with(['tasks' => function ($query) use ($arrDate)
            {
                $query->whereIn(DB::raw("DATE(updated_at)"), $arrDate);
            }])
            ->where('created_by', '=', \Auth::user()->creatorId())
            ->orderBy('order', 'ASC')
            ->get();
        }else
        {
            $stages = TaskStage::with(['tasks' => function ($query) use ($arrDate)
            {
                $query->whereIn(DB::raw("DATE(updated_at)"), $arrDate);
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
                });

            }])
            ->where('created_by', '=', \Auth::user()->creatorId())
            ->orderBy('order', 'ASC')
            ->get();
        }

        // $stages  = TaskStage::where('created_by', '=', $usr->creatorId())->get();
        $arrTask = [];

        $i = 0;
        if($usr->type == 'company')
        {
            foreach($stages as $key => $stage)
            {
                $data = [];
                foreach($arrDate as $d)
                {
                    // $data[] = Task::where('stage_id', '=', $stage->id)->whereDate('updated_at', '=', $d)->count();
                    $data[] = $stage->tasks->filter(function ($item) use($d,$format) {
                        return date($format, strtotime($item['updated_at'])) == $d;
                    })->count();
                }

                $dataset['label']           = $stage->name;
                $dataset['fill']            = '!0';
                $dataset['backgroundColor'] = $stage->color;
                $dataset['borderColor']     = $stage->color;
                $dataset['data']            = $data;
                $arrTask[]                  = $dataset;
                $i++;
            }

            $arrTaskData = array_merge($arrDay, ['dataset' => $arrTask]);

            return $arrTaskData;
        }
        elseif($usr->type == 'client')
        {
            foreach($stages as $key => $stage)
            {
                $data = [];
                foreach($arrDate as $d)
                {
                    $data[] = $stage->tasks->filter(function ($item) use($d,$format) {
                        return date($format, strtotime($item['updated_at'])) == $d;
                    })->count();
                }

                $dataset['label']           = $stage->name;
                $dataset['fill']            = '!0';
                $dataset['backgroundColor'] = $stage->color;
                $dataset['borderColor']     = $stage->color;
                $dataset['data']            = $data;
                $arrTask[]                  = $dataset;
                $i++;
            }
            $arrTaskData = array_merge($arrDay, ['dataset' => $arrTask]);

            return $arrTaskData;
        }
        else
        {
            foreach($stages as $key => $stage)
            {
                $data = [];
                foreach($arrDate as $d)
                {
                    // $data[] = $usr->tasks()->where('stage_id', '=', $stage->id)->whereDate('tasks.updated_at', '=', $d)->count();
                    $data[] = $stage->tasks->filter(function ($item) use($d,$format) {
                        return date($format, strtotime($item['updated_at'])) == $d;
                    })->count();
                }


                $dataset['label']           = $stage->name;
                $dataset['fill']            = '!0';
                $dataset['backgroundColor'] = $stage->color;
                $dataset['borderColor']     = $stage->color;
                $dataset['data']            = $data;
                $arrTask[]                  = $dataset;
                $i++;
            }
            $arrTaskData = array_merge($arrDay, ['dataset' => $arrTask]);

            return $arrTaskData;
        }
    }
}
