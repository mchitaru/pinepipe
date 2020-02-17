@php
use App\Http\Helpers;
use Carbon\Carbon;

$last_stage = \Auth::user()->last_projectstage();

@endphp

@php clock()->startEvent('projects.index', "Display projects"); @endphp

@foreach ($projects as $key=>$project)

@php

    $project->computeStatistics($last_stage->id);
    
@endphp

    <div class="col-lg-6">
        <div class="card card-project">
            <div class="progress">
                <div class="progress-bar {{Helpers::getProgressColor($project->progress)}}" role="progressbar" style="width: {{$project->progress}}%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            <div class="card-body">
                @if(Gate::check('edit project') || Gate::check('delete project') || Gate::check('create user'))
                        <div class="dropdown card-options">
                            @if($project->enabled)
                                <button class="btn-options" type="button" id="project-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                @can('edit project')
                                    <a class="dropdown-item" href="{{ route('projects.edit', $project->id) }}" data-remote="true" data-type="text">
                                        {{__('Edit')}}
                                    </a>
                                @endcan
                                @can('manage invite user')
                                    <a class="dropdown-item" href="{{ route('projects.invite.create', $project->id) }}" data-remote="true" data-type="text">
                                        {{__('Add User')}}
                                    </a>
                                @endcan
                                <div class="dropdown-divider"></div>

                                @can('edit project')
                                    @if(!$project->archived)
                                        <a class="dropdown-item text-danger" href="{{ route('projects.update', $project->id) }}" data-method="patch" data-remote="true" data-type="text">
                                            <span>{{__('Archive')}}</span>
                                        </a>
                                    @else
                                        {!! Form::open(['method' => 'PATCH', 'route' => ['projects.update', $project->id]]) !!}
                                        {!! Form::hidden('archived', 0) !!}
                                        {!! Form::submit(__('Restore'), array('class'=>'dropdown-item text-danger')) !!}
                                        {!! Form::close() !!}
                                    @endif
                                @endcan

                                @can('delete project')
                                    <a class="dropdown-item text-danger" href="{{ route('projects.destroy', $project->id) }}" data-method="delete" data-remote="true" data-type="text">
                                        {{__('Delete')}}
                                    </a>
                                @endcan
                                </div>
                            @else
                                <i class="material-icons">lock</i>
                            @endif
                        </div>
                @endif
                <div class="card-title d-flex justify-content-between align-items-center">
                    @can('show project')
                        <a href="{{ $project->enabled?route('projects.show', $project->id):'#' }}">
                    @endcan
                            <h5 data-filter-by="text">{{ $project->name }}</h5>
                    @can('show project')
                        </a>
                    @endcan

                    @if(!$project->archived)
                        <span class="badge badge-info">{{__('active')}}</span>
                    @else
                        <span class="badge badge-success">{{__('archived')}}</span>
                    @endif
                </div>
                <ul class="avatars">

                    @foreach($project->users as $user)
                    <li>
                            <a href="{{ $project->enabled?route('users.index', $user->id):'#' }}" data-toggle="tooltip" title="{{(!empty($user)?$user->name:'')}}">
                                {!!Helpers::buildAvatar($user)!!}
                            </a>
                    </li>
                    @endforeach
                </ul>
                <div class="card-meta d-flex justify-content-between">
                    {{-- <div class="d-flex align-items-center" data-toggle="tooltip" title="{{__('Completed Tasks')}}">
                        <i class="material-icons mr-1">playlist_add_check</i>
                            <a  href="{{ $project->enabled?route('projects.show', $project->id):'#' }}">
                                {{$project->completed_tasks}}/{{$project->tasks->count()}}
                            </a>
                    </div> --}}
                    <div class="d-flex align-items-center" data-toggle="tooltip" title="{{__('Client')}}">
                        <i class="material-icons mr-1">apartment</i>
                        @can('show client')
                            <a href="{{ $project->enabled?route('clients.show', $project->client->id):'#' }}" data-filter-by="text">
                        @endcan
                                {{(!empty($project->client)?$project->client->name:'---')}}
                        @can('show client')
                            </a>
                        @endcan
                    </div>
                    <span class="text-small {{($project->due_date<now())?'text-danger':''}}" data-filter-by="text">{{__('Due ')}}
                        {{ Carbon::parse($project->due_date)->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@endforeach

<div class="col-12">
    {{ $projects->links() }}
</div>

@php clock()->endEvent('projects.index'); @endphp
