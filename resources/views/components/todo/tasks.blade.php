@php
use App\Project;
use Carbon\Carbon;

$current_user=\Auth::user();
$stage_done = \Auth::user()->getLastTaskStage()->id;
@endphp

<div class="scrollable-list col" style="max-height:90vh">
    <div class="card-list">
        <div class="card-list-head">
            <div class="d-flex align-items-center">
                <div class="icon pr-2">
                    <i class="material-icons">{{$icon}}</i>
                </div>
                <button class="btn-options" type="button" data-toggle="collapse" data-target="#{{$type}}">
                    {{__('You have')}} <span class="badge badge-{{count($items) ? 'primary' : 'light'}} mx-1">{{count($items)}}</span> {{$text}}
                </button>
            </div>
            <button class="btn-options" type="button" data-toggle="collapse" data-target="#{{$type}}">
                <i class="material-icons">expand_more</i>
            </button>
        </div>
        <div class="card-list-body collapse" id="{{$type}}">
            @foreach($items as $task)
                @include('tasks.task')
            @endforeach
        </div>
    </div>
</div>
