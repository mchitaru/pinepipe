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
use Laravel\Cashier\Billable;
use App\Jobs\EmailVerificationJob;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles;
    use Notifiable;
    use SoftDeletes;
    use Billable;

    public static $SEED_COMPANY_COUNT = 1;
    public static $SEED_STAFF_COUNT = 5;
    
    public static $SEED_COMPANY_IDX = 0;
    public static $SEED_COMPANY_ID = 0;

    protected $appends = ['profile'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'avatar',
        'bio',
        'lang',
        'client_id',
        'created_by',
        'notify_task_assign',
        'notify_project_assign',
        'notify_project_activity',
        'notify_item_overdue',
        'notify_newsletter',
        'notify_major_updates',
        'notify_minor_updates'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'notify_task_assign' => 'boolean',
        'notify_project_assign' => 'boolean',
        'notify_project_activity' => 'boolean',
        'notify_item_overdue' => 'boolean',
        'notify_newsletter' => 'boolean',
        'notify_major_updates' => 'boolean',
        'notify_minor_updates' => 'boolean'
    ];
    public    $settings;

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
                "site_currency" => "EUR",
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

    public function client()
    {
        return $this->hasOne('App\Client', 'id', 'client_id');
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
    
    public function projectsByUserType()
    {
        if($this->type == 'client'){

            return $this->client->projects();
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

            return $this->client->tasks();
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

    public function getProfileAttribute()
    {
        return null;
    }

    public static function companyStaff()
    {
        User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client');
    }

    public static function companyClients()
    {
        return Client::where('created_by', '=', \Auth::user()->creatorId());
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

        return money($price*100, $settings['site_currency'])->format();

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

    public function last_leadstage()
    {
        return LeadStage::where('created_by', '=', $this->creatorId())->orderBy('order', 'DESC')->first();
    }

    public function last_projectstage()
    {
        return ProjectStage::where('created_by', '=', $this->creatorId())->orderBy('order', 'DESC')->first();
    }

    public function total_lead()
    {
        if(\Auth::user()->type == 'company')
        {
            return Lead::where('created_by', '=', $this->creatorId())->count();
        }
        elseif(\Auth::user()->type == 'client')
        {
            return Lead::where('client_id', '=', $this->client_id)->count();
        }
        else
        {
            return Lead::where('user_id', '=', $this->client_id)->count();
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
            return Lead::where('client_id', '=', $this->client_id)->where('stage_id', '=', $last_leadstage)->count();
        }
        else
        {
            return Lead::where('user_id', '=', $this->id)->where('stage_id', '=', $last_leadstage)->count();
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
            return Task::join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.client_id', '=', $this->client_id)->count();
        }
        else
        {
            return Task::select('tasks.*', 'user_projects.id as up_id')->join('user_projects', 'user_projects.project_id', '=', 'tasks.project_id')->where('user_projects.user_id', '=', $this->id)->count();
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

    public function project_complete_task($project_last_stage)
    {

        if(\Auth::user()->type == 'company')
        {
            // return Task::join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.created_by', '=', $this->creatorId())->where('tasks.stage_id', '=', $project_last_stage)->count();
        }
        elseif(\Auth::user()->type == 'client')
        {
            return Task::join('projects', 'projects.id', '=', 'tasks.project_id')->where('projects.client_id', '=', $this->client_id)->where('tasks.stage_id', '=', $project_last_stage)->count();
        }
        else
        {
            return Task::select('tasks.*', 'user_projects.id as up_id')->join('user_projects', 'user_projects.project_id', '=', 'tasks.project_id')->where('user_projects.user_id', '=', $this->id)->where('tasks.stage_id', '=', $project_last_stage)->count();
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
                'projects.client_id', '=', $this->client_id
            )->get();
        }
    }

    public function checkProjectLimit()
    {
        $company        = User::find($this->creatorId());

        if(!$company->subscribed()){
            $plan = PaymentPlan::first();
        }else{
            $plan = PaymentPlan::where('braintree_id', $company->subscription()->braintree_plan)->first();
        }

        if(!isset($plan->max_projects)) return true;

        $total_projects = Project::where('created_by', '=', $company->id)->count();


        return $total_projects < $plan->max_projects;
    }

    public function checkClientLimit()
    {
        $company        = User::find($this->creatorId());

        if(!$company->subscribed()){
            $plan = PaymentPlan::first();
        }else{
            $plan = PaymentPlan::where('braintree_id', $company->subscription()->braintree_plan)->first();
        }

        if(!isset($plan->max_clients)) return true;

        $total_clients = Client::where('created_by', '=', $company->id)->count();

        return $total_clients < $plan->max_clients;
    }

    public function checkUserLimit()
    {
        $company        = User::find($this->creatorId());

        if(!$company->subscribed()){
            $plan = PaymentPlan::first();
        }else{
            $plan = PaymentPlan::where('braintree_id', $company->subscription()->braintree_plan)->first();
        }

        if(!isset($plan->max_users)) return true;

        $total_users = User::where('type', '!=', 'client')->where('created_by', '=', $company->id)->count();

        return $total_users < $plan->max_users;
    }
        
    public function countCompany()
    {
        return User::where('type', '=', 'company')->where('created_by', '=', \Auth::user()->id)->count();
    }

    public function countPaidCompany()
    {
        return 0;
        // return User::where('type', '=', 'company')->whereNotIn(
        //     'plan_id', [
        //               0,
        //               1,
        //           ]
        // )->where('created_by', '=', \Auth::user()->id)->count();
    }

    public function makeClientRole()
    {
        $permissions = [
            'manage account',
            'edit account',
            'change password account',
            'show project',
            'manage project',
            'manage invoice',
            'show invoice',
            'manage expense',
            'manage payment',
            'manage timesheet',
        ];

        $role               =   new Role();
        $role->name         =   'client';
        $role->created_by   =   $this->id;
        $role->save();

        foreach($permissions as $ap)
        {
            $permission = Permission::findByName($ap);
            $role->givePermissionTo($permission);
        }
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
        $this->makeClientRole();
        $this->makeEmployeeRole();

        $id = $this->id;

        $colors = [
            "#92dacb", "#e7afa9",  "#acd6f1", "#e4c695", "#728191", "#a3e4d7", "#93d6af", "#7fb2d4", "#dab7e9", "#7c9cbd",
            "#dfce8c", "#dfb999", "#9fdfb9", "#ecf0f1", "#95a5a6", "#dcb5eb", "#e0b699", "#e4a9a1", "#bdc3c7", "#90a0a1"
        ];

        // LeadStage
        $leadStages = [
            'Initial Contact',
            'Qualification',
            'Proposal',
            'Won',
            'Lost',
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

    public function total_company_user($company_id)
    {
        return User::where('type', '!=', 'client')->where('created_by', '=', $company_id)->count();
    }

    public function total_company_client($company_id)
    {
        return Client::where('created_by', '=', $company_id)->count();
    }

    public function total_company_project($company_id)
    {
        return Project::where('created_by', '=', $company_id)->count();
    }

    public function sendEmailVerificationNotification()
    {
        EmailVerificationJob::dispatch($this);
    }
}
