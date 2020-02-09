<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    public static $SEED_COMPANY_COUNT = 100;
    public static $SEED_CLIENT_COUNT = 5;
    public static $SEED_STAFF_COUNT = 10;
    
    public static $SEED_COMPANY_IDX = 0;
    public static $SEED_COMPANY_ID = 0;

    protected $appends = ['profile'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'avatar',
        'lang',
        'plan_id',
        'plan_expire_date',
        'created_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public    $settings;

    public function authId()
    {
        return $this->id;
    }

    public function creatorId()
    {
        if($this->type == 'company' || $this->type == 'super admin')
        {
            return $this->id;
        }
        else
        {
            return $this->created_by;
        }
    }

    public function settings()
    {
        if(empty($this->settings))
        {
            $data     = DB::table('settings')->where('created_by', '=', $this->creatorId())->get();
            $settings = [
                "site_currency" => "Dollars",
                "site_currency_symbol" => "$",
                "site_currency_symbol_position" => "pre",
                "site_date_format" => "M j, Y",
                "site_time_format" => "g:i A",
                "company_name" => "",
                "company_logo" => "",
                "company_address" => "",
                "company_city" => "",
                "company_state" => "",
                "company_zipcode" => "",
                "company_country" => "",
                "company_telephone" => "",
                "company_email" => "",
                "company_email_from_name" => "",
                "invoice_prefix" => "#INV",
            ];

            foreach($data as $row)
            {
                $settings[$row->name] = $row->value;
            }

            $this->settings = $settings;
        }

        return $this->settings;
    }


    public function languages()
    {
        $dir     = base_path() . '/resources/lang/';
        $glob    = glob($dir . "*", GLOB_ONLYDIR);
        $arrLang = array_map(
            function ($value) use ($dir){
                return str_replace($dir, '', $value);
            }, $glob
        );
        $arrLang = array_map(
            function ($value) use ($dir){
                return preg_replace('/[0-9]+/', '', $value);
            }, $arrLang
        );
        $arrLang = array_filter($arrLang);

        return $arrLang;
    }

    public function currentLanguage()
    {
        return $this->lang;
    }

    public function plan()
    {
        return $this->hasOne('App\PaymentPlan', 'id', 'plan_id');
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact', 'user_id', 'id');
    }

    public function leads()
    {
        return $this->hasMany('App\Lead', 'user_id', 'id');
    }

    public function expenses()
    {
        return $this->hasMany('App\Expense', 'user_id', 'id');
    }

    public function timesheets()
    {
        return $this->hasMany('App\Timesheet', 'user_id', 'id');
    }

    public function projects()
    {
        return $this->belongsToMany('App\Project', 'user_projects');
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Task', 'user_tasks');
    }

    public function events()
    {
        return $this->hasMany('App\Event', 'user_id', 'id');
    }

    
    public function staffTasks()
    {
        return Task::where(function ($query) 
        {
            $query->whereHas('users', function ($query) {

                // tasks with the current user assigned.
                $query->where('users.id', $this->id);

            })->orWhereHas('project', function ($query) {
                
                // only include tasks with projects where...
                $query->whereHas('users', function ($query) {

                    // ...the current user is assigned.
                    $query->where('users.id', $this->id);
                });
            });
        });
    }

    public function clientLeads()
    {
        return $this->hasMany('App\Lead', 'client_id', 'id');
    }

    public function clientProjects()
    {
        return $this->hasMany('App\Project', 'client_id', 'id');
    }

    public function clientTasks()
    {
        return $this->hasManyThrough('App\Task', 'App\Project', 'client_id', 'project_id', 'id');
    }

    public function clientContacts()
    {
        return $this->hasMany('App\Contact', 'client_id', 'id');
    }

    public function companyLeads()
    {
        return Lead::where('created_by', '=', $this->creatorId());
    }

    public function companyProjects()
    {
        return Project::where('created_by', '=', $this->creatorId());
    }

    public function companyTasks()
    {
        return Task::with('project')->where('created_by', '=', $this->creatorId());
    }
    
    public function planByUserType()
    {
        if($this->type == 'client' || 
            $this->type == 'company'){

            return $this->plan;
        }else{

            $company = User::with('plan')->find($this->creatorId());
            return $company->plan;
        }    
    }

    public function projectsByUserType()
    {
        if($this->type == 'client'){

            return $this->clientProjects();
        }
        else if($this->type == 'company'){

            return $this->companyProjects();
        }else{

            return $this->projects();

        }    
    }

    public function tasksByUserType()
    {
        if($this->type == 'client'){

            return $this->clientTasks();
        }
        else if($this->type == 'company'){

            return $this->companyTasks();
        }else{

            return $this->staffTasks();
        }    
    }

    public function projectStages()
    {
        return ProjectStage::where('created_by', '=', $this->creatorId())->orderBy('order', 'ASC');
    }

    public function getProjectsByUserType()
    {
        return $this->projectsByUserType()->get();
    }

    public function getTasksByUserType()
    {
        return $this->tasksByUserType()->get();
    }

    public function getProfileAttribute()
    {
        return null;
    }

    public static function getCompanyStaff()
    {
        User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->get();
    }

    public static function getCompanyClients()
    {
        User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'client')->get();
    }

    public function user_projects_count()
    {
        return $this->projectsByUserType()->count();
    }
    

    public function user_tasks_count()
    {
        return $this->tasks()->count();
    }

    public function totalExpenses()
    {
        return $this->expenses()->sum('amount');
    }


    public function priceFormat($price)
    {
        $settings = $this->settings();

        return (($settings['site_currency_symbol_position'] == "pre") ? $settings['site_currency_symbol'] : '') . number_format($price, 2) . (($settings['site_currency_symbol_position'] == "post") ? $settings['site_currency_symbol'] : '');
    }

    public function dateFormat($date)
    {
        $settings = $this->settings();

        return date($settings['site_date_format'], strtotime($date));
    }

    public function timeFormat($time)
    {
        $settings = $this->settings();

        return date($settings['site_time_format'], strtotime($time));
    }

    public function invoiceNumberFormat($number)
    {
        $settings = $this->settings();

        return $settings["invoice_prefix"] . sprintf("%05d", $number);
    }

    public function clientPermission($project_id)
    {
        return ProjectClientPermission::where('client_id', '=', $this->id)->where('project_id', '=', $project_id)->first();
    }

    public function last_leadstage()
    {
        return LeadStage::where('created_by', '=', $this->creatorId())->orderBy('order', 'DESC')->first();
    }

    public function total_lead()
    {
        if(\Auth::user()->type == 'company')
        {
            return Lead::where('created_by', '=', $this->creatorId())->count();
        }
        elseif(\Auth::user()->type == 'client')
        {
            return Lead::where('client_id', '=', $this->authId())->count();
        }
        else
        {
            return Lead::where('user_id', '=', $this->authId())->count();
        }
    }

    public function total_complete_lead($last_leadstage)
    {
        if(\Auth::user()->type == 'company')
        {
            return Lead::where('created_by', '=', $this->creatorId())->where('stage_id', '=', $last_leadstage)->count();
        }
        elseif(\Auth::user()->type == 'client')
        {
            return Lead::where('client_id', '=', $this->authId())->where('stage_id', '=', $last_leadstage)->count();
        }
        else
        {
            return Lead::where('user_id', '=', $this->authId())->where('stage_id', '=', $last_leadstage)->count();
        }
    }

    public function created_total_project_task()
    {
        if(\Auth::user()->type == 'company')
        {
            return Task::join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.created_by', '=', $this->creatorId())->count();
        }
        elseif(\Auth::user()->type == 'client')
        {
            return Task::join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.client_id', '=', $this->authId())->count();
        }
        else
        {
            return Task::select('tasks.*', 'user_projects.id as up_id')->join('user_projects', 'user_projects.project_id', '=', 'tasks.project_id')->where('user_projects.user_id', '=', $this->authId())->count();
        }

    }

    public function created_top_due_task()
    {
        return  \Auth::user()->tasksByUserType()->where('tasks.due_date', '>', date('Y-m-d'))->limit(5)->orderBy('tasks.due_date', 'ASC')->get();

        // if(\Auth::user()->type == 'company')
        // {
        //     return Task::select('projects.*', 'tasks.id as task_id', 'tasks.title', 'tasks.due_date as task_due_date', 'project_stages.name as stage_name')->join('projects', 'projects.id', '=', 'tasks.project_id')->join('project_stages', 'tasks.stage_id', '=', 'project_stages.id')->where('projects.created_by', '=', $this->creatorId())->where('tasks.due_date', '>', date('Y-m-d'))->limit(5)->orderBy('task_due_date', 'ASC')->get();
        // }
        // elseif(\Auth::user()->type == 'client')
        // {
        //     return Task::select('projects.*', 'tasks.id as task_id', 'tasks.title', 'tasks.due_date as task_due_date', 'project_stages.name as stage_name')->join('projects', 'projects.id', '=', 'tasks.project_id')->join('project_stages', 'tasks.stage_id', '=', 'project_stages.id')->where('projects.client_id', '=', $this->authId())->where('tasks.due_date', '>', date('Y-m-d'))->limit(5)->orderBy('task_due_date', 'ASC')->get();
        // }
        // else
        // {
        //     return Task::select('tasks.*', 'tasks.id as task_id', 'tasks.due_date as task_due_date', 'user_projects.id as up_id', 'projects.name as project_name', 'project_stages.name as stage_name')->join('user_projects', 'user_projects.project_id', '=', 'tasks.project_id')->join('projects', 'user_projects.project_id', '=', 'projects.id')->join('project_stages', 'tasks.stage_id', '=', 'project_stages.id')->where('user_projects.user_id', '=', $this->authId())->where('tasks.due_date', '>', date('Y-m-d'))->limit(5)->orderBy(
        //         'tasks.due_date', 'ASC')->get();
        // }
    }

    public function total_project()
    {
        return Project::where('created_by', '=', $this->creatorId())->count();
    }

    public function last_projectstage()
    {
        return ProjectStage::where('created_by', '=', $this->creatorId())->orderBy('order', 'DESC')->first();
    }

    public function project_complete_task($project_last_stage)
    {

        if(\Auth::user()->type == 'company')
        {
            // return Task::join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.created_by', '=', $this->creatorId())->where('tasks.stage_id', '=', $project_last_stage)->count();
        }
        elseif(\Auth::user()->type == 'client')
        {
            return Task::join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.client_id', '=', $this->authId())->where('tasks.stage_id', '=', $project_last_stage)->count();
        }
        else
        {
            return Task::select('tasks.*', 'user_projects.id as up_id')->join('user_projects', 'user_projects.project_id', '=', 'tasks.project_id')->where('user_projects.user_id', '=', $this->authId())->where('tasks.stage_id', '=', $project_last_stage)->count();
        }
    }


    public function created_total_invoice()
    {
        if(\Auth::user()->type == 'company')
        {
            return Invoice::where('created_by', '=', $this->creatorId())->limit(5)->get();
        }
        elseif(\Auth::user()->type == 'client')
        {
            return Invoice::select('invoices.*', 'projects.client_id')->join('projects', 'projects.id', '=', 'invoices.project_id')->where(
                'projects.client_id', '=', $this->authId()
            )->get();
        }
    }

    public function checkProjectLimit()
    {
        $company        = User::find($this->creatorId());
        $plan           = PaymentPlan::find($company->plan_id);

        if($plan->max_projects == null) return true;

        $total_projects = Project::where('created_by', '=', $company->id)->count();


        return $total_projects < $plan->max_projects;
    }

    public function checkClientLimit()
    {
        $company        = User::find($this->creatorId());
        $plan           = PaymentPlan::find($company->plan_id);

        if($plan->max_clients == null) return true;

        $total_clients = User::where('type', '=', 'client')->where('created_by', '=', $company->id)->count();

        return $total_clients < $plan->max_clients;
    }

    public function checkUserLimit()
    {
        $company        = User::find($this->creatorId());
        $plan           = PaymentPlan::find($company->plan_id);

        if($plan->max_users == null) return true;

        $total_users = User::where('type', '!=', 'client')->where('created_by', '=', $company->id)->count();

        return $total_users < $plan->max_users;
    }

    public function assignPlan($planId)
    {
        $plan = PaymentPlan::find($planId);

        if($plan)
        {
            $this->plan_id = $plan->id;
            if($plan->duration == 'month')
            {
                $this->plan_expire_date = Carbon::now()->addMonths(1)->isoFormat('YYYY-MM-DD');
            }
            elseif($plan->duration == 'year')
            {
                $this->plan_expire_date = Carbon::now()->addYears(1)->isoFormat('YYYY-MM-DD');
            }
            else
            {
                $this->plan_expire_date = null;
            }
            $this->save();

            $projects = Project::where('created_by', '=', $this->creatorId())->get();
            $users    = User::where('created_by', '=', $this->creatorId())->where('type', '!=', 'client')->get();
            $clients  = User::where('created_by', '=', $this->creatorId())->where('type', 'client')->get();

            $projectCount = 0;
            foreach($projects as $project)
            {
                $projectCount++;
                if($projectCount <= $plan->max_projects)
                {
                    $project->enabled = true;
                    $project->save();
                }
                else
                {
                    $project->enabled = false;
                    $project->save();
                }
            }

            $userCount = 0;
            foreach($users as $user)
            {
                $userCount++;
                if($userCount <= $plan->max_users)
                {
                    $user->enabled = true;
                    $user->save();
                }
                else
                {
                    $user->enabled = false;
                    $user->save();
                }
            }
            $clientCount = 0;
            foreach($clients as $client)
            {
                $clientCount++;
                if($clientCount <= $plan->max_clients)
                {
                    $client->enabled = true;
                    $client->save();
                }
                else
                {
                    $client->enabled = false;
                    $client->save();
                }
            }

            return ['is_success' => true];
        }
        else
        {
            return [
                'is_success' => false,
                'error' => 'PaymentPlan is deleted.',
            ];
        }
    }

    public function countCompany()
    {
        return User::where('type', '=', 'company')->where('created_by', '=', \Auth::user()->id)->count();
    }

    public function countPaidCompany()
    {
        return User::where('type', '=', 'company')->whereNotIn(
            'plan_id', [
                      0,
                      1,
                  ]
        )->where('created_by', '=', \Auth::user()->id)->count();
    }

    public function makeEmployeeRole()
    {
        $permissions = [
            'manage account',
            'change password account',
            'edit account',
            'create event',
            'manage event',
            'edit event',
            'show event',
            'manage project',
            'show project',
            'create task',
            'manage task',
            'move task',
            'show task',
            'create checklist',
            'manage lead',
            'create timesheet',
            'manage timesheet',
            'edit timesheet',
            'delete timesheet'
        ];

        $role               =   new Role();
        $role->name         =   'employee';
        $role->created_by   =   $this->id;
        $role->save();

        foreach($permissions as $ap)
        {
            $permission = Permission::findByName($ap);
            $role->givePermissionTo($permission);
        }
    }

    public function initCompanyDefaults()
    {
        $this->makeEmployeeRole();

        $id = $this->id;

        $colors = [
            '#e7505a',
            '#F4D03F',
            '#32c5d2',
            '#1BBC9B',
        ];

        // LeadStage
        $leadStages = [
            'Initial Contact',
            'Qualification',
            'Proposal',
            'Close',
        ];
        foreach($leadStages as $key => $stage)
        {
            LeadStage::create(
                [
                    'name' => $stage,
                    'color' => $colors[$key],
                    'order' => $key,
                    'created_by' => $id,
                ]
            );
        }

        // ProjectStages
        $projectStages = [
            'To Do',
            'In Progress',
            'Bugs',
            'Done',
        ];
        foreach($projectStages as $key => $stage)
        {
            ProjectStage::create(
                [
                    'name' => $stage,
                    'color' => $colors[$key],
                    'order' => $key,
                    'created_by' => $id,
                ]
            );
        }

        // LeadSource
        $leadSource = [
            'Email',
            'Facebook',
            'Google',
            'Phone',
        ];
        foreach($leadSource as $source)
        {
            Leadsource::create(
                [
                    'name' => $source,
                    'created_by' => $id,
                ]
            );
        }

        // Label
        $labels = [
            'On Hold' => 'bg-red-thunderbird bg-font-red-thunderbird',
            'New' => 'bg-yellow-casablanca bg-font-yellow-casablanca',
            'Pending' => 'bg-purple-intense bg-font-purple-intense',
            'Loss' => 'bg-purple-medium bg-font-purple-medium',
            'Win' => 'bg-yellow-soft bg-font-yellow-soft',
        ];

        // ProductUnits
        $productUnits = [
            'Kilogram',
            'Piece',
            'Set',
            'Item',
            'Hour',
        ];
        foreach($productUnits as $unit)
        {
            ProductUnit::create(
                [
                    'name' => $unit,
                    'created_by' => $id,
                ]
            );
        }

        // ExpenseCategory
        $expenseCat = [
            'Snack',
            'Server Charge',
            'Bills',
            'Office',
            'Assests',
        ];
        foreach($expenseCat as $cat)
        {
            ExpenseCategory::create(
                [
                    'name' => $cat,
                    'created_by' => $id,
                ]
            );
        }

        // Payments
        $payments = [
            'Cash',
            'Bank',
        ];
        foreach($payments as $payment)
        {
            PaymentType::create(
                [
                    'name' => $payment,
                    'created_by' => $id,
                ]
            );
        }

        // EventCategory
        $eventCat = [
            'Call',
            'Meeting',
            'ToDo',
            'Deadline',
            'Email',
            'Lunch'            
        ];
        foreach($eventCat as $category)
        {
            EventCategory::create(
                [
                    'name' => $category,
                    'created_by' => $id,
                ]
            );
        }

    }

    public function destroyUserProjectInfo($user_id)
    {
        return UserProject::where('user_id', '=', $user_id)->delete();
    }

    public function removeUserLeadInfo($user_id)
    {
        return Lead::where('user_id', '=', $user_id)->update(array('user_id' => null));
    }

    public function removeUserExpenseInfo($user_id)
    {
        return Expense::where('user_id', '=', $user_id)->update(array('user_id' => null));
    }

    public function removeUserTaskInfo($user_id)
    {
        return UserTask::where('user_id', '=', $user_id)->delete();
    }

    public function destroyUserNotesInfo($user_id)
    {
        return Note::where('created_by', '=', $user_id)->delete();
    }

    public function destroyUserTaskAllInfo($user_id)
    {
        TaskChecklist::where('created_by', '=', $user_id)->delete();
        TaskComment::where('created_by', '=', $user_id)->delete();
        TaskFile::where('created_by', '=', $user_id)->delete();
    }

    public function removeClientProjectInfo($user_id)
    {
        return Project::where('client_id', '=', $user_id)->update(array('client_id' => null));
    }

    public function removeClientLeadInfo($user_id)
    {
        return Lead::where('client_id', '=', $user_id)->update(array('client_id' => null));
    }

    public function total_company_user($company_id)
    {
        return User::where('type', '!=', 'client')->where('created_by', '=', $company_id)->count();
    }

    public function total_company_client($company_id)
    {
        return User::where('type', '=', 'client')->where('created_by', '=', $company_id)->count();
    }

    public function total_company_project($company_id)
    {
        return Project::where('created_by', '=', $company_id)->count();
    }
}
