<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\User;
use App\UserProject;

class ProjectInviteController extends Controller
{
    public function create(Project $project)
    {
        $assign_user = UserProject::select('user_id')->where('project_id', $project->id)->get()->pluck('user_id');
        $user    = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->whereNotIn('id', $assign_user)->get()->pluck('name', 'id');

        return view('projects.invite', compact('user', 'project'));
    }

    public function store(Request $request, Project $project)
    {
        // $validator = \Validator::make(
        //     $request->all(), [
        //                        'user' => 'required',
        //                    ]
        // );
        // if($validator->fails())
        // {
        //     $messages = $validator->getMessageBag();

        //     return redirect()->route('projects.show', $project_id)->with('error', $messages->first());
        // }

        // foreach($request->user as $key => $user)
        // {
        //     $userproject             = new UserProject();
        //     $userproject->user_id    = $user;
        //     $userproject->project_id = $project_id;
        //     $userproject->save();
        // }


        return redirect()->route('projects.show', $project->id)->with('success', __('User successfully Invited.'));
    }
}
