<?php

namespace App\Http\Controllers;

use App\User;
use App\Contact;
use App\LeadStage;
use Illuminate\Http\Request;

class ClientSectionController extends Controller
{
    public function index()
    {
        $client=\Auth::user();

        if(\Auth::user()->can('manage client') ||
            \Auth::user()->can('manage lead'))
        {
            if(\Auth::user()->can('manage client'))
            {
                $clients = User::where('created_by','=',$client->creatorId())->where('type','=','client')->get();            
                $contacts = Contact::where('created_by','=',$client->creatorId())->get();

                $contact_clients = array();
            
                foreach($contacts as $key => $contact)
                    $contact_clients[$key] = $contact->company;
    
                $contacts_count = array_count_values($contact_clients);    
            }

            if(\Auth::user()->can('manage lead'))
            {
                $stages = LeadStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order')->get();

                $leads_count = 0;
                foreach($stages as $stage)
                    $leads_count = $leads_count + count($stage->leads()->get());
            }

            return view('sections.clients.index', compact('clients', 'contacts', 'contacts_count', 'stages', 'leads_count'));
        }else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
