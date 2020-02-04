<?php

namespace App\Http\Controllers;

use App\Contact;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\ContactStoreRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Requests\ContactDestroyRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ContactsController extends ClientsSectionController
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'client')->get()->pluck('name', 'id');

        return view('contacts.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactStoreRequest $request)
    {
        $post = $request->validated();

        Contact::createContact($post);

        $request->session()->flash('success', __('Contact successfully created.'));

        $url = redirect()->back()->getTargetUrl().'/#contacts';
        return "<script>window.location='{$url}'</script>";
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
        if(\Auth::user()->can('edit contact')) 
        {
            $clients = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'client')->get()->pluck('name', 'id');

            return view('contacts.edit', compact('contact', 'clients'));

        }else
        {
            return Redirect::to(URL::previous() . "#contacts")->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(ContactUpdateRequest $request, Contact $contact)
    {
        $post = $request->validated();

        $contact->updateContact($post);

        $request->session()->flash('success', __('Contact successfully updated.'));

        $url = redirect()->back()->getTargetUrl().'/#contacts';
        return "<script>window.location='{$url}'</script>";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactDestroyRequest $request, Contact $contact)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        $contact->detachContact();

        $contact->delete();

        return Redirect::to(URL::previous() . "#contacts")->with('success', __('Contact successfully deleted.'));
    }
}
