@php
use App\Project;
use Carbon\Carbon;
use App\TaskStage;

$current_user=\Auth::user();
$stage_done = TaskStage::where('created_by', '=', \Auth::user()->creatorId())->get()->last()->id;
@endphp

<div class="scrollable-list col" style="max-height:80vh">
    <div class="card-list">
        <div class="card-list-head">
            <div class="d-flex align-items-center">
                <div class="icon pr-2">
                    <i class="material-icons">{{$icon}}</i>
                </div>
                You have {{count($items)}} {{$text}}
            </div>
            <button class="btn-options" type="button" data-toggle="collapse" data-target="#{{$type}}">
                <i class="material-icons">more_horiz</i>
            </button>
        </div>
        <div class="card-list-body collapse" id="{{$type}}">
            @foreach($items as $task)
                @include('tasks.task')
            @endforeach
        </div>
    </div>
</div>
