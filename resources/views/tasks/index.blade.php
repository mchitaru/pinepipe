@php clock()->startEvent('tasks.index', "Display tasks"); @endphp

@php
    use Carbon\Carbon;
    use App\Project;
    use App\TaskStage;

    $current_user=\Auth::user();
    $stage_done = TaskStage::where('created_by', '=', \Auth::user()->creatorId())->get()->last()->id;
@endphp

@foreach($stages as $key=>$stage)

    <div class="card-list">
    <div class="card-list-head ">
        <h6>{{$stage->name}} ({{ $stage->tasks->count() }})</h6>
        <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">more_vert</i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item disabled" href="#">{{__('Rename')}}</a>
            <a class="dropdown-item text-danger disabled" href="#">{{__('Archive')}}</a>
            </div>
        </div>
        </div>
        <div class="card-list-body">

        @foreach($stage->tasks as $key=>$task)
            @include('tasks.task')
        @endforeach

        </div>
    </div>
@endforeach

@php clock()->endEvent('tasks.index'); @endphp
