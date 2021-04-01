@php
    $progress = $invoice->getTotal()?($invoice->getPaid()/$invoice->getTotal())*100.0:0;
@endphp

<div class="card card-task">
    <div class="progress">
        <div class="progress-bar bg-{{Helpers::getProgressColor($progress)}}" role="progressbar" style="width: {{$progress}}%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="container row align-items-center">
        <div class="card-body">
            <div class="card-title col-sm-4">
                @if($can_show_invoice)
                <a href="{{ route('invoices.show', $invoice->id) }}">
                @endif
                    <h6 data-filter-by="text">{{ Auth::user()->dateFormat($invoice->issue_date) }}
                        {!! $invoice->getStatusBadge() !!}
                    </h6>
                @if($can_show_invoice)
                </a>
                @endif
                <p>
                    {!!\Helpers::showDateForHumans($invoice->due_date)!!}
                </p>

            </div>
            <div class="card-title col-sm-1">
                <div class="container row align-items-center">
                    <span data-filter-by="text" class="text-small">{{ $invoice->number ? $invoice->number : Auth::user()->invoiceNumberFormat($invoice->increment) }}</span>
                </div>
            </div>
            <div class="card-title col-sm-4">
                @if($invoice->project)
                <div class="container row align-items-center"  title="{{__('Project')}}">
                    <i class="material-icons">folder</i>
                    <span data-filter-by="text" class="text-truncate text-small" style="max-width: 200px;">{{ $invoice->project->name }}</span>
                </div>
                @endif
                <div class="container row align-items-center"  title="{{__('Client')}}">
                    <i class="material-icons">business</i>
                    <span data-filter-by="text" class="text-small text-truncate " style="max-width: 200px;">
                        @if(Gate::check('viewAny', 'App\Client') && !empty($invoice->client))
                        <a href="{{ route('clients.show', $invoice->client->id) }}" data-filter-by="text">
                            {{$invoice->client->name}}
                        </a>
                        @else
                            {{(!empty($invoice->client)?$invoice->client->name:'---')}}
                        @endif
                    </span>
                </div>
            </div>
            <div class="card-title col-sm-1">
                <div class="container row align-items-center">
                    <span data-filter-by="text" class="text-small">{{ $invoice->priceFormat($invoice->getTotal()) }}</span>
                </div>
            </div>
            @can('update', $invoice)
            <div class="dropdown card-options">
                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    @can('update', $invoice)
                    <a class="dropdown-item {{$invoice->payments->isEmpty()?'':'disabled'}}" href="{{ route('invoices.edit',$invoice->id) }}" data-remote="true" data-type="text">
                        <span>{{__('Edit')}}</span>
                    </a>
                    @endcan
                    @can('delete', $invoice)
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ route('invoices.destroy', $invoice->id) }}" data-method="delete" data-remote="true" data-type="text">
                            <span>{{__('Delete')}}</span>
                        </a>
                    @endcan
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
