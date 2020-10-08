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

    public function user()
    {
        return $this->belongsTo(User::class);
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
            case 'activity_create_contact': 
            case 'activity_update_contact': 
            case 'activity_create_note': 
                return true;
        }

        return false;
    }

    public static function createTask(Task $task)
    {
        if($task->project) {
            $task->project->activities()->create(
                [
                    'user_id' => \Auth::user()->id,
                    'created_by' => \Auth::user()->created_by,
                    'action' => 'activity_create_task',
                    'value' => $task->title,
                    'url'    => route('tasks.show', $task->id),
                ]
            );
        }
    }

    public static function updateTask(Task $task)
    {
        if($task->project) {
            $task->project->activities()->create(
                [
                    'user_id' => \Auth::user()->id,
                    'created_by' => \Auth::user()->created_by,
                    'action' => 'activity_update_task',
                    'value' => $task->title,
                    'url'    => route('tasks.show', $task->id),
                ]
            );
        }
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
        if($task->project) {
            $task->project->activities()->create(
                [
                    'user_id' => \Auth::user()->id,
                    'created_by' => \Auth::user()->created_by,
                    'action' => 'activity_upload_file',
                    'value' => $file->file_name,
                    'url'    => route('tasks.file.download', [$task->id, $file->id]),
                ]
            );
        }
    }    

    public static function createInvoice(Invoice $invoice)
    {
        if($invoice->project) {
            $invoice->project->activities()->create(
                [
                    'user_id' => \Auth::user()->id,
                    'created_by' => \Auth::user()->created_by,
                    'action' => 'activity_create_invoice',
                    'value' => \Auth::user()->invoiceNumberFormat($invoice->id),
                    'url'    => route('invoices.show', $invoice->id),
                ]
            );
        }
    }

    public static function updateInvoice(Invoice $invoice)
    {
        if($invoice->project) {
            $invoice->project->activities()->create(
                [
                    'user_id' => \Auth::user()->id,
                    'created_by' => \Auth::user()->created_by,
                    'action' => 'activity_update_invoice',
                    'value' => \Auth::user()->invoiceNumberFormat($invoice->id),
                    'url'    => route('invoices.show', $invoice->id),
                ]
            );
        }
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

    public static function createLeadEvent(Lead $lead, Event $event)
    {
        $lead->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_create_event',
                'value' => $event->name,
                'url'    => route('events.edit', $event->id),
            ]
        );
    }

    public static function updateLeadEvent(Lead $lead, Event $event)
    {
        $lead->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->created_by,
                'action' => 'activity_update_event',
                'value' => $event->name,
                'url'    => route('events.edit', $event->id),
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

}
