@php
use Carbon\Carbon;
@endphp

@php clock()->startEvent('events.index', "Display events"); @endphp

@foreach($events as $event)
<div class="card card-task">
    <div class="container row align-items-center">
        <div class="pl-2 position-absolute">
        </div>
        <div class="card-body">
            <div class="card-title col-xs-12 col-sm-3">
                @if(Gate::check('update', $event))
                <a href="{{ route('events.edit', $event->id) }}" data-remote="true" data-type="text">
                    <h6 data-filter-by="text">{{$event->name}}</h6>
                </a>
                @else
                    <h6 data-filter-by="text">{{$event->name}}</h6>
                @endif
                {!!\Helpers::showTimeForHumans($event->start)!!}
                {!!\Helpers::showTimespan($event->start, $event->end)!!}
            </div>
            <div class="card-title col-xs-12 col-sm-5">
            </div>
            <div class="card-meta col">
            </div>
            <div class="dropdown card-options">
                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    @can('update', $event)
                    <a class="dropdown-item" href="{{ route('events.edit', $event->id) }}" data-remote="true" data-type="text">
                        <span>{{__('Edit')}}</span>
                    </a>
                    @endcan
                    <div class="dropdown-divider"></div>
                    @can('delete', $event)
                        <a class="dropdown-item text-danger" href="{{ route('events.destroy', $event->id) }}" data-method="delete" data-remote="true" data-type="text">
                            <span>{{__('Delete')}}</span>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@if(method_exists($events,'links'))
{{ $events->links() }}
@endif

@php clock()->endEvent('events.index'); @endphp
