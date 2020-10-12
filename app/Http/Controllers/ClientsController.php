<?php

namespace App\Http\Controllers;

use App\SubscriptionPlan;
use App\Client;
use App\User;
use App\Contact;
use App\Project;
use App\Lead;
use App\Stage;
use App\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\ClientStoreRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Http\Requests\ClientDestroyRequest;

class ClientsController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('viewAny', 'App\Client');

        $user = \Auth::user();

        if (!$request->ajax())
        {
            return view('clients.page');
        }

        clock()->startEvent('ClientsController', "Load clients");

        if(!empty($request['tag'])){
            $status = array(array_search($request['tag'], Client::$status));
        }else{
            $status = array(array_search('active', Client::$status));
        }

        $clients = $user->companyClients()
                    ->with(['contacts', 'projects', 'leads'])
                    ->whereIn('archived', $status)
                    ->where(function ($query) use ($request) {
                        $query->where('name','like','%'.$request['filter'].'%')
                        ->orWhere('email','like','%'.$request['filter'].'%');
                    })
                    ->orderBy($request['sort']?$request['sort']:'name', $request['dir']?$request['dir']:'asc')
                    ->paginate(25, ['*'], 'client-page');

        clock()->endEvent('ClientsController');

        return view('clients.index', ['clients' => $clients])->render();
    }

    public function create()
    {
        Gate::authorize('create', 'App\Client');

        return view('clients.create');
    }


    public function store(ClientStoreRequest $request)
    {
        Gate::authorize('create', 'App\Client');

        $post = $request->validated();

        if(\Auth::user()->checkClientLimit())
        {
            $client = Client::createClient($post);

            $request->session()->flash('success', __('Client successfully created.'));

            $url = redirect()->route('clients.show', $client->id)->getTargetUrl().'#profile';
            return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
        }
        else
        {
            $request->session()->flash('error', __('Your have reached you client limit. Please upgrade your subscription to add more clients!'));
        }

        $url = redirect()->route('subscription')->getTargetUrl();
        return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
    }



    public function edit(Client $client)
    {
        Gate::authorize('update', $client);
        
        return view('clients.edit', compact('client'));
    }


    public function update(ClientUpdateRequest $request, Client $client)
    {
        Gate::authorize('update', $client);

        if($request->ajax() && $request->isMethod('patch') && !isset($request['archived']))
        {
            return view('helpers.archive');
        }

        $post = $request->validated();

        $client->updateClient($post);

        if($request->hasFile('avatar')){

            $client->clearMediaCollection('logos');
            $file = $client->addMedia($request->file('avatar'))->toMediaCollection('logos');
        }

        $request->session()->flash('success', __('Client successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }


    public function destroy(Request $request, Client $client)
    {
        Gate::authorize('delete', $client);

        if($request->ajax()){

            return view('helpers.destroy');
        }

        $client->delete();

        if(URL::previous() == route('clients.show', $client)){

            return Redirect::to(route('clients.index'))->with('success', __('Client successfully deleted.'));
        }

        return Redirect::to(URL::previous())->with('success', __('Client successfully deleted.'));
    }

    public function show(Client $client)
    {
        Gate::authorize('view', $client);

        $user = \Auth::user();

        clock()->startEvent('ClientsController', "Load contacts, leads, projects");

        if($user->type == 'company')
        {
            $contacts = $client->contacts;
            $projects = $client->projects()
                                ->where('archived', 0)
                                ->get();

            $leads = Lead::with(['client', 'user', 'stage'])
                    ->where('archived', 0)
                    ->where('client_id', '=', $client->id)
                    ->orderBy('order')
                    ->get();

            $activities = Activity::whereHas('clients', function ($query) use ($client) {
                $query->where('id', $client->id);
            })
            ->orWhereHas('projects', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->orWhereHas('leads', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->orWhereHas('contacts', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->limit(20)
            ->orderBy('id', 'desc')
            ->get();

        }else
        {
            $contacts = $user->contacts()
                        ->with(['client', 'user'])
                        ->where('client_id', '=', $client->id)
                        ->get();

            $projects = $user->projects()
                        ->with(['client', 'users'])
                        ->where('client_id', '=', $client->id)
                        ->get();

            $leads = $user->leads()
                        ->with(['client', 'user', 'stage'])
                        ->where('client_id', '=', $client->id)
                        ->orderBy('order')
                        ->get();

            if($user->type == 'client'){

                $activities = Activity::whereHas('projects', function ($query) use ($client) {
                    $query->where('client_id', $client->id);
                })
                ->limit(20)
                ->orderBy('id', 'desc')
                ->get();

            }else{

                $activities = Activity::whereHas('projects', function ($query) use ($client) {
                    $query->where('client_id', $client->id)
                            ->whereHas('users', function ($query) {

                                // tasks with the current user assigned.
                                $query->where('users.id', \Auth::user()->id);

                            });
                })
                ->limit(20)
                ->orderBy('id', 'desc')
                ->get();

            }
        }

        $stages = Stage::where('class', Lead::class)
                            ->where('created_by', \Auth::user()->created_by)
                            ->get()
                            ->pluck('id')
                            ->toArray();

        foreach($leads as $lead){

            $index = array_search($lead->stage_id, $stages);
            $lead->progress = ($index + 1) * 100 / (count($stages) - 1);    
        }

        clock()->endEvent('ClientsController');

        return view('clients.show', compact('client', 'contacts', 'projects', 'leads', 'activities'));
    }
}
