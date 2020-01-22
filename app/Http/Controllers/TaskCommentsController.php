<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskComment;
use App\Http\Requests\TaskCommentRequest;
use Illuminate\Http\Request;
use App\Http\Traits\TaskTraits;

class TaskCommentsController extends Controller
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
    public function store(TaskCommentRequest $request, Task $task)
    {
        $post = $request->validated();

        $post['task_id']    = $task->id;
        $post['created_by'] = \Auth::user()->authId();

        $comment            = TaskComment::create($post);

        return redirect()->route('tasks.comment.index', $task->id)->with('success', __('Comment Created.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task, TaskComment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task, TaskComment $comment)
    {
        $comment->delete();

        return $this->taskShow($comment->task_id);
   }
}
