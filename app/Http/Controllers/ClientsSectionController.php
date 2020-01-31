<?php

namespace App\Http\Controllers;

use App\User;
use App\Contact;
use App\LeadStage;
use Illuminate\Http\Request;

class ClientsSectionController extends Controller
{
    public function index()
    {
        $client=\Auth::user();

        if(\Auth::user()->can('manage client'))
        {
            $clients = User::where('created_by','=',$client->creatorId())->where('type','=','client')->get();            
            $contacts = Contact::where('created_by','=',$client->creatorId())->get();

            $leads_count = 0;
            if(\Auth::user()->can('manage lead'))
            {
                $stages = LeadStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order')->get();

                foreach($stages as $stage)
                    $leads_count = $leads_count + count($stage->leads()->get());
            }    

            $client_id = null;
            $activities = array();

            return view('sections.clients.index', compact('clients', 'client_id', 'contacts', 'stages', 'leads_count', 'activities'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
