<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\TaskChecklist;
use App\Client;
use App\ProjectClientPermission;
use App\TaskComment;
use App\Invoice;
use App\Lead;
use App\Milestone;
use App\PaymentPlan;
use App\Project;
use App\ProjectFile;
use App\ProjectStage;
use App\SubTask;
use App\Task;
use App\TaskFile;
use App\Timesheet;
use App\Expense;
use App\User;
use App\UserProject;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Requests\ProjectDestroyRequest;
use App\Utility;
use Auth;
use File;
use Illuminate\Http\Request;

class ProjectsController extends ProjectsSectionController
{

    public function create()
    {
        $users   = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->get()->pluck('name', 'id');
        $clients = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'client')->get()->pluck('name', 'id');
        $leads   = Lead::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('projects.create', compact('clients', 'users', 'leads'));
    }


    public function store(ProjectStoreRequest $request)
    {
        $post = $request->validated();

        if(\Auth::user()->checkProjectLimit())
        {
            $project = Project::createProject($post);

            $request->session()->flash('success', __('Project successfully created.'));

            $url = redirect()->route('projects.show', $project->id)->getTargetUrl();
            return "<script>window.location='{$url}'</script>";
        }
        else
        {
            $request->session()->flash('error', __('Your have reached your project limit. Please upgrade your plan to add more projects!'));
        }

        $url = redirect()->back()->getTargetUrl().'/#projects';
        return "<script>window.location='{$url}'</script>";
    }


    public function edit(Project $project)
    {
        $clients = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'client')->get()->pluck('name', 'id');
        $users   = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->get()->pluck('name', 'id');
        $leads   = Lead::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        $user_id = $project->users()->get()->pluck('id');
        
        $start_date = $project->start_date;
        $due_date = $project->due_date;

        return view('projects.edit', compact('project', 'clients', 'user_id', 'users', 'leads', 'start_date', 'due_date'));
    }

    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $post = $request->validated();

        $project->updateProject($post);

        $request->session()->flash('success', __('Project successfully updated.'));

        $url = redirect()->back()->getTargetUrl();
        return "<script>window.location='{$url}'</script>";
    }

    public function show(Project $project)
    {
        if(\Auth::user()->can('show project'))
        {
            $project_id = $project->id;
            $stages  = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order', 'ASC')->get();
            $project_files = ProjectFile::where('project_id', $project_id)->get();
            $activities = $project->activities;

            if(\Auth::user()->can('manage timesheet'))
            {
                if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
                {
                    $timesheets = Timesheet::where('project_id', '=', $project_id)->get();
                }
                else
                {
                    $timesheets = Timesheet::where('user_id', '=', \Auth::user()->id)->where('project_id', '=', $project_id)->get();
                }
            }
            else
            {
                $timesheets = array();
            }

            if(\Auth::user()->can('manage invoice'))
            {
                $invoices = Invoice::where('project_id', '=', $project_id)->get();
            }
            else
            {
                $invoices = array();
            }

            if(\Auth::user()->can('manage expense'))
            {
                if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
                {
                    $expenses = Expense::where('project_id', '=', $project_id)->get();
                }
                else
                {
                    $expenses = Expense::where('user_id', '=', \Auth::user()->id)->where('project_id', '=', $project_id)->get();
                }
            }
            else
            {
                $expenses = array();
            }

            $task_count = 0;
            foreach($stages as $stage){
                $task_count = $task_count + count($stage->getTasksByUserType($project_id));
            }

            return view('projects.show', compact('project', 'project_id', 'stages', 'task_count', 'project_files', 'timesheets', 'invoices', 'expenses', 'activities'));
        }
        else
        {
            return Redirect::to(URL::previous() . "#projects")->with('error', __('Permission denied.'));
        }
    }

    public function destroy(ProjectDestroyRequest $request, Project $project)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        $project->detachProject();

        $project->delete();

        return Redirect::to(URL::previous() . "#projects")->with('success', __('Project successfully deleted'));
    }

    public function clientPermission($project_id, $client_id)
    {
        $client   = User::find($client_id);
        $project  = Project::find($project_id);
        $selected = $client->clientPermission($project->id);
        if($selected)
        {
            $selected = explode(',', $selected->permissions);
        }
        else
        {
            $selected = [];
        }
        $permissions = Project::$permission;

        return view('clients.permissions', compact('permissions', 'project_id', 'client_id', 'selected'));
    }

    public function storeClientPermission(request $request, $project_id, $client_id)
    {
        $this->validate(
            $request, [
                        'permissions' => 'required',
                    ]
        );

        $project = Project::find($project_id);
        if($project->created_by == \Auth::user()->creatorId())
        {
            $client      = User::find($client_id);
            $permissions = $client->clientPermission($project->id);
            if($permissions)
            {
                $permissions->permissions = implode(',', $request->permissions);
                $permissions->save();
            }
            else
            {
                ProjectClientPermission::create(
                    [
                        'client_id' => $client->id,
                        'project_id' => $project->id,
                        'permissions' => implode(',', $request->permissions),
                    ]
                );
            }

            return redirect()->back()->with('success', __('Permissions successfully updated.'))->with('status', 'clients');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'))->with('status', 'clients');
        }

    }

    public function search($search)
    {
        if(\Auth::user()->type == 'client')
        {
            $objProject = Project::select(
                [
                    'projects.id',
                    'projects.name',
                ]
            )->where('projects.client_id', '=', Auth::user()->id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('projects.name', 'LIKE', $search . "%")->get();
            $arrProject = [];
            foreach($objProject as $project)
            {
                $arrProject[] = [
                    'text' => $project->name,
                    'link' => route('projects.show', [$project->id]),
                ];
            }

            $objTask = Task::select(
                [
                    'tasks.project_id',
                    'tasks.title',
                ]
            )->join('projects', 'tasks.project_id', '=', 'projects.id')->where('projects.client_id', '=', Auth::user()->id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('tasks.title', 'LIKE', $search . "%")->get();
            $arrTask = [];
            foreach($objTask as $task)
            {
                $arrTask[] = [
                    'text' => $task->title,
                    'link' => route('projects.show', [$task->project_id]),
                ];
            }
        }
        else if(\Auth::user()->type == 'company')
        {
            $objProject = Project::select(
                [
                    'projects.id',
                    'projects.name',
                ]
            )->where('projects.created_by', '=', \Auth::user()->id)->where('projects.name', 'LIKE', $search . "%")->get();
            $arrProject = [];
            foreach($objProject as $project)
            {
                $arrProject[] = [
                    'text' => $project->name,
                    'link' => route('projects.show', [$project->id]),
                ];
            }

            $objTask = Task::select(
                [
                    'tasks.project_id',
                    'tasks.title',
                ]
            )->join('projects', 'tasks.project_id', '=', 'projects.id')->where('projects.created_by', '=', \Auth::user()->id)->where('tasks.title', 'LIKE', $search . "%")->get();
            $arrTask = [];
            foreach($objTask as $task)
            {
                $arrTask[] = [
                    'text' => $task->title,
                    'link' => route('projects.show', [$task->project_id]),
                ];
            }
        }
        else
        {
            $objProject = Project::select(
                [
                    'projects.id',
                    'projects.name',
                ]
            )->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', Auth::user()->id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('projects.name', 'LIKE', $search . "%")->get();
            $arrProject = [];
            foreach($objProject as $project)
            {
                $arrProject[] = [
                    'text' => $project->name,
                    'link' => route('projects.show', [$project->id]),
                ];
            }

            $objTask = Task::select(
                [
                    'tasks.project_id',
                    'tasks.title',
                ]
            )->join('projects', 'tasks.project_id', '=', 'projects.id')->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', Auth::user()->id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('tasks.title', 'LIKE', $search . "%")->get();
            $arrTask = [];
            foreach($objTask as $task)
            {
                $arrTask[] = [
                    'text' => $task->title,
                    'link' => route('projects.show', [$task->project_id]),
                ];
            }
        }

        return json_encode(
            [
                'Projects' => $arrProject,
                'Tasks' => $arrTask,
            ]
        );
    }

}
