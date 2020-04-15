<?php

namespace App\Http\Controllers;

use App\Lead;
use App\TaskStage;
use App\Task;
use Auth;
use Illuminate\Http\Request;

class TaskStagesController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage project stage'))
        {
            $taskStages = TaskStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order')->get();

            return view('taskstages.index', compact('taskStages'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create project stage'))
        {
            return view('taskstages.create');
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create project stage'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('taskstages.index')->with('error', $messages->first());
            }
            $all_stage         = TaskStage::where('created_by', \Auth::user()->creatorId())->orderBy('id', 'DESC')->first();
            $stage             = new TaskStage();
            $stage->name       = $request->name;
            $stage->color      = $request->color;
            $stage->created_by = \Auth::user()->creatorId();
            $stage->order      = (!empty($all_stage) ? ($all_stage->order + 1) : 0);

            $stage->save();

            return redirect()->route('taskstages.index')->with('success', __('Project stage successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function edit($id)
    {
        if(\Auth::user()->can('edit project stage'))
        {
            $leadstages = TaskStage::findOrfail($id);
            if($leadstages->created_by == \Auth::user()->creatorId())
            {
                return view('taskstages.edit', compact('leadstages'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function update(Request $request, $id)
    {
        if(\Auth::user()->can('edit project stage'))
        {
            $leadstages = TaskStage::findOrfail($id);
            if($leadstages->created_by == \Auth::user()->creatorId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('taskstages.index')->with('error', $messages->first());
                }

                $leadstages->name  = $request->name;
                $leadstages->color = $request->color;
                $leadstages->save();

                return redirect()->route('taskstages.index')->with('success', __('Project stage successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function destroy($id)
    {
        if(\Auth::user()->can('delete project stage'))
        {
            $taskStages = TaskStage::findOrfail($id);
            if($taskStages->created_by == \Auth::user()->creatorId())
            {
                $checkStage = Task::where('stage', '=', $taskStages->id)->get()->toArray();
                if(empty($checkStage))
                {
                    $taskStages->delete();

                    return redirect()->route('taskstages.index')->with('success', __('Project stage successfully deleted.'));
                }
                else
                {
                    return redirect()->route('taskstages.index')->with('error', __('Project task already assign this stage , so please remove or move task to other project stage.'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function order(Request $request)
    {
        $post = $request->all();
        foreach($post['order'] as $key => $item)
        {
            $stage        = TaskStage::where('id', '=', $item)->first();
            $stage->order = $key;
            $stage->save();
        }
    }
}
