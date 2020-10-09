@php
use Carbon\Carbon;
@endphp

@php clock()->startEvent('projects.index', "Display projects"); @endphp

@foreach ($projects as $key=>$project)
@can('view', $project)
    @include('projects.project')
@endcan
@endforeach

@if(!$projects->isEmpty() && method_exists($projects,'links'))
<div class="col-12">
    {{ $projects->links() }}
</div>
@endif

@php clock()->endEvent('projects.index'); @endphp
