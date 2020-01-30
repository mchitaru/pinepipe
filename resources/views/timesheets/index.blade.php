@foreach($timesheets as $timesheet)

<div class="card card-task mb-1">
    <div class="card-body p-2" style="min-height: 77px;">
    <div class="card-title col-xs-12 col-sm-3">
        <h6 data-filter-by="text">{{ Auth::user()->dateFormat($timesheet->date) }}</h6>
        <p>
            <span class="text-small">{{ !empty($timesheet->task)?$timesheet->task->title : '---'}}</span>
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
            <span data-filter-by="text" title="{{ $timesheet->remark }}" class="text-small text-truncate" style="max-width: 150px;">{{ $timesheet->remark }}</span>
        </div>
    </div>
    <div class="card-meta">
        @if(!empty($timesheet->user))
        <a href="#" data-toggle="tooltip" title="{{!empty($timesheet->user)?$timesheet->user->name:''}}">
            <img alt="{{$timesheet->user->name}}" {!! empty($timesheet->user->avatar) ? "avatar='".$timesheet->user->name."'" : "" !!} class="avatar" src="{{Storage::url($timesheet->user->avatar)}}" data-filter-by="alt"/>
        </a>
        @endif
        @if(\Auth::user()->type!='client')
            <div class="dropdown card-options">
            <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="material-icons">more_vert</i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">

                <a href="{{ route('projects.timesheet.edit',[$project->id,$timesheet->id]) }}" class="dropdown-item" data-remote="true" data-type="text">
                    {{__('Edit')}}
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="#">Archive</a>
                <a class="dropdown-item text-danger" href="{{ route('projects.timesheet.destroy', [$project->id,$timesheet->id]) }}" data-method="delete" data-remote="true" data-type="text">
                    {{__('Delete')}}
                </a>
            </div>
            </div>
        @endif
    </div>
</div>
</div>

@endforeach
