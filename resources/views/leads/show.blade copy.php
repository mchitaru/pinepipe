@extends('layouts.app')

@php
use Carbon\Carbon;
use App\Lead;

$current_user=\Auth::user();
$dz_id = 'lead-files-dz';

@endphp

@push('stylesheets')
@endpush

@push('scripts')

<script>
       
$(function() {

    initDropzone('#{{$dz_id}}', '{{route('leads.file.upload',[$lead->id])}}', '{{$lead->id}}', {!! json_encode($files) !!});

});

</script>

@endpush

@section('page-title')
    {{__('Lead Details')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ route('leads.board') }}">{{__('Leads')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{$lead->name}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
          <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            @if(Gate::check('edit lead') || Gate::check('delete lead'))

            @can('edit lead')
                <a class="dropdown-item" href="{{ route('leads.edit', $lead->id) }}" data-remote="true" data-type="text">
                    {{__('Edit Lead')}}
                </a>
            @endcan

            <div class="dropdown-divider"></div>
            
            @can('delete lead')
                <a class="dropdown-item text-danger" href="{{ route('leads.destroy', $lead->id) }}" data-method="delete" data-remote="true" data-type="text">
                    {{__('Delete')}}
                </a>
            @endcan

            @endif
        </div>
    </div>
</div>
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h1>{{$lead->name}}</h1>
                    <div class="pl-2">
                        <i class="material-icons mr-1">apartment</i>
                        <a href="{{ route('clients.show',$lead->client->id) }}" data-toggle="tooltip" data-title="{{__('Client')}}">
                            {{ (!empty($lead->client)?$lead->client->name:'') }}
                        </a>
                    </div>
                </div>
            <p class="lead">{{ $lead->notes }}</p>
            <div class="d-flex align-items-center">
                <ul class="avatars">

                    <li>
                        <a href="{{ route('users.index',$lead->user->id) }}" data-toggle="tooltip" title="{{$lead->user->name}}">
                            {!!Helpers::buildUserAvatar($lead->user)!!}
                        </a>
                    </li>

                </ul>
            </div>
            <div>
                <div class="progress">
                        <div class="progress-bar {{Helpers::getProgressColor($progress)}}" style="width:{{$progress}}%;"></div>
                </div>
                <div class="d-flex justify-content-between text-small">
                    <div class="d-flex align-items-center" data-toggle="tooltip" title="{{__('Stage')}}">
                        <span class="badge badge-info">{{$lead->stage->name}}</span>
                    </div>
                    <div class="d-flex align-items-center" data-toggle="tooltip" title="{{__('Value')}}">
                        <span>{{ \Auth::user()->priceFormat($lead->price) }}</span>
                    </div>
                </div>
            </div>
            </div>

            
            <div class="container">
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card-list">
                            <div class="card-list-head">
                            <h6>{{__('Upcoming Events')}}</h6>
                            <button class="btn-options" type="button" data-toggle="collapse" data-target="#events">
                                <i class="material-icons">more_horiz</i>
                            </button>
                            </div>
                            <div class="card-list-body collapse show" id="events">
                                @foreach($events as $event)
            
                                <div class="card card-task">
                                    <div class="card-body p-2">
                                        <div class="card-title m-0">
                                            <a href="{{ route('events.edit', $event->id) }}" data-remote="true" data-type="text">
                                                <h6 data-filter-by="text">{{$event->name}}</h6>
                                            </a>
                                            <span class="text-small {{($event->end && $event->end<now())?'text-danger':''}}">
                                                {{ Carbon::parse($event->end)->diffForHumans() }}
                                            </span>
                                        </div>
                                        {{-- <div class="card-title">
                                            <ul class="avatars">
            
                                                @foreach($top_task->users as $user)
                                                <li>
                                                    <a href="{{ route('users.index',$user->id) }}" data-toggle="tooltip" title="{{$user->name}}">
                                                        {!!Helpers::buildUserAvatar($user)!!}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
            
                                        </div> --}}
                                        <div class="card-meta float-right">
                                        <div class="dropdown card-options">
                                            <button class="btn-options" type="button" id="event-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="material-icons">more_vert</i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @can('delete event')
                                                    <a class="dropdown-item text-danger" href="{{ route('events.destroy', $event->id) }}" data-method="delete" data-remote="true" data-type="text">
                                                        <span>{{'Delete'}}</span>
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                @endforeach
                            </div>
                            <div class="card-list-footer">
                                <a href="{{ route('events.create') }}" class="btn btn-link btn-sm text-small" data-params="lead_id={{$lead->id}}" data-remote="true" data-type="text">
                                    {{__('Add Event')}}
                                </a>
                            </div>          
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card-list">
                            <div class="card-list-head">
                                <h6>{{__('Notes')}}</h6>
                                <button class="btn-options" type="button" data-toggle="collapse" data-target="#notes">
                                    <i class="material-icons">more_horiz</i>
                                </button>
                            </div>
                            <div class="card-list-body collapse show" id="notes">
                                @foreach($notes as $note)
                                <div class="card card-note">
                                <div class="card-header p-1">
                                    <div class="media align-items-center">
                                        {!!Helpers::buildUserAvatar($note->user)!!}
                                    <div class="media-body">
                                        <h6 class="mb-0" data-filter-by="text">{{$note->user->name}}</h6>
                                    </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                    <span data-filter-by="text">{{$note->created_at->diffForHumans()}}</span>
                                    <div class="ml-1 dropdown card-options">
                                        <button class="btn-options" type="button" id="note-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item disabled" href="#">{{__('Edit')}}</a>
                                            <a href="{{route('tasks.comment.destroy', [$task->id, $note->id])}}" class="dropdown-item text-danger" data-method="delete" data-remote="true">
                                                {{__('Delete')}}
                                            </a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="card-body p-1" data-filter-by="text">
                                    {{$note->comment}}
                                </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="card-list-footer">
                                <a href="{{ route('events.create') }}" class="btn btn-link btn-sm text-small" data-params="lead_id={{$lead->id}}" data-remote="true" data-type="text">
                                    {{__('Add Note')}}
                                </a>
                            </div>          
                        </div>
                    </div>
                    <div class="col-lg-6 col-xs-12 col-sm-12">
                        <div class="card card-info">
                            <div class="card-body">
                                <div class="card-title">
                                    {{__('Files')}}
                                </div>
                                <div class="content-list-body row">@include('files.index')</div>
                            </div>
                        </div>        
                    </div>  
                    <div class="col-lg-6 col-xs-12 col-sm-12">
                        <div class="card card-info">
                            <div class="card-body">
                                <div class="card-title">
                                    {{__('Activity')}} 
                                </div>
                                <ol class="timeline small">
                                    @foreach($activities as $activity)
                                    <li>
                                        <div class="media align-items-center">
                                            <div class="media-body">
                                                <div>
                                                    <span data-filter-by="text"> <strong>{{$activity->user->name}}</strong> {!! $activity->getAction() !!} </span>
                                                    <a href="{!! $activity->url !!}" {!! $activity->isModal()?'data-remote="true" data-type="text"':''!!}>{{$activity->value}}</a>
                                                </div>
                                                <span class="text-small" data-filter-by="text">{{$activity->created_at->diffforhumans()}}</span>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>                            
                </div>                            
            </div>            
        </div>
    </div>
</div>
@endsection
