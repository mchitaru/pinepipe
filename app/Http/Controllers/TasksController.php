<?php

namespace App\Http\Controllers;

use App\User;
use App\Task;
use App\Project;
use App\Milestone;
use App\UserProject;
use App\Activity;
use App\TaskStage;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Requests\TaskDestroyRequest;
use App\Traits\Taskable;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class TasksController extends Controller
{
    use Taskable;

    public function board($project_id = null)
    {
        if(\Auth::user()->can('manage task'))
        {
            clock()->startEvent('TasksController', "Load tasks");

            if($project_id)
            {
                $project = Project::find($project_id);
                $stages = $project->stages()->get();
                $project_name = $project->name;
            }
            else
            {
                $project = null;
                $project_name = null;
                $stages = TaskStage::stagesByUserType()->get();
            }

            clock()->endEvent('TasksController');

            return view('tasks.board', compact('stages', 'project_id', 'project_name'));
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $project_id = $request['project_id'];

        $projects   = Project::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $priority   = Project::$priority;

        if($project_id)
        {
            $project = Project::find($project_id);
            $users   = $project->users()->get()->pluck('name', 'id');

            if(isSet($users[\Auth::user()->id])) {
                $users[\Auth::user()->id] = __('(myself)');
            }

        }else
        {
            $users   = User::where('created_by', '=', \Auth::user()->creatorId())
                        ->where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id')
                        ->prepend('(myself)', \Auth::user()->id);
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
    public function store(TaskStoreRequest $request)
    {
        $post = $request->validated();

        Task::createTask($post);

        $request->session()->flash('success', __('Task successfully created.'));

        return response()->json(['success'], 207);
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
        $stages     = TaskStage::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        if($project)
        {
            $project_id = $project->id;
            $users   = $project->users()->get()->pluck('name', 'id');

            if(isSet($users[\Auth::user()->id])) {
                $users[\Auth::user()->id] = __('(myself)');
            }

        }else
        {
            $project_id = null;
            $users   = User::where('created_by', '=', \Auth::user()->creatorId())
                        ->where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id')
                        ->prepend('(myself)', \Auth::user()->id);
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

        return response()->json(['success'], 207);
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

        return Redirect::to(URL::previous())->with('success', __('Task successfully deleted'));
    }


    public function order(Request $request)
    {
        $post  = $request->all();

        foreach($post['order'] as $key => $item)
        {
            $task = Task::find($item);

            $task->updateOrder($post['stage_id'], $key);
        }
    }

    public function refresh(Request $request, $task_id)
    {
        $request->flash();

        if($task_id)
        {
            $task = Task::find($task_id);
            $task->project_id = $request['project_id'];

            return $this->edit($task);
        }

        return $this->create($request);
    }
}
