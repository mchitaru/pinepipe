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
use Illuminate\Support\Facades\Gate;
use App\Chart;

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
        if(\Auth::user()->isSuperAdmin()){

            clock()->startEvent('DahsboardController', "Load dash");

            $user = \Auth::user();
            $user['total_user'] = User::withoutGlobalScopes()
                                        ->where('type', '=', 'company')
                                        ->count();

            $user['total_paid_user'] = User::withoutGlobalScopes()
                                                ->whereHas('subscriptions')
                                                ->where('type', '=', 'company')
                                                ->count();

            $user['total_orders'] = Subscription::count();
            $user['total_orders_price'] = 0;
            $user['total_plan'] = SubscriptionPlan::total_plan();
            $user['most_purchese_plan'] = 0;

            $charts = [];

            $chart_options = [
                'chart_title' => 'Users by month',
                'report_type' => 'group_by_date',
                'model' => 'App\User',

                'group_by_field' => 'created_at',
                'group_by_period' => 'month',

                'chart_type' => 'bar',
                'withoutGlobalScopes',
                'where_raw' => 'email_verified_at IS NOT NULL'
            ];

            $charts[] = new Chart($chart_options);

            $chart_options = [
                'chart_title' => 'Task changes (last 30 days)',
                'chart_type' => 'line',
                'report_type' => 'group_by_date',
                'model' => 'App\Task',
                
                'group_by_field' => 'updated_at',
                'group_by_period' => 'day',       

                'filter_field' => 'updated_at',
                'filter_period' => 'month', // show only transactions for this month
                'filter_days' => 30, // show only transactions for last 30 days
                'continuous_time' => false, // show continuous timeline including dates without data
                'withoutGlobalScopes'
            ];

            $charts[] = new Chart($chart_options);

            $chart_options = [
                'chart_title' => 'Task changes by users (last 30 days)',
                'chart_type' => 'bar',
                'report_type' => 'group_by_relationship',
                'model' => 'App\Task',
            
                'relationship_name' => 'company', // represents function user() on Transaction model
                'group_by_field' => 'email', // users.name
            
                'filter_field' => 'updated_at',
                'filter_period' => 'month', // show only transactions for this month
                'filter_days' => 30, // show only transactions for last 30 days
                'withoutGlobalScopes'
            ];

            $charts[] = new Chart($chart_options);

            $chart_options = [
                'chart_title' => 'Timesheet changes (last 30 days)',
                'chart_type' => 'line',
                'report_type' => 'group_by_date',
                'model' => 'App\Timesheet',
                
                'group_by_field' => 'updated_at',
                'group_by_period' => 'day',       

                'filter_field' => 'updated_at',
                'filter_period' => 'month', // show only transactions for this month
                'filter_days' => 30, // show only transactions for last 30 days
                'continuous_time' => false, // show continuous timeline including dates without data
                'withoutGlobalScopes'
            ];

            $charts[] = new Chart($chart_options);

            $chart_options = [
                'chart_title' => 'Timesheet changes by users (last 30 days)',
                'chart_type' => 'bar',
                'report_type' => 'group_by_relationship',
                'model' => 'App\Timesheet',
            
                'relationship_name' => 'company', // represents function user() on Transaction model
                'group_by_field' => 'email', // users.name
            
                'filter_field' => 'updated_at',
                'filter_period' => 'month', // show only transactions for this month
                'filter_days' => 30, // show only transactions for last 30 days
                'withoutGlobalScopes'
            ];

            $charts[] = new Chart($chart_options);

            $chart_options = [
                'chart_title' => 'Leads changes (last 30 days)',
                'chart_type' => 'line',
                'report_type' => 'group_by_date',
                'model' => 'App\Lead',
                
                'group_by_field' => 'updated_at',
                'group_by_period' => 'day',       

                'filter_field' => 'updated_at',
                'filter_period' => 'month', // show only transactions for this month
                'filter_days' => 30, // show only transactions for last 30 days
                'continuous_time' => false, // show continuous timeline including dates without data
                'where_raw' => 'name != "Sample Lead" AND name != "Exemplu de oportunitate"',
                'withoutGlobalScopes'
            ];

            $charts[] = new Chart($chart_options);

            $chart_options = [
                'chart_title' => 'Leads changes by users (last 30 days)',
                'chart_type' => 'bar',
                'report_type' => 'group_by_relationship',
                'model' => 'App\Lead',
            
                'relationship_name' => 'company', // represents function user() on Transaction model
                'group_by_field' => 'email', // users.name
            
                'filter_field' => 'updated_at',
                'filter_period' => 'month', // show only transactions for this month
                'filter_days' => 30, // show only transactions for last 30 days
                'where_raw' => 'name != "Sample Lead" AND name != "Exemplu de oportunitate"',
                'withoutGlobalScopes'
            ];

            $charts[] = new Chart($chart_options);

            $chart_options = [
                'chart_title' => 'Invoice changes (last 30 days)',
                'chart_type' => 'line',
                'report_type' => 'group_by_date',
                'model' => 'App\Invoice',
                
                'group_by_field' => 'updated_at',
                'group_by_period' => 'day',       

                'filter_field' => 'updated_at',
                'filter_period' => 'month', // show only transactions for this month
                'filter_days' => 30, // show only transactions for last 30 days
                'continuous_time' => false, // show continuous timeline including dates without data
                'where_raw' => 'number != "#INV00001"',
                'withoutGlobalScopes'
            ];

            $charts[] = new Chart($chart_options);

            $chart_options = [
                'chart_title' => 'Invoice changes by users (last 30 days)',
                'chart_type' => 'bar',
                'report_type' => 'group_by_relationship',
                'model' => 'App\Invoice',
            
                'relationship_name' => 'company', // represents function user() on Transaction model
                'group_by_field' => 'email', // users.name
            
                'filter_field' => 'updated_at',
                'filter_period' => 'month', // show only transactions for this month
                'filter_days' => 30, // show only transactions for last 30 days
                'where_raw' => 'number != "#INV00001"',
                'withoutGlobalScopes'
            ];

            $charts[] = new Chart($chart_options);

            clock()->endEvent('DashboardController');

            return view('dashboard.admin',compact('user','charts'));
        }


        clock()->startEvent('DahsboardController', "Load dash");

        $todayTasks = \Auth::user()->getTodayTasks();
        $thisWeekTasks = \Auth::user()->getThisWeekTasks();
        $nextWeekTasks = \Auth::user()->getNextWeekTasks();

        $todayEvents = \Auth::user()->getTodayEvents();
        $thisWeekEvents = \Auth::user()->getThisWeekEvents();
        $nextWeekEvents = \Auth::user()->getNextWeekEvents();

        $projects = \Auth::user()->companyUserProjects()                                    
                                ->where('archived', '0')
                                ->orderBy('due_date', 'ASC')
                                ->get();

        $invoices = \Auth::user()->companyInvoices()
                                ->where('status', '<', '3')
                                ->orderBy('due_date', 'ASC')
                                ->get();

        $leads = \Auth::user()->leads()
                                ->whereHas('stage', function ($query)
                                {
                                    $query->where('open', 1);
                                })
                                ->whereDate('updated_at', '<', Carbon::now()->subDays(7))
                                ->orderBy('order', 'ASC')
                                ->get();

        $tasks = \Auth::user()->tasks()
                                ->whereHas('stage', function ($query)
                                {
                                    $query->where('open', 1);
                                })
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
        if(\Auth::user()->can('viewAny', 'App\Project')){

            $projects = \Auth::user()->companyUserProjects()
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
        if(\Auth::user()->can('viewAny', 'App\Task')){

            $tasks = \Auth::user()->companyUserTasks()
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
                'link' => (Gate::check('update', $event)?route('events.edit', [$event->id]):route('events.show', [$event->id])),
                'param' => 'data-remote="true" data-type="text"'
            ];
        }

        //clients
        $arrClient = [];
        if(\Auth::user()->can('viewAny', 'App\Client')){

            $clients = \Auth::user()->companyClients()
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
        if(\Auth::user()->can('viewAny', 'App\Contact')){

            $contacts = \Auth::user()->contactsByUserType()
                                    ->where(function ($query) use ($search) {
                                        $query->where('name','like', $search.'%');
                                    })
                                    ->orderBy('name', 'asc')
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
        if(\Auth::user()->can('viewAny', 'App\Lead')){

            $leads = \Auth::user()->companyLeads()
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
        if(\Auth::user()->can('viewAny', 'App\Invoice')){

            $invoices = \Auth::user()->companyInvoices()
                                    ->where(function ($query) use ($search) {
                                        $query->where('number','like', '%'.$search.'%');
                                    })
                                    ->orderBy('due_date', 'ASC')
                                    ->get();

            foreach($invoices as $invoice)
            {
                $arrInvoice[] = [
                    'text' => $invoice->number ? $invoice->number : \Auth::user()->invoiceNumberFormat($invoice->increment),
                    'link' => route('invoices.show', [$invoice->id])
                ];
            }
        }

        // //expenses
        // $arrExpense = [];
        // if(\Auth::user()->can('viewAny', 'App\Expense')){

        //     $expenses = \Auth::user()->companyExpenses()
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

