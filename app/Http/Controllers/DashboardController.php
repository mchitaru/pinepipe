<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Invoice;
use App\PaymentPlan;
use App\Project;
use App\User;
use App\Client;
use App\Subscription;
class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(\Auth::user()->type == 'company'){

            clock()->startEvent('DahsboardController', "Load dash");

            $todayTasks = \Auth::user()->getTodayTasks();
            $thisWeekTasks = \Auth::user()->getThisWeekTasks();
            $nextWeekTasks = \Auth::user()->getNextWeekTasks();

            $todayEvents = \Auth::user()->getTodayEvents();
            $thisWeekEvents = \Auth::user()->getThisWeekEvents();
            $nextWeekEvents = \Auth::user()->getNextWeekEvents();

            $projects = Project::where('created_by', '=', \Auth::user()->creatorId())
                                    ->where('archived', '0')
                                    ->get();

            $invoices = Invoice::with('project')
                                    ->where('created_by', '=', \Auth::user()->creatorId())
                                    ->where('status', '<', '3')
                                    ->get();
            $leads = [];
            $tasks = \Auth::user()->tasks()
                                    ->where('stage_id', '<', \Auth::user()->last_projectstage()->id)
                                    ->get();

            clock()->endEvent('DashboardController');

            return view('dashboard.company', compact('todayTasks', 'thisWeekTasks', 'nextWeekTasks',
                                                    'todayEvents', 'thisWeekEvents', 'nextWeekEvents',
                                                    'projects', 'tasks', 'invoices', 'leads'));

        }else if(\Auth::user()->type == 'client'){

            clock()->startEvent('DahsboardController', "Load dash");

            $todayTasks = \Auth::user()->getTodayTasks();
            $thisWeekTasks = \Auth::user()->getThisWeekTasks();
            $nextWeekTasks = \Auth::user()->getNextWeekTasks();

            $todayEvents = \Auth::user()->getTodayEvents();
            $thisWeekEvents = \Auth::user()->getThisWeekEvents();
            $nextWeekEvents = \Auth::user()->getNextWeekEvents();

            $projects = Project::where('created_by', '=', \Auth::user()->creatorId())
                                    ->where('client_id', \Auth::user()->client_id)
                                    ->where('archived', '0')
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
                                    ->get();

            $tasks = \Auth::user()->tasks()
                                    ->where('stage_id', '<', \Auth::user()->last_projectstage()->id)
                                    ->get();

            clock()->endEvent('DashboardController');

            return view('dashboard.client', compact('todayTasks', 'thisWeekTasks', 'nextWeekTasks',
                                                    'todayEvents', 'thisWeekEvents', 'nextWeekEvents',
                                                    'projects', 'tasks', 'invoices'));

        }else if(\Auth::user()->type == 'super admin'){

            clock()->startEvent('DahsboardController', "Load dash");

            $user = \Auth::user();
            $user['total_user']=$user->countCompany();
            $user['total_paid_user']=$user->countPaidCompany();
            $user['total_orders'] = Subscription::count();
            $user['total_orders_price'] = 0;
            $user['total_plan'] = PaymentPlan::total_plan();
            $user['most_purchese_plan'] = 0;
            $chartData = $this->getOrderChart(['duration'=>'week']);

            clock()->endEvent('DashboardController');

            return view('dashboard.admin',compact('user','chartData'));

        }else {

            clock()->startEvent('DahsboardController', "Load dash");

            $todayTasks = \Auth::user()->getTodayTasks();
            $thisWeekTasks = \Auth::user()->getThisWeekTasks();
            $nextWeekTasks = \Auth::user()->getNextWeekTasks();

            $todayEvents = \Auth::user()->getTodayEvents();
            $thisWeekEvents = \Auth::user()->getThisWeekEvents();
            $nextWeekEvents = \Auth::user()->getNextWeekEvents();

            $projects = \Auth::user()->projects()
                                    ->where('archived', '0')
                                    ->get();

            $leads = [];
            $tasks = \Auth::user()->tasks()
                                    ->where('stage_id', '<', \Auth::user()->last_projectstage()->id)
                                    ->get();

            clock()->endEvent('DashboardController');

            return view('dashboard.collaborator', compact('todayTasks', 'thisWeekTasks', 'nextWeekTasks',
                                                            'todayEvents', 'thisWeekEvents', 'nextWeekEvents',
                                                            'projects', 'tasks', 'leads'));
        }
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
}

