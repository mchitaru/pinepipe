@extends('layouts.app')

@php
use Carbon\Carbon;
use App\Lead;

$dz_id = 'lead-files-dz';
$model = $lead;

@endphp

@push('stylesheets')
@endpush

@push('scripts')

<script>

$(function() {
    $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
        window.history.replaceState(null, null, $(e.target).attr('href'));
        window.location.hash = $(e.target).attr('href');

        var id = $(e.target).attr("href");
        sessionStorage.setItem('leads.tab', id);
    });

    var hash = window.location.hash ? window.location.hash : sessionStorage.getItem('leads.tab');

    if(hash == null) hash = '#events';

    $('a[data-toggle="tab"][href="' + hash + '"]').tab('show');

    initDropzone('#{{$dz_id}}', '{{route('leads.file.upload',[$lead->id])}}', '{{$lead->id}}', {!! json_encode($files) !!});

});

</script>

@endpush

@section('page-title')
    {{__('Lead Details')}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h1>{{$lead->name}}</h1>
                    <div class="pl-2">
                        <i class="material-icons mr-1">business</i>
                        <a href="{{ route('clients.show',$lead->client->id) }}"  data-title="{{__('Client')}}">
                            {{ (!empty($lead->client)?$lead->client->name:'') }}
                        </a>
                    </div>
                </div>
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <ul class="avatars">

                        <li>
                            <a href="{{route('collaborators')}}"  title="{{$lead->user->name}}">
                                {!!Helpers::buildUserAvatar($lead->user)!!}
                            </a>
                        </li>

                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons">expand_more</i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        @if(Gate::check('update', $lead) || Gate::check('delete', $lead))
                            @can('update', $lead)
                                <a class="dropdown-item" href="{{ route('leads.edit', $lead->id) }}" data-remote="true" data-type="text">
                                    {{__('Edit Lead')}}
                                </a>
                            @endcan
                            <div class="dropdown-divider"></div>
                            @can('update', $lead)
                                @if(!$lead->archived)
                                    <a class="dropdown-item text-danger" href="{{ route('leads.update', $lead->id) }}" data-method="PATCH" data-remote="true" data-type="text">
                                        {{__('Archive')}}
                                    </a>
                                @else
                                    <a href="{{ route('leads.update', $lead->id) }}" class="dropdown-item text-danger" data-params="archived=0" data-method="PATCH" data-remote="true" data-type="text">
                                        {{__('Restore')}}
                                    </a>
                                @endif
                            @endcan
                            @can('delete', $lead)
                                <a class="dropdown-item text-danger" href="{{ route('leads.destroy', $lead->id) }}" data-method="delete" data-remote="true" data-type="text">
                                    {{__('Delete')}}
                                </a>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <div class="d-flex align-items-center flex-row-reverse" title="{{__('Stage')}}">
                    <small class="badge badge-{{Helpers::getProgressColor($progress)}}" style="float:right;">{{$lead->stage->name}}</small>
                </div>
                <div class="progress mt-1">
                    <div class="progress-bar bg-{{Helpers::getProgressColor($progress)}}" style="width:{{$progress}}%;"></div>
                </div>
                <div class="d-flex justify-content-between text-small">
                    <div class="d-flex align-items-center"  title="{{__('Status')}}">
                        @if(!$lead->archived)
                            <span class="badge badge-success">{{__('active')}}</span>
                        @else
                            <span class="badge badge-light">{{__('archived')}}</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center"  title="{{__('Value')}}">
                        <span>{{ \Auth::user()->priceFormat($lead->price) }}</span>
                    </div>
                </div>
            </div>
            </div>

            <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#events" role="tab" aria-controls="events" aria-selected="true">{{__('Events')}}
                    @if(!$events->isEmpty())
                        <span class="badge badge-light bg-white">{{ $events->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#notes" role="tab" aria-controls="notes" aria-selected="false">{{__('Notes')}}
                    @if(!$notes->isEmpty())
                        <span class="badge badge-light bg-white">{{ $notes->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#lead-files" role="tab" aria-controls="lead-files" aria-selected="false">{{__('Files')}}
                    @if(!empty($files))
                        <span class="badge badge-light bg-white">{{ count($files) }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#projects" role="tab" aria-controls="projects" aria-selected="true">{{__('Projects')}}
                    @if(!$projects->isEmpty())
                        <span class="badge badge-light bg-white">{{ $projects->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false">{{__('Activity')}}</a>
            </li>
            </ul>

            <div class="tab-content">
            <div class="tab-pane fade show" id="events" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>{{__('Events')}}</h3>
                        @can('create', 'App\Event')
                            <a href="{{ route('events.create')  }}" class="btn btn-primary btn-round" data-params="lead_id={{$lead->id}}" data-remote="true" data-type="text" >
                                <i class="material-icons">add</i>
                            </a>
                        @endcan
                    </div>
                    <div class="col-md-auto">
                        <form>
                            <div class="input-group input-group-round">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="material-icons">filter_list</i>
                                </span>
                            </div>
                            <input type="search" class="form-control filter-list-input" placeholder="{{__("Filter events")}}" aria-label="Filter Events">
                            </div>
                        </form>
                    </div>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">@include('events.index')</div>
                <!--end of content list-->
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show" id="notes" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Notes')}}</h3>
                    <a href="{{ route('notes.create') }}" class="btn btn-primary btn-round" data-params="lead_id={{$lead->id}}" data-remote="true" data-type="text" >
                        <i class="material-icons">add</i>
                    </a>
                </div>
                <form class="col-md-auto">
                    <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="material-icons">filter_list</i>
                        </span>
                    </div>
                    <input type="search" class="form-control filter-list-input" placeholder="{{__("Filter notes")}}" aria-label="Filter notes">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">@include('notes.index')</div>
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show" id="lead-files" role="tabpanel" data-filter-list="dropzone-previews">
                <div class="content-list">
                <div class="row content-list-head">
                    <div class="col-auto">
                    <h3>{{__('Files')}}</h3>
                    </div>
                    <form class="col-md-auto">
                        <div class="input-group input-group-round">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">filter_list</i>
                            </span>
                            </div>
                            <input type="search" class="form-control filter-list-input" placeholder="{{__("Filter files")}}" aria-label="Filter Files">
                        </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body row">@include('files.index')</div>
                </div>
                <!--end of content list-->
            </div>
            <div class="tab-pane fade show" id="projects" role="tabpanel" data-filter-list="content-list-body">
                <div class="content-list">
                    <div class="row content-list-head">
                        <div class="col-auto">
                        <h3>{{__('Projects')}}</h3>
                        @can('create', 'App\Project')
                        <a href="{{ route('projects.create') }}" class="btn btn-primary btn-round" data-params="lead_id={{$lead->id}}" data-remote="true" data-type="text">
                            <i class="material-icons">add</i>
                        </a>
                        @endcan
                        </div>
                        <form class="col-md-auto">
                        <div class="input-group input-group-round">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">filter_list</i>
                            </span>
                            </div>
                            <input type="search" class="form-control filter-list-input" placeholder="{{__("Filter projects")}}" aria-label="Filter Projects">
                        </div>
                        </form>
                    </div>
                    <!--end of content list head-->
                    <div class="content-list-body row">@include('projects.index')</div>
                <!--end of content list body-->
                </div>
                <!--end of content list-->
            </div>
            <!--end of tab-->
            @if(\Auth::user()->type!='client')
            <div class="tab-pane fade" id="activity" role="tabpanel" data-filter-list="list-group-activity">
                <div class="content-list">
                <div class="row content-list-head">
                    <div class="col-auto">
                    <h3>{{__('Activity')}}</h3>
                    </div>
                    <form class="col-md-auto">
                    <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                        </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="{{__("Filter activity")}}" aria-label="Filter activity">
                    </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">@include('activity.index')</div>
                </div>
                <!--end of content list-->
            </div>
            @endif
            </div>
        </div>
    </div>
</div>
@endsection
