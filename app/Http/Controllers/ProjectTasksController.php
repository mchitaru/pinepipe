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
    public function board(Project $project)
    {
        if(\Auth::user()->can('show project'))
        {
            $stages  = \Auth::user()->getProjectStages();

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
    public function create(Project $project)
    {
        $projects   = Project::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $milestones = Milestone::where('project_id', '=', $project->id)->get()->pluck('title', 'id');
        $priority   = Project::$priority;
        $users      = $project->users()->get()->pluck('name', 'id');
        $project_id = $project->id;
        $is_create = true;

        return view('tasks.create', compact('project_id', 'projects', 'priority', 'users', 'milestones', 'is_create'));
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

        $project->addTask($post);
        
        $request->session()->flash('success', __('Task successfully created.'));

        $url = route('projects.show', $project->id);
        return "<script>window.location='{$url}'</script>";
    }
}
