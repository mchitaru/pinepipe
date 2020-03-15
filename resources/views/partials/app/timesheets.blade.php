@php
use Carbon\Carbon;
$user=\Auth::user();
$timesheet=$user?$user->getActiveTimesheet():null;
@endphp

<div class="dropdown float-right" id="timer-popup">
    <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" title="{{$timesheet?__('Started...'):__('Stopped')}}">
        <i class="material-icons {{$timesheet?'btn-fade':''}}">{{$timesheet?'timer':'timer_off'}}</i>
    </a>
    
    <div class="dropdown-menu pre-scrollable" id="timer-menu">
        <div class="dropdown-header d-flex justify-content-between">
            <h2 id="active-timer">{{$timesheet?$timesheet->formatTime():'00:00:00'}}</h2>
            @if($timesheet)
                <button type="button" href="{{route('timesheets.timer')}}" class="btn btn-round btn-danger btn-fade timer-entry" data-toggle="tooltip" title="{{__('Stop timer')}}">
                    <i class="material-icons">stop</i>
                </button>
            @else
                <button type="button" href="{{route('timesheets.timer')}}" class="btn btn-round btn-success timer-entry" data-toggle="tooltip" title="{{__('Start timer')}}">
                    <i class="material-icons">play_arrow</i>
                </button>
            @endif
        </div>

        @foreach ($user->timesheets()->orderBy('started_at', 'desc')->orderBy('updated_at', 'desc')->get() as $timesheet)
        <a class="dropdown-item timer-entry {{$timesheet->isStarted()?'active':''}}" href="{{route('timesheets.timer')}}" data-id="{{$timesheet->id}}" data-toggle="tooltip" title="{{__('Continue this timesheet.')}}">
            {!!'<u>'.Auth::user()->dateFormat($timesheet->date).'</u> - '.($timesheet->project?$timesheet->project->name:'---'.'('.$timesheet->formatTime().')')!!}
        </a>
        @endforeach                

    </div>    
</div>
