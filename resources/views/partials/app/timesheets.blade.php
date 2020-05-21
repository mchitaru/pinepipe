@php
use Carbon\Carbon;
@endphp

<div class="timer-popup d-flex align-items-center">
    <a role="button" href="{{route('timesheets.timer')}}" data-timesheet="{{$timesheet?$timesheet->id:null}}" class="btn btn-round {{$timesheet&&$timesheet->isStarted()?'btn-danger fade-animation':'btn-primary'}} timer-entry" title="{{$timesheet&&$timesheet->isStarted()?__('Stop timer'):__('Start timer')}}">
        <i class="material-icons">{{$timesheet&&$timesheet->isStarted()?'stop':'play_arrow'}}</i>
    </a>
    <a href="#" role="button" class="pl-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" title="{{$timesheet->task?$timesheet->task->title:__('Select timesheet')}}">
        <span class="active-timer text-light dropdown-toggle">{{$timesheet?$timesheet->formatTime():'00:00:00'}}</span>
    </a>
    <div class="dropdown-menu pre-scrollable" id="timer-menu">
        <div class="dropdown-header d-flex align-items-center justify-content-between">
            <h3>{{__('Timesheets')}}</h3>
            @can('create timesheet')
                <a role="button" href="{{route('timesheets.timer')}}" class="btn btn-round timer-entry" title="{{__('Start new timesheet')}}" >
                    <i class="material-icons">add</i>
                </a>
            @endcan
        </div>
        @foreach ($timesheets as $key => $timesheet)
        <a class="dropdown-item timer-entry d-flex align-items-center {{$timesheet->isStarted()?'active fade-animation':($key==0?'border border-primary':'')}}" href="{{route('timesheets.timer')}}" data-timesheet="{{$timesheet->id}}" data-toggle="tooltip" title="{{$timesheet->isStarted()?__('Stop this timesheet.'):__('Continue this timesheet.')}}">
            @if($timesheet->isStarted())
                <i class="material-icons">stop</i>
            @else
                <i class="item-options material-icons">play_arrow</i>
            @endif
            {!!'<u>'.Auth::user()->dateFormat($timesheet->date).' ['.$timesheet->formatTime().']</u> - '.
                ($timesheet->project?'<b>'.$timesheet->project->name.'</b>':'').
                ($timesheet->task?'-'.$timesheet->task->title:'')!!}
        </a>
        @endforeach                
    </div>    
</div>