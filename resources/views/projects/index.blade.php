@php
use App\Http\Helpers;
@endphp

@foreach ($projects as $key=>$project)

@php
    $permissions=$project->client_project_permission();
    $perArr=(!empty($permissions)? explode(',',$permissions->permissions):[]);

    $project_last_stage = ($project->project_last_stage($project->id)? $project->project_last_stage($project->id)->id:'');

    $total_task = $project->project_total_task($project->id);
    $completed_task=$project->project_complete_task($project->id,$project_last_stage);
    
    $percentage=0;
    if($total_task!=0){
        $percentage = intval(($completed_task / $total_task) * 100);
    }

    $label='';
    if($percentage<=15){
        $label='bg-danger';
    }else if ($percentage > 15 && $percentage <= 33) {
        $label='bg-warning';
    } else if ($percentage > 33 && $percentage <= 70) {
        $label='bg-primary';
    } else {
        $label='bg-success';
    }

@endphp

    <div class="col-lg-6">
        <div class="card card-project">
            <div class="progress">
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{$percentage}}%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
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
                                <a class="dropdown-item text-danger disabled" href="#">{{__('Archive')}}</a>
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

                    <span class="badge badge-secondary">{{!$project->archived?__('Active'):__('Completed')}}</span>
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
                    <div class="d-flex align-items-center" data-toggle="tooltip" title="{{__('Completed Tasks')}}">
                        <i class="material-icons mr-1">playlist_add_check</i>
                            <a  href="{{ $project->enabled?route('projects.show', $project->id):'#' }}">
                                {{$completed_task}}/{{$total_task}}
                            </a>
                    </div>
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
                    <span class="text-small" data-filter-by="text">{{__('Due on ')}}
                        {{ \Auth::user()->dateFormat($project->due_date) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@endforeach
