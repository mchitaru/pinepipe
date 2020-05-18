        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-11">
                    <p><strong>{{ Auth::user()->invoiceNumberFormat($invoice->invoice_id) }}</strong>
                        {{__('invoice')}}
                        <span class="d-print-none">{!! $invoice->getStatusBadge() !!}</span>
                    </p>
                </div>
                <div class="col-1 dropdown card-options d-print-none">
                    <button class="btn-options float-right" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('invoices.pdf', $invoice->id) }}">
                            <span>{{__('Download PDF')}}</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        @can('edit invoice')
                        <a class="dropdown-item" href="{{ route('invoices.items.create',$invoice->id) }}" data-remote="true" data-type="text">
                            <span>{{__('Add Item')}}</span>
                        </a>
                        @endcan
                        @can('edit invoice')
                        <a class="dropdown-item" href="{{ route('invoices.payments.create',$invoice->id) }}" data-remote="true" data-type="text">
                            <span>{{__('Add Payment')}}</span>
                        </a>
                        @endcan
                        @can('edit invoice')
                        <a class="dropdown-item" href="{{ route('invoices.edit',$invoice->id) }}" data-remote="true" data-type="text">
                            <span>{{__('Edit')}}</span>
                        </a>
                        @endcan
                        <a class="dropdown-item disabled" href="#">
                            <span>{{__('Save As Template')}}</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        @can('delete invoice')
                            <a class="dropdown-item text-danger" href="{{ route('invoices.destroy', $invoice->id) }}" data-method="delete" data-remote="true" data-type="text">
                                <span>{{'Delete'}}</span>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 p-4">
                        <div class="row">
                            <div class="col-12 text-center">
                                <address>
                                    <span class="row align-items-center justify-content-center">
                                        <img width="60" height="60" alt="{{$companyName}}" {!! !$companyLogo ? "avatar='".$companyName."'" : "" !!} class="rounded" src="{{$companyLogo?$companyLogo->getFullUrl():""}}" data-filter-by="alt"/>
                                    </span>
                                    <span class="row align-items-center justify-content-center">
                                        <h5>{{$client->name}}</h5>
                                    </span>
                                    <span class="row align-items-center justify-content-center">
                                        <i class="material-icons d-print-none" title="{{__('Project')}}">folder</i>
                                        {{$invoice->project->name }}
                                    </span>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <hr>
                    <div class="col-lg-12 p-4">
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <strong>{{__('From')}} : </strong><br>
                                @if($companySettings && $companyName)
                                {{$companyName}}<br>
                                {{$companySettings->address}}<br>
                                {{$companySettings->city}}, {{$companySettings->state}}-{{$companySettings->zipcode}}<br>
                                {{$companySettings->country}}<br>
                                @if(!empty($companySettings->tax))
                                {{__('TAX ID')}}: {{$companySettings->tax}}<br><br>
                                @endif
                                @else
                                <a href="{{ route('profile.edit', \Auth::user()->handle()) }}#company">
                                    <u>{{__('Edit Company Info')}}</u>
                                </a>
                                @endif
                            </div>
                            <div class="col-xs-12 col-md-6 text-right">
                                <strong>{{__('To')}}:</strong><br>
                                {{$client->name}}<br>
                                {{$client->address}}<br>
                                {{$client->email}}<br>
                                {{$client->phone}}<br>
                                @if(!empty($client->tax))
                                {{__('TAX ID')}}: {{$client->tax}}<br>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-6 text-left">
                                <strong>{{__('Issue Date')}}:</strong><br>
                                {{ AUth::user()->dateFormat($invoice->issue_date) }}<br>
                            </div>
                            <div class="col-xs-12 col-md-6 text-right">
                                <strong>{{__('Due Date')}}:</strong><br>
                                {{ AUth::user()->dateFormat($invoice->due_date) }}<br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <hr>
                    <div class="col p-4">
                        <div class="section-title"><b>{{__('Order Summary')}}</b>
                            @can('edit invoice')
                            <div class="col-md-12 text-right d-print-none">
                                <a href="{{ route('invoices.items.create',$invoice->id) }}" data-remote="true" data-type="text">
                                    <u>{{__('Add Item')}}</u>
                                </a>
                            </div>
                            @endcan
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th class="text-left">{{__('Item')}}</th>
                                        <th class="text-right">{{__('Quantity')}}</th>
                                        <th class="text-right">{{__('Price')}}</th>
                                        <th class="text-right">{{__('Total')}}</th>
                                        <th class="text-right d-print-none pl-0 pr-0"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i=0; @endphp

                                @foreach($invoice->items as $item)
                                    <tr>
                                        <td>
                                            {{++$i}}
                                        </td>
                                        <td class="text-left">
                                            <i>{{$item->text}}</i>
                                        </td>
                                        <td class="text-right">
                                            {{number_format($item->quantity, 2)}}
                                        </td>
                                        <td class="text-right">
                                            {{Auth::user()->priceFormat($item->price)}}
                                        </td>
                                        <td class="text-right">
                                            {{Auth::user()->priceFormat($item->quantity * $item->price)}}
                                        </td>
                                        @can('edit invoice')
                                        <td class="table-actions text-right d-print-none pl-0 pr-0">
                                            <div class="dropdown float-right">
                                                <button class="btn-options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="material-icons">more_vert</i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{ route('invoices.items.edit', [$invoice->id, $item->id]) }}" data-remote="true" data-type="text">
                                                        <span>{{__('Edit')}}</span>
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="{{ route('invoices.items.delete', [$invoice->id, $item->id]) }}" data-method="delete" data-remote="true" data-type="text">
                                                        <span>{{'Delete'}}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        @endcan
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot class="borderless">
                                    @php
                                        $subTotal = $invoice->getSubTotal();
                                        $tax = $invoice->getTax();
                                    @endphp
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-muted text-right"><span>{{__('Subtotal')}}</span></th>
                                        <th class="text-right"><span class="text-muted">{{Auth::user()->priceFormat($subTotal)}}</span></th>
                                        <th class="d-print-none"></th>
                                    </tr>
                                    @if($invoice->discount > 0)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-small text-right"><span>{{__('Discount')}}</span></td>
                                        <td class="text-small text-right"><span class="text-muted">{{$invoice->discount}}%</span></td>
                                        <td class="d-print-none"></td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-small text-right"><span>{{(!empty($invoice->tax)?$invoice->tax->name:'Tax')}} ({{(!empty($invoice->tax->rate)?$invoice->tax->rate:'0')}} %)</span></td>
                                        <td class="text-small text-right"><span class="text-muted">{{Auth::user()->priceFormat($tax)}}</span></td>
                                        <td class="d-print-none"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <th class="text-right"><span><h5>{{__('Total')}}</h5></span></th>
                                        <th class="text-right"><h5>{{Auth::user()->priceFormat($subTotal-$invoice->discount+$tax)}}</h5></th>
                                        <th class="d-print-none"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row d-print-none">
                    <hr>
                    <div class="col-md-12 p-4">
                        <div class="section-title"><b>{{__('Payment History')}}</b>
                            @can('edit invoice')
                            <div class="col-md-12 text-right d-print-none">
                                <a href="{{ route('invoices.payments.create',$invoice->id) }}" data-remote="true" data-type="text">
                                    <span><i class="fas fa-plus"></i></span>
                                    <u>{{__('Add Payment')}}</u>
                                </a>
                            </div>
                            @endcan
                        </div>
                        <div class="table-responsive">
                            <table class="table table-md table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{__('Transaction')}}</th>
                                        <th class="text-left">{{__('Date')}}</th>
                                        <th class="text-right">{{__('Method')}}</th>
                                        <th class="text-right">{{__('Amount')}}</th>
                                        <th class="text-right d-print-none pl-0 pr-0"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i=0; @endphp
                                @foreach($invoice->payments as $payment)
                                    <tr>
                                        <td>
                                            {{sprintf("%05d", $payment->transaction_id)}}
                                        </td>
                                        <td class="text-left">
                                            <i>{{ Auth::user()->dateFormat($payment->date) }}</i>
                                        </td>
                                        <td class="text-right">
                                            {{($payment->category?$payment->category->name:'')}}
                                        </td>
                                        <td class="text-right">
                                            {{Auth::user()->priceFormat($payment->amount)}}
                                        </td>
                                        @can('edit invoice')
                                        <td class="table-actions text-right pl-0 pr-0 d-print-none">
                                            <div class="dropdown float-right">
                                                <button class="btn-options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="material-icons">more_vert</i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{ route('invoices.payments.edit', [$invoice->id, $payment->id]) }}" data-remote="true" data-type="text">
                                                        <span>{{__('Edit')}}</span>
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="{{ route('invoices.payments.delete', [$invoice->id, $payment->id]) }}" data-method="delete" data-remote="true" data-type="text">
                                                        <span>{{'Delete'}}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        @endcan
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot class="borderless">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="text-muted text-right"><span>{{__('Total Due')}}</span></th>
                                        <th class="text-right"><span class="text-muted">{{Auth::user()->priceFormat($invoice->getDue())}}</span></th>
                                        </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>

                {{--                    <div class="text-right">--}}
                {{--                        <button class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Print</button>--}}
                {{--                    </div>--}}
        </div>
