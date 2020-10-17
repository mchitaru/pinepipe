@php clock()->startEvent('tasks.index', "Display tasks"); @endphp

@php
    use Carbon\Carbon;
    use App\Project;
@endphp

@foreach($stages as $key=>$stage)
    <div class="card-list">
    <div class="card-list-head ">
        <h6>{{$stage->name}} ({{ $stage->tasks->count() }})</h6>
        @can('update', $stage)
            <div class="dropdown">
                <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    @can('update', $stage)
                        <a class="dropdown-item" href="{{ route('stages.edit',$stage->id) }}" data-remote="true" data-type="text">
                            <span>{{__('Edit')}}</span>
                        </a>
                    @endcan
                    @can('delete', $stage)
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ route('stages.destroy',$stage->id) }}" data-method="delete" data-remote="true" data-type="text">
                            <span>{{__('Delete')}}</span>
                        </a>
                    @endcan
                </div>
            </div>
        @endcan
        </div>
        <div class="card-list-body">
            @foreach($stage->tasks as $key=>$task)
            @can('view', $task)
                @include('tasks.task')
            @endcan
            @endforeach
        </div>
    </div>
@endforeach

@php clock()->endEvent('tasks.index'); @endphp
