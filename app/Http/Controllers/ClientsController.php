<?php

namespace App\Http\Controllers;

use App\PaymentPlan;
use App\Client;
use App\User;
use App\Contact;
use App\Project;
use App\Lead;
use App\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\ClientStoreRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Http\Requests\ClientDestroyRequest;

class ClientsController extends Controller
{

    public function index(Request $request)
    {
        $user = \Auth::user();

        if($user->can('manage client'))
        {
            clock()->startEvent('ClientsController', "Load clients");

            if($user->type == 'company')
            {
                $clients = Client::with(['contacts', 'projects', 'leads'])
                            ->where('created_by','=',$user->creatorId())
                            ->where(function ($query) use ($request) {
                                $query->where('name','like','%'.$request['filter'].'%')
                                ->orWhere('email','like','%'.$request['filter'].'%');
                            })
                            ->orderBy($request['sort']?$request['sort']:'name', $request['dir']?$request['dir']:'asc')
                            ->paginate(25, ['*'], 'client-page');
            }else{

                $clients = Client::with(['contacts' => function ($query) {
                                $query->where('user_id', \Auth::user()->id);
                            },
                            'projects' => function ($query) {
                                // only include tasks with projects where...
                                $query->whereHas('users', function ($query) {

                                    // ...the current user is assigned.
                                    $query->where('users.id', \Auth::user()->id);
                                });
                            },
                            'leads' => function ($query) {
                                $query->where('user_id', \Auth::user()->id);
                            }])
                            ->where('created_by','=',$user->creatorId())
                            ->where(function ($query) use ($request) {
                                $query->where('name','like','%'.$request['filter'].'%')
                                ->orWhere('email','like','%'.$request['filter'].'%');
                            })
                            ->orderBy($request['sort']?$request['sort']:'name', $request['dir']?$request['dir']:'asc')
                            ->paginate(25, ['*'], 'client-page');
            }

            clock()->endEvent('ClientsController');

            if ($request->ajax())
            {
                return view('clients.index', ['clients' => $clients])->render();
            }

            return view('clients.page', compact('clients'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create client'))
        {
            return view('clients.create');
        }
        else
        {
            Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }
    }


    public function store(ClientStoreRequest $request)
    {
        $post = $request->validated();

        if(\Auth::user()->checkClientLimit())
        {
            $client = Client::createClient($post);

            $request->session()->flash('success', __('Client successfully created.'));

            $url = redirect()->route('clients.show', $client->id)->getTargetUrl().'#profile';
            return "<script>window.location='{$url}'</script>";
        }
        else
        {
            $request->session()->flash('error', __('Your have reached you client limit. Please upgrade your subscription to add more clients!'));
        }

        $url = redirect()->route('profile.show')->getTargetUrl().'/#subscription';
        return "<script>window.location='{$url}'</script>";
    }



    public function edit(Client $client)
    {
        if(\Auth::user()->can('edit client'))
        {
            return view('clients.edit', compact('client'));
        }
        else
        {
            Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }
    }


    public function update(ClientUpdateRequest $request, Client $client)
    {
        $post = $request->validated();

        $client->updateClient($post);

        $request->session()->flash('success', __('Client successfully updated.'));

        return "<script>window.location.reload()</script>";
    }


    public function destroy(Request $request, Client $client)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        $client->detachClient();
        $client->delete();

        return Redirect::to(URL::previous())->with('success', __('Client successfully deleted.'));
    }

    public function show(Client $client)
    {
        $user = \Auth::user();

        if($user->can('show client'))
        {
            clock()->startEvent('ClientsController', "Load contacts, leads, projects");

            if($user->type == 'company')
            {
                $contacts = $client->contacts;
                $projects = $client->projects;

                $leads = Lead::with(['client', 'user', 'stage'])
                        ->where('client_id', '=', $client->id)
                        ->where('created_by', '=', $user->creatorId())
                        ->orderBy('order')
                        ->get();

            }else
            {
                $contacts = $user->contacts()
                            ->with(['client', 'user'])
                            ->where('client_id', '=', $client->id)
                            ->where('created_by', '=', $user->creatorId())
                            ->get();

                $projects = $user->projects()
                            ->with(['client', 'users'])
                            ->where('client_id', '=', $client->id)
                            ->where('created_by', '=', $user->creatorId())
                            ->get();

                $leads = $user->leads()
                            ->with(['client', 'user', 'stage'])
                            ->where('client_id', '=', $client->id)
                            ->where('created_by', '=', $user->creatorId())
                            ->orderBy('order')
                            ->get();
            }

            $activities = array(); //TO DO
            // $activities = Activity::whereHas('project', function ($query) use ($client) {
            //     $query->where('client_id', $client->id);
            // })
            // ->limit(20)
            // ->orderBy('id', 'desc')
            // ->get();

            clock()->endEvent('ClientsController');

            return view('clients.show', compact('client', 'contacts', 'projects', 'leads', 'activities'));
        }else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }
    }

    // public function profile()
    // {
    //     $userDetail=\Auth::user();
    //     return view('clients.profile')->with('userDetail',$userDetail);
    // }

    // public function editprofile(Request $request)
    // {
    //     $userDetail=\Auth::user();
    //     $user = User::findOrFail($userDetail['id']);
    //     $this->validate($request, [
    //         'name'=>'required|max:120',
    //         'email'=>'required|email|unique:users,email,'.$userDetail['id'],
    //     ]);

    //     if($request->hasFile('profile'))
    //     {
    //         $path = \Helpers::storePublicFile($request->file('profile'));
    //         $user['avatar'] = $path;
    //     }

    //     $user['name'] = $request['name'];
    //     $user['email'] = $request['email'];
    //     $user->save();

    //     return Redirect::to(URL::previous())->with('error', __('Profile successfully updated.'));
    // }

    // public function convert($id)
    // {
    //     $leads=Lead::find($id);
    //     return view('client.convert_client',compact('leads'));
    // }
}
