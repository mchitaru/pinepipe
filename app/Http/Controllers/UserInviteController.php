<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Client;
use App\Mail\InviteUserMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

use App\Http\Requests\InviteUserStoreRequest;
use App\Http\Requests\InviteUserUpdateRequest;

class UserInviteController extends Controller
{
    public function create($role = 'collaborator')
    {
        if($role == 'employee')
            return view('users.invite.employee');

        return view('users.invite.collaborator');
    }

    public function store(InviteUserStoreRequest $request)
    {
        $post = $request->validated();

        $user = User::withoutGlobalScopes()
                        ->where('email', $post['email'])
                        ->first();

        if($user && ($user->isCollaborator() || $user->isEmployee())){

            //resend
            Mail::to($user->email)->queue(new InviteUserMail($user, \Auth::user()));

            $request->session()->flash('success', __('Invitation successfully resent.'));

            return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
        }

        if(!\Auth::user()->hasMaxUsers()){

            if($user == null){

                //new user -> send email invite

                $name = $post['email'];
                $name = explode('@', $name)[0];

                $data = [
                    'name' => $name,
                    'email' => $post['email'],
                    'type' => ($post['role'] == 'employee') ? 'employee' : 'company'
                ];

                if($post['role'] == 'employee'){
                    $data['created_by'] = \Auth::user()->id;
                }

                $user = User::createUser($data);

                Mail::to($user->email)->queue(new InviteUserMail($user, \Auth::user()));

                $request->session()->flash('success', __('An invitation was sent to the email address. You can now assign the user to a project.'));

            }elseif($post['role'] != 'employee' && $user->isCompany()){

                $request->session()->flash('success', __('User successfully invited. You can now assign him/her to a project.'));
            }

            if($user != \Auth::user()){

                if($post['role'] != 'employee' && $user->isCompany()){

                    \Auth::user()->collaborators()->attach($user->id, ['type' => 'collaborator']);

                    if(\Auth::user()->companySettings &&
                        $user->companyClients()
                                ->where(function ($query) {
                                    $query->where('name', \Auth::user()->companySettings->name)
                                            ->orWhere('email', \Auth::user()->companySettings->email);
                                })->first() == null) {

                        if($user->checkClientLimit()){

                            //add a client for current company
                            $client = Client::create(
                                [
                                    'name' => \Auth::user()->companySettings->name,
                                    'email' => \Auth::user()->companySettings->email,
                                    'phone' => \Auth::user()->companySettings->phone,
                                    'address' => \Auth::user()->companySettings->getFullAddress(),
                                    'website' => \Auth::user()->companySettings->website,
                                    'tax' => \Auth::user()->companySettings->tax,
                                    'registration' =>\Auth::user()->companySettings->registration,
                                    'user_id' => $user->id,
                                    'created_by' => $user->id
                                ]
                            );

                            $client->created_by = $user->id;
                            $client->save();
                        }
                    }
                }

                return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
            }

            $request->session()->flash('error', __('This email address cannot be invited! Please contact support for more information.'));

            return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
        }
        else
        {
            $request->session()->flash('error', __('Your have reached your user limit. Please upgrade your subscription to add more users!'));
        }

        $url = redirect()->route('subscription')->getTargetUrl();
        return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
    }

    public function edit(User $user)
    {
        return view('users.invite.edit', compact('user'));
    }

    public function update(InviteUserUpdateRequest $request, User $user)
    {
        $post = $request->validated();

        if($user->password == null) {

            $user->name = $post['name'];
            $user->password = Hash::make($post['password']);
            $user->email_verified_at = now();
            $user->handle = $user->handle();

            $user->initCompanyDefaults();

            $location = geoip($request->ip());
            $user->setLocale($location);

            $user->subscribeNewsletter();

            $user->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => $request->getClientIp()
            ]);

            $user->save();

            auth()->login($user);

            return redirect()->route('home');
        }

        return redirect()->back()->with('error', __('The information for this account was already updated. Please login with your email and password, to continue using the app!'));
    }

}
