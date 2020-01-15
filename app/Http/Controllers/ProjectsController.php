<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\CheckList;
use App\Client;
use App\ClientPermission;
use App\Comment;
use App\Invoice;
use App\Labels;
use App\Leads;
use App\Milestone;
use App\Plan;
use App\Project;
use App\ProjectFile;
use App\Projects;
use App\Projectstages;
use App\SubTask;
use App\Task;
use App\TaskFile;
use App\Timesheet;
use App\User;
use App\Userprojects;
use App\Utility;
use Auth;
use File;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage project'))
        {
            $user = \Auth::user();
            if($user->type == 'client')
            {
                $projects = Projects::where('client', '=', $user->id)->get();
            }
            else
            {
                $projects = $user->projects;

            }

            $project_status = Projects::$project_status;

            return view('projects.index', compact('projects', 'project_status'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create project'))
        {
            $users   = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->get()->pluck('name', 'id');
            $clients = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'client')->get()->pluck('name', 'id');
            $labels  = Labels::where('created_by', '=', \Auth::user()->creatorId())->get();
            $leads   = Leads::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('projects.create', compact('clients', 'labels', 'users', 'leads'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function store(Request $request)
    {

        if(\Auth::user()->can('create project'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                                   'price' => 'required',
                                   'start_date' => 'required',
                                   'due_date' => 'required',
                                   'label' => 'required',
                                   'user' => 'required',
                                   'lead' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('projects.index')->with('error', $messages->first());
            }

            $objUser      = \Auth::user();
            $total_client = $objUser->countProject();
            $plan         = Plan::find($objUser->plan);

            if($total_client < $plan->max_clients || $plan->max_clients == -1)
            {
                $project              = new Projects();
                $project->name        = $request->name;
                $project->price       = $request->price;
                $project->start_date  = $request->start_date;
                $project->due_date    = $request->due_date;
                $project->client      = $request->client;
                $project->label       = $request->label;
                $project->description = $request->description;
                $project->lead        = $request->lead;
                $project->created_by  = \Auth::user()->creatorId();
                $project->save();

                $userproject             = new Userprojects();
                $userproject->user_id    = \Auth::user()->creatorId();
                $userproject->project_id = $project->id;
                $userproject->save();

                foreach($request->user as $key => $user)
                {
                    $userproject             = new Userprojects();
                    $userproject->user_id    = $user;
                    $userproject->project_id = $project->id;
                    $userproject->save();
                }

                $permissions = Projects::$permission;
                ClientPermission::create(
                    [
                        'client_id' => $project->client,
                        'project_id' => $project->id,
                        'permissions' => implode(',', $permissions),
                    ]
                );

                return redirect()->route('projects.index')->with('success', __('Project successfully created.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Your project limit is over, Please upgrade plan.'));
            }

        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function edit($id)
    {

        if(\Auth::user()->can('edit project'))
        {
            $clients = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'client')->get()->pluck('name', 'id');
            $labels  = Labels::where('created_by', '=', \Auth::user()->creatorId())->get();
            $leads   = Leads::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $project = Projects::findOrfail($id);
            if($project->created_by == \Auth::user()->creatorId())
            {
                return view('projects.edit', compact('project', 'clients', 'labels', 'leads'));
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

    public function show($project_id)
    {
        if(\Auth::user()->can('show project'))
        {
            $project        = Projects::where('id', $project_id)->first();
            $project_user   = Userprojects::where('project_id', $project_id)->get();
            $project_status_list = Projects::$project_status;
            $stages  = Projectstages::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order', 'ASC')->get();
            $project_files = ProjectFile::where('project_id', $project_id)->get();
            
            $timeSheets = '';
            if(\Auth::user()->can('manage timesheet'))
            {
                if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
                {
                    $timeSheets = Timesheet::where('project_id', '=', $project_id)->get();
                }
                else
                {
                    $timeSheets = Timesheet::where('user_id', '=', \Auth::user()->id)->where('project_id', '=', $project_id)->get();
                }
            }

            $project_status = __('Unknown');
            foreach($project_status_list as $key => $status)
            {
                if($key== $project->status)
                {
                    $project_status = $status;
                }
            }

            return view('projects.show', compact('project', 'project_user', 'project_status', 'stages', 'project_files', 'timeSheets'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(Request $request, $id)
    {
        if(\Auth::user()->can('edit project'))
        {
            $project = Projects::findOrfail($id);
            if($project->created_by == \Auth::user()->creatorId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                       'price' => 'required',
                                       'date' => 'required',
                                       'label' => 'required',
                                       'lead' => 'required',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('users')->with('error', $messages->first());
                }

                $project->name        = $request->name;
                $project->price       = $request->price;
                $project->due_date    = $request->date;
                $project->client      = $request->client;
                $project->label       = $request->label;
                $project->lead        = $request->lead;
                $project->description = $request->description;
                $project->save();

                ClientPermission::where('client_id','=',$project->client)->where('project_id','=', $project->id)->delete();
                $permissions = Projects::$permission;
                ClientPermission::create(
                    [
                        'client_id' => $project->client,
                        'project_id' => $project->id,
                        'permissions' => implode(',', $permissions),
                    ]
                );

                return redirect()->route('projects.index')->with('success', __('Project successfully updated.'));
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

    public function updateStatus(Request $request, $id)
    {
        if(\Auth::user()->can('edit project'))
        {
            $project = Projects::findOrfail($id);
            if($project->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'status' => 'required',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', 'Project Status is required.');
                }

                $project->status = $request->status;
                $project->save();

                return redirect()->route('projects.show', compact('id'))->with('success', __('Status Updated!'));
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
        if(\Auth::user()->can('delete project'))
        {
            $project = Projects::findOrfail($id);
            if($project->created_by == \Auth::user()->creatorId())
            {
                //                $project->delete();
                Milestone::where('project_id', $id)->delete();
                Userprojects::where('project_id', $id)->delete();
                ActivityLog::where('project_id', $id)->delete();

                $projectFile = ProjectFile::select('file_path')->where('project_id', $id)->get()->map(
                    function ($file){
                        $dir        = storage_path('app/public/project_files/');
                        $file->file = $dir . $file->file;

                        return $file;
                    }
                );
                if(!empty($projectFile))
                {
                    foreach($projectFile->pluck('file_path') as $file)
                    {
                        File::delete($file);
                    }
                }
                ProjectFile::where('project_id', $id)->delete();

                Invoice::where('project_id', $id)->update(array('project_id' => 0));
                $tasks     = Task::select('id')->where('project_id', $id)->get()->pluck('id');
                $comment   = Comment::whereIn('task_id', $tasks)->delete();
                $checklist = CheckList::whereIn('task_id', $tasks)->delete();

                $taskFile = TaskFile::select('file')->whereIn('task_id', $tasks)->get()->map(
                    function ($file){
                        $dir        = storage_path('app/public/tasks/');
                        $file->file = $dir . $file->file;

                        return $file;
                    }
                );
                if(!empty($taskFile))
                {
                    foreach($taskFile->pluck('file') as $file)
                    {
                        File::delete($file);
                    }
                }
                TaskFile::whereIn('task_id', $tasks)->delete();

                Task::where('project_id', $id)->delete();

                return redirect()->route('projects.index')->with('success', __('Project successfully deleted.'));
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

    public function userInvite($project_id)
    {
        $assign_user = Userprojects::select('user_id')->where('project_id', $project_id)->get()->pluck('user_id');
        $employee    = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->whereNotIn('id', $assign_user)->get()->pluck('name', 'id');

        return view('projects.invite', compact('employee', 'project_id'));
    }

    public function Invite(Request $request, $project_id)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'user' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('projects.show', $project_id)->with('error', $messages->first());
        }

        foreach($request->user as $key => $user)
        {
            $userproject             = new Userprojects();
            $userproject->user_id    = $user;
            $userproject->project_id = $project_id;
            $userproject->save();
        }


        return redirect()->route('projects.show', $project_id)->with('success', __('User successfully Invited.'));
    }


    public function milestone($project_id)
    {
        $project = Projects::find($project_id);
        $status  = Projects::$status;

        return view('projects.milestone', compact('project', 'status'));
    }

    public function milestoneStore(Request $request, $project_id)
    {
        $project = Projects::find($project_id);
        $request->validate(
            [
                'title' => 'required',
                'status' => 'required',
                'cost' => 'required',
            ]
        );

        $milestone              = new Milestone();
        $milestone->project_id  = $project->id;
        $milestone->title       = $request->title;
        $milestone->status      = $request->status;
        $milestone->cost        = $request->cost;
        $milestone->description = $request->description;
        $milestone->save();

        ActivityLog::create(
            [
                'user_id' => \Auth::user()->creatorId(),
                'project_id' => $project->id,
                'log_type' => 'Create Milestone',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('created milestone') .
                            ' <a href="' . route('project.milestone.show', $milestone->id) . '">'. $milestone->title.'</a>',
            ]
        );

        return redirect()->back()->with('success', __('Milestone successfully created.'));
    }

    public function milestoneEdit($id)
    {
        $milestone = Milestone::find($id);
        $status    = Projects::$status;

        return view('projects.milestoneEdit', compact('milestone', 'status'));
    }

    public function milestoneUpdate($id, Request $request)
    {
        $request->validate(
            [
                'title' => 'required',
                'status' => 'required',
                'cost' => 'required',
            ]
        );

        $milestone              = Milestone::find($id);
        $milestone->title       = $request->title;
        $milestone->status      = $request->status;
        $milestone->cost        = $request->cost;
        $milestone->description = $request->description;
        $milestone->save();

        return redirect()->back()->with('success', __('Milestone updated successfully.'));
    }

    public function milestoneDestroy($id)
    {
        $milestone = Milestone::find($id);
        $milestone->delete();

        return redirect()->back()->with('success', __('Milestone successfully deleted.'));
    }

    public function milestoneShow($id)
    {
        $milestone = Milestone::find($id);

        return view('projects.milestoneShow', compact('milestone'));
    }

    public function fileUpload($id, Request $request)
    {
        $project = Projects::find($id);
        $request->validate(['file' => 'required|mimes:png,jpeg,jpg,pdf,doc,txt|max:2048']);
        $file_name = $request->file->getClientOriginalName();
        $file_path = $project->id . "_" . md5(time()) . "_" . $request->file->getClientOriginalName();
        $request->file->storeAs('public/project_files', $file_path);

        $file                 = ProjectFile::create(
            [
                'project_id' => $project->id,
                'file_name' => $file_name,
                'file_path' => $file_path,
            ]
        );
        $return               = [];
        $return['is_success'] = true;
        $return['download']   = route(
            'projects.file.download', [
                                        $project->id,
                                        $file->id,
                                    ]
        );
        $return['delete']     = route(
            'projects.file.delete', [
                                      $project->id,
                                      $file->id,
                                  ]
        );

        ActivityLog::create(
            [
                'user_id' => \Auth::user()->creatorId(),
                'project_id' => $project->id,
                'log_type' => 'Upload File',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('uploaded file') .
                            ' <a href="' . route('projects.file.download', [$project->id, $file->id]) . '">'. $file_name.'</a>',
            ]
        );

        return response()->json($return);
    }

    public function fileDownload($id, $file_id)
    {

        $project = Projects::find($id);
        $file    = ProjectFile::find($file_id);
        if($file)
        {
            $file_path = storage_path('app/public/project_files/' . $file->file_path);
            $filename  = $file->file_name;

            return \Response::download(
                $file_path, $filename, [
                              'Content-Length: ' . filesize($file_path),
                          ]
            );
        }
        else
        {
            return redirect()->back()->with('error', __('File is not exist.'));
        }
    }

    public function fileDelete($id, $file_id)
    {
        $project = Projects::find($id);

        $file = ProjectFile::find($file_id);
        if($file)
        {
            $path = storage_path('app/public/project_files/' . $file->file_path);
            if(file_exists($path))
            {
                \File::delete($path);
            }
            $file->delete();

            return response()->json(['is_success' => true], 200);
        }
        else
        {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('File is not exist.'),
                ], 200
            );
        }
    }

    public function taskBoard($project_id)
    {
        $stages  = Projectstages::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order', 'ASC')->get();
        $project = Projects::where('id', $project_id)->first();
        $perArr  = (!empty($permissions) ? explode(',', $permissions->permissions) : []);

        return view('projects.taskboard', compact('project', 'stages', 'perArr'));
    }

    public function taskCreate($project_id)
    {
        $project    = Projects::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $project_id)->first();
        $projects   = Projects::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $milestones = Milestone::where('project_id', '=', $project->id)->get()->pluck('title', 'id');
        $priority   = Projects::$priority;
        $usersArr   = Userprojects::where('project_id', '=', $project_id)->get();
        $users      = array();
        foreach($usersArr as $user)
        {
            $users[$user->project_assign_user->id] = ($user->project_assign_user->name . ' - ' . $user->project_assign_user->email);
        }

        return view('projects.taskCreate', compact('project', 'projects', 'priority', 'users', 'milestones'));
        // return view('partials.taskadd');
    }

    public function taskStore(Request $request, $projec_id)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'priority' => 'required',
                                   'assign_to' => 'required',
                                   'due_date' => 'required',
                                   'start_date' => 'required',
                               ]
            );
        }
        else
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'priority' => 'required',
                                   'due_date' => 'required',
                                   'start_date' => 'required',
                               ]
            );
        }
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('leads.index')->with('error', $messages->first());
        }
        $project = Projects::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $projec_id)->first();
        if($project)
        {
            $post = $request->all();
            if(\Auth::user()->type != 'company')
            {
                $post['assign_to'] = \Auth::user()->id;
            }
            $post['project_id'] = $projec_id;
            $post['stage']      = Projectstages::where('created_by', '=', \Auth::user()->creatorId())->first()->id;
            $task               = Task::create($post);
            ActivityLog::create(
                [
                    'user_id' => \Auth::user()->creatorId(),
                    'project_id' => $projec_id,
                    'log_type' => 'Create Task',
                    'remark' => \Auth::user()->name . ' ' . __('Create new Task') . " <b>" . $task->title . "</b>",
                    'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                                __('create task') .
                                ' <a href="' . route('task.show', $task->id) . '">'. $task->title.'</a>',
                ]
            );

            return redirect()->route(
                'task.show', [$task->id]
            )->with('success', __('Task successfully created'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function taskEdit($task_id)
    {
        $task       = Task::find($task_id);
        $project    = Projects::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $task->project_id)->first();
        $projects   = Projects::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $usersArr   = Userprojects::where('project_id', '=', $task->project_id)->get();
        $priority   = Projects::$priority;
        $milestones = Milestone::where('project_id', '=', $project->id)->get()->pluck('title', 'id');
        $users      = array();
        foreach($usersArr as $user)
        {
            $users[$user->project_assign_user->id] = ($user->project_assign_user->name . ' - ' . $user->project_assign_user->email);
        }

        return view('projects.taskEdit', compact('project', 'projects', 'users', 'task', 'priority', 'milestones'));
    }


    public function taskUpdate(Request $request, $task_id)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'priority' => 'required',
                                   'assign_to' => 'required',
                                   'due_date' => 'required',
                                   'start_date' => 'required',
                                   'milestone_id' => 'required',
                               ]
            );
        }

        $task    = Task::find($task_id);
        $project = Projects::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $task->project_id)->first();
        if($project)
        {
            $post               = $request->all();
            $post['project_id'] = $task->project_id;
            $task->update($post);

            return redirect()->route(
                'projects.show', [$task->project_id]
            )->with('success', __('Task Updated Successfully!'));
        }
        else
        {
            return redirect()->route(
                'projects.show', [$task->project_id]
            )->with('error', __('You can \'t Edit Task!'));
        }
    }

    public function taskDestroy($task_id)
    {
        $task    = Task::find($task_id);
        $project = Projects::find($task->project_id);
        if($project->created_by == \Auth::user()->creatorId())
        {
            $task->delete();

            return redirect()->route(
                'projects.show', [$task->project_id]
            )->with('success', __('Task successfully deleted'));
        }
        else
        {
            return redirect()->route(
                'projects.show', [$task->project_id]
            )->with('error', __('You can\'t Delete Task.'));
        }
    }

    public function taskOrderUpdate(Request $request, $slug, $projectID)
    {
        if(isset($request->sort))
        {
            foreach($request->sort as $index => $taskID)
            {
                echo $index . "-" . $taskID;
                $task        = Task::find($taskID);
                $task->order = $index;
                $task->save();
            }
        }
        if($request->new_status != $request->old_status)
        {
            $task         = Task::find($request->id);
            $task->status = $request->new_status;
            $task->save();

            if(isset($request->client_id) && !empty($request->client_id))
            {
                $client = Client::find($request->client_id);
                $name   = $client->name . " <b>(" . __('Client') . ")</b>";
                $id     = 0;
            }
            else
            {
                $name = \Auth::user()->name;
                $id   = \Auth::user()->creatorId();
            }

            ActivityLog::create(
                [
                    'user_id' => $id,
                    'project_id' => $projectID,
                    'log_type' => 'Move',
                    'remark' => $name . " " . __('Move Task') . " <b>" . $task->title . "</b> " . __('from') . " " . ucwords($request->old_status) . " " . __('to') . " " . ucwords($request->new_status),
                    'remark' => '<b>'. $name . '</b> ' .
                                __('moved task') .
                                ' <a href="' . route('task.show', $task->id) . '">'. $task->title.'</a>' . __('from') . ' ' . ucwords($request->old_status) . ' ' . __('to') . ' ' . ucwords($request->new_status),
                ]
            );

            return $task->toJson();
        }
    }

    public function order(Request $request)
    {
        $post  = $request->all();
        $task  = Task::find($post['task_id']);
        $stage = Projectstages::find($post['stage_id']);

        if(!empty($stage))
        {
            $task->stage = $post['stage_id'];
            $task->save();
        }

        foreach($post['order'] as $key => $item)
        {
            $task_order        = Task::find($item);
            $task_order->order = $key;
            $task_order->stage = $post['stage_id'];
            $task_order->save();
        }
    }

    public function taskShow($task_id, $client_id = '')
    {
        $task    = Task::find($task_id);
        $project = Projects::find($task->project_id);

        $permissions = $project->client_project_permission();
        $perArr      = (!empty($permissions) ? explode(',', $permissions->permissions) : []);

        return view('projects.taskShow', compact('task', 'perArr', 'project'));
    }

    public function commentStore(Request $request, $project_id, $task_id)
    {

        $post               = [];
        $post['task_id']    = $task_id;
        $post['comment']    = $request->comment;
        $post['created_by'] = \Auth::user()->authId();
        $post['user_type']  = \Auth::user()->type;
        $comment            = Comment::create($post);

        $comment->deleteUrl = route('comment.destroy', [$comment->id]);

        return $comment->toJson();
    }

    public function commentDestroy($comment_id)
    {
        $comment = Comment::find($comment_id);
        $comment->delete();

        return "true";
    }


    public function taskFileUpload($task_id, Request $request)
    {
        $task    = Task::find($task_id);
        $project = Projects::find($task->project_id);

        $request->validate(
            ['file' => 'required|mimes:jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,zip,rar|max:2048']
        );
        $fileName = $task_id . time() . "_" . $request->file->getClientOriginalName();

        $request->file->storeAs('public/tasks', $fileName);
        $post['task_id']    = $task_id;
        $post['file']       = $fileName;
        $post['name']       = $request->file->getClientOriginalName();
        $post['extension']  = "." . $request->file->getClientOriginalExtension();
        $post['file_size']  = round(($request->file->getSize() / 1024) / 1024, 2) . ' MB';
        $post['created_by'] = \Auth::user()->authId();
        $post['user_type']  = \Auth::user()->type;

        $file            = TaskFile::create($post);
        $file->deleteUrl = route('task.file.delete', [$task_id, $file->id]);

        $return               = [];
        $return['is_success'] = true;
        $return['download']   = route(
            'task.file.download', [
                                        $task_id,
                                        $file->id,
                                    ]
        );
        $return['delete']     = route(
            'task.file.delete', [
                                      $task_id,
                                      $file->id,
                                  ]
        );

        ActivityLog::create(
            [
                'user_id' => \Auth::user()->creatorId(),
                'project_id' => $project->id,
                'log_type' => 'Upload File',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('uploaded file') .
                            ' <a href="' . route('task.file.download', [$task_id, $file->id]) . '">'. $request->file->getClientOriginalName().'</a>',
            ]
        );

        return response()->json($return);
    }

    public function taskFileDownload($id, $file_id)
    {

        $file    = TaskFile::find($file_id);
        if($file)
        {
            $file_path = storage_path('app/public/tasks/' . $file->file);
            $filename  = $file->name;

            return \Response::download(
                $file_path, $filename, [
                              'Content-Length: ' . filesize($file_path),
                          ]
            );
        }
        else
        {
            return redirect()->back()->with('error', __('File does not exist.'));
        }
    }

    public function taskFileDelete($id, $file_id)
    {
        $file = TaskFile::find($file_id);
        if($file)
        {
            $path = storage_path('app/public/tasks/' . $file->file);
            if(file_exists($path))
            {
                \File::delete($path);
            }
            $file->delete();

            return response()->json(['is_success' => true], 200);
        }
        else
        {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('File is not exist.'),
                ], 200
            );
        }
    }

    public function checkListStore(Request $request, $task_id)
    {
        $post['task_id']      = $task_id;
        $post['name']         = __('Check Item');
        $post['created_by']   = \Auth::user()->authId();
        $CheckList            = CheckList::create($post);
        $CheckList->deleteUrl = route(
            'task.checklist.destroy', [
                                        $CheckList->task_id,
                                        $CheckList->id,
                                    ]
        );
        $CheckList->updateUrl = route(
            'task.checklist.update', [
                                       $CheckList->task_id,
                                       $CheckList->id,
                                   ]
        );

        return $CheckList->toJson();
    }

    public function checklistDestroy(Request $request, $task_id, $checklist_id)
    {
        $checklist = CheckList::find($checklist_id);
        $checklist->delete();

        return "true";
    }

    public function checklistUpdate(Request $request, $task_id, $checklist_id)
    {
        $checkList = CheckList::find($checklist_id);
        
        if ($request->has('status')) {
            $checkList->status = $request->status;
        }        
        if ($request->has('name')) {
            $checkList->name = $request->name;
        }        

        $checkList->save();

        return $checkList->toJson();
    }

    public function clientPermission($project_id, $client_id)
    {
        $client   = User::find($client_id);
        $project  = Projects::find($project_id);
        $selected = $client->clientPermission($project->id);
        if($selected)
        {
            $selected = explode(',', $selected->permissions);
        }
        else
        {
            $selected = [];
        }
        $permissions = Projects::$permission;

        return view('clients.create', compact('permissions', 'project_id', 'client_id', 'selected'));
    }

    public function storeClientPermission(request $request, $project_id, $client_id)
    {
        $this->validate(
            $request, [
                        'permissions' => 'required',
                    ]
        );

        $project = Projects::find($project_id);
        if($project->created_by == \Auth::user()->creatorId())
        {
            $client      = User::find($client_id);
            $permissions = $client->clientPermission($project->id);
            if($permissions)
            {
                $permissions->permissions = implode(',', $request->permissions);
                $permissions->save();
            }
            else
            {
                ClientPermission::create(
                    [
                        'client_id' => $client->id,
                        'project_id' => $project->id,
                        'permissions' => implode(',', $request->permissions),
                    ]
                );
            }

            return redirect()->back()->with('success', __('Permissions successfully updated.'))->with('status', 'clients');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'))->with('status', 'clients');
        }

    }

    public function getSearchJson($search)
    {

        if(\Auth::user()->type == 'client')
        {
            $objProject = Projects::select(
                [
                    'projects.id',
                    'projects.name',
                ]
            )->where('projects.client', '=', Auth::user()->id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('projects.name', 'LIKE', $search . "%")->get();
            $arrProject = [];
            foreach($objProject as $project)
            {
                $arrProject[] = [
                    'text' => $project->name,
                    'link' => route('projects.show', [$project->id]),
                ];
            }

            $objTask = Task::select(
                [
                    'tasks.project_id',
                    'tasks.title',
                ]
            )->join('projects', 'tasks.project_id', '=', 'projects.id')->where('projects.client', '=', Auth::user()->id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('tasks.title', 'LIKE', $search . "%")->get();
            $arrTask = [];
            foreach($objTask as $task)
            {
                $arrTask[] = [
                    'text' => $task->title,
                    'link' => route('project.taskboard', [$task->project_id]),
                ];
            }
        }
        else
        {
            $objProject = Projects::select(
                [
                    'projects.id',
                    'projects.name',
                ]
            )->join('userprojects', 'userprojects.project_id', '=', 'projects.id')->where('userprojects.user_id', '=', Auth::user()->id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('projects.name', 'LIKE', $search . "%")->get();
            $arrProject = [];
            foreach($objProject as $project)
            {
                $arrProject[] = [
                    'text' => $project->name,
                    'link' => route('projects.show', [$project->id]),
                ];
            }

            $objTask = Task::select(
                [
                    'tasks.project_id',
                    'tasks.title',
                ]
            )->join('projects', 'tasks.project_id', '=', 'projects.id')->join('userprojects', 'userprojects.project_id', '=', 'projects.id')->where('userprojects.user_id', '=', Auth::user()->id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('tasks.title', 'LIKE', $search . "%")->get();
            $arrTask = [];
            foreach($objTask as $task)
            {
                $arrTask[] = [
                    'text' => $task->title,
                    'link' => route('project.taskboard', [$task->project_id]),
                ];
            }
        }

        return json_encode(
            [
                'Projects' => $arrProject,
                'Tasks' => $arrTask,
            ]
        );
    }

    public function timeSheetCreate($project_id)
    {
        if(\Auth::user()->can('create timesheet'))
        {

            $tasks = Task::where('project_id', '=', $project_id)->get()->pluck('title', 'id');

            return view('projects.timesheetCreate', compact('tasks'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }


    }

    public function timeSheetStore(Request $request, $project_id)
    {
        if(\Auth::user()->can('create timesheet'))
        {
            $timeSheet             = new Timesheet();
            $timeSheet->project_id = $project_id;
            $timeSheet->task_id    = $request->task_id;
            $timeSheet->date       = $request->date;
            $timeSheet->hours      = $request->hours;
            $timeSheet->remark     = $request->remark;
            $timeSheet->user_id    = \Auth::user()->id;
            $timeSheet->save();

            return redirect()->route('task.timesheetRecord', $project_id)->with('success', __('Task timesheet successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }


    }

    public function timeSheetEdit($project_id, $timeSheet_id)
    {
        if(\Auth::user()->can('edit timesheet'))
        {

            $timeSheet = Timesheet::find($timeSheet_id);
            $tasks     = Task::where('project_id', '=', $project_id)->get()->pluck('title', 'id');

            return view('projects.timesheetEdit', compact('tasks', 'timeSheet', 'project_id'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }

    }

    public function timeSheetUpdate(Request $request, $project_id, $timeSheet_id)
    {
        if(\Auth::user()->can('edit timesheet'))
        {

            $timeSheet          = Timesheet::find($timeSheet_id);
            $timeSheet->task_id = $request->task_id;
            $timeSheet->date    = $request->date;
            $timeSheet->hours   = $request->hours;
            $timeSheet->remark  = $request->remark;
            $timeSheet->save();

            return redirect()->route('task.timesheetRecord', $project_id)->with('success', __('Task timesheet successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }

    }

    public function timeSheetDestroy($project_id, $timeSheet_id)
    {
        if(\Auth::user()->can('delete timesheet'))
        {
            $timeSheet = Timesheet::find($timeSheet_id);
            $timeSheet->delete();

            return redirect()->route('task.timesheetRecord', $project_id)->with('success', __('Task timesheet successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }


    }

}
