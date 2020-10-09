@php
use Carbon\Carbon;
$timesheets = $_user->timesheets;
$timesheet = $timesheets->first();
@endphp

<div class="timer-popup d-flex align-items-center">
    <a role="button" href="{{route('timesheets.timer')}}" data-timesheet="{{$timesheet?$timesheet->id:null}}" class="btn btn-round {{$timesheet&&$timesheet->isStarted()?'btn-danger fade-animation':'btn-primary'}} timer-entry" title="{{$timesheet&&$timesheet->isStarted()?__('Stop working'):__('Start working')}}">
        <i class="material-icons">{{$timesheet&&$timesheet->isStarted()?'stop':'play_arrow'}}</i>
    </a>
    <a href="#" role="button" class="pl-2 d-flex align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  title="{{$timesheet&&$timesheet->task?__('Timesheet: ').$timesheet->task->title:__('No title')}}">
        <span class="d-none d-lg-block text-muted text-light pr-2"><b>{{$timesheet&&$timesheet->task?$timesheet->task->title:__('No title')}}</b></span>
        <span class="active-timer text-muted text-center text-light dropdown-toggle mr-3" data-timesheet="{{$timesheet?$timesheet->id:null}}">{{$timesheet?$timesheet->formatTime():'00:00:00'}}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-right pre-scrollable" id="timer-menu" style="min-width: 350px">
        <div class="dropdown-header d-flex align-items-center justify-content-between">
            <h3>{{__('Timesheets')}}</h3>
            @can('create', 'App\Timesheet')
                <a role="button" href="{{route('timesheets.timer')}}" class="btn btn-primary btn-round timer-entry mb-2" title="{{__('Start new timesheet')}}" >
                    <i class="material-icons">add</i>
                </a>
            @endcan
        </div>
        @foreach ($timesheets as $key => $timesheet)
        @can('view', $timesheet)
        <a class="dropdown-item timer-entry {{$timesheet->isStarted()?'active':($key==0?'border border-primary':'')}}" href="{{route('timesheets.timer')}}" data-timesheet="{{$timesheet->id}}"  title="{{$timesheet->isStarted()?__('Stop this timesheet.'):__('Continue this timesheet.')}}">
            <div class="row align-items-center">
                <div class="col-4">
                    {!!Auth::user()->dateFormat($timesheet->date).'<br>['.$timesheet->formatTime().']<br>'!!}
                </div>
                <div class="col-8">
                    @if($timesheet->task)
                        {!!$timesheet->task->title.'<br>'.($timesheet->project?'<b>'.$timesheet->project->name.'</b>':'')!!}
                    @else
                        {!!__('No title').'<br>---</b>'!!}
                    @endif
                </div>
            </div>
        </a>
        @endcan
        @endforeach
    </div>
</div>
