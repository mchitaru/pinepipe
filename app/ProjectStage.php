<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProjectStage extends Model
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

    public static function stagesByUserType()
    {
        if(\Auth::user()->type == 'client')
        {
            return ProjectStage::with(['tasks' => function ($query) 
            {
                $query->WhereHas('project', function ($query) {
                    
                    $query->where('client_id', '=', \Auth::user()->id);

                })->orderBy('order', 'ASC');
            },'tasks.users'])
            ->where('created_by', '=', \Auth::user()->creatorId())
            ->orderBy('order', 'ASC');

        }else if(\Auth::user()->type == 'company')
        {
            return ProjectStage::with(['tasks' => function ($query)
            {
                $query->orderBy('order', 'ASC');

            },'tasks.users'])
            ->where('created_by', '=', \Auth::user()->creatorId())
            ->orderBy('order', 'ASC');
        }else
        {
            return ProjectStage::with(['tasks' => function ($query)
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
                })->orderBy('order', 'ASC');

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
                return \Auth::user()->clientTasks()->where('tasks.stage_id', '=', $this->id)->orderBy('order');

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
            $date              = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));
            $arrDay['label'][] = date('D', mktime(0, 0, 0, $m, ($de - $i), $y));
            $arrDate[]         = $date;
        }

        if(\Auth::user()->type == 'client')
        {
            $stages = ProjectStage::with(['tasks' => function ($query) use ($arrDate)
            {                
                $query->whereIn(DB::raw("DATE(updated_at)"), $arrDate);
                $query->WhereHas('project', function ($query) {
                    
                    $query->where('client_id', '=', \Auth::user()->id);

                });
            }])
            ->where('created_by', '=', \Auth::user()->creatorId())
            ->orderBy('order', 'ASC')
            ->get();

        }else if(\Auth::user()->type == 'company')
        {
            $stages = ProjectStage::with(['tasks' => function ($query) use ($arrDate)
            {
                $query->whereIn(DB::raw("DATE(updated_at)"), $arrDate);
            }])
            ->where('created_by', '=', \Auth::user()->creatorId())
            ->orderBy('order', 'ASC')
            ->get();
        }else
        {
            $stages = ProjectStage::with(['tasks' => function ($query) use ($arrDate)
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

        // $stages  = ProjectStage::where('created_by', '=', $usr->creatorId())->get();
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

                $dataset['label'] = $stage->name;
                //                $dataset['fill']            = '!0';
                //                                $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor'] = $stage->color;
                //                $dataset['borderColor']     = $stage->color;
                $dataset['data'] = $data;
                $arrTask[]       = $dataset;
                $i++;
            }

            $arrTaskData = array_merge($arrDay, ['dataset' => $arrTask]);
            unset($arrTaskData['dataset'][$i - 1]['fill']);
            $arrTaskData['dataset'][$i - 1]['backgroundColor'] = '#ccc';

            return $arrTaskData;
        }
        elseif($usr->type == 'client')
        {
            foreach($stages as $key => $stage)
            {
                $data = [];
                foreach($arrDate as $d)
                {
                    // $data[] = Task::join('projects', 'tasks.project_id', '=', 'projects.id')->where('projects.client_id', '=', $usr->id)->where('stage_id', '=', $stage->id)->whereDate('tasks.updated_at', '=', $d)->count();
                    $data[] = $stage->tasks->filter(function ($item) use($d,$format) {
                        return date($format, strtotime($item['updated_at'])) == $d;
                    })->count();
                }

                $dataset['label']           = $stage->name;
                $dataset['fill']            = '!0';
                $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = $stage->color;
                $dataset['data']            = $data;
                $arrTask[]                  = $dataset;
                $i++;
            }
            $arrTaskData = array_merge($arrDay, ['dataset' => $arrTask]);
            unset($arrTaskData['dataset'][$i - 1]['fill']);
            $arrTaskData['dataset'][$i - 1]['backgroundColor'] = '#ccc';

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
                $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = $stage->color;
                $dataset['data']            = $data;
                $arrTask[]                  = $dataset;
                $i++;
            }
            $arrTaskData = array_merge($arrDay, ['dataset' => $arrTask]);
            unset($arrTaskData['dataset'][$i - 1]['fill']);
            $arrTaskData['dataset'][$i - 1]['backgroundColor'] = '#ccc';

            return $arrTaskData;
        }
    }
}
