<div class="card card-task mb-1" style="min-height: 77px;">
    <div class="container row align-items-center">
        <div class="pl-2 position-absolute">
        </div>
        <div class="card-body p-2">
            <div class="card-title col-sm-3">
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
                    {!!\Helpers::showDateForHumans($invoice->due_date, __('Due'))!!}
                </p>

            </div>
            <div class="card-title col-sm-2">
                <div class="container row align-items-center">
                    <span data-filter-by="text" class="text-small">{{ Auth::user()->invoiceNumberFormat($invoice->id) }}</span>
                </div>
            </div>
            <div class="card-title col-sm-5">
                <div class="container row align-items-center" data-toggle="tooltip" title="{{__('Project')}}">
                    <i class="material-icons">folder</i>
                    <span data-filter-by="text" class="text-truncate text-small">{{ $invoice->project->name }}</span>
                </div>
                <div class="container row align-items-center" data-toggle="tooltip" title="{{__('Client')}}">
                    <i class="material-icons">apartment</i>
                    <span data-filter-by="text" class="text-small text-truncate ">
                        @if(Gate::check('show client'))
                        <a href="{{ route('clients.show', $invoice->project->client->id) }}" data-filter-by="text">
                            {{$invoice->project->client->name}}
                        </a>
                        @else
                            {{$invoice->project->client->name}}
                        @endif
                    </span>
                </div>
            </div>
            <div class="card-meta col">
                <div class="container row align-items-center">
                    <span data-filter-by="text" class="text-small">{{ Auth::user()->priceFormat($invoice->getTotal()) }}</span>
                </div>
            </div>
            @if(Gate::check('edit invoice') || Gate::check('delete invoice'))
            <div class="dropdown card-options">
                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    @can('edit invoice')
                    <a class="dropdown-item" href="{{ route('invoices.edit',$invoice->id) }}" data-remote="true" data-type="text">
                        <span>{{__('Edit')}}</span>
                    </a>
                    @endcan
                    <div class="dropdown-divider"></div>
                    @can('delete invoice')
                        <a class="dropdown-item text-danger" href="{{ route('invoices.destroy', $invoice->id) }}" data-method="delete" data-remote="true" data-type="text">
                            <span>{{'Delete'}}</span>
                        </a>
                    @endcan
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
