<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Checklist;
use App\Client;
use App\Comment;
use App\Invoice;
use App\Lead;
use App\Milestone;
use App\SubscriptionPlan;
use App\Project;
use App\ProjectFile;
use App\SubTask;
use App\Task;
use App\Timesheet;
use App\Expense;
use App\User;
use App\UserProject;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Requests\ProjectDestroyRequest;
use App\Utility;
use Auth;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class ProjectsController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('viewAny', 'App\Project');

        $user = \Auth::user();

        if (!$request->ajax())
        {
            return view('projects.page');
        }

        clock()->startEvent('ProjectsController.index', "Load projects");

        if($request['tag']){
            $status = array(array_search($request['tag'], Project::$status));
        }else{
            $status = array(array_search('active', Project::$status));
        }

        $projects = $user->projectsByUserType()
                        ->with(['tasks', 'users', 'client'])
                        ->whereIn('archived', $status)
                        ->where(function ($query) use ($request) {
                            $query->where('name','like','%'.$request['filter'].'%')
                            ->orWhereHas('client', function ($query) use($request) {

                                $query->where('name','like','%'.$request['filter'].'%');
                            });
                        })
                        ->orderBy($request['sort']?$request['sort']:'name', $request['dir']?$request['dir']:'asc')
                        ->paginate(25, ['*'], 'project-page');

        clock()->endEvent('ProjectsController.index');

        return view('projects.index', ['projects' => $projects])->render();
    }

    public function create(Request $request)
    {
        Gate::authorize('create', 'App\Project');

        $client_id = $request['client_id'];
        $lead_id = $request['lead_id'];

        $start_date = $request->start_date;
        $due_date = $request->due_date;

        $users   = User::where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id')
                        ->prepend(__('(myself)'), \Auth::user()->id);
        $user_id = \Auth::user()->id;

        $clients = \Auth::user()->companyClients()
                            ->get()
                            ->pluck('name', 'id');

        if($lead_id){

            $lead = Lead::find($lead_id);

            if($lead){

                $client_id = $lead->client->id;
            }
        }

        if($client_id)
        {
            if(is_numeric($client_id)) {

                $leads   = Lead::where('client_id', '=', $client_id)
                                ->get()
                                ->pluck('name', 'id');
            }else{

                //new client
                $leads = [];
                $clients[$client_id] = json_decode('"\u271A '.$client_id.'"');
            }
        }else
        {
                $leads   = \Auth::user()->companyLeads()
                                        ->get()
                                        ->pluck('name', 'id');
        }

        return view('projects.create', compact('clients', 'users', 'user_id', 'client_id', 'start_date', 'due_date', 'leads', 'lead_id'));
    }


    public function store(ProjectStoreRequest $request)
    {
        Gate::authorize('create', 'App\Project');

        $post = $request->validated();

        if(\Auth::user()->checkProjectLimit())
        {
            if($project = Project::createProject($post))
            {
                $users = [];

                if(!empty($post['users'])){

                    foreach($post['users'] as $user){
        
                        if($user != \Auth::user()->id){
                            
                            $users[] = $user;
                        }
                    }
                }

                $project->notifyAssignedUsers($users);

                $request->session()->flash('success', __('Project successfully created.'));
    
                $url = redirect()->route('projects.show', $project->id)->getTargetUrl();
                return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
            }
            else
            {
                $request->session()->flash('error', __('Your have reached you client limit. Please upgrade your subscription to add more clients!'));
            }
    
            $url = redirect()->route('subscription')->getTargetUrl();
            return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);                
        }
        else
        {
            $request->session()->flash('error', __('Your have reached your project limit. Please upgrade your subscription to add more projects!'));
        }

        $url = redirect()->route('subscription')->getTargetUrl();
        return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
    }


    public function edit(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $start_date = $request->start_date?$request->start_date:$project->start_date;
        $due_date = $request->due_date?$request->due_date:$project->due_date;

        $clients = \Auth::user()->companyClients()
                            ->get()
                            ->pluck('name', 'id');

        $users   = User::where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id')
                        ->prepend(__('(myself)'), \Auth::user()->id);

        $client_id    = $project->client_id;

        if($client_id)
        {
            if(is_numeric($client_id)) {

                $leads   = Lead::where('client_id', '=', $client_id)
                                ->get()
                                ->pluck('name', 'id');
            }else{

                //new client
                $leads = [];
                $clients[$client_id] = json_decode('"\u271A '.$client_id.'"');
            }
        }else
        {
                $leads   = \Auth::user()->companyLeads()
                                        ->get()
                                        ->pluck('name', 'id');
        }
                        
        $user_id = $project->users()->get()->pluck('id');

        return view('projects.edit', compact('project', 'clients', 'user_id', 'users', 'leads', 'start_date', 'due_date'));
    }

    public function update(ProjectUpdateRequest $request, Project $project)
    {
        Gate::authorize('update', $project);

        if($request->ajax() && $request->isMethod('patch') && !isset($request['archived']))
        {
            return view('helpers.archive');
        }

        $post = $request->validated();

        $users = [];

        if(!empty($post['users'])) {

            foreach($post['users'] as $user){

                if(($user != \Auth::user()->id) && !$project->users->contains($user)){
                    
                    $users[] = $user;
                }
            }
        }

        $project->updateProject($post);

        $project->notifyAssignedUsers($users);

        $request->session()->flash('success', __('Project successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    public function show(Request $request, Project $project)
    {
        Gate::authorize('view', $project);

        $user = \Auth::user();

        clock()->startEvent('ProjectsController', "Load project");

        $dz_id = 'project-files-dz';

        $project_id = $project->id;

        $stages = $project->stages($request['filter'], $request['sort'], $request['dir'], [])->get();

        $task_count = 0;
        foreach($stages as $stage)
        {
            $task_count += $stage->tasks->count();
        }

        $files = [];
        foreach($project->getMedia('projects') as $media)
        {
            $file = [];

            $file['file_name'] = $media->file_name;
            $file['size'] = $media->size;
            $file['download'] = route('projects.file.download',[$project->id, $media->id]);
            $file['delete'] = route('projects.file.delete',[$project->id, $media->id]);

            $files[] = $file;
        }

        $invoices = $project->invoices;

        //only activities for company or from collaborators
        $activities = $project->activities()
                                ->where('created_by', \Auth::user()->created_by)
                                ->orWhereIn('created_by', \Auth::user()->collaborators->pluck('id'))
                                ->get();

        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
        {
            $timesheets = $project->timesheets;
            $expenses = $project->expenses;
        }
        else
        {
            $timesheets = $project->timesheets()->where('user_id', '=', \Auth::user()->id)->get();
            $expenses = $project->expenses()->where('user_id', '=', \Auth::user()->id)->get();
        }

        $notes = $project->notes;

        $project->computeStatistics();

        clock()->endEvent('ProjectsController');

        if ($request->ajax())
        {
            switch($request['id']){

                case 'tasks-container': return view('tasks.index', compact('stages'))->render();
                case 'timesheets-container': return view('timesheets.index', compact('timesheets'))->render();
                case 'invoices-container': return view('invoices.index', compact('invoices'))->render();
                case 'expenses-container': return view('expenses.index', compact('expenses'))->render();
                case 'notes-container': return view('notes.index', compact('notes'))->render();
                case 'activity-container': return view('activity.index', compact('activities'))->render();
            }                
        }

        return view('projects.show', compact('project', 'project_id', 'stages', 'task_count', 'timesheets', 'invoices', 'expenses', 'files', 'notes', 'activities', 'dz_id'));
    }

    public function destroy(ProjectDestroyRequest $request, Project $project)
    {
        Gate::authorize('delete', $project);

        if($request->ajax()){

            return view('helpers.destroy');
        }

        $project->delete();

        if(URL::previous() == route('projects.show', $project)){

            return Redirect::to(route('projects.index'))->with('success', __('Project successfully deleted'));
        }

        return Redirect::to(URL::previous())->with('success', __('Project successfully deleted'));
    }

    public function refresh(Request $request, $project_id)
    {
        $request->flash();

        if($project_id)
        {
            $project = Project::find($project_id);
            $project->client_id = $request['client_id'];

            return $this->edit($request, $project);
        }

        return $this->create($request);
    }
}
