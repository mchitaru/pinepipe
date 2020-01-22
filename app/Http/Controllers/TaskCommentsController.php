<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskComment;
use App\Http\Requests\TaskCommentStoreRequest;
use Illuminate\Http\Request;

class TaskCommentsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskCommentStoreRequest $request, Task $task)
    {
        $post = $request->validated();

        $post['task_id']    = $task->id;
        $post['created_by'] = \Auth::user()->authId();

        $comment            = TaskComment::create($post);

        return redirect()->route('tasks.show', $task->id)->with('success', __('Comment Created.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskComment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TaskComment $comment)
    {
        $comment->delete();

        return app('App\Http\Controllers\TasksController')->show($comment->task_id);
   }
}
