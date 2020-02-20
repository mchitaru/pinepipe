@php clock()->startEvent('timesheets.index', "Display timesheets"); @endphp

@php
use App\Http\Helpers;
@endphp

@foreach($timesheets as $timesheet)

<div class="card card-task mb-1">
    <div class="card-body p-2" style="min-height: 77px;">
    <div class="card-title col-xs-12 col-sm-3">
        @can('edit timesheet')
        <a href="{{ route('projects.timesheet.edit',[$project->id,$timesheet->id]) }}" data-remote="true" data-type="text">
        @endcan
            <h6 data-filter-by="text">{{ Auth::user()->dateFormat($timesheet->date) }}</h6>
        @can('edit timesheet')
        </a>
        @endcan
        <p>
            <span class="text-small text-truncate" data-filter-by="text">{{ !empty($timesheet->task)?$timesheet->task->title : '---'}}</span>
        </p>
    </div>
    <div class="card-title col-xs-12 col-sm-2">
        <div class="container row align-items-center">
            <i class="material-icons">access_time</i>
            <span class="text-small" data-filter-by="text">{{ $timesheet->hours }}h</span>
        </div>
    </div>
    <div class="card-title col-xs-12 col-sm-3">
        <div class="container row align-items-center">
            <i class="material-icons">note</i>
            <span data-filter-by="text" title="{{ $timesheet->remark }}" class="text-small text-truncate" >{{ $timesheet->remark }}</span>
        </div>
    </div>
    <div class="card-meta">
        @if(!empty($timesheet->user))
        <a href="#" data-toggle="tooltip" title="{{!empty($timesheet->user)?$timesheet->user->name:''}}">
            {!!Helpers::buildAvatar($timesheet->user)!!}
        </a>
        @endif
        @if(\Auth::user()->type!='client')
            <div class="dropdown card-options">
            <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="material-icons">more_vert</i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                
                @can('edit timesheet')
                <a href="{{ route('projects.timesheet.edit',[$project->id,$timesheet->id]) }}" class="dropdown-item" data-remote="true" data-type="text">
                    {{__('Edit')}}
                </a>
                @endcan
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="#">Archive</a>
                @can('delete timesheet')
                <a class="dropdown-item text-danger" href="{{ route('projects.timesheet.destroy', [$project->id,$timesheet->id]) }}" data-method="delete" data-remote="true" data-type="text">
                    {{__('Delete')}}
                </a>
                @endcan
            </div>
            </div>
        @endif
    </div>
</div>
</div>

@endforeach

@php clock()->endEvent('timesheets.index'); @endphp
