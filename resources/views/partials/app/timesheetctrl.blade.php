<div class="timer-control d-flex align-items-center mr-auto">
    <a role="button" href="{{route('timesheets.timer')}}" data-task="{{$task?$task->id:null}}" data-timesheet="{{$timesheet?$timesheet->id:null}}" class="btn btn-round {{$timesheet&&$timesheet->isStarted()?'btn-danger fade-animation':'btn-primary'}} timer-entry" title="{{$timesheet&&$timesheet->isStarted()?__('Stop timer'):__('Start timer')}}">
        <i class="material-icons">{{$timesheet&&$timesheet->isStarted()?'stop':'play_arrow'}}</i>
    </a>
    <span class="active-timer pl-2" data-timesheet="{{$timesheet?$timesheet->id:null}}" title="{{__('Logged time')}}">{{$timesheet?$timesheet->formatTime():'00:00:00'}}</span>
</div>