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
            if (!$request->ajax())
            {
                return view('contacts.page');
            }

            clock()->startEvent('ContactsController', "Load contacts");

            $contacts = Contact::contactsByUserType()
                        ->where(function ($query) use ($request) {
                            $query->where('name','like','%'.$request['filter'].'%')
                            ->orWhere('email','like','%'.$request['filter'].'%')
                            ->orWhere('phone','like','%'.$request['filter'].'%')
                            ->orWhereHas('tags', function ($query) use($request)
                            {
                                $query->where('tags.name','like','%'.$request['filter'].'%');
        
                            });
                        })
                        ->orderBy($request['sort']?$request['sort']:'name', $request['dir']?$request['dir']:'asc')
                        ->paginate(25, ['*'], 'contact-page');

            clock()->endEvent('ContactsController');

            return view('contacts.index', ['contacts' => $contacts])->render();
        }
        else
        {
            return redirect()->back()->with('error', __('You dont have the right to perform this operation!'));
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

        $clients = \Auth::user()->companyClients()
                    ->get()                    
                    ->pluck('name', 'id');

        $tags = Tag::where('created_by', '=', \Auth::user()->created_by)
                    ->whereHas('contacts')
                    ->get()
                    ->pluck('name', 'name');


        return view('contacts.create', compact('clients', 'tags', 'client_id'));
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

        if(Contact::createContact($post))
        {
            $request->session()->flash('success', __('Contact successfully created.'));
    
            return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
        }
        else
        {
            $request->session()->flash('error', __('Your have reached you client limit. Please upgrade your subscription to add more clients!'));
        }

        $url = redirect()->route('profile.edit', \Auth::user()->handle())->getTargetUrl().'/#subscription';
        return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        $clients = \Auth::user()->companyClients()
                        ->get()
                        ->pluck('name', 'id');

        $tags = Tag::where('created_by', '=', \Auth::user()->created_by)
                    ->whereHas('contacts')
                    ->get()
                    ->pluck('name', 'name');

        $contact_tags = [];
        foreach($contact->tags as $tag)
        {
            $contact_tags[] = $tag->name;    
        }

        return view('contacts.edit', compact('contact', 'clients', 'tags', 'contact_tags'));
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

        $contact->delete();

        return Redirect::to(URL::previous())->with('success', __('Contact successfully deleted.'));
    }
}
