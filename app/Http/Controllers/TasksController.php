<?php

namespace App\Http\Controllers;

use App\User;
use App\Task;
use App\Project;
use App\Milestone;
use App\UserProject;
use App\ActivityLog;
use App\Http\Requests\TaskDestroyRequest;
use App\ProjectStage;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Traits\TaskTraits;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TasksController extends ProjectsSectionController
{
    use TaskTraits;

    public function board()
    {
        if(\Auth::user()->can('show project'))
        {
            $stages  = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order', 'ASC')->get();
            $project_id = null;

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
    public function create()
    {
        $projects   = Project::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id')->prepend('--', '');
        $priority   = Project::$priority;
        $usersArr   = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->get();
        $users      = array();

        $users[''] = '--';
        foreach($usersArr as $user)
        {
            $users[$user->id] = ($user->name . ' - ' . $user->email);
        }

        $task = '';
        $project = '';
        $milestones = '';
        $is_create = true;

        return view('tasks.create', compact('task', 'project', 'projects', 'users', 'priority', 'milestones', 'is_create'));
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

        $post['stage_id']   = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->first()->id;
        $task               = Task::make($post);
        $task->created_by  = \Auth::user()->creatorId();
        $task->save();

        $users = $post['user_id'];
        if(\Auth::user()->type != 'company'){        

            $users->prepend(\Auth::user()->id);
        }

        $task->users()->sync($users);

        ActivityLog::createTask($task);

        $request->session()->flash('success', __('Task successfully created.'));

        $url = redirect()->back()->getTargetUrl();
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
        $projects   = Project::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id')->prepend('--', '0');
        $users      = array();
        
        if(!empty($project)){
            $usersArr   = UserProject::where('project_id', '=', $task->project_id)->get();
            foreach($usersArr as $user)
            {
                $users[$user->project_assign_user->id] = ($user->project_assign_user->name . ' - ' . $user->project_assign_user->email);
            }
        }else{
            $usersArr   = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->get();
            foreach($usersArr as $user)
            {
                $users[$user->id] = ($user->name . ' - ' . $user->email);
            }
        }
        $priority   = Project::$priority;
        $milestones = Milestone::where('project_id', '=', $task->project_id)->get()->pluck('title', 'id');
        $is_create = false;

        return view('tasks.create', compact('task', 'project', 'projects', 'users', 'priority', 'milestones', 'is_create'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskStoreRequest $request, Task $task)
    {
        $post = $request->validated();

        if(isset($post['status']) && $post['status'] == 'done')
        {
            $stage = ProjectStage::all()->last();
            $post['stage_id'] = $stage->id;
        }

        $task->update($post);

        $users = $post['user_id'];
        if(\Auth::user()->type != 'company'){        

            $users->prepend(\Auth::user()->id);
        }

        $task->users()->sync($users);

        $request->session()->flash('success', __('Task successfully updated.'));

        $url = redirect()->back()->getTargetUrl();
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

        $task->delete();

        return redirect()->back()->with('success', __('Task successfully deleted'));
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
