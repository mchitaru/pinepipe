<?php

namespace App\Http\Controllers;

use App\User;
use App\Task;
use App\Project;
use App\Milestone;
use App\UserProject;
use App\Activity;
use App\ProjectStage;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Requests\TaskDestroyRequest;
use App\Http\Traits\TaskTraits;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class TasksController extends Controller
{
    use TaskTraits;

    public function board($project_id)
    {
        if(\Auth::user()->can('show project'))
        {
            clock()->startEvent('TasksController', "Load tasks");

            if($project_id)
            {
                $project = Project::find($project_id)->first();
                $stages = $project->stages()->get();
            }
            else
            {
                $project = null;
                $stages = ProjectStage::stagesByUserType()->get();
            }

            clock()->endEvent('TasksController');

            return view('tasks.board', compact('stages', 'project'));
        }
        else
        {
            return Redirect::to(URL::previous() . "#tasks")->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
        $projects   = Project::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $priority   = Project::$priority;

        if($project_id)
        {
            $project = Project::find($project_id);
            $users   = $project->users()->get()->pluck('name', 'id');
        }else
        {
            $users   = User::where('created_by', '=', \Auth::user()->creatorId())
                        ->where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id');
        }

        $milestones = null;

        return view('tasks.create', compact('project_id', 'projects', 'users', 'priority', 'milestones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskStoreRequest $request, $project_id)
    {
        $post = $request->validated();

        Task::createTask($post);

        $request->session()->flash('success', __('Task successfully created.'));

        $url = redirect()->back()->getTargetUrl().'/#tasks';
        return "<script>window.location='{$url}'</script>";
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return $this->taskShow($task);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $project    = Project::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $task->project_id)->first();
        $projects   = Project::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $stages     = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                
        if($project)
        {
            $project_id = $project->id;
            $users   = $project->users()->get()->pluck('name', 'id');
        }else
        {
            $project_id = null;
            $users   = User::where('created_by', '=', \Auth::user()->creatorId())
                        ->where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id');
        }

        $user_id = $task->users()->get()->pluck('id');

        $priority   = Project::$priority;
        $milestones = Milestone::where('project_id', '=', $task->project_id)->get()->pluck('title', 'id');

        $due_date = $task->due_date;

        return view('tasks.edit', compact('task', 'stages', 'project_id', 'projects', 'user_id', 'users', 'priority', 'milestones', 'due_date'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        $post = $request->validated();

        $task->updateTask($post);

        $request->session()->flash('success', __('Task successfully updated.'));

        $url = redirect()->back()->getTargetUrl().'/#tasks';
        return "<script>window.location='{$url}'</script>";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskDestroyRequest $request, Task $task)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        $task->detachTask();

        $task->delete();

        return Redirect::to(URL::previous() . "#tasks")->with('success', __('Task successfully deleted'));
    }


    public function order(Request $request)
    {
        $post  = $request->all();

        foreach($post['order'] as $key => $item)
        {
            $task = Task::find($item);
            $task->order = $key;
            $task->stage_id = $post['stage_id'];
            $task->save();
        }
    }

    public function refresh(Request $request, $task_id)
    {
        if($task_id)
        {
            $task = Task::find($task_id);
            $task->project_id = $request['project_id'];

            return $this->edit($task);
        }

        return $this->create($request['project_id']);
    }
}
