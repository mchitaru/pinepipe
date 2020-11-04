@php
use App\Project;
use Carbon\Carbon;
@endphp

<div wire:init="load" class="scrollable-list col" style="max-height:90vh">
    <div class="card-list">
        <div class="card-list-head">
            <div class="d-flex align-items-center">
                <div class="icon pr-2">
                    <i class="material-icons">phone</i>
                </div>
                <button class="btn-options" type="button" data-toggle="collapse" data-target="#leads">
                    {{__('You have')}} <span class="badge badge-{{count($items) ? 'warning' : 'light bg-white'}} mx-1">{{count($items)}}</span> {{__('lead(s) rotting.')}}
                </button>
            </div>
            <button class="btn-options" type="button" data-toggle="collapse" data-target="#leads">
                <i class="material-icons">expand_more</i>
            </button>
        </div>
        <div class="card-list-body collapse" id="leads">
            @foreach($items as $lead)
            @can('view', $lead)
                @include('leads.lead')
            @endcan
            @endforeach
        </div>
    </div>
</div>
