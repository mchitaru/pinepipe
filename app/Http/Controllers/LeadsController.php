<?php

namespace App\Http\Controllers;

use App\ExpenseCategory;
use App\Lead;
use App\Leadsource;
use App\LeadStage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

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
            return Redirect::to(URL::previous() . "#leads")->with('error', __('Permission denied.'));
        }
    }

    public function create(Request $request)
    {
        if(\Auth::user()->can('create lead'))
        {
            $stage_id = $request['stage_id'];

            $stages  = LeadStage::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $owners  = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'client')->get()->pluck('name', 'id');
            $clients = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'client')->get()->pluck('name', 'id');
            $sources = Leadsource::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('leads.create', compact('stage_id', 'stages', 'owners', 'clients', 'sources'));
        }
        else
        {
            return Redirect::to(URL::previous() . "#leads")->with('error', __('Permission denied.'));
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

                return Redirect::to(URL::previous() . "#leads")->with('error', $messages->first());
            }
            
            $stage = LeadStage::find($request->stage_id);
            
            $leads        = new Lead();
            $leads->name  = $request->name;
            $leads->price = $request->price;
            $leads->stage_id = $request->stage_id;
            $leads->order = $stage->leads->count();
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

            return Redirect::to(URL::previous() . "#leads")->with('success', __('Lead successfully created.'));
        }
        else
        {
            return Redirect::to(URL::previous() . "#leads")->with('error', __('Permission denied.'));
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
            return Redirect::to(URL::previous() . "#leads")->with('error', __('Permission denied.'));
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

                    return Redirect::to(URL::previous() . "#leads")->with('error', $messages->first());
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

                return Redirect::to(URL::previous() . "#leads")->with('success', __('Lead successfully updated.'));
            }
            else
            {
                return Redirect::to(URL::previous() . "#leads")->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return Redirect::to(URL::previous() . "#leads")->with('error', __('Permission denied.'));
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

                return Redirect::to(URL::previous() . "#leads")->with('success', __('Lead successfully deleted.'));
            }
            else
            {
                return Redirect::to(URL::previous() . "#leads")->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return Redirect::to(URL::previous() . "#leads")->with('error', __('Permission denied.'));
        }
    }

    public function show(Lead $lead)
    {
        return view('leads.show', compact('lead'));
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
}
