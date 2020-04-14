<?php

namespace App\Http\Controllers;

use App\ExpenseCategory;
use App\Lead;
use App\Leadsource;
use App\LeadStage;
use App\User;
use App\Client;
use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\LeadStoreRequest;
use App\Http\Requests\LeadUpdateRequest;
use App\Http\Requests\LeadDestroyRequest;
use Illuminate\Support\Arr;

class LeadsController extends Controller
{
    public function board()
    {
        if(\Auth::user()->can('manage lead'))
        {
            clock()->startEvent('LeadsController', "Load leads");

            $stages = LeadStage::stagesByUserType()->get();

            clock()->endEvent('LeadsController');

            return view('leads.board', compact('stages'));
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }
    }

    public function create(Request $request)
    {
        if(\Auth::user()->can('create lead'))
        {
            $stage_id = $request['stage_id'];
            $client_id = $request['client_id'];

            $stages  = LeadStage::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            $owners  = User::where('created_by', '=', \Auth::user()->creatorId())
                            ->where('type', '!=', 'client')
                            ->get()
                            ->pluck('name', 'id')
                            ->prepend('(myself)', \Auth::user()->id);

            $clients = Client::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            if($client_id)
            {
                $contacts = Contact::contactsByUserType()
                                    ->where('client_id', '=', $client_id)
                                    ->get()->pluck('name', 'id');
            }else
            {
                $contacts = Contact::contactsByUserType()
                                    ->get()->pluck('name', 'id');
            }
            $sources = Leadsource::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('leads.create', compact('client_id', 'stage_id', 'stages', 'owners', 'clients', 'contacts', 'sources'));
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }
    }

    public function store(LeadStoreRequest $request)
    {
        $post = $request->validated();

        $lead = Lead::createLead($post);

        $request->session()->flash('success', __('Lead successfully created.'));

        return "<script>window.location.reload()</script>";
    }

    public function edit(Lead $lead)
    {
        if(\Auth::user()->can('edit lead'))
        {
            $stages  = LeadStage::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $owners  = User::where('created_by', '=', \Auth::user()->creatorId())
                            ->where('type', '=', 'employee')
                            ->get()
                            ->pluck('name', 'id')
                            ->prepend('(myself)', \Auth::user()->id);

            $clients = Client::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $sources = Leadsource::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            $client_id    = $lead->client_id;

            if($client_id)
            {
                $contacts = Contact::contactsByUserType()
                                    ->where('client_id', '=', $client_id)
                                    ->get()->pluck('name', 'id');
            }else
            {
                $contacts = Contact::contactsByUserType()
                                    ->get()->pluck('name', 'id');
            }

            return view('leads.edit', compact('stages', 'owners', 'sources', 'lead', 'clients', 'contacts'));
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }
    }

    public function update(LeadUpdateRequest $request, Lead $lead)
    {
        $post = $request->validated();

        $lead->updateLead($post);

        $request->session()->flash('success', __('Lead successfully updated.'));

        return "<script>window.location.reload()</script>";
    }

    public function destroy(LeadDestroyRequest $request, Lead $lead)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        $lead->detachLead();
        $lead->delete();

        return Redirect::to(URL::previous())->with('success', __('Lead successfully deleted.'));
    }

    public function order(Request $request)
    {
        $post  = $request->all();

        foreach($post['order'] as $key => $item)
        {
            $lead = Lead::find($item);
            $lead->order = $key;
            $lead->stage_id = $post['stage_id'];
            $lead->save();
        }

        $return               = [];
        $return['is_success'] = true;
        $return['total_old']   = \Auth::user()->priceFormat($post['total_old']);
        $return['total_new']   = \Auth::user()->priceFormat($post['total_new']);

        return response()->json($return);
    }

    public function refresh(Request $request, $lead_id)
    {
        $request->flash();

        if($lead_id)
        {
            $lead = Lead::find($lead_id);
            $lead->client_id = $request['client_id'];

            return $this->edit($lead);
        }

        return $this->create($request);
    }
}
