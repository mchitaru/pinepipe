<?php

namespace App\Http\Controllers;

use App\TaskComment;
use Illuminate\Http\Request;

class TaskCommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $task_id)
    {
        $post               = [];
        $post['task_id']    = $task_id;
        $post['comment']    = $request->comment;
        $post['created_by'] = \Auth::user()->authId();
        $post['user_type']  = \Auth::user()->type;
        $comment            = TaskComment::create($post);

        return redirect()->route('tasks.show', $task_id)->with('success', __('Comment Created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TaskComment  $taskComment
     * @return \Illuminate\Http\Response
     */
    public function show(TaskComment $taskComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TaskComment  $taskComment
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskComment $taskComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskComment  $taskComment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskComment $taskComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskComment  $taskComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $task_id, $comment_id)
    {
        // $comment = TaskComment::find($comment_id);
        // $comment->delete();

        //return redirect()->route('tasks.show', $task_id)->with('success', __('Comment Deleted.'));
        return redirect()->back();
   }
}
