@php
use App\Project;
use Carbon\Carbon;
$can_show_invoice = Gate::check('viewAny', 'App\Invoice');
@endphp

<div wire:init="load" class="scrollable-list col" style="max-height:90vh">
    <div class="card-list">
        <div class="card-list-head">
            <div class="d-flex align-items-center">
                <div class="icon pr-2">
                    <i class="material-icons">description</i>
                </div>
                <button class="btn-options" type="button" data-toggle="collapse" data-target="#invoices">
                    {{__('You have')}} <span class="badge badge-{{count($items) ? 'warning' : 'light bg-white'}} mx-1">{{count($items)}}</span> {{__('uncollected invoice(s).')}}
                </button>
            </div>
            <button class="btn-options" type="button" data-toggle="collapse" data-target="#invoices">
                <i class="material-icons">expand_more</i>
            </button>
        </div>
        <div class="card-list-body collapse" id="invoices">
            @foreach($items as $invoice)
            @can('view', $invoice)
                @include('invoices.item')
            @endcan
            @endforeach
        </div>
    </div>
</div>
