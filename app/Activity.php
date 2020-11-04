<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

use App\Scopes\CollaboratorTenantScope;

class Activity extends Model
{
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CollaboratorTenantScope);
    }

    public function actionable()
    {
        return $this->morphTo();
    }

    public function projects()
    {
        return $this->belongsTo(Project::class, 'actionable_id')
            ->whereActionableType(Project::class);
    }

    public function tasks()
    {
        return $this->belongsTo(Task::class, 'actionable_id')
            ->whereActionableType(Task::class);
    }

    public function timesheets()
    {
        return $this->belongsTo(Timesheet::class, 'actionable_id')
            ->whereActionableType(Timesheet::class);
    }

    public function events()
    {
        return $this->belongsTo(Event::class, 'actionable_id')
            ->whereActionableType(Event::class);
    }

    public function leads()
    {
        return $this->belongsTo(Lead::class, 'actionable_id')
            ->whereActionableType(Lead::class);
    }

    public function contacts()
    {
        return $this->belongsTo(Contact::class, 'actionable_id')
            ->whereActionableType(Contact::class);
    }

    public function clients()
    {
        return $this->belongsTo(Client::class, 'actionable_id')
            ->whereActionableType(Client::class);
    }

    public function invoices()
    {
        return $this->belongsTo(Invoice::class, 'actionable_id')
            ->whereActionableType(Invoice::class);
    }

    public function expenses()
    {
        return $this->belongsTo(Expense::class, 'actionable_id')
            ->whereActionableType(Expense::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function getAction()
    {
        switch($this->action)
        {
            case 'activity_create_note': 
                return __('created note');
            case 'activity_create_event': 
                return __('created event');
            case 'activity_update_event': 
                return __('updated event');
            case 'activity_create_lead': 
                return __('created lead');
            case 'activity_update_lead': 
                return __('updated lead');
            case 'activity_create_task': 
                return __('created task');
            case 'activity_update_task': 
                return __('updated task');
            case 'activity_create_timesheet': 
                return __('created timesheet');
            case 'activity_update_timesheet': 
                return __('updated timesheet');
            case 'activity_create_project': 
                return __('created project');
            case 'activity_update_project': 
                return __('updated project');
            case 'activity_upload_file': 
                return __('uploaded file');
            case 'activity_create_invoice': 
                return __('created invoice');
            case 'activity_update_invoice': 
                return __('updated invoice');
            case 'activity_create_contact': 
                return __('created contact');
            case 'activity_update_contact': 
                return __('updated contact');
            case 'activity_create_client': 
                return __('created client');
            case 'activity_update_client': 
                return __('updated client');
            case 'activity_create_comment': 
                return __('added comment');
            case 'activity_update_comment': 
                return __('updated comment');
            case 'activity_create_expense': 
                return __('added expense');
            case 'activity_update_expense': 
                return __('updated expense');
        }
    }

    public function isModal()
    {
        switch($this->action)
        {
            case 'activity_create_event': 
            case 'activity_update_event': 
            case 'activity_create_task': 
            case 'activity_update_task': 
            case 'activity_create_timesheet': 
            case 'activity_update_timesheet': 
            case 'activity_create_contact': 
            case 'activity_update_contact': 
            case 'activity_create_note': 
            case 'activity_create_comment': 
            case 'activity_update_comment': 
            case 'activity_create_expense': 
            case 'activity_update_expense': 
                return true;
        }

        return false;
    }

    public static function createEvent(Event $event)
    {
        $event->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_create_event',
                'value' => $event->name,
                'url'    => route('events.show', $event->id),
            ]
        );
    }

    public static function updateEvent(Event $event)
    {
        $event->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_update_event',
                'value' => $event->name,
                'url'    => route('events.show', $event->id),
            ]
        );
    }

    public static function createTask(Task $task)
    {
        $task->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_create_task',
                'value' => $task->title,
                'url'    => route('tasks.show', $task->id),
            ]
        );
    }

    public static function updateTask(Task $task)
    {
        $task->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_update_task',
                'value' => $task->title,
                'url'    => route('tasks.show', $task->id),
            ]
        );
    }

    public static function createTimesheet(Timesheet $timesheet)
    {
        $timesheet->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_create_timesheet',
                'value' => $timesheet->task ? $timesheet->task->title:__('No title'),
                'url'    => route('timesheets.edit', $timesheet->id),
            ]
        );
    }

    public static function updateTimesheet(Timesheet $timesheet)
    {
        $timesheet->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_update_task',
                'value' => $timesheet->task ? $timesheet->task->title:__('No title'),
                'url'    => route('timesheets.edit', $timesheet->id),
            ]
        );
    }

    public static function createProject(Project $project)
    {
        $project->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_create_project',
                'value' => $project->name,
                'url'    => route('projects.show', $project->id),
            ]
        );
    }

    public static function updateProject(Project $project)
    {
        $project->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_update_project',
                'value' => $project->name,
                'url'    => route('projects.show', $project->id),
            ]
        );
    }

    public static function createProjectFile(Project $project, Media $file)
    {
        $project->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_upload_file',
                'value' => $file->file_name,
                'url'    => route('projects.file.download', [$project->id, $file->id]),
            ]
        );
    }

    public static function createTaskFile(Task $task, Media $file)
    {
        $task->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_upload_file',
                'value' => $file->file_name,
                'url'    => route('tasks.file.download', [$task->id, $file->id]),
            ]
        );
    }    

    public static function createTaskComment(Task $task, Comment $comment)
    {
        $task->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_create_comment',
                'value' => $comment->comment,
                'url'    => route('tasks.show', $task->id)."/comment",
            ]
        );
    }    

    public static function updateTaskComment(Task $task, Comment $comment)
    {
        $task->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_update_comment',
                'value' => $comment->comment,
                'url'    => route('tasks.show', $task->id)."/comment",
            ]
        );
    }    

    public static function createInvoice(Invoice $invoice)
    {
        $invoice->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_create_invoice',
                'value' => \Auth::user()->invoiceNumberFormat($invoice->id),
                'url'    => route('invoices.show', $invoice->id),
            ]
        );
    }

    public static function updateInvoice(Invoice $invoice)
    {
        $invoice->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_update_invoice',
                'value' => \Auth::user()->invoiceNumberFormat($invoice->id),
                'url'    => route('invoices.show', $invoice->id),
            ]
        );
    }    

    public static function createLead(Lead $lead)
    {
        $lead->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_create_lead',
                'value' => $lead->name,
                'url'    => route('leads.show', $lead->id),
            ]
        );
    }

    public static function updateLead(Lead $lead)
    {
        $lead->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_update_lead',
                'value' => $lead->name,
                'url'    => route('leads.show', $lead->id),
            ]
        );
    }

    public static function createLeadFile(Lead $lead, Media $file)
    {
        $lead->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_upload_file',
                'value' => $file->file_name,
                'url'    => route('leads.file.download', [$lead->id, $file->id]),
            ]
        );
    }

    public static function createLeadNote(Lead $lead, Note $note)
    {
        $lead->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_create_note',
                'value' => $note->text,
                'url'    => route('notes.edit', $note->id),
            ]
        );
    }

    public static function createProjectNote(Project $project, Note $note)
    {
        $project->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_create_note',
                'value' => $note->text,
                'url'    => route('notes.edit', $note->id),
            ]
        );
    }

    public static function createContact(Contact $contact)
    {
        $contact->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_create_contact',
                'value' => $contact->name,
                'url'    => route('contacts.edit', $contact->id),
            ]
        );
    }

    public static function updateContact(Contact $contact)
    {
        $contact->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_update_contact',
                'value' => $contact->name,
                'url'    => route('contacts.edit', $contact->id),
            ]
        );
    }

    public static function createClient(Client $client)
    {
        $client->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_create_client',
                'value' => $client->name,
                'url'    => route('clients.show', $client->id),
            ]
        );
    }

    public static function updateClient(Client $client)
    {
        $client->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_update_client',
                'value' => $client->name,
                'url'    => route('clients.show', $client->id),
            ]
        );
    }

    public static function createExpense(Expense $expense)
    {
        $expense->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_create_expense',
                'value' => $expense->category?$expense->category->name.' ('.\Auth::user()->priceFormat($expense->amount).')':\Auth::user()->priceFormat($expense->amount),
                'url'    => route('expenses.edit', $expense->id),
            ]
        );
    }

    public static function updateExpense(Expense $expense)
    {
        $expense->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_update_expense',
                'value' => $expense->category?$expense->category->name.' ('.\Auth::user()->priceFormat($expense->amount).')':\Auth::user()->priceFormat($expense->amount),
                'url'    => route('expenses.edit', $expense->id),
            ]
        );
    }

}
