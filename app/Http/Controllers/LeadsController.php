<?php

namespace App\Http\Controllers;

use App\Category;
use App\Lead;
use App\Stage;
use App\User;
use App\Client;
use App\Contact;
use App\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\LeadStoreRequest;
use App\Http\Requests\LeadUpdateRequest;
use App\Http\Requests\LeadDestroyRequest;
use Illuminate\Support\Arr;

class LeadsController extends Controller
{
    public function board(Request $request)
    {
        if(\Auth::user()->can('view lead'))
        {
            if (!$request->ajax())
            {
                return view('leads.page');
            }

            clock()->startEvent('LeadsController', "Load leads");

            $stages = Stage::leadStagesByUserType($request['filter'], $request['sort'], $request['dir'], $request['tag'])->get();

            clock()->endEvent('LeadsController');

            return view('leads.board', compact('stages'))->render();
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('You dont have the right to perform this operation!'));
        }
    }

    public function create(Request $request)
    {
        if(\Auth::user()->can('create lead'))
        {
            $stage_id = $request['stage_id'];
            $client_id = $request['client_id'];
            $category_id = $request['category_id'];

            $stages = Stage::where('class', Lead::class)
                                    ->get()
                                    ->pluck('name', 'id');

            $clients = \Auth::user()->companyClients()
                                ->get()
                                ->pluck('name', 'id');
            if($client_id)
            {
                if(is_numeric($client_id)) {

                    $contacts = Contact::contactsByUserType()
                                        ->where('client_id', '=', $client_id)
                                        ->get()
                                        ->pluck('name', 'id');
                }else{

                    //new client
                    $contacts = [];
                    $clients[$client_id] = json_decode('"\u271A '.$client_id.'"');
                }
            }else
            {
                $contacts = Contact::contactsByUserType()
                                    ->get()
                                    ->pluck('name', 'id');
            }

            $categories = Category::where('class', Lead::class)
                                    ->get()
                                    ->pluck('name', 'id');

            if(isset($category_id) && !is_numeric($category_id)){

                $categories->prepend(json_decode('"\u271A '.$category_id.'"'), $category_id);
            }

            return view('leads.create', compact('client_id', 'stage_id', 'category_id', 'stages', 'clients', 'contacts', 'categories'));
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('You dont have the right to perform this operation!'));
        }
    }

    public function store(LeadStoreRequest $request)
    {
        $post = $request->validated();

        if($lead = Lead::createLead($post))
        {
            $request->session()->flash('success', __('Lead successfully created.'));
    
            $url = redirect()->route('leads.show', $lead->id)->getTargetUrl();
            return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
        }
        else
        {
            $request->session()->flash('error', __('Your have reached you client limit. Please upgrade your subscription to add more clients!'));
        }

        $url = redirect()->route('profile.edit', \Auth::user()->handle())->getTargetUrl().'/#subscription';
        return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
    }

    public function edit(Request $request, Lead $lead)
    {
        if(\Auth::user()->can('edit lead'))
        {
            $category_id = $request['category_id'];

            $stages  = Stage::where('class', Lead::class)
                            ->get()
                            ->pluck('name', 'id');

            $clients = \Auth::user()->companyClients()
                                ->get()
                                ->pluck('name', 'id');

            $categories = Category::where('class', Lead::class)
                                    ->get()
                                    ->pluck('name', 'id');

            if(isset($category_id) && !is_numeric($category_id)){

                $categories->prepend(json_decode('"\u271A '.$category_id.'"'), $category_id);
            }


            $client_id    = $lead->client_id;

            if($client_id)
            {
                if(is_numeric($client_id)) {

                    $contacts = Contact::contactsByUserType()
                                        ->where('client_id', '=', $client_id)
                                        ->get()
                                        ->pluck('name', 'id');
                }else{

                    //new client
                    $contacts = [];
                    $clients[$client_id] = json_decode('"\u271A '.$client_id.'"');
                }
            }else
            {
                $contacts = Contact::contactsByUserType()
                                    ->get()
                                    ->pluck('name', 'id');
            }

            return view('leads.edit', compact('category_id', 'stages', 'categories', 'lead', 'clients', 'contacts'));
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('You dont have the right to perform this operation!'));
        }
    }

    public function update(LeadUpdateRequest $request, Lead $lead)
    {
        if($request->ajax() && $request->isMethod('patch') && !isset($request['archived']))
        {
            return view('helpers.archive');
        }

        $post = $request->validated();

        $lead->updateLead($post);

        $request->session()->flash('success', __('Lead successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    public function destroy(LeadDestroyRequest $request, Lead $lead)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        $lead->delete();

        if(URL::previous() == route('leads.show', $lead)){

            return Redirect::to(route('leads.board'))->with('success', __('Lead successfully deleted.'));
        }

        return Redirect::to(URL::previous())->with('success', __('Lead successfully deleted.'));
    }

    public function show(Lead $lead)
    {
        $user = \Auth::user();

        if(\Auth::user()->can('view lead'))
        {
            clock()->startEvent('LeadsController', "Load lead");

            $events = $lead->events;
            $projects = $lead->projects;
            $activities = $lead->activities;
            $notes = $lead->notes;

            $files = [];
            foreach($lead->getMedia('leads') as $media)
            {
                $file = [];

                $file['file_name'] = $media->file_name;
                $file['size'] = $media->size;
                $file['download'] = route('leads.file.download',[$lead->id, $media->id]);
                $file['delete'] = route('leads.file.delete',[$lead->id, $media->id]);

                $files[] = $file;
            }

            $stages = Stage::where('class', Lead::class)
                                ->get()
                                ->pluck('id');


            $index = array_search($lead->stage_id, $stages->toArray());

            $progress = ($index + 1) * 100 / ($stages->count() - 1);

            clock()->endEvent('LeadsController');

            return view('leads.show', compact('lead', 'events', 'projects', 'notes', 'files', 'activities', 'progress'));
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('You dont have the right to perform this operation!'));
        }
    }

    public function order(Request $request)
    {
        $updated = false;
        $post  = $request->all();

        foreach($post['order'] as $key => $item)
        {
            $lead = Lead::find($item);

            $updated = $lead->updateOrder($post['stage_id'], $key) || $updated;
        }

        $return               = [];
        $return['is_success'] = $updated;
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

            return $this->edit($request, $lead);
        }

        return $this->create($request);
    }
}
