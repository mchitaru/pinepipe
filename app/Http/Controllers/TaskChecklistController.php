<?php

namespace App\Http\Controllers;


use App\Task;
use App\TaskChecklist;
use App\Http\Requests\TaskChecklistRequest;
use Illuminate\Http\Request;
use App\Http\Traits\TaskTraits;

class TaskChecklistController extends Controller
{
    use TaskTraits;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Task $task)
    {
        return $this->taskShow($task->id);
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
        $post['name']         = __('Check Item');
        $post['created_by']   = \Auth::user()->authId();
        
        $checklist            = TaskChecklist::create($post);

        return redirect()->route('tasks.show', $task->id)->with('success', __('Checklist Created.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskChecklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function update(TaskChecklistRequest $request, Task $task, TaskChecklist $checklist)
    {        
        $post = $request->validated();

        if (isset($post['name'])){

            $checklist->name = $post['name'];

        }else{

            $checklist->status = !$checklist->status;

        }

        $checklist->save();

        return app('App\Http\Controllers\TasksController')->show($checklist->task_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskChecklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task, TaskChecklist $checklist)
    {
        $checklist->delete();

        return $this->taskShow($checklist->task_id);
    }
}
