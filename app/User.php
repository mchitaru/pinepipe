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
use App\Jobs\EmailVerificationJob;
use App\Traits\Actionable;
use App\Traits\Billable;
use Illuminate\Support\Facades\Hash;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use App\Traits\Eventable;
use Illuminate\Support\Str;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Contracts\Translation\HasLocalePreference;

class User extends Authenticatable implements MustVerifyEmail, HasMedia, HasLocalePreference
{
    use HasRoles, Notifiable, SoftDeletes, Actionable, Billable, Eventable, HasMediaTrait;

    public static $SEED_COMPANY_COUNT = 2;
    public static $SEED_STAFF_COUNT = 2;

    public static $SEED_COMPANY_IDX = 0;
    public static $SEED_COMPANY_ID = 0;

    protected $appends = ['profile'];

    protected $fillable = [
        'name',
        'handle',
        'email',
        'password',
        'type',
        'bio',
        'locale',
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

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {

        });

        static::deleting(function ($user) {

            $user->events()->detach();
            $user->projects()->detach();
            $user->tasks()->detach();

            $user->removeUserLeadInfo();
            $user->destroyUserNotesInfo();
            $user->removeUserExpenseInfo();
            $user->destroyUserTaskAllInfo();

            $user->activities()->delete();

            $user->googleAccounts()->each(function($account) {
                $account->delete();
            });

        });

        static::updating(function ($user) {

        });
    }

    public function handle()
    {
        if($this->handle == null) {

            $this->handle = Str::of($this->name)->slug('-');

            if (User::where('handle', $this->handle)->exists()) {

                $this->handle = Str::of($this->name.' '.$this->id)->slug('-');
             }

            $this->save();
        }

        return $this->handle;
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
        return $this->hasMany('App\Timesheet', 'user_id', 'id')->orderBy('started_at', 'desc')->orderBy('updated_at', 'desc');
    }

    public function projects()
    {
        return $this->belongsToMany('App\Project', 'user_projects');
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Task', 'user_tasks');
    }

    public function userEvents()
    {
        return $this->hasMany('App\Event', 'user_id', 'id');
    }

    public function client()
    {
        return $this->hasOne('App\Client', 'id', 'client_id');
    }

    public function googleAccounts()
    {
        return $this->hasMany(GoogleAccount::class);
    }    

    public function companySettings()
    {
        if($this->type == 'company' || $this->type == 'super admin') {

            return $this->hasOne('App\CompanySettings', 'created_by', 'id');

        }else{

            return $this->hasOne('App\CompanySettings', 'created_by', 'created_by');
        }
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

    public function getActiveTimesheet()
    {
        return $this->timesheets()->where('started_at','!=',null)->first();
    }

    public function getLastTimesheet()
    {
        return $this->timesheets()->orderBy('started_at', 'desc')->orderBy('updated_at', 'desc')->first();
    }

    public function getEmptyTimesheet()
    {
        return $this->timesheets()->where('date', date('Y-m-d'))
                                    ->where('project_id', null)
                                    ->first();
    }

    public function getTodayTasks($lastStageId)
    {
        return  \Auth::user()->tasks()
                                ->where('stage_id', '<', $lastStageId)
                                ->whereDate('tasks.due_date', '<=', Carbon::now())
                                ->orderBy('tasks.due_date', 'ASC')
                                ->get();
    }

    public function getThisWeekTasks($lastStageId)
    {
        return  \Auth::user()->tasks()
                                ->where('stage_id', '<', $lastStageId)
                                ->whereDate('tasks.due_date', '>=', Carbon::parse('tomorrow'))
                                ->whereDate('tasks.due_date', '<=', Carbon::parse('sunday this week'))
                                ->orderBy('tasks.due_date', 'ASC')
                                ->get();
    }

    public function getNextWeekTasks($lastStageId)
    {
        return  \Auth::user()->tasks()
                                ->where('stage_id', '<', $lastStageId)
                                ->whereDate('tasks.due_date', '>=', Carbon::parse('monday next week'))
                                ->whereDate('tasks.due_date', '<=', Carbon::parse('sunday next week'))
                                ->orderBy('tasks.due_date', 'ASC')
                                ->get();
    }

    public function getTodayEvents()
    {
        return  \Auth::user()->events()
                                ->whereDate('events.start', '<=', Carbon::now())
                                ->whereDate('events.end', '>=', Carbon::now())
                                ->orderBy('events.end', 'ASC')
                                ->get();
    }

    public function getThisWeekEvents()
    {
        return  \Auth::user()->events()
                                ->whereDate('events.start', '>=', Carbon::parse('tomorrow'))
                                ->whereDate('events.end', '<=', Carbon::parse('sunday this week'))
                                ->orderBy('events.end', 'ASC')
                                ->get();
    }

    public function getNextWeekEvents()
    {
        return  \Auth::user()->events()
                                ->whereDate('events.start', '>=', Carbon::parse('monday next week'))
                                ->whereDate('events.end', '<=', Carbon::parse('sunday next week'))
                                ->orderBy('events.end', 'ASC')
                                ->get();
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

    public function setLocale($locale)
    {
        if($this->locale == null) {

            $this->locale = \Helpers::countryToLocale($locale->iso_code);
        }
        $this->timezone = $locale->timezone;
        $this->save();
    }

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->locale;
    }    

    public function priceFormat($price)
    {
        $settings = $this->companySettings;
        $currency = $settings?$settings->currency:'EUR';

        $money = new Money((int)\Helpers::ceil($price * 100), new Currency($currency));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);

    }

    public function dateFormat($date)
    {
        return date('M j, Y', strtotime($date));
    }

    public function timeFormat($time)
    {
        return date('g:i A', strtotime($time));
    }

    public function invoiceNumberFormat($number)
    {
        $settings = $this->companySettings;
        $prefix = $settings?$settings->invoice:'#INV';

        return $prefix . sprintf("%05d", $number);
    }

    public function getFirstTaskStage()
    {
        return Stage::where('class', Task::class)
                    ->where('open', 1)
                    ->where('created_by', $this->creatorId())
                    ->orderBy('order', 'asc')
                    ->first();
    }

    public function getLastTaskStage()
    {
        return Stage::where('class', Task::class)
                    ->where('open', 0)
                    ->where('created_by', $this->creatorId())
                    ->orderBy('order', 'desc')
                    ->first();
    }

    public function getFirstLeadStage()
    {
        return Stage::where('class', Lead::class)
                    ->where('open', 1)
                    ->where('created_by', $this->creatorId())
                    ->orderBy('order', 'asc')
                    ->first();
    }

    public function getLastLeadStage()
    {
        return Stage::where('class', Lead::class)
                    ->where('open', 0)
                    ->where('created_by', $this->creatorId())
                    ->orderBy('order', 'asc')
                    ->first();
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
        //     return Task::select('projects.*', 'tasks.id as task_id', 'tasks.title', 'tasks.due_date as task_due_date', 'task_stages.name as stage_name')->join('projects', 'projects.id', '=', 'tasks.project_id')->join('task_stages', 'tasks.stage_id', '=', 'task_stages.id')->where('projects.created_by', '=', $this->creatorId())->where('tasks.due_date', '>', date('Y-m-d'))->limit(5)->orderBy('task_due_date', 'ASC')->get();
        // }
        // elseif(\Auth::user()->type == 'client')
        // {
        //     return Task::select('projects.*', 'tasks.id as task_id', 'tasks.title', 'tasks.due_date as task_due_date', 'task_stages.name as stage_name')->join('projects', 'projects.id', '=', 'tasks.project_id')->join('task_stages', 'tasks.stage_id', '=', 'task_stages.id')->where('projects.client_id', '=', $this->authId())->where('tasks.due_date', '>', date('Y-m-d'))->limit(5)->orderBy('task_due_date', 'ASC')->get();
        // }
        // else
        // {
        //     return Task::select('tasks.*', 'tasks.id as task_id', 'tasks.due_date as task_due_date', 'user_projects.id as up_id', 'projects.name as project_name', 'task_stages.name as stage_name')->join('user_projects', 'user_projects.project_id', '=', 'tasks.project_id')->join('projects', 'user_projects.project_id', '=', 'projects.id')->join('task_stages', 'tasks.stage_id', '=', 'task_stages.id')->where('user_projects.user_id', '=', $this->authId())->where('tasks.due_date', '>', date('Y-m-d'))->limit(5)->orderBy(
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
            $max_projects = SubscriptionPlan::first()->max_projects;
        }else{
            $max_projects = $company->subscription()->max_projects;
        }

        if(!isset($max_projects)) return true;

        $total_projects = Project::where('created_by', '=', $company->id)->count();

        return $total_projects < $max_projects;
    }

    public function checkClientLimit()
    {
        $company        = User::find($this->creatorId());

        if(!$company->subscribed()){
            $max_clients = SubscriptionPlan::first()->max_clients;
        }else{
            $max_clients = $company->subscription()->max_clients;
        }

        if(!isset($max_clients)) return true;

        $total_clients = Client::where('created_by', '=', $company->id)->count();

        return $total_clients < $max_clients;
    }

    public function checkUserLimit()
    {
        $company        = User::find($this->creatorId());

        if(!$company->subscribed()){
            $max_users = SubscriptionPlan::first()->max_users;
        }else{
            $max_users = $company->subscription()->max_users;
        }

        if(!isset($max_users)) return true;

        $total_users = User::where('type', '!=', 'client')->where('created_by', '=', $company->id)->count();

        return $total_users < $max_users;
    }

    public function countCompany()
    {
        return User::where('type', '=', 'company')->count();
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

    public function initCompanyDefaults()
    {
        $id = $this->id;

        $colors = [
            "#92dacb", "#e7afa9",  "#acd6f1", "#e4c695", "#728191", "#a3e4d7", "#93d6af", "#7fb2d4", "#dab7e9", "#7c9cbd",
            "#dfce8c", "#dfb999", "#9fdfb9", "#ecf0f1", "#95a5a6", "#dcb5eb", "#e0b699", "#e4a9a1", "#bdc3c7", "#90a0a1"
        ];

        // // LeadSource
        // $leads = [
        //     __('Facebook'),
        //     __('Email'),
        //     __('Phone'),
        // ];
        // foreach($leads as $key => $lead)
        // {
        //     Category::create(
        //         [
        //             'name' => $lead,
        //             'class' => Lead::class,
        //             'order' => $key,
        //             'user_id' => $id,
        //             'created_by' => $id,
        //         ]
        //     );
        // }

        // // Expense Category
        // $expenses = [
        //     __('Gas'),
        //     __('Travel'),
        // ];
        // foreach($expenses as $key => $expense)
        // {
        //     Category::create(
        //         [
        //             'name' => $expense,
        //             'class' => Expense::class,
        //             'order' => $key,
        //             'user_id' => $id,
        //             'created_by' => $id,
        //         ]
        //     );
        // }

        // // Payments
        // $payments = [
        //     __('Cash'),
        //     __('Bank'),
        // ];
        // foreach($payments as $key => $payment)
        // {
        //     Category::create(
        //         [
        //             'name' => $payment,
        //             'class' => Payment::class,
        //             'order' => $key,
        //             'user_id' => $id,
        //             'created_by' => $id,
        //         ]
        //     );
        // }

        // LeadStage
        $leadStages = [
            __('Initial Contact'),
            __('Qualification'),
            __('Proposal'),
            __('Won'),
            __('Lost'),
        ];

        $leadStage = null;
        foreach($leadStages as $key => $stage)
        {
            $s = Stage::create(
                [
                    'name' => $stage,
                    'class' => Lead::class,
                    'order' => $key,
                    'open' => ($key < count($leadStages) - 2) ? 1 : 0,
                    'user_id' => $id,
                    'created_by' => $id,
                ]
            );

            if($leadStage == null){

                $leadStage = $s;
            }
        }

        // TaskStages
        $taskStages = [
            __('To Do'),
            __('In Progress'),
            __('Bugs'),
            __('Done'),
        ];

        $taskStage = null;
        foreach($taskStages as $key => $stage)
        {
            $s = Stage::create(
                [
                    'name' => $stage,
                    'class' => Task::class,
                    'order' => $key,
                    'open' => ($key < count($taskStages) - 1) ? 1 : 0,
                    'user_id' => $id,
                    'created_by' => $id,
                ]
            );

            if($taskStage == null){

                $taskStage = $s;
            }
        }

        //Sample Client
        $client = Client::create(
            [
                'name' => __('Sample Client'),
                'email' => __('client@example.com'),
                'phone' => __('1-540-568-0645'),
                'address' => __('45646 Jaleel Pines South Laron, SD 45620'),
                'website' => 'https://pinepipe.com',
                'user_id' => $id,
                'created_by' => $id
            ]
        );

        //Sample Contact
        $contact = Contact::create(
            [
                'name' => __('Sample Contact'),
                'client_id' => $client->id,
                'email' => __('contact@example.com'),
                'phone' => __('1-540-568-0645'),
                'address' => __('45646 Jaleel Pines South Laron, SD 45620'),
                'company' => __('Sample Client'),
                'job' => __('CEO'),
                'website' => 'https://pinepipe.com',
                'birthday' => '1981-05-09',
                'notes' => null,
                'user_id' => $id,
                'created_by' => $id
            ]
        );

        //Sample Lead
        $lead = Lead::create(
            [
                'name' => __('Sample Lead'),
                'price' => '10000',
                'stage_id'=> $leadStage->id,
                'user_id'=> $id,
                'client_id' => $client->id,
                'contact_id' => $contact->id,
                'created_by' => $id
            ]
        );

        //Sample Project
        $project = Project::create(
            [
                'name' => __('Sample Project'),
                'price' => '1000',
                'start_date' => null,
                'due_date' => null,
                'client_id' => $client->id,
                'description' => __("Learn about Pinepipe's cool features."),
                'archived' => false,
                'user_id' => $id,
                'created_by' => $id,
            ]
        );

        $project->users()->sync(array($id));

        //Sample Timesheet
        Timesheet::create(
            [
                'project_id' => $project->id,
                'user_id' => $id,
                'task_id' => null,
                'date' => Carbon::now(),
                'rate' => 50,
                'hours' => 8,
                'minutes' => 0,
                'seconds' => 0,
                'remark' => null,
                'created_by' => $id
            ]
        );

        //Sample Expense
        Expense::create(
            [
                'amount' => 500,
                'date' => Carbon::now(),
                'project_id' => $project->id,
                'category_id' => null,
                'user_id' => $id,
                'description' => null,
                'attachment' => null,
                'created_by' => $id,
            ]
        );

        //Sample Invoice
        Invoice::create(
            [
                'invoice_id' => 1,
                'project_id' => $project->id,
                'status' => 0,
                'issue_date' => Carbon::now(),
                'due_date' => Carbon::now()->addDays(30),
                'discount' => '0',
                'tax_id' => null,
                'notes' => null,
                'user_id' => $id,
                'created_by'=> $id,
            ]
        );
    }

    public function removeUserLeadInfo()
    {
        return Lead::where('user_id', '=', $this->id)->update(array('user_id' => null));
    }

    public function removeUserExpenseInfo()
    {
        return Expense::where('user_id', '=', $this->id)->update(array('user_id' => null));
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

    // public function sendEmailVerificationNotification()
    // {
    //     EmailVerificationJob::dispatch($this);
    // }

    public static function createCompany($post)
    {
        $post['password']   = Hash::make($post['password']);
        $user['type']       = 'company';
        $user['created_by'] = \Auth::user()->creatorId();

        $user = User::create($post);

        $role_r = Role::findByName('company');
        $user->initCompanyDefaults();
        $user->assignRole($role_r);

        return $user;
    }

    public static function createUser($post)
    {
        $role_r                = Role::findById($post['role']);

        $post['password']   = Hash::make($post['password']);
        $post['type']       = $role_r->name;
        $post['created_by'] = \Auth::user()->creatorId();

        $user = User::create($post);

        $user->assignRole($role_r);

        return $user;
    }

    public function updateCompany($post)
    {
        $this->update($post);
    }

    public function updateUser($post)
    {
        $role          = Role::findById($post['role']);

        if($role->name != 'client')
            $post['client_id'] = null;

        $post['type'] = $role->name;

        $this->update($post);

        $roles[] = $post['role'];

        $this->roles()->sync($roles);
    }

}
