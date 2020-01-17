<?php

namespace App\Http\Controllers;

use App\Label;
use App\Project;
use Illuminate\Http\Request;

class LabelsController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage label'))
        {
            $labels = Label::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('labels.index', compact('labels'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create label'))
        {
            $colors = Label::$colors;

            return view('labels.create')->with('colors', $colors);
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }

    }


    public function store(Request $request)
    {

        if(\Auth::user()->can('create label'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                                   'color' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('users')->with('error', $messages->first());
            }

            $label             = new Label();
            $label->name       = $request->name;
            $label->color      = $request->color;
            $label->created_by = \Auth::user()->creatorId();
            $label->save();

            return redirect()->route('labels.index')->with('success', __('Label successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }

    }


    public function edit($id)
    {
        if(\Auth::user()->can('edit label'))
        {
            $label = Label::findOrfail($id);
            if($label->created_by == \Auth::user()->creatorId())
            {
                $colors = Label::$colors;

                return view('labels.edit', compact('label', 'colors'));
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
        if(\Auth::user()->can('edit label'))
        {
            $label = Label::findOrfail($id);
            if($label->created_by == \Auth::user()->creatorId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                       'color' => 'required',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('users')->with('error', $messages->first());
                }

                $label->name  = $request->name;
                $label->color = $request->color;
                $label->save();

                return redirect()->route('labels.index')->with('success', __('Label successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied');
        }

    }

    public function destroy($id)
    {
        if(\Auth::user()->can('delete label'))
        {
            $labels = Label::findOrfail($id);
            if($labels->created_by == \Auth::user()->creatorId())
            {
                $labels->delete();
                Project::where('label', '=', $labels->id)->update(array('label' => 0));

                return redirect()->route('labels.index')->with('success', __('Lead stage successfully deleted.'));
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

}
