@php
use App\Project;
use Carbon\Carbon;
$can_show_invoice = Gate::check('view invoice');
@endphp

<div class="scrollable-list col" style="max-height:90vh">
    <div class="card-list">
        <div class="card-list-head">
            <div class="d-flex align-items-center">
                <div class="icon pr-2">
                    <i class="material-icons">{{$icon}}</i>
                </div>
                {{__('You have')}} {{count($items)}} {{$text}}
            </div>
            <button class="btn-options" type="button" data-toggle="collapse" data-target="#{{$type}}">
                <i class="material-icons">more_horiz</i>
            </button>
        </div>
        <div class="card-list-body collapse" id="{{$type}}">
            @foreach($items as $invoice)
                @include('invoices.invoice')
            @endforeach
        </div>
    </div>
</div>
