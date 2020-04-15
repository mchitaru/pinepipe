<?php

namespace App\Http\Controllers;

use App\Task;
use App\Comment;
use App\Http\Requests\TaskCommentRequest;
use Illuminate\Http\Request;
use App\Traits\Taskable;

class TaskCommentsController extends Controller
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
    public function store(TaskCommentRequest $request, Task $task)
    {
        $post = $request->validated();

        $post['created_by'] = \Auth::user()->id;

        $comment = $task->comments()->create($post);

        return redirect()->route('tasks.comment.index', $task->id)->with('success', __('Comment Created.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task, Comment $comment)
    {
        $comment->delete();

        return $this->taskShow($task);
   }
}
