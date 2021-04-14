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
use App\Payment;
use App\Expense;
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
                                        ->whereIn('type', ['company', 'employee'])
                                        ->count();

            $user['total_paid_user'] = User::withoutGlobalScopes()
                                                ->whereHas('subscriptions', function ($query)
                                                {
                                                    $query->where('ends_at', null)
                                                            ->orWhere('ends_at', '>=', now());
                                                })
                                                ->where('type', '=', 'company')
                                                ->count();

            $user['total_plan'] = SubscriptionPlan::total_plan();
            $user['most_purchese_plan'] = 0;

            clock()->endEvent('DashboardController');

            return view('dashboard.admin',compact('user'));
        }

        clock()->startEvent('DahsboardController', "Load dash");


        clock()->endEvent('DashboardController');

        return view('dashboard.company');
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
        $events = \Auth::user()->companyEvents()
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

            $contacts = \Auth::user()->companyContacts()
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

