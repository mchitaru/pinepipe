<?php

namespace App\Http\Controllers;

use App\TaskChecklist;
use Illuminate\Http\Request;

class TaskChecklistController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $task_id)
    {
        $post['task_id']      = $task_id;
        $post['name']         = __('Check Item');
        $post['created_by']   = \Auth::user()->authId();
        $checklist            = TaskChecklist::create($post);
        // $Checklist->deleteUrl = route(
        //     'tasks.checklist.destroy', [
        //                                 $Checklist->task_id,
        //                                 $Checklist->id,
        //                             ]
        // );
        // $Checklist->updateUrl = route(
        //     'tasks.checklist.update', [
        //                                $Checklist->task_id,
        //                                $Checklist->id,
        //                            ]
        // );

        // return $Checklist->toJson();
        return redirect()->route('tasks.checklist.show', $task_id, $checklist->id)->with('success', __('Checklist Created.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskChecklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskChecklist $checklist)
    {
        // $checkList = TaskChecklist::find($checklist_id);

        // if ($request->has('status')) {
        //     $checkList->status = $request->status;
        // }
        // if ($request->has('name')) {
        //     $checkList->name = $request->name;
        // }

        // $checkList->save();

        // return $checkList->toJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskChecklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskChecklist $checklist)
    {
        // $checklist = TaskChecklist::find($checklist_id);
        // $checklist->delete();

        // return "true";
    }
}
