@php clock()->startEvent('timesheets.index', "Display timesheets"); @endphp

@foreach($timesheets as $timesheet)
@can('view', $timesheet)
<div class="card card-task">
    <div class="container row align-items-center">
        <div class="card-body">
            <div class="card-title col-xs-12 col-sm-4">
                @if(Gate::check('update', $timesheet))
                <a href="{{ route('timesheets.edit',$timesheet->id) }}" data-remote="true" data-type="text">
                    <h6 data-filter-by="text">{{ Auth::user()->dateFormat($timesheet->date) }}</h6>
                </a>
                @else
                    <h6 data-filter-by="text">{{ Auth::user()->dateFormat($timesheet->date) }}</h6>
                @endif
                <p>
                    <div class="text-small text-truncate" style="max-width: 210px;" data-filter-by="text" title="{{ !empty($timesheet->task)?$timesheet->task->title : ''}}">{{ !empty($timesheet->task)?$timesheet->task->title : '---'}}
                    </div>
                </p>
            </div>
            <div class="card-title col-xs-12 col-sm-2">
                <div class="container row align-items-center">
                    <i class="material-icons">access_time</i>
                    <span class="text-small" data-filter-by="text">{{ $timesheet->formatTime() }}</span>
                </div>
            </div>
            <div class="card-title col-xs-12 col-sm-4">        
                <div class="row pl-2 align-items-center">
                    <i class="material-icons">folder</i>
                    @if($timesheet->project)
                        <a href="{{route('projects.show',$timesheet->project->id)}}" title="{{__('Project')}}">
                            <span data-filter-by="text" class="text-small text-truncate" style="max-width: 210px;">{{$timesheet->project->name}}</span>
                        </a>
                    @else
                        <span data-filter-by="text" class="text-small">---</span>
                    @endif
                </div>
            </div>
            <div class="card-title col-xs-12 col-sm-1">
                @if(!empty($timesheet->user))
                <a href="{{route('collaborators')}}" class="float-right" title="{{!empty($timesheet->user)?$timesheet->user->name:''}}">
                    {!!Helpers::buildUserAvatar($timesheet->user)!!}
                </a>
                @endif
            </div>
            <div class="card-meta col">
                @can('update', $timesheet)
                    <div class="dropdown card-options">
                    <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        @can('update', $timesheet)
                            <a href="{{ route('timesheets.edit', $timesheet->id) }}" class="dropdown-item" data-remote="true" data-type="text">
                                {{__('Edit')}}
                            </a>
                        @endcan
                        @can('delete', $timesheet)
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="{{ route('timesheets.destroy', $timesheet->id) }}" data-method="delete" data-remote="true" data-type="text">
                                {{__('Delete')}}
                            </a>
                        @endcan
                    </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endcan
@endforeach

@php clock()->endEvent('timesheets.index'); @endphp
