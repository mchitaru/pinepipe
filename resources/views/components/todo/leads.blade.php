@php
use App\Project;
use Carbon\Carbon;
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
            @foreach($items as $item)

            <div class="card card-item">
                <div class="card-body p-2">
                    <div class="card-title">
                        <a href="{{ route('tasks.show', $item->id) }}" data-remote="true" data-type="text">
                            <h6 data-filter-by="text">{{$item->title}}</h6>
                        </a>
                        {!!\Helpers::showDateForHumans($item->due_date)!!}
                    </div>
                    <div class="card-meta float-right">
                        <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="item-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Mark as done</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Archive</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
