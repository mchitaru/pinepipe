<?php

namespace App\Http\Controllers;

use App\User;
use App\Task;
use App\Project;
use App\Milestone;
use App\UserProject;
use App\ActivityLog;
use App\ProjectStage;
use App\Http\Requests\ProjectTaskStoreRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProjectTasksController extends Controller
{
    public function board($project_id)
    {
        if(\Auth::user()->can('show project'))
        {
            $stages  = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order', 'ASC')->get();

            return view('tasks.board', compact('stages', 'project_id'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
        $project    = Project::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $project_id)->first();
        $projects   = Project::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $milestones = Milestone::where('project_id', '=', $project->id)->get()->pluck('title', 'id');
        $priority   = Project::$priority;
        $usersArr   = UserProject::where('project_id', '=', $project->id)->get();
        $users      = array();
        foreach($usersArr as $user)
        {
            $users[$user->project_assign_user->id] = ($user->project_assign_user->name . ' - ' . $user->project_assign_user->email);
        }
        $project_id = $project->id;
        $is_create = true;

        return view('tasks.create', compact('project', 'projects', 'priority', 'users', 'milestones', 'project_id', 'is_create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectTaskStoreRequest $request, Project $project)
    {
        $post = $request->validated();

        $post['project_id'] = $project->id;
        $post['stage_id']   = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->first()->id;
        $task               = Task::make($post);
        $task->created_by   = \Auth::user()->creatorId();
        $task->save();

        $users = null;
        if(isset($post['user_id'])){

            $users = $post['user_id'];
        }

        if(\Auth::user()->type != 'company'){        

            $users->prepend(\Auth::user()->id);
        }

        $task->users()->sync($users);

        ActivityLog::createTask($task);

        $request->session()->flash('success', __('Task successfully created.'));

        $url = route('projects.show', $project->id);
        return "<script>window.location='{$url}'</script>";
    }
}
