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

        if(\Auth::user()->can('create client')) {
            $this->validate($request, [
                'name'=>'required|max:120',
                'email'=>'required|email|unique:users',
                'password'=>'required|min:6',
            ]);

            $objUser = \Auth::user();
            $total_client=$objUser->countClient();
            $plan    = PaymentPlan::find($objUser->plan);

            if($total_client < $plan->max_clients || $plan->max_clients == -1)
            {

                $request['password'] = Hash::make($request->password);
                $request['type']='client';
                $request['lang']='en';
                $request['created_by']=\Auth::user()->creatorId();
                $user = User::create($request->all());
                $role_r = Role::findByName('client');
                $user->assignRole($role_r);

                return redirect()->route('clients.index')
                                 ->with('success','Client successfully added.');
            }else{
                return redirect()->back()->with('error', __('Your client limit is over, Please upgrade plan.'));
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

            return redirect()->route('clients.index')
                             ->with('success','Client successfully updated.');
        }else{
            return redirect()->back();
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

            return redirect()->route('clients.index')->with('success',  __('Client Deleted Successfully.'));
        }else{
            return redirect()->back();
        }
    }

    public function show(User $client)
    {
        if(\Auth::user()->can('manage client'))
        {

            $client = User::find($client->id);
            $contacts = Contact::where('created_by','=',$client->creatorId())->where('company','=',$client->name)->get();
            $projects = Project::where('client_id', '=', $client->id)->get();

            $project_status = Project::$project_status;

            $leads_count = 0;
            if(\Auth::user()->can('manage lead'))
            {
                $stages = LeadStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order')->get();

                foreach($stages as $stage){
                    $leads_count = $leads_count + count($stage->leads()->where('client_id','=',$client->id)->get());
                }
            }

            $activities = array();

            return view('clients.show', compact('client', 'contacts', 'projects', 'project_status', 'stages', 'leads_count', 'activities'));
        }else{
            return redirect()->back()->with('error', 'Permission denied.');;
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
        return redirect()->route('dashboard')
                         ->with('success', 'Profile successfully updated.');
    }

    public function convert($id)
    {
        $leads=Lead::find($id);
        return view('client.convert_client',compact('leads'));
    }
}
