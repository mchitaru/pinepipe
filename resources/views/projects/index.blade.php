@php
use Carbon\Carbon;

$last_stage = \Auth::user()->getLastTaskStage();
@endphp

@php clock()->startEvent('projects.index', "Display projects"); @endphp

@foreach ($projects as $key=>$project)
    @include('projects.project')
@endforeach

@if(!$projects->isEmpty() && method_exists($projects,'links'))
<div class="col-12">
    {{ $projects->links() }}
</div>
@endif

@php clock()->endEvent('projects.index'); @endphp
