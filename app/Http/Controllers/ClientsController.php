<?php

namespace App\Http\Controllers;

use App\PaymentPlan;
use App\User;
use App\Contact;
use App\Project;
use App\LeadStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Http\Helpers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ClientsController extends ClientsSectionController
{
 
    public function create()
    {
        if(\Auth::user()->can('create client')) {
            return view('clients.create');
        }else{
            return redirect()->back();
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create client')) 
        {
            $this->validate($request, [
                'name'=>'required|max:120',
                'email'=>'required|email|unique:users',
                'password'=>'required|min:6',
            ]);

            if(\Auth::user()->checkClientLimit())
            {

                $request['password'] = Hash::make($request->password);
                $request['type']='client';
                $request['lang']='en';
                $request['created_by']=\Auth::user()->creatorId();
                $user = User::create($request->all());
                $role_r = Role::findByName('client');
                $user->assignRole($role_r);

                return Redirect::to(URL::previous() . "#clients")->with('success', __('Client successfully created.'));
            }else
            {
                return Redirect::to(URL::previous() . "#clients")->with('error', __('Your have reached you client limit. Please upgrade your plan to add more clients!'));
            }

        }else{
            return redirect()->back();
        }

    }



    public function edit(User $client)
    {
        if(\Auth::user()->can('edit client')) 
        {
            return view('clients.edit', compact('client'));
        }else{
            return redirect()->back();
        }

    }


    public function update(Request $request, User $client)
    {
        if(\Auth::user()->can('edit client')) 
        {
            $this->validate($request, [
                'name'=>'required|max:120',
                'email'=>'required|email|unique:users,email,'.$client->id,
            ]);

            $input = $request->all();
            $client->fill($input)->save();

            return Redirect::to(URL::previous() . "#clients")->with('success', __('Client successfully updated.'));
        }else
        {
            return Redirect::to(URL::previous() . "#clients")->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Request $request, User $client)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        if(\Auth::user()->can('delete client')) 
        {
            $client->delete();
            $client->destroyUserNotesInfo($client->id);
            $client->removeClientProjectInfo($client->id);
            $client->removeClientLeadInfo($client->id);
            $client->destroyUserTaskAllInfo($client->id);

            return Redirect::to(URL::previous() . "#clients")->with('success', __('Client successfully deleted.'));
        }else
        {
            return Redirect::to(URL::previous() . "#clients")->with('error', __('Permission denied.'));
        }
    }

    public function show(User $client)
    {
        if(\Auth::user()->can('manage client'))
        {
            $contacts = $client->clientContacts;
            $projects = Project::where('client_id', '=', $client->id)->get();

            $leads_count = 0;
            $stages = null;
            if(\Auth::user()->can('manage lead'))
            {
                $stages = LeadStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order')->get();

                foreach($stages as $stage){
                    $leads_count = $leads_count + count($stage->leads()->where('client_id','=',$client->id)->get());
                }
            }

            $client_id = $client->id;
            $activities = array();

            return view('clients.show', compact('client', 'client_id', 'contacts', 'projects', 'stages', 'leads_count', 'activities'));
        }else
        {
            return Redirect::to(URL::previous() . "#clients")->with('error', __('Permission denied.'));
        }
    }

    public function profile(){
        $userDetail=\Auth::user();
        return view('clients.profile')->with('userDetail',$userDetail);
    }

    public function editprofile(Request $request){
        $userDetail=\Auth::user();
        $user = User::findOrFail($userDetail['id']);
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$userDetail['id'],
        ]);

        if($request->hasFile('profile')) 
        {
            $path = Helpers::storePublicFile($request->file('profile'));
            $user['avatar'] = $path;
        }

        $user['name'] = $request['name'];
        $user['email'] = $request['email'];
        $user->save();

        return Redirect::to(URL::previous() . "#clients")->with('error', __('Profile successfully updated.'));
    }

    public function convert($id)
    {
        $leads=Lead::find($id);
        return view('client.convert_client',compact('leads'));
    }
}
