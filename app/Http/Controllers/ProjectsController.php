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
use App\User;
use App\UserProject;
use App\Http\Requests\ProjectStoreRequest;
use App\Utility;
use Auth;
use File;
use Illuminate\Http\Request;

class ProjectsController extends ProjectsSectionController
{

    public function create()
    {
        if(\Auth::user()->can('create project'))
        {
            $users   = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->get()->pluck('name', 'id');
            $clients = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'client')->get()->pluck('name', 'id');
            $leads   = Lead::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            $is_create = true;

            return view('projects.create', compact('clients', 'users', 'leads', 'is_create'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function store(ProjectStoreRequest $request)
    {
        $post = $request->validated();

        $objUser      = \Auth::user();
        $total_client = $objUser->countProject();
        $plan         = PaymentPlan::find($objUser->plan);

        if($total_client < $plan->max_clients || $plan->max_clients == -1)
        {
            $project              = Project::make($post);
            $project->created_by  = \Auth::user()->creatorId();
            $project->save();

            User::find(\Auth::user()->creatorId())->projects()->attach($project->id);

            foreach($post['user'] as $key => $user)
            {
                User::find($user)->projects()->attach($project->id);
            }

            $permissions = Project::$permission;
            ProjectClientPermission::create(
                [
                    'client_id' => $project->client,
                    'project_id' => $project->id,
                    'permissions' => implode(',', $permissions),
                ]
            );


            $request->session()->flash('success', __('Project successfully created.'));
        }
        else
        {
            $request->session()->flash('error', __('Your project limit is over, Please upgrade plan.'));
        }

        $url = redirect()->back()->getTargetUrl();
        return "<script>window.location='{$url}'</script>";
    }


    public function edit($id)
    {

        if(\Auth::user()->can('edit project'))
        {
            $clients = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'client')->get()->pluck('name', 'id');
            $leads   = Lead::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $project = Project::findOrfail($id);
            if($project->created_by == \Auth::user()->creatorId())
            {
                return view('projects.edit', compact('project', 'clients', 'leads'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function show($project_id)
    {
        if(\Auth::user()->can('show project'))
        {
            $project        = Project::where('id', $project_id)->first();
            $project_user   = UserProject::where('project_id', $project_id)->get();
            $project_id = $project->id;
            $project_status_list = Project::$project_status;
            $stages  = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order', 'ASC')->get();
            $project_files = ProjectFile::where('project_id', $project_id)->get();
            $activities = $project->activities;

            $timeSheets = '';
            if(\Auth::user()->can('manage timesheet'))
            {
                if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
                {
                    $timeSheets = Timesheet::where('project_id', '=', $project_id)->get();
                }
                else
                {
                    $timeSheets = Timesheet::where('user_id', '=', \Auth::user()->id)->where('project_id', '=', $project_id)->get();
                }
            }

            $project_status = __('Unknown');
            foreach($project_status_list as $key => $status)
            {
                if($key== $project->status)
                {
                    $project_status = $status;
                }
            }

            return view('projects.show', compact('project', 'project_user', 'project_status', 'project_id', 'stages', 'project_files', 'timeSheets', 'activities'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(Request $request, $id)
    {
        if(\Auth::user()->can('edit project'))
        {
            $project = Project::findOrfail($id);
            if($project->created_by == \Auth::user()->creatorId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                       'price' => 'required',
                                       'date' => 'required',
                                       'lead' => 'required',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('users')->with('error', $messages->first());
                }

                $project->name        = $request->name;
                $project->price       = $request->price;
                $project->due_date    = $request->date;
                $project->client      = $request->client;
                $project->lead        = $request->lead;
                $project->description = $request->description;
                $project->save();

                ProjectClientPermission::where('client_id','=',$project->client)->where('project_id','=', $project->id)->delete();
                $permissions = Project::$permission;
                ProjectClientPermission::create(
                    [
                        'client_id' => $project->client,
                        'project_id' => $project->id,
                        'permissions' => implode(',', $permissions),
                    ]
                );

                return redirect()->route('projects.index')->with('success', __('Project successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        if(\Auth::user()->can('edit project'))
        {
            $project = Project::findOrfail($id);
            if($project->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'status' => 'required',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', 'Project Status is required.');
                }

                $project->status = $request->status;
                $project->save();

                return redirect()->route('projects.show', compact('id'))->with('success', __('Status Updated!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function destroy($id)
    {
        if(\Auth::user()->can('delete project'))
        {
            $project = Project::findOrfail($id);
            if($project->created_by == \Auth::user()->creatorId())
            {
                //                $project->delete();
                Milestone::where('project_id', $id)->delete();
                UserProject::where('project_id', $id)->delete();
                ActivityLog::where('project_id', $id)->delete();

                $projectFile = ProjectFile::select('file_path')->where('project_id', $id)->get()->map(
                    function ($file){
                        $dir        = storage_path('app/public/project_files/');
                        $file->file = $dir . $file->file;

                        return $file;
                    }
                );
                if(!empty($projectFile))
                {
                    foreach($projectFile->pluck('file_path') as $file)
                    {
                        File::delete($file);
                    }
                }
                ProjectFile::where('project_id', $id)->delete();

                Invoice::where('project_id', $id)->update(array('project_id' => 0));
                $tasks     = Task::select('id')->where('project_id', $id)->get()->pluck('id');
                $comment   = TaskComment::whereIn('task_id', $tasks)->delete();
                $checklist = TaskChecklist::whereIn('task_id', $tasks)->delete();

                $taskFile = TaskFile::select('file')->whereIn('task_id', $tasks)->get()->map(
                    function ($file){
                        $dir        = storage_path('app/public/tasks/');
                        $file->file = $dir . $file->file;

                        return $file;
                    }
                );
                if(!empty($taskFile))
                {
                    foreach($taskFile->pluck('file') as $file)
                    {
                        File::delete($file);
                    }
                }
                TaskFile::whereIn('task_id', $tasks)->delete();

                Task::where('project_id', $id)->delete();

                return redirect()->route('projects.index')->with('success', __('Project successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function userInvite($project_id)
    {
        $assign_user = UserProject::select('user_id')->where('project_id', $project_id)->get()->pluck('user_id');
        $employee    = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->whereNotIn('id', $assign_user)->get()->pluck('name', 'id');

        return view('projects.invite', compact('employee', 'project_id'));
    }

    public function Invite(Request $request, $project_id)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'user' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('projects.show', $project_id)->with('error', $messages->first());
        }

        foreach($request->user as $key => $user)
        {
            $userproject             = new UserProject();
            $userproject->user_id    = $user;
            $userproject->project_id = $project_id;
            $userproject->save();
        }


        return redirect()->route('projects.show', $project_id)->with('success', __('User successfully Invited.'));
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

    public function getSearchJson($search)
    {
        if(\Auth::user()->type == 'client')
        {
            $objProject = Project::select(
                [
                    'projects.id',
                    'projects.name',
                ]
            )->where('projects.client', '=', Auth::user()->id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('projects.name', 'LIKE', $search . "%")->get();
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
            )->join('projects', 'tasks.project_id', '=', 'projects.id')->where('projects.client', '=', Auth::user()->id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('tasks.title', 'LIKE', $search . "%")->get();
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
                'Project' => $arrProject,
                'Tasks' => $arrTask,
            ]
        );
    }

}
