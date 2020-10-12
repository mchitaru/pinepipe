<div class="card card-task">
    <div class="container row align-items-center">
        <div class="pl-2 position-absolute">
        </div>
        <div class="card-body">
            <div class="card-title col-sm-4">
                @if($can_show_invoice)
                <a href="{{ route('invoices.show', $invoice->id) }}">
                @endif
                    <h6 data-filter-by="text">{{ $invoice->dateFormat($invoice->issue_date) }}
                        {!! $invoice->getStatusBadge() !!}
                    </h6>
                @if($can_show_invoice)
                </a>
                @endif
                <p>
                    {!!\Helpers::showDateForHumans($invoice->due_date)!!}
                </p>

            </div>
            <div class="card-title col-sm-2">
                <div class="container row align-items-center">
                    <span data-filter-by="text" class="text-small">{{ $invoice->number ? $invoice->number : Auth::user()->invoiceNumberFormat($invoice->increment) }}</span>
                </div>
            </div>
            <div class="card-title col-sm-4">
                <div class="container row align-items-center"  title="{{__('Project')}}">
                    <i class="material-icons">folder</i>
                    <span data-filter-by="text" class="text-truncate text-small">{{ $invoice->project->name }}</span>
                </div>
                <div class="container row align-items-center"  title="{{__('Client')}}">
                    <i class="material-icons">business</i>
                    <span data-filter-by="text" class="text-small text-truncate ">
                        @if(Gate::check('viewAny', 'App\Client') && !empty($invoice->project->client))
                        <a href="{{ route('clients.show', $invoice->project->client->id) }}" data-filter-by="text">
                            {{$invoice->project->client->name}}
                        </a>
                        @else
                            {{(!empty($invoice->project->client)?$invoice->project->client->name:'---')}}
                        @endif
                    </span>
                </div>
            </div>
            <div class="card-meta col">
                <div class="container row align-items-center">
                    <span data-filter-by="text" class="text-small">{{ $invoice->priceFormat($invoice->getTotal()) }}</span>
                </div>
            </div>
            @if(Gate::check('update', $invoice) || Gate::check('delete', $invoice))
            <div class="dropdown card-options">
                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    @can('update', $invoice)
                    <a class="dropdown-item" href="{{ route('invoices.edit',$invoice->id) }}" data-remote="true" data-type="text">
                        <span>{{__('Edit')}}</span>
                    </a>
                    @endcan
                    <div class="dropdown-divider"></div>
                    @can('delete', $invoice)
                        <a class="dropdown-item text-danger" href="{{ route('invoices.destroy', $invoice->id) }}" data-method="delete" data-remote="true" data-type="text">
                            <span>{{__('Delete')}}</span>
                        </a>
                    @endcan
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
