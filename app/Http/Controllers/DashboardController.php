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
                                    ->orderBy('due_date', 'ASC')
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
                                ->orderBy('due_date', 'ASC')
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
        //projects                                    
        $arrProject = [];
        if(\Auth::user()->can('view project')){

            $projects = \Auth::user()->projectsByUserType()
            ->with(['tasks', 'users', 'client'])
            ->where(function ($query) use ($search) {
                $query->where('name','like', $search.'%');
            })
            ->get();

            foreach($projects as $project)
            {
                $arrProject[] = [
                    'text' => $project->name,
                    'link' => route('projects.show', [$project->id]),
                ];
            }
        }

        //tasks
        $arrTask = [];
        if(\Auth::user()->can('view task')){

            $tasks = \Auth::user()->tasksByUserType()
                                    ->with(['users'])
                                    ->where(function ($query) use ($search) {
                                        $query->where('title','like', $search.'%');
                                    })
                                    ->get();

            foreach($tasks as $task)
            {
                $arrTask[] = [
                    'text' => $task->title,
                    'link' => route('tasks.show', [$task->id]),
                    'param' => 'data-remote="true" data-type="text"'
                ];
            }
        }

        //events
        $arrEvent = [];
        $events = \Auth::user()->eventsByUserType()
                                ->where(function ($query) use ($search) {
                                    $query->where('name','like', $search.'%');
                                })
                                ->get();

        foreach($events as $event)
        {
            $arrEvent[] = [
                'text' => $event->name,
                'link' => route('events.edit', [$event->id]),
                'param' => 'data-remote="true" data-type="text"'
            ];
        }

        //clients
        $arrClient = [];
        if(\Auth::user()->can('view client')){

            $clients = \Auth::user()->clientsByUserType()
                                        ->where(function ($query) use ($search) {
                                            $query->where('name','like', $search.'%');
                                        })
                                        ->get();

            foreach($clients as $client)
            {
                $arrClient[] = [
                    'text' => $client->name,
                    'link' => route('clients.show', [$client->id]),
                ];
            }
        }

        //contacts
        $arrContact = [];
        if(\Auth::user()->can('view contact')){

            $contacts = \Auth::user()->contactsByUserType()
                                    ->where(function ($query) use ($search) {
                                        $query->where('name','like', $search.'%');
                                    })
                                    ->get();

            foreach($contacts as $contact)
            {
                $arrContact[] = [
                    'text' => $contact->name,
                    'link' => route('contacts.edit', [$contact->id]),
                    'param' => 'data-remote="true" data-type="text"'
                ];
            }
        }

        //leads
        $arrLead = [];
        if(\Auth::user()->can('view lead')){

            $leads = \Auth::user()->leadsByUserType()
                                    ->where(function ($query) use ($search) {
                                        $query->where('name','like', $search.'%');
                                    })
                                    ->get();

            foreach($leads as $lead)
            {
                $arrLead[] = [
                    'text' => $lead->name,
                    'link' => route('leads.show', [$lead->id])
                ];
            }
        }

        //invoices
        $arrInvoice = [];
        if(\Auth::user()->can('view invoice')){

            $invoices = \Auth::user()->invoicesByUserType()
                                    ->where(function ($query) use ($search) {
                                        $query->where('invoice_id','like', $search.'%');
                                    })
                                    ->get();

            foreach($invoices as $invoice)
            {
                $arrInvoice[] = [
                    'text' => Auth::user()->invoiceNumberFormat($invoice->invoice_id),
                    'link' => route('invoices.show', [$invoice->id])
                ];
            }
        }

        // //expenses
        // $arrExpense = [];
        // if(\Auth::user()->can('view expense')){

        //     $expenses = \Auth::user()->expensesByUserType()
        //                             ->where(function ($query) use ($search) {
        //                                 $query->where('name','like', $search.'%');
        //                             })
        //                             ->get();

        //     foreach($expense as $expense)
        //     {
        //         $arrExpense[] = [
        //             'text' => $expense->name,
        //             'link' => route('expenses.edit', [$expense->id]),
        //             'param' => 'data-remote="true" data-type="text"'
        //         ];
        //     }
        // }
        
        return json_encode(
            [
                'Projects' => $arrProject,
                'Tasks' => $arrTask,
                'Events' => $arrEvent,
                'Clients' => $arrClient,
                'Contacts' => $arrContact,
                'Leads' => $arrLead,
                'Invoices' => $arrInvoice,
                // 'Expenses' => $arrExpense
            ]
        );
    }
}

