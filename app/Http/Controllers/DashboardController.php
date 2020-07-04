<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Invoice;
use App\SubscriptionPlan;
use App\Project;
use Carbon\Carbon;
use App\User;
use App\Client;
use App\Subscription;
use App\Task;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //admin dash
        if(\Auth::user()->type == 'super admin'){

            clock()->startEvent('DahsboardController', "Load dash");

            $user = \Auth::user();
            $user['total_user']=$user->countCompany();
            $user['total_paid_user']=$user->countPaidCompany();
            $user['total_orders'] = Subscription::count();
            $user['total_orders_price'] = 0;
            $user['total_plan'] = SubscriptionPlan::total_plan();
            $user['most_purchese_plan'] = 0;
            $chartData = $this->getOrderChart(['duration'=>'week']);

            clock()->endEvent('DashboardController');

            return view('dashboard.admin',compact('user','chartData'));
        }


        clock()->startEvent('DahsboardController', "Load dash");

        $lastTaskStageId = \Auth::user()->getLastTaskStage()->id;
        $lastLeadStageId = \Auth::user()->getLastLeadStage()->id;

        $todayTasks = \Auth::user()->getTodayTasks($lastTaskStageId);
        $thisWeekTasks = \Auth::user()->getThisWeekTasks($lastTaskStageId);
        $nextWeekTasks = \Auth::user()->getNextWeekTasks($lastTaskStageId);

        $todayEvents = \Auth::user()->getTodayEvents();
        $thisWeekEvents = \Auth::user()->getThisWeekEvents();
        $nextWeekEvents = \Auth::user()->getNextWeekEvents();

        if(\Auth::user()->type == 'company'){

            $projects = Project::where('created_by', '=', \Auth::user()->creatorId())
                                    ->where('archived', '0')
                                    ->orderBy('due_date', 'ASC')
                                    ->get();

            $invoices = Invoice::with('project')
                                    ->where('created_by', '=', \Auth::user()->creatorId())
                                    ->where('status', '<', '3')
                                    ->orderBy('due_date', 'ASC')
                                    ->get();

            $leads = \Auth::user()->leads()
                                    ->where('stage_id', '<', $lastLeadStageId)
                                    ->whereDate('updated_at', '<', Carbon::now()->subDays(7))
                                    ->orderBy('order', 'ASC')
                                    ->get();

            $tasks = \Auth::user()->tasks()
                                    ->where('stage_id', '<', $lastTaskStageId)
                                    ->where(function ($query){
                                        $query->where('priority', 'high')
                                                ->orWhereDate('due_date', '=', Carbon::now());
                                    })
                                    ->orderBy('priority', 'ASC')
                                    ->get();

            clock()->endEvent('DashboardController');

            return view('dashboard.company', compact('todayTasks', 'thisWeekTasks', 'nextWeekTasks',
                                                    'todayEvents', 'thisWeekEvents', 'nextWeekEvents',
                                                    'projects', 'tasks', 'invoices', 'leads'));

        }else if(\Auth::user()->type == 'client'){

            $projects = Project::where('created_by', '=', \Auth::user()->creatorId())
                                    ->where('client_id', \Auth::user()->client_id)
                                    ->where('archived', '0')
                                    ->orderBy('due_date', 'ASC')
                                    ->get();

            $invoices = Invoice::with('project')
                                    ->whereHas('project', function ($query)
                                    {
                                        $query->whereHas('client', function ($query)
                                        {
                                            $query->where('id', \Auth::user()->client_id);
                                        });
                                    })
                                    ->where('created_by', '=', \Auth::user()->creatorId())
                                    ->where('status', '<', '3')
                                    ->orderBy('due_date', 'ASC')
                                    ->get();

            $tasks = \Auth::user()->tasks()
                                    ->where('stage_id', '<', $lastTaskStageId)
                                    ->where(function ($query){
                                        $query->where('priority', 'high')
                                                ->orWhereDate('due_date', '=', Carbon::now());
                                    })
                                    ->orderBy('priority', 'ASC')
                                    ->get();

            clock()->endEvent('DashboardController');

            return view('dashboard.client', compact('todayTasks', 'thisWeekTasks', 'nextWeekTasks',
                                                    'todayEvents', 'thisWeekEvents', 'nextWeekEvents',
                                                    'projects', 'tasks', 'invoices'));

        }

        //collaborator dash
        $projects = \Auth::user()->projects()
                                ->where('archived', '0')
                                ->orderBy('due_date', 'ASC')
                                ->get();

        $leads = \Auth::user()->leads()
                                ->where('stage_id', '<', $lastLeadStageId)
                                ->whereDate('updated_at', '<', Carbon::now()->subDays(7))
                                ->orderBy('order', 'ASC')
                                ->get();
                                
        $tasks = \Auth::user()->tasks()
                                ->where('stage_id', '<', $lastTaskStageId)
                                ->where(function ($query){
                                    $query->where('priority', 'high')
                                            ->orWhereDate('due_date', '=', Carbon::now());
                                })
                                ->orderBy('priority', 'ASC')
                                ->get();

        clock()->endEvent('DashboardController');

        return view('dashboard.collaborator', compact('todayTasks', 'thisWeekTasks', 'nextWeekTasks',
                                                        'todayEvents', 'thisWeekEvents', 'nextWeekEvents',
                                                        'projects', 'tasks', 'leads'));
    }

    public function getOrderChart($arrParam){
        $arrDuration = [];
        if($arrParam['duration']){

            if($arrParam['duration'] == 'week'){
                $previous_week = strtotime("-1 week +1 day");
                for ($i=0;$i<7;$i++){
                    $arrDuration[date('Y-m-d',$previous_week)] = date('D',$previous_week);
                    $previous_week = strtotime(date('Y-m-d',$previous_week). " +1 day");
                }
            }
        }

        $arrTask = [];
        $arrTask['label'] = [];
        $arrTask['data'] = [];
        foreach ($arrDuration as $date => $label){

            $data = Subscription::select(\DB::raw('count(*) as total'))
                         ->whereDate('created_at','=',$date)->first();
            $arrTask['label'][]=$label;
            $arrTask['data'][]=$data->total;
        }
        return $arrTask;
    }

    public function search($search)
    {
        if(\Auth::user()->type == 'client')
        {
            $objProject = Project::select(
                [
                    'projects.id',
                    'projects.name',
                ]
            )->where('projects.client_id', '=', Auth::user()->client_id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('projects.name', 'LIKE', $search . "%")->get();
            $arrProject = [];
            foreach($objProject as $project)
            {
                $arrProject[] = [
                    'text' => $project->name,
                    'link' => route('projects.show', [$project->id]),
                ];
            }

            $objTask = Task::select(
                [
                    'tasks.id',
                    'tasks.project_id',
                    'tasks.title',
                ]
            )->join('projects', 'tasks.project_id', '=', 'projects.id')->where('projects.client_id', '=', Auth::user()->client_id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('tasks.title', 'LIKE', $search . "%")->get();
            $arrTask = [];
            foreach($objTask as $task)
            {
                $arrTask[] = [
                    'text' => $task->title,
                    'link' => route('tasks.show', [$task->id]),
                    'param' => 'data-remote="true" data-type="text"'
                ];
            }
        }
        else if(\Auth::user()->type == 'company')
        {
            $objProject = Project::select(
                [
                    'projects.id',
                    'projects.name',
                ]
            )->where('projects.created_by', '=', \Auth::user()->id)->where('projects.name', 'LIKE', $search . "%")->get();
            $arrProject = [];
            foreach($objProject as $project)
            {
                $arrProject[] = [
                    'text' => $project->name,
                    'link' => route('projects.show', [$project->id]),
                ];
            }

            $objTask = Task::select(
                [
                    'tasks.id',
                    'tasks.project_id',
                    'tasks.title',
                ]
            )->join('projects', 'tasks.project_id', '=', 'projects.id')->where('projects.created_by', '=', \Auth::user()->id)->where('tasks.title', 'LIKE', $search . "%")->get();
            $arrTask = [];
            foreach($objTask as $task)
            {
                $arrTask[] = [
                    'text' => $task->title,
                    'link' => route('tasks.show', [$task->id]),
                    'param' => 'data-remote="true" data-type="text"'
                ];
            }
        }
        else
        {
            $objProject = Project::select(
                [
                    'projects.id',
                    'projects.name',
                ]
            )->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', Auth::user()->id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('projects.name', 'LIKE', $search . "%")->get();
            $arrProject = [];
            foreach($objProject as $project)
            {
                $arrProject[] = [
                    'text' => $project->name,
                    'link' => route('projects.show', [$project->id]),
                ];
            }

            $objTask = Task::select(
                [
                    'tasks.id',
                    'tasks.project_id',
                    'tasks.title',
                ]
            )->join('projects', 'tasks.project_id', '=', 'projects.id')->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', Auth::user()->id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('tasks.title', 'LIKE', $search . "%")->get();
            $arrTask = [];
            foreach($objTask as $task)
            {
                $arrTask[] = [
                    'text' => $task->title,
                    'link' => route('tasks.show', [$task->id]),
                    'param' => 'data-remote="true" data-type="text"'
                ];
            }
        }

        return json_encode(
            [
                'Projects' => $arrProject,
                'Tasks' => $arrTask,
            ]
        );
    }
}

