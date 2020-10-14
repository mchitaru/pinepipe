@php clock()->startEvent('activities.index', "Display activities"); @endphp

<ol class="list-group list-group-activity">
    @foreach($activities as $activity)
    @can('view', $activity)
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
                <span data-filter-by="text"> <strong>{{$activity->user?$activity->user->name:__('Unknown')}}</strong> {!! $activity->getAction() !!} </span>
                <a href="{!! $activity->url !!}" {!! $activity->isModal()?'data-remote="true" data-type="text"':''!!}>{{$activity->value}}</a>
            </div>
            <span class="text-small" data-filter-by="text">{{$activity->created_at->diffforhumans()}}</span>
        </div>
        </div>
    </li>
    @endcan
    @endforeach
</ol>

@php clock()->endEvent('activities.index'); @endphp
