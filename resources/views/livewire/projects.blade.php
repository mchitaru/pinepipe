@php
use App\Project;
use Carbon\Carbon;
@endphp

<div class="scrollable-list col" style="max-height:90vh">
    <div class="card-list">
        <div class="card-list-head">
            <div class="d-flex align-items-center">
                <div class="icon pr-2">
                    <i class="material-icons">{{$icon}}</i>
                </div>
                <button class="btn-options" type="button" data-toggle="collapse" data-target="#projects">
                    {{__('You have')}} <span class="badge badge-{{count($items) ? 'warning' : 'light bg-white'}} mx-1">{{count($items)}}</span> {{$text}}
                </button>
            </div>
            <button class="btn-options" type="button" data-toggle="collapse" data-target="#projects">
                <i class="material-icons">expand_more</i>
            </button>
        </div>
        <div class="card-list-body collapse" id="projects">
            @foreach($items as $project)

            @php
                $project->computeStatistics();
            @endphp

            <div class="card card-task">
                <div class="progress">
                    <div class="progress-bar bg-{{Helpers::getProgressColor($project->progress)}}" role="progressbar" style="width: {{$project->progress}}%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div class="card-body">
                    <div class="card-title col-xs-12 col-sm-4">

                        @if(Gate::check('view', $project))
                            <a href="{{ $project->enabled?route('projects.show', $project->id):'#' }}">
                                <h6 data-filter-by="text">{{ $project->name }}</h6>
                            </a>
                        @else
                            <h5 data-filter-by="text">{{ $project->name }}</h5>
                        @endif

                        {!!\Helpers::showDateForHumans($project->due_date)!!}
                    </div>

                    <div class="card-title d-none d-xl-block col-xs-12 col-sm-4">
                        <div class="row align-items-center"  title="{{__('Client')}}">
                            <i class="material-icons mr-1">business</i>
                            @if(Gate::check('viewAny', 'App\Client') && $project->client)
                                <a href="{{ $project->enabled?route('clients.show', $project->client->id):'#' }}" data-filter-by="text">
                                    {{($project->client->name)}}
                                </a>
                            @else
                            ---
                            @endif
                        </div>
                    </div>
                    <div class="card-title col-xs-12 col-sm-3 text-right">
                        <ul class="avatars">

                            @foreach($project->users as $user)
                            <li>
                                    <a href="{{ $project->enabled?route('users.index', $user->id):'#' }}"  title="{{(!empty($user)?$user->name:'')}}">
                                        {!!Helpers::buildUserAvatar($user)!!}
                                    </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="card-meta d-flex justify-content-between">
                        @if(Gate::check('update', $project) || Gate::check('delete', $project) || Gate::check('create', 'App\User'))
                        <div class="dropdown card-options">
                            @if($project->enabled)
                                <button class="btn-options" type="button" id="project-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                @can('update', $project)
                                    <a class="dropdown-item" href="{{ route('projects.edit', $project->id) }}" data-remote="true" data-type="text">
                                        {{__('Edit')}}
                                    </a>
                                @endcan
                                @can('view invite user')
                                    <a class="dropdown-item" href="{{ route('projects.invite.create', $project->id) }}" data-remote="true" data-type="text">
                                        {{__('Add User')}}
                                    </a>
                                @endcan
                                <div class="dropdown-divider"></div>

                                @can('update', $project)
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

                                @can('delete', $project)
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
                    </div>
                </div>
            </div>
                    {{-- <div class="card card-item">
                <div class="card-body p-2">
                    <div class="card-title">
                        <a href="{{ route('projects.show', $item->id) }}">
                            <h6 data-filter-by="text">{{$item->name}}</h6>
                        </a>
                        {!!\Helpers::showDateForHumans($item->due_date)!!}
                    </div>
                    <div class="card-meta float-right">
                        <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="item-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Mark as done</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Archive</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            @endforeach
        </div>
    </div>
</div>