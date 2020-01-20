<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use App\Milestone;
use App\UserProject;
use App\ActivityLog;
use App\ProjectStage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TasksController extends ProjectsSectionController
{

    public function board()
    {
        if(\Auth::user()->can('show project'))
        {
            $stages  = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order', 'ASC')->get();
            $project_id = 0;

            return view('tasks.board', compact('stages', $project_id));
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
    public function create()
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

        return view('tasks.create', compact('project', 'projects', 'priority', 'users', 'milestones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'priority' => 'required',
                                   'assign_to' => 'required',
                                   'due_date' => 'required',
                                   'start_date' => 'required',
                               ]
            );
        }
        else
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'priority' => 'required',
                                   'due_date' => 'required',
                                   'start_date' => 'required',
                               ]
            );
        }
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $project = Project::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $project_id)->first();
        if($project)
        {
            $post = $request->all();
            if(\Auth::user()->type != 'company')
            {
                $post['assign_to'] = \Auth::user()->id;
            }
            $post['project_id'] = $project_id;
            $post['stage']      = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->first()->id;
            $task               = Task::create($post);

            ActivityLog::create(
                [
                    'user_id' => \Auth::user()->creatorId(),
                    'project_id' => $project_id,
                    'log_type' => 'Create Task',
                    'remark' => \Auth::user()->name . ' ' . __('Create new Task') . " <b>" . $task->title . "</b>",
                    'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                                __('create task') .
                                ' <a href="' . route('tasks.show', $task->id) . '">'. $task->title.'</a>',
                ]
            );

            return redirect()->back()->with('success', __('Task successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('You can \'t Add Task.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($task_id)
    {
        $task    = Task::find($task_id);
        $project = Project::find($task->project_id);

        $permissions = $project->client_project_permission();
        $perArr      = (!empty($permissions) ? explode(',', $permissions->permissions) : []);

        $activities = array();

        return view('tasks.show', compact('task', 'perArr', 'project', 'activities'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit($task_id)
    {
        $task       = Task::find($task_id);
        $project    = Project::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $task->project_id)->first();
        $projects   = Project::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $usersArr   = UserProject::where('project_id', '=', $task->project_id)->get();
        $priority   = Project::$priority;
        $milestones = Milestone::where('project_id', '=', $project->id)->get()->pluck('title', 'id');
        $users      = array();
        foreach($usersArr as $user)
        {
            $users[$user->project_assign_user->id] = ($user->project_assign_user->name . ' - ' . $user->project_assign_user->email);
        }

        return view('tasks.edit', compact('project', 'projects', 'users', 'task', 'priority', 'milestones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $task_id)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'priority' => 'required',
                                   'assign_to' => 'required',
                                   'due_date' => 'required',
                                   'start_date' => 'required',
                                   'milestone_id' => 'required',
                               ]
            );
        }

        $task    = Task::find($task_id);
        $project = Project::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $task->project_id)->first();
        if($project)
        {
            $post               = $request->all();
            $post['project_id'] = $task->project_id;
            $task->update($post);

            return redirect()->route(
                'projects.show', [$task->project_id]
            )->with('success', __('Task Updated Successfully!'));
        }
        else
        {
            return redirect()->route(
                'projects.show', [$task->project_id]
            )->with('error', __('You can \'t Edit Task!'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($task_id)
    {
        $task    = Task::find($task_id);
        $project = Project::find($task->project_id);
        if($project->created_by == \Auth::user()->creatorId())
        {
            $task->delete();

            return redirect()->route(
                'projects.show', [$task->project_id]
            )->with('success', __('Task successfully deleted'));
        }
        else
        {
            return redirect()->route(
                'projects.show', [$task->project_id]
            )->with('error', __('You can\'t Delete Task.'));
        }
    }
}


// public function taskOrderUpdate(Request $request, $slug, $projectID)
// {
//     if(isset($request->sort))
//     {
//         foreach($request->sort as $index => $taskID)
//         {
//             echo $index . "-" . $taskID;
//             $task        = Task::find($taskID);
//             $task->order = $index;
//             $task->save();
//         }
//     }
//     if($request->new_status != $request->old_status)
//     {
//         $task         = Task::find($request->id);
//         $task->status = $request->new_status;
//         $task->save();

//         if(isset($request->client_id) && !empty($request->client_id))
//         {
//             $client = Client::find($request->client_id);
//             $name   = $client->name . " <b>(" . __('Client') . ")</b>";
//             $id     = 0;
//         }
//         else
//         {
//             $name = \Auth::user()->name;
//             $id   = \Auth::user()->creatorId();
//         }

//         ActivityLog::create(
//             [
//                 'user_id' => $id,
//                 'project_id' => $projectID,
//                 'log_type' => 'Move',
//                 'remark' => $name . " " . __('Move Task') . " <b>" . $task->title . "</b> " . __('from') . " " . ucwords($request->old_status) . " " . __('to') . " " . ucwords($request->new_status),
//                 'remark' => '<b>'. $name . '</b> ' .
//                             __('moved task') .
//                             ' <a href="' . route('tasks.show', $task->id) . '">'. $task->title.'</a>' . __('from') . ' ' . ucwords($request->old_status) . ' ' . __('to') . ' ' . ucwords($request->new_status),
//             ]
//         );

//         return $task->toJson();
//     }
// }

// public function order(Request $request)
// {
//     $post  = $request->all();
//     $task  = Task::find($post['task_id']);
//     $stage = ProjectStage::find($post['stage_id']);

//     if(!empty($stage))
//     {
//         $task->stage = $post['stage_id'];
//         $task->save();
//     }

//     foreach($post['order'] as $key => $item)
//     {
//         $task_order        = Task::find($item);
//         $task_order->order = $key;
//         $task_order->stage = $post['stage_id'];
//         $task_order->save();
//     }
// }
