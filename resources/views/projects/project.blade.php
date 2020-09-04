@php
    $project->computeStatistics($last_stage->id);
@endphp

<div class="col-lg-6">
    <div class="card card-project">
        <div class="progress">
            <div class="progress-bar bg-{{Helpers::getProgressColor($project->progress)}}" role="progressbar" style="width: {{$project->progress}}%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
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
                            {{-- @can('view invite user')
                                <a class="dropdown-item" href="{{ route('projects.invite.create', $project->id) }}" data-remote="true" data-type="text">
                                    {{__('Add User')}}
                                </a>
                            @endcan --}}
                            <div class="dropdown-divider"></div>

                            @can('edit project')
                                @if(!$project->archived)
                                    <a class="dropdown-item text-danger" href="{{ route('projects.update', $project->id) }}" data-method="PATCH" data-remote="true" data-type="text">
                                        {{__('Archive')}}
                                    </a>
                                @else
                                    <a href="{{ route('projects.update', $project->id) }}" class="dropdown-item text-danger" data-params="archived=0" data-method="PATCH" data-remote="true" data-type="text">
                                        {{__('Restore')}}
                                    </a>

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
                @if(Gate::check('view project'))
                    <a href="{{ $project->enabled?route('projects.show', $project->id):'#' }}">
                        <h5 data-filter-by="text">{{ $project->name }}</h5>
                    </a>
                @else
                    <h5 data-filter-by="text">{{ $project->name }}</h5>
                @endif

                @if(!$project->archived)
                    <span class="badge badge-success">{{__('active')}}</span>
                @else
                    <span class="badge badge-light">{{__('archived')}}</span>
                @endif
            </div>
            <ul class="avatars">

                @foreach($project->users as $user)
                <li>
                        <a href="{{ $project->enabled?route('users.index', $user->id):'#' }}"  title="{{(!empty($user)?$user->name:'')}}">
                            {!!Helpers::buildUserAvatar($user)!!}
                        </a>
                </li>
                @endforeach
            </ul>
            <div class="card-meta d-flex justify-content-between">
                {{-- <div class="d-flex align-items-center"  title="{{__('Completed Tasks')}}">
                    <i class="material-icons mr-1">playlist_add_check</i>
                        <a  href="{{ $project->enabled?route('projects.show', $project->id):'#' }}">
                            {{$project->completed_tasks}}/{{$project->tasks->count()}}
                        </a>
                </div> --}}
                <div class="d-flex align-items-center"  title="{{__('Client')}}">
                    <i class="material-icons mr-1">business</i>
                    @if(Gate::check('view client') && !empty($project->client))
                        <a href="{{ $project->enabled?route('clients.show', $project->client->id):'#' }}" data-filter-by="text">
                            {{$project->client->name}}
                        </a>
                    @else
                        {{(!empty($project->client)?$project->client->name:'---')}}
                    @endif
                </div>
                {!!\Helpers::showDateForHumans($project->due_date)!!}
            </div>
        </div>
    </div>
</div>
