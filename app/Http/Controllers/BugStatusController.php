<?php

namespace App\Http\Controllers;

use App\BugStatus;
use Illuminate\Http\Request;

class BugStatusController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('manage bug status'))
        {
            $bugStatus = BugStatus::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order')->get();

            return view('bugstatus.index', compact('bugStatus'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }


    }


    public function create()
    {
        if(\Auth::user()->can('create bug status'))
        {
            return view('bugstatus.create');
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }


    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create bug status'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required|max:20',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('bugstatus.index')->with('error', $messages->first());
            }
            $all_status         = BugStatus::where('created_by', \Auth::user()->creatorId())->orderBy('id', 'DESC')->first();
            $status             = new BugStatus();
            $status->title      = $request->title;
            $status->created_by = \Auth::user()->creatorId();
            $status->order      = (!empty($all_status) ? ($all_status->order + 1) : 0);
            $status->save();

            return redirect()->route('bugstatus.index')->with('success', __('Bug status successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }


    }


    public function edit($id)
    {
        if(\Auth::user()->can('edit bug status'))
        {

            $bugStatus = BugStatus::findOrfail($id);
            if($bugStatus->created_by == \Auth::user()->creatorId())
            {
                return view('bugstatus.edit', compact('bugStatus'));
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
        if(\Auth::user()->can('edit bug status'))
        {
            $bugstatus = BugStatus::findOrfail($id);
            if($bugstatus->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'title' => 'required|max:20',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('bugstatus.index')->with('error', $messages->first());
                }

                $bugstatus->title = $request->title;
                $bugstatus->save();

                return redirect()->route('bugstatus.index')->with('success', __('Bug status successfully updated.'));
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
        if(\Auth::user()->can('delete bug status'))
        {

            $bugstatus = BugStatus::findOrfail($id);
            if($bugstatus->created_by == \Auth::user()->creatorId())
            {
                //            $checkStatus = BugStatus::where('id', '=', $bugstatus->id)->get();
                //            dd($checkStatus);
                //            if(empty($checkStatus))
                //            {
                $bugstatus->delete();

                return redirect()->route('bugstatus.index')->with('success', __('Bug status successfully deleted.'));
                //            }
                //            else
                //            {
                //                return redirect()->route('bugstatus.index')->with('error', __('Bug status already assign this stage , so please remove or move task to other project stage.'));
                //            }
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
        if(\Auth::user()->can('move bug status'))
        {
            $post = $request->all();
            foreach($post['order'] as $key => $item)
            {
                $status        = BugStatus::where('id', '=', $item)->first();
                $status->order = $key;
                $status->save();
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }


    }
}
