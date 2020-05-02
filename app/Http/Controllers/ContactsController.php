<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Contact;
use App\User;
use App\Client;
use Illuminate\Http\Request;
use App\Http\Requests\ContactStoreRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Requests\ContactDestroyRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ContactsController extends Controller
{

    public function index(Request $request)
    {
        $user = \Auth::user();

        if($user->can('view contact'))
        {
            clock()->startEvent('ContactsController', "Load contacts");

            $contacts = Contact::contactsByUserType()
                        ->where(function ($query) use ($request) {
                            $query->where('name','like','%'.$request['filter'].'%')
                            ->orWhere('email','like','%'.$request['filter'].'%')
                            ->orWhere('phone','like','%'.$request['filter'].'%');
                        })
                        ->orderBy($request['sort']?$request['sort']:'name', $request['dir']?$request['dir']:'asc')
                        ->paginate(25, ['*'], 'contact-page');

            clock()->endEvent('ContactsController');

            if ($request->ajax())
            {
                return view('contacts.index', ['contacts' => $contacts])->render();
            }

            return view('contacts.page', compact('contacts'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $client_id = $request['client_id'];

        $clients = Client::where('created_by', '=', \Auth::user()->creatorId())
                    ->get()
                    ->pluck('name', 'id');
        $owners  = User::where('created_by', '=', \Auth::user()->creatorId())
                        ->where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id')
                        ->prepend('(myself)', \Auth::user()->id);

        $tags = Tag::where('created_by', '=', \Auth::user()->creatorId())
                    ->whereHas('contacts')
                    ->get()
                    ->pluck('name', 'name');


        return view('contacts.create', compact('clients', 'owners', 'tags', 'client_id'));
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

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        $clients = Client::where('created_by', '=', \Auth::user()->creatorId())
                        ->get()
                        ->pluck('name', 'id');

        $owners  = User::where('created_by', '=', \Auth::user()->creatorId())
                        ->where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id')
                        ->prepend('(myself)', \Auth::user()->id);

        $tags = Tag::where('created_by', '=', \Auth::user()->creatorId())
                    ->whereHas('contacts')
                    ->get()
                    ->pluck('name', 'name');

        $contact_tags = [];
        foreach($contact->tags as $tag)
        {
            $contact_tags[] = $tag->name;    
        }

        return view('contacts.edit', compact('contact', 'clients', 'owners', 'tags', 'contact_tags'));
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

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
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

        return Redirect::to(URL::previous())->with('success', __('Contact successfully deleted.'));
    }
}
