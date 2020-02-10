<?php

namespace App\Http\Controllers;

use App\User;
use App\Contact;
use App\Lead;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientsSectionController extends Controller
{
    public function index()
    {
        $user = \Auth::user();

        if($user->can('manage client'))
        {
            clock()->startEvent('ClientsSectionController', "Load clients");

            $clients = User::with(['clientContacts:id','clientProjects:id', 'clientLeads:id'])
                        ->where('created_by','=',$user->creatorId())
                        ->where('type','=','client')
                        ->paginate(25, ['*'], 'client-page');

            clock()->endEvent('ClientsSectionController');

            return view('sections.clients.index', compact('clients'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
