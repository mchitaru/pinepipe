<?php

namespace App\Http\Controllers;

use App\ExpenseCategory;
use App\Lead;
use App\Leadsource;
use App\LeadStage;
use App\User;
use Illuminate\Http\Request;

class LeadsController extends ClientsSectionController
{
    public function board()
    {
        if(\Auth::user()->can('manage lead'))
        {
            $stages = LeadStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order')->get();

            return view('leads.board', compact('stages'));
        }    
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create lead'))
        {
            $stages  = LeadStage::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $owners  = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->get()->pluck('name', 'id');
            $clients = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'client')->get()->pluck('name', 'id');
            $sources = Leadsource::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('leads.create', compact('stages', 'owners', 'clients', 'sources'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function store(Request $request)
    {

        if(\Auth::user()->can('create lead'))
        {
            if(\Auth::user()->type == 'company')
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                       'price' => 'required',
                                       'stage_id' => 'required',
                                       'user_id' => 'required',
                                       'client_id' => 'required',
                                       'source_id' => 'required',
                                   ]
                );
            }
            else
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                       'price' => 'required',
                                       'stage_id' => 'required',
                                       'source_id' => 'required',
                                       'client_id' => 'required',
                                   ]
                );
            }


            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('leads.index')->with('error', $messages->first());
            }
            $leads        = new Lead();
            $leads->name  = $request->name;
            $leads->price = $request->price;
            $leads->stage_id = $request->stage_id;
            if(\Auth::user()->type == 'company')
            {
                $leads->user_id = $request->user_id;
            }
            else
            {
                $leads->user_id = \Auth::user()->id;
            }
            $leads->source_id     = $request->source_id;
            $leads->notes      = $request->notes;
            $leads->client_id     = $request->client_id;
            $leads->created_by = \Auth::user()->creatorId();
            $leads->save();

            return redirect()->route('leads.index')->with('success', __('Lead successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function edit(Lead $lead)
    {
        if(\Auth::user()->can('edit lead'))
        {
            if($lead->created_by == \Auth::user()->creatorId())
            {
                $stages  = LeadStage::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $owners  = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'employee')->get()->pluck('name', 'id');
                $clients = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'client')->get()->pluck('name', 'id');
                $sources = Leadsource::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

                return view('leads.edit', compact('stages', 'owners', 'sources', 'lead','clients'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function update(Request $request, Lead $lead)
    {
        if(\Auth::user()->can('edit lead'))
        {
            if($lead->created_by == \Auth::user()->creatorId())
            {
                if(\Auth::user()->type == 'company')
                {
                    $validator = \Validator::make(
                        $request->all(), [
                                           'name' => 'required|max:20',
                                           'price' => 'required',
                                           'stage_id' => 'required',
                                           'user_id' => 'required',
                                           'source_id' => 'required',
                                           'client_id' => 'required',
                                       ]
                    );
                }
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('leads.index')->with('error', $messages->first());
                }
                $lead->name       = $request->name;
                $lead->price      = $request->price;
                $lead->stage_id   = $request->stage_id;
                $lead->user_id    = $request->user_id;
                $lead->source_id  = $request->source_id;
                $lead->client_id  = $request->client_id;
                $lead->notes      = $request->notes;
                $lead->created_by = \Auth::user()->creatorId();
                $lead->save();

                return redirect()->route('leads.index')->with('success', __('Lead successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function destroy(Request $request, Lead $lead)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        if(\Auth::user()->can('delete lead'))
        {
            if($lead->created_by == \Auth::user()->creatorId())
            {
                $lead->removeProjectLead();
                $lead->delete();

                return redirect()->route('leads.index')->with('success', __('Lead successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function order(Request $request)
    {
        $post  = $request->all();

        $lead  = Lead::find($post['lead_id']);
        $stage = LeadStage::find($post['stage_id']);

        if(!empty($stage))
        {
            $lead->stage = $post['stage_id'];
            $lead->save();
        }

        foreach($post['order'] as $key => $item)
        {
            $lead_order             = Lead::find($item);
            $lead_order->order      = $key;
            $lead_order->stage      = $post['stage_id'];
            $lead_order->save();
        }
    }

    public function show(Lead $lead)
    {
        return view('leads.show', compact('lead'));
    }
}
