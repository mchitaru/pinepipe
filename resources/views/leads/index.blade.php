@php
use Carbon\Carbon;
@endphp

@php clock()->startEvent('leads.index', "Display leads"); @endphp

@foreach($leads as $lead)
@can('view', $lead)
@include('leads.lead')
@endcan
@endforeach

@if(method_exists($leads,'links'))
 {{ $leads->fragment('leads')->links() }}
@endif

@php clock()->endEvent('leads.index'); @endphp
