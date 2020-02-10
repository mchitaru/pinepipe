@php clock()->startEvent('activities.index', "Display activities"); @endphp

<ol class="list-group list-group-activity">

    @foreach($activities as $activity)
    <li class="list-group-item">
        <div class="media align-items-center">
        <ul class="avatars">
            <li>
            <div class="avatar bg-primary">
                <i class="material-icons">playlist_add_check</i>
            </div>
            </li>
        </ul>
        <div class="media-body">
            <div>
            <span class="h6" data-filter-by="text">{{$activity->log_type}}</span>
            <span data-filter-by="text"> {!! $activity->remark !!}</span>
            </div>
            <span class="text-small" data-filter-by="text">{{$activity->created_at->diffforhumans()}}</span>
        </div>
        </div>
    </li>
    @endforeach

</ol>

@php clock()->endEvent('activities.index'); @endphp
