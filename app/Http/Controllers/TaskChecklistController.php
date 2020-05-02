<?php

namespace App\Http\Controllers;


use App\Task;
use App\Checklist;
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

        $post['title']         = __('Something To Do...');
        
        $subtask = $task->checklist()->create($post);

        return redirect()->route('tasks.show', $task->id)->with('success', __('Subtask Created.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Checklist  $subtask
     * @return \Illuminate\Http\Response
     */
    public function update(TaskChecklistRequest $request, Task $task, Checklist $subtask)
    {        
        $post = $request->validated();

        if (isset($post['title'])){

            $subtask->title = $post['title'];

        }else{

            $subtask->status = !$subtask->status;

        }

        $subtask->save();

        return response('');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Checklist  $subtask
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task, Checklist $subtask)
    {
        $subtask->delete();

        return response('');
    }

    public function order(Request $request, Task $task)
    {
        $post  = $request->all();

        if($post['container_id'] == 'sort')
        {
            foreach($post['order'] as $key => $item)
            {
                $check = Checklist::find($item);
                $check->order = $key;
                $check->save();
            }                
        }
        else{

            $check = Checklist::find($post['check_id']);
            $check->delete();
        }
    }
}
