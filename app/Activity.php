<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public function actionable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }    

    public function getAction()
    {
        switch($this->action)
        {
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
        }
    }

    public function isModal()
    {
        return $this->actionable_type == 'App\Task';
    }

    public static function createTask(Task $task)
    {
        $task->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'project_id' => $task->project_id,
                'created_by' => \Auth::user()->creatorId(),
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
                'project_id' => $task->project_id,
                'created_by' => \Auth::user()->creatorId(),
                'action' => 'activity_update_task',
                'value' => $task->title,
                'url'    => route('tasks.show', $task->id),
            ]
        );
    }

    public static function createProject(Project $project)
    {
        $project->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'project_id' => $project->id,
                'created_by' => \Auth::user()->creatorId(),
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
                'project_id' => $project->id,
                'created_by' => \Auth::user()->creatorId(),
                'action' => 'activity_update_project',
                'value' => $project->name,
                'url'    => route('projects.show', $project->id),
            ]
        );
    }

    public static function createProjectFile(Project $project, Media $file)
    {
        $file->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'project_id' => $project->id,
                'created_by' => \Auth::user()->creatorId(),
                'action' => 'activity_upload_file',
                'value' => $file->file_name,
                'url'    => route('projects.file.download', [$project->id, $file->id]),
            ]
        );
    }

    public static function createTaskFile(Task $task, Media $file)
    {
        $file->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'project_id' => $task->project_id,
                'created_by' => \Auth::user()->creatorId(),
                'action' => 'activity_upload_file',
                'value' => $file->file_name,
                'url'    => route('tasks.file.download', [$task->id, $file->id]),
            ]
        );
    }    

    public static function createInvoice(Invoice $invoice)
    {
        $invoice->activities()->create(
            [
                'user_id' => \Auth::user()->id,
                'project_id' => $invoice->project_id,
                'created_by' => \Auth::user()->creatorId(),
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
                'project_id' => $invoice->project_id,
                'created_by' => \Auth::user()->creatorId(),
                'action' => 'activity_update_invoice',
                'value' => \Auth::user()->invoiceNumberFormat($invoice->id),
                'url'    => route('invoices.show', $invoice->id),
            ]
        );
    }

}
