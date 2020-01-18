<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;

class ContactsController extends ClientsSectionController
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Auth::user()->can('create client')) {
            return view('clients.create');
        }else{
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        $userDetail=\Auth::user();
        return view('clients.profile')->with('userDetail',$userDetail);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        if(\Auth::user()->can('edit client')) {
            $client = User::findOrFail($id);
            return view('clients.edit', compact('client'));
        }else{
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
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
