<?php

namespace App\Http\Controllers;

use App\Tag;
use App\User;
use App\Task;
use App\Project;
use App\Milestone;
use App\UserProject;
use App\Activity;
use App\Stage;
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

    public function board(Request $request, $project_id = null)
    {
        if(\Auth::user()->can('manage task'))
        {
            clock()->startEvent('TasksController', "Load tasks");

            if($request['tag'] == 'all'){
                $users = [];
            }else{
                $users = [\Auth::user()->id];
            }

            if($project_id)
            {
                $project = Project::find($project_id);
                $stages = $project->stages($request['sort']?$request['sort']:'order', $request['dir'], $users)->get();
                $project_name = $project->name;
            }
            else
            {
                $project = null;
                $project_name = null;
                $stages = Stage::taskStagesByUserType($request['sort'], $request['dir'], $users)->get();
            }

            clock()->endEvent('TasksController');

            if ($request->ajax())
            {
                return view('tasks.board', compact('stages', 'project_id', 'project_name'))->render();
            }

            return view('tasks.page', compact('stages', 'project_id', 'project_name'));
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

        $user_id = null;

        if($project_id)
        {
            $project = Project::find($project_id);
            $users   = $project->users()->get()->pluck('name', 'id');

            if(isSet($users[\Auth::user()->id])) {
                $users[\Auth::user()->id] = __('(myself)');
                $user_id = \Auth::user()->id;
            }

        }else
        {
            $users   = User::where('created_by', '=', \Auth::user()->creatorId())
                        ->where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id')
                        ->prepend('(myself)', \Auth::user()->id);

            $user_id = \Auth::user()->id;
        }

        $milestones = null;
        $tags = Tag::where('created_by', '=', \Auth::user()->creatorId())
                        ->whereHas('tasks')
                        ->get()
                        ->pluck('name', 'name');

        return view('tasks.create', compact('project_id', 'projects', 'users', 'user_id', 'priority', 'milestones', 'tags'));
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

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
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
        $stages     = Stage::where('class', Task::class)
                            ->where('created_by', \Auth::user()->creatorId())
                            ->get()
                            ->pluck('name', 'id');

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

        $tags = Tag::where('created_by', '=', \Auth::user()->creatorId())
                        ->whereHas('tasks')
                        ->get()
                        ->pluck('name', 'name');

        $task_tags = [];
        foreach($task->tags as $tag)
        {
            $task_tags[] = $tag->name;    
        }

        $due_date = $task->due_date;

        return view('tasks.edit', compact('task', 'stages', 'project_id', 'projects', 'user_id', 'users', 'priority', 'milestones', 'tags', 'task_tags', 'due_date'));
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

        $task->updateTask($post, $request->isMethod('patch'));

        $request->session()->flash('success', __('Task successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
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
        $updated = false;
        $post  = $request->all();

        foreach($post['order'] as $key => $item)
        {
            $task = Task::find($item);

            $updated = $task->updateOrder($post['stage_id'], $key) || $updated;
        }

        $return               = [];
        $return['is_success'] = $updated;

        return response()->json($return);
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
