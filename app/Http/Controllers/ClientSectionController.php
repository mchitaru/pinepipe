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

        if(\Auth::user()->can('manage client'))
        {
            $clients = User::where('created_by','=',$client->creatorId())->where('type','=','client')->get();            
            $contacts = Contact::where('created_by','=',$client->creatorId())->get();

            $contact_clients = array();
        
            foreach($contacts as $key => $contact)
                $contact_clients[$key] = $contact->company;

            $contacts_count = array_count_values($contact_clients);    

            $leads_count = 0;
            if(\Auth::user()->can('manage lead'))
            {
                $stages = LeadStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order')->get();

                foreach($stages as $stage)
                    $leads_count = $leads_count + count($stage->leads()->get());
            }    

            $activities = array();

            return view('sections.clients.index', compact('clients', 'contacts', 'contacts_count', 'stages', 'leads_count', 'activities'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
