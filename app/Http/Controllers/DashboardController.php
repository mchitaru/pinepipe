<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Order;
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
        if(\Auth::user()->type != 'super admin')
        {
            clock()->startEvent('DahsboardController', "Load dash");

            $last_project_stage = \Auth::user()->last_projectstage();

            $total_lead     = \Auth::user()->total_lead();
            $last_leadstage = \Auth::user()->last_leadstage();

            $complete_leads           = \Auth::user()->total_complete_lead($last_leadstage);
            $complete_lead           =(!empty($complete_leads)?$complete_leads->id:0);

            $lead_percentage         = ($total_lead!=0? intval(($complete_lead / $total_lead) * 100):0);
            $lead['total_lead']      = $total_lead;
            $lead['lead_percentage'] = $lead_percentage;

            if(\Auth::user()->type == 'company')
            {
                $project['projects'] = Project::where('created_by', '=', \Auth::user()->creatorId())->where('due_date', '>', date('Y-m-d'))->limit(5)->orderBy('due_date')->get();
                $activities = Activity::where('created_by', \Auth::user()->creatorId())->limit(20)->orderBy('id', 'desc')->get();
            }
            elseif(\Auth::user()->type == 'client')
            {
                $project['projects']       = Project::where('client_id', '=', \Auth::user()->client_id)->where('due_date', '>', date('Y-m-d'))->limit(5)->orderBy('due_date')->get();
                $project['project_budget'] = Project::where('client_id', \Auth::user()->client_id)->sum('price');
                $activities = null;
            }
            else
            {
                $project['projects'] = Project::select('projects.*', 'user_projects.id as up_id')->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', \Auth::user()->id)->where('due_date', '>', date('Y-m-d'))->limit(5)->orderBy('due_date')->get();
                $activities = Activity::select('activities.*', 'user_projects.id as up_id')->join('user_projects', 'user_projects.project_id', '=', 'activities.project_id')->where('user_projects.user_id', '=', \Auth::user()->id)->limit(20)->orderBy('id', 'desc')->get();
            }

            $project_last_stages       = \Auth::user()->last_projectstage();
            $project_last_stage       = (!empty($project_last_stages)?$project_last_stages->id:0);
            $project['total_project'] = \Auth::user()->user_projects_count();
            $total_project_task       = \Auth::user()->created_total_project_task();
            $complete_task            = \Auth::user()->project_complete_task($project_last_stage);


            $project['project_percentage'] =  ($total_project_task!=0) ? intval(($complete_task / $total_project_task) * 100) : 0;

            $invoice = [];
            $top_due_invoice = [];
            if(\Auth::user()->type == 'client' || \Auth::user()->type == 'company')
            {

                $total_invoices           = $top_due_invoice = \Auth::user()->created_total_invoice();
                $invoice['total_invoice'] = count($total_invoices);
                $complete_invoice         = 0;
                $total_due_amount         = 0;
                $top_due_invoice          = array();
                $pay_amount=0;
                foreach($total_invoices as $total_invoice)
                {
                    $amount           = $total_due = $total_invoice->getDue();
                    $payments          = $total_invoice->payments;


                    foreach($payments as $payment){
                       $pay_amount+=$payment->amount;
                    }

                    $total_due_amount += $total_due;
                    if($amount == 0.00)
                    {
                        $complete_invoice++;
                    }
                    if($amount > 0)
                    {
                        $total_invoice['due_amount'] = $amount;
                        $top_due_invoice[]           = $total_invoice;
                    }
                }
                if(count($total_invoices) > 0)
                {
                    $invoice['invoice_percentage'] = intval(($complete_invoice / count($total_invoices)) * 100);
                }
                else
                {
                    $invoice['invoice_percentage'] = 0;
                }

                $top_due_invoice = array_slice($top_due_invoice, 0, 5);
            }

            if(\Auth::user()->type == 'client')
            {
                if(!empty($project['project_budget'])){
                    $project['client_project_budget_due_per']= intval(($pay_amount / $project['project_budget'] ) * 100);
                }else{
                    $project['client_project_budget_due_per']=0;
                }

            }

            $top_tasks       = \Auth::user()->created_top_due_task();
            $users['staff']  = User::where('created_by', '=', \Auth::user()->creatorId())->count();
            $users['user']   = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->count();
            $users['client'] = Client::where('created_by', '=', \Auth::user()->creatorId())->count();

            $projectData     = \App\Project::getProjectStatus();
            $taskData        = \App\ProjectStage::getChartData();

            clock()->endEvent('DashboardController');

            return view('dashboard.index', compact('lead', 'project', 'invoice', 'top_tasks', 'top_due_invoice', 'users', 'projectData', 'taskData', 'last_project_stage', 'activities'));
        }
        else
        {
            clock()->startEvent('DahsboardController', "Load dash");

            $activities = Activity::limit(50)->orderBy('id', 'desc')->get();

            $user=\Auth::user();
            $user['total_user']=$user->countCompany();
            $user['total_paid_user']=$user->countPaidCompany();
            $user['total_orders'] = Subscription::count();
            $user['total_orders_price'] = 0;
            $user['total_plan'] = PaymentPlan::total_plan();
            $user['most_purchese_plan'] = 0;
            $chartData = $this->getOrderChart(['duration'=>'week']);

            clock()->endEvent('DashboardController');

            return view('dashboard.admin',compact('user','chartData','activities'));
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

