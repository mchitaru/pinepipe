<?php

namespace App\Http\Controllers;


use App\Task;
use App\TaskChecklist;
use App\Http\Requests\TaskChecklistRequest;
use Illuminate\Http\Request;
use App\Traits\Taskable;

class TaskChecklistController extends Controller
{
    use Taskable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Task $task)
    {
        return $this->taskShow($task);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskChecklistRequest $request, Task $task)
    {
        $post = $request->validated();

        $post['task_id']      = $task->id;
        $post['name']         = __('Something To Do...');
        $post['created_by']   = \Auth::user()->id;
        
        $subtask            = TaskChecklist::create($post);

        return redirect()->route('tasks.show', $task->id)->with('success', __('Subtask Created.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskChecklist  $subtask
     * @return \Illuminate\Http\Response
     */
    public function update(TaskChecklistRequest $request, Task $task, TaskChecklist $subtask)
    {        
        $post = $request->validated();

        if (isset($post['name'])){

            $subtask->name = $post['name'];

        }else{

            $subtask->status = !$subtask->status;

        }

        $subtask->save();

        return $this->taskShow($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskChecklist  $subtask
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task, TaskChecklist $subtask)
    {
        $subtask->delete();

        return $this->taskShow($task);
    }

    public function order(Request $request, Task $task)
    {
        $post  = $request->all();

        if($post['container_id'] == 'sort')
        {
            foreach($post['order'] as $key => $item)
            {
                $check = TaskChecklist::find($item);
                $check->order = $key;
                $check->save();
            }                
        }
        else{

            $check = TaskChecklist::find($post['check_id']);
            $check->delete();
        }
    }
}
