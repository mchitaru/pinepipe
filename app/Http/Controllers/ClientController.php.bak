<?php

namespace App\Http\Controllers;

use App\Plan;
use App\User;
use App\Contacts;
use App\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ClientController extends Controller
{
    public function index()
    {
        $client=\Auth::user();
        if(\Auth::user()->can('manage client')){
            $clients = User::where('created_by','=',$client->creatorId())->where('type','=','client')->get();
            
            $contacts = Contacts::where('created_by','=',$client->creatorId())->get();

            $contact_clients = array();
            
            foreach($contacts as $key => $contact)
                $contact_clients[$key] = $contact->company;

            $contacts_count = array_count_values($contact_clients);

            return view('client.index', compact('clients', 'contacts', 'contacts_count'));
        }else{
            return redirect()->back();
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create client')) {
            return view('client.create');
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
            $plan    = Plan::find($objUser->plan);

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



    public function edit($id)
    {
        if(\Auth::user()->can('edit client')) {
            $client = User::findOrFail($id);
            return view('client.edit', compact('client'));
        }else{
            return redirect()->back();
        }

    }


    public function update(Request $request, $id)
    {
        if(\Auth::user()->can('edit client')) {
            $client = User::findOrFail($id);
            $this->validate($request, [
                'name'=>'required|max:120',
                'email'=>'required|email|unique:users,email,'.$id,
            ]);

            $input = $request->all();
            $client->fill($input)->save();

            return redirect()->route('clients.index')
                             ->with('success','Client successfully updated.');
        }else{
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        if(\Auth::user()->can('delete client')) {
            $user = User::find($id);
            if($user) {
                $user->delete();
                $user->destroyUserNotesInfo($user->id);
                $user->removeClientProjectInfo($user->id);
                $user->removeClientLeadInfo($user->id);
                $user->destroyUserTaskAllInfo($user->id);

                return redirect()->route('clients.index')->with('success',  __('Client Deleted Successfully.'));
            }else{
                return redirect()->back()->with('error',__('Something is wrong.'));
            }
        }else{
            return redirect()->back();
        }
    }

    public function show($id)
    {
        $user=\Auth::user();

        if(\Auth::user()->can('manage client')){

            $client = User::find($id);
            $contacts = Contacts::where('created_by','=',$user->creatorId())->where('company','=',$client->name)->get();
            $projects = Projects::where('client', '=', $client->id)->get();

            $project_status = Projects::$project_status;

            return view('client.show', compact('client', 'contacts', 'projects', 'project_status'));
        }else{
            return redirect()->back()->with('error', 'Permission denied.');;
        }
    }

    public function profile(){
        $userDetail=\Auth::user();
        return view('client.profile')->with('userDetail',$userDetail);
    }

    public function editprofile(Request $request){
        $userDetail=\Auth::user();
        $user = User::findOrFail($userDetail['id']);
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$userDetail['id'],
        ]);
        if($request->hasFile('profile')) {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            $dir= storage_path('app/public/avatar/');
            $image_path = $dir .$userDetail['avatar'];

            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $path = $request->file('profile')->storeAs('public/avatar/', $fileNameToStore);

        }

        if(!empty($request->profile)){
            $user['avatar'] = $fileNameToStore;
        }
        $user['name'] = $request['name'];
        $user['email'] = $request['email'];
        $user->save();
        return redirect()->route('dashboard')
                         ->with('success', 'Profile successfully updated.');
    }

    public function contactCreate()
    {
        if(\Auth::user()->can('create client')) {
            return view('client.create');
        }else{
            return redirect()->back();
        }
    }

    public function contactEdit($id)
    {
        if(\Auth::user()->can('edit client')) {
            $client = User::findOrFail($id);
            return view('client.edit', compact('client'));
        }else{
            return redirect()->back();
        }

    }
    
    public function contactShow(){
        $userDetail=\Auth::user();
        return view('client.profile')->with('userDetail',$userDetail);
    }

    public function contactDestroy($id)
    {
        if(\Auth::user()->can('delete client')) {
            $user = User::find($id);
            if($user) {
                $user->delete();
                $user->destroyUserNotesInfo($user->id);
                $user->removeClientProjectInfo($user->id);
                $user->removeClientLeadInfo($user->id);
                $user->destroyUserTaskAllInfo($user->id);

                return redirect()->route('clients.index')->with('success',  __('Client Deleted Successfully.'));
            }else{
                return redirect()->back()->with('error',__('Something is wrong.'));
            }
        }else{
            return redirect()->back();
        }
    }
}
