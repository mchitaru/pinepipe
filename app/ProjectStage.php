<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public function tasks()
    {
        return $this->hasMany('App\Task', 'stage_id', 'id');
    }

    public function getTasksByUserType($project_id)
    {
        if(!empty($project_id))
        {
            return $this->tasks()->where('project_id', '=', $project_id)->orderBy('order')->get();
        }
        else
        {
            if(\Auth::user()->type == 'client')
            {
                return \Auth::user()->clientTasks()->where('stage_id', '=', $this->id)->orderBy('order')->get();

            }else if(\Auth::user()->type == 'company')
            {
                return $this->tasks()->orderBy('order')->get();

            }else
            {
                return $this->tasks()->whereHas('project', function ($query) {
                    // only include tasks with projects where...
                    $query->whereHas('users', function ($query) {

                        // ...the current user is assigned.
                        $query->where('users.id', \Auth::user()->id);
                    });
                })->where('stage_id', '=', $this->id)->orderBy('order')->get();
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

        $stages  = ProjectStage::where('created_by', '=', $usr->creatorId())->get();
        $arrTask = [];

        $i = 0;
        if($usr->type == 'company')
        {
            foreach($stages as $key => $stage)
            {
                $data = [];
                foreach($arrDate as $d)
                {
                    $data[] = Task::where('stage_id', '=', $stage->id)->whereDate('updated_at', '=', $d)->count();
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
                    $data[] = Task::join('projects', 'tasks.project_id', '=', 'projects.id')->where('projects.client_id', '=', $usr->id)->where('stage_id', '=', $stage->id)->whereDate('tasks.updated_at', '=', $d)->count();
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
                    $data[] = $usr->tasks()->where('stage_id', '=', $stage->id)->whereDate('tasks.updated_at', '=', $d)->count();
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
