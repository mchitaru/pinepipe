<div class="card-header">
    <div class="align-items-center">
        <div class="float-left">
            <p>{{__('Invoice', [], $invoice->locale)}} <strong>{{ $invoice->number ? $invoice->number : Auth::user()->invoiceNumberFormat($invoice->increment) }}</strong>                        
                <span class="d-print-none">{!! $invoice->getStatusBadge() !!}</span>
                @if($invoice->currency && ($invoice->currency != \Auth::user()->getCurrency()))
                <span class="pl-2 text-small d-print-none">({!! $invoice->priceFormat(1.0).' = '.\Auth::user()->priceFormat(\Helpers::priceConvert(1.0, 1.0/$invoice->rate, 4), 4) !!})</span>
                @endif
            </p>
        </div>
        <div class="float-right dropdown card-options d-print-none">
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
                <a class="dropdown-item" href="{{ route('invoices.payments.create',$invoice->id) }}" data-remote="true" data-type="text">
                    <span>{{__('Add Payment')}}</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('invoices.edit',$invoice->id) }}" data-remote="true" data-type="text">
                    <span>{{__('Edit Invoice')}}</span>
                </a>
                @endcan
                <a class="dropdown-item disabled" href="#">
                    <span>{{__('Save As Template')}}</span>
                </a>
                <div class="dropdown-divider"></div>
                @can('delete invoice')
                    <a class="dropdown-item text-danger" href="{{ route('invoices.destroy', $invoice->id) }}" data-method="delete" data-remote="true" data-type="text">
                        <span>{{__('Delete')}}</span>
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>
<div class="card-body">
    <div class="p-4">
        <div class="text-center">
            <address>
                <span class="align-items-center justify-content-center">
                    <img width=60 height=60 alt="{{$companyName}}" {!! !$companyLogo ? "avatar='".$companyName."'" : "" !!} class="rounded" src="{{$companyLogo?$companyLogo->getFullUrl('thumb'):""}}" data-filter-by="alt"/>
                </span><br><br>
                <span class="align-items-center justify-content-center" style="vertical-align: middle;">
                    <img alt="{{__('Project', [], $invoice->locale)}}" width=24 src="{{ asset('assets/img/folder.svg') }}" />
                    {{$invoice->project->name }}
                </span>
            </address>
        </div>
    </div>
    <hr style="border-width:1px;border-style:dotted;">
    <div class="pl-4 pr-4">
        <div class="table-responsive">
            <table class="table">
                <tfoot class="borderless gapless">
                    <tr>
                        <td class="text-left"><strong>{{__('From', [], $invoice->locale)}} : </strong></td>
                        <td class="text-right"><strong>{{__('To', [], $invoice->locale)}}:</strong></td>
                    </tr>
                    <tr>
                        @if($companySettings && $companyName)
                            <td class="text-left">{{$companyName}}</td>
                        @else
                            <td class="text-left"><a href="{{ route('profile.edit') }}#company"><u>{{__('Edit Company Info')}}</u></a></td>
                        @endif

                        <td class="text-right">{{$client->name}}</td>
                    </tr>
                    <tr>
                        <td class="text-left">{{$companySettings?$companySettings->address:''}}</td>
                        <td class="text-right">{{$client->address}}</td>
                    </tr>
                    <tr>
                        <td class="text-left">{{($companySettings&&$companySettings->city)?($companySettings->city.', '.$companySettings->state.' - '.$companySettings->zipcode):''}}</td>
                        <td class="text-right">{{$client->email}}</td>
                    </tr>
                    <tr>
                        <td class="text-left">{{$companySettings?$companySettings->country:''}}</td>
                        <td class="text-right">{{$client->phone}}</td>
                    </tr>
                    <tr>
                        <td class="text-left">{{($companySettings&&$companySettings->tax)?(__('TAX ID', [], $invoice->locale).': '.$companySettings->tax):''}}</td>
                        <td class="text-right">{{$client->tax?(__('TAX ID', [], $invoice->locale).': '.$client->tax):''}}</td>
                    </tr>
                    <tr>
                        <td class="text-left">{{($companySettings&&$companySettings->registration)?(__('Registration ID', [], $invoice->locale).': '.$companySettings->registration):''}}</td>
                        <td class="text-right">{{$client->registration?(__('Registration ID', [], $invoice->locale).': '.$client->registration):''}}</td>
                    </tr>
                    <tr>
                        <td class="text-left">{{($companySettings&&$companySettings->bank)?(__('Bank', [], $invoice->locale).': '.$companySettings->bank):''}}</td>
                    </tr>
                    <tr>
                        <td class="text-left">{{($companySettings&&$companySettings->iban)?(__('IBAN', [], $invoice->locale).': '.$companySettings->iban):''}}</td>
                    </tr>
                </tfoot>
            </table>
            <table class="table">
                <tfoot class="borderless gapless">
                    <tr>
                        <td class="text-left"><strong>{{__('Issue Date', [], $invoice->locale)}}:</strong></td>
                        <td class="text-right"><strong>{{__('Due Date', [], $invoice->locale)}}:</strong></td>
                    </tr>
                    <tr>
                        <td class="text-left">{{ $invoice->dateFormat($invoice->issue_date) }}</td>
                        <td class="text-right">{{ $invoice->dateFormat($invoice->due_date) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <hr class="d-print-none" style="border-width:1px;border-style:dotted;">
    <div class="pl-4 pr-4">
        <div class="section-title d-print-none"><b>{{__('Order Summary', [], $invoice->locale)}}</b>
            @can('edit invoice')
            <div class="text-right d-print-none">
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
                        <th class="text-left">{{__('Item name', [], $invoice->locale)}}</th>
                        <th class="text-right">{{__('Quantity', [], $invoice->locale)}}</th>
                        <th class="text-right">{{__('Unit price', [], $invoice->locale)}}</th>
                        <th class="text-right">{{__('Total', [], $invoice->locale)}}</th>
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
                            {{$item->text}}
                        </td>
                        <td class="text-right">
                            {{number_format($item->quantity, 3, '.', '')}}
                        </td>
                        <td class="text-right">
                            {!! htmlentities($invoice->priceFormat($item->price), ENT_COMPAT, 'UTF-8') !!}
                        </td>
                        <td class="text-right">
                            {!! htmlentities($invoice->priceFormat($item->quantity * $item->price), ENT_COMPAT, 'UTF-8') !!}
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
                                        <span>{{__('Delete')}}</span>
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
                        <th class="text-muted text-right"><span>{{__('Subtotal', [], $invoice->locale)}}</span></th>
                        <th class="text-right"><span class="text-muted">{!! htmlentities($invoice->priceFormat($subTotal), ENT_COMPAT, 'UTF-8') !!}</span></th>
                        <th class="d-print-none"></th>
                    </tr>
                    @if($invoice->discount > 0)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-small text-right"><span>{{__('Discount', [], $invoice->locale)}}</span></td>
                        <td class="text-small text-right"><span class="text-muted">{{$invoice->discount}}%</span></td>
                        <td class="d-print-none"></td>
                    </tr>
                    @endif
                    @if(!empty($invoice->tax))
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-small text-right"><span>{{__('Tax', [], $invoice->locale)}} ({{(!empty($invoice->tax->rate)?$invoice->tax->rate:'0')}}%)</span></td>
                        <td class="text-small text-right"><span class="text-muted">{!! htmlentities($invoice->priceFormat($tax), ENT_COMPAT, 'UTF-8') !!}</span></td>
                        <td class="d-print-none"></td>
                    </tr>
                    @endif
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <th class="text-right"><span><h5>{{__('Total', [], $invoice->locale)}}</h5></span></th>
                        <th class="text-right"><h5>{!! htmlentities($invoice->priceFormat($subTotal-$invoice->discount+$tax), ENT_COMPAT, 'UTF-8') !!}</h5></th>
                        <th class="d-print-none"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="d-print-none" style="color:#C1C1C1">
        <hr style="border-width:1px;border-style:dotted;">
        <div class="pl-4 pr-4">
            <div class="section-title"><b>{{__('Payment History', [], $invoice->locale)}}</b>
                @can('edit invoice')
                <div class="text-right d-print-none">
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
                            <th style="color:#C1C1C1">{{__('No.', [], $invoice->locale)}}</th>
                            <th class="text-left" style="color:#C1C1C1">{{__('Date', [], $invoice->locale)}}</th>
                            <th class="text-right" style="color:#C1C1C1">{{__('Method', [], $invoice->locale)}}</th>
                            <th class="text-right" style="color:#C1C1C1">{{__('Amount', [], $invoice->locale)}}</th>
                            <th class="text-right d-print-none pl-0 pr-0"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $i=0; @endphp
                    @foreach($invoice->payments as $payment)
                        <tr style="color:#C1C1C1">
                            <td>
                                {{sprintf("%05d", $payment->transaction_id)}}
                            </td>
                            <td class="text-left">
                                {{ $invoice->dateFormat($payment->date) }}
                            </td>
                            <td class="text-right">
                                {{($payment->category?$payment->category->name:'')}}
                            </td>
                            <td class="text-right">
                                {!! htmlentities($invoice->priceFormat($payment->amount), ENT_COMPAT, 'UTF-8') !!}
                            </td>
                            @can('edit invoice')
                            <td class="table-actions text-right pl-0 pr-0 d-print-none">
                                <div class="dropdown float-right">
                                    <button class="btn-options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons" style="color:#C1C1C1">more_vert</i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="{{ route('invoices.payments.edit', [$invoice->id, $payment->id]) }}" data-remote="true" data-type="text">
                                            <span>{{__('Edit')}}</span>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="{{ route('invoices.payments.delete', [$invoice->id, $payment->id]) }}" data-method="delete" data-remote="true" data-type="text">
                                            <span>{{__('Delete')}}</span>
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
                            <th class="text-right" style="color:#C1C1C1"><span>{{__('Total Due', [], $invoice->locale)}}</span></th>
                            <th class="text-right" style="color:#C1C1C1"><span>{!! htmlentities($invoice->priceFormat($invoice->getDue()), ENT_COMPAT, 'UTF-8') !!}</span></th>
                            </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
    @foreach($invoice->payments as $payment)
        @if($payment->receipt)
        <hr style="border-width:1px;border-style:dotted;">
        <div class="pl-4 pr-4" style="page-break-inside: avoid">
            <div class="table-responsive">
                <table class="table">
                    <tfoot class="borderless gapless">
                        <tr>
                            @if($companySettings && $companyName)
                                <td class="text-left">{{$companyName}}</td>
                            @else
                                <td class="text-left"><a href="{{ route('profile.edit') }}#company"><u>{{__('Edit Company Info')}}</u></a></td>
                            @endif

                            <td class="text-right"><h4>{{__('Receipt', [], $invoice->locale)}}</h4></td>
                        </tr>
                        <tr>
                            <td class="text-left">{{$companySettings?$companySettings->address:''}}</td>
                            <td class="text-right">{{ $payment->receipt }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">{{($companySettings&&$companySettings->city)?($companySettings->city.', '.$companySettings->state.' - '.$companySettings->zipcode):''}}</td>                                    
                            <td class="text-right">{{ $invoice->dateFormat($payment->date) }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">{{$companySettings?$companySettings->country:''}}</td>
                            <td class="text-right"></td>
                        </tr>
                        <tr>
                            <td class="text-left">{{($companySettings&&$companySettings->tax)?(__('TAX ID', [], $invoice->locale).': '.$companySettings->tax):''}}</td>
                            <td class="text-right"></td>
                        </tr>
                    </tfoot>
                </table>
                <table class="table">
                    <tfoot class="borderless gapless">
                        <tr>
                            <td class="text-left">{{__('I received from:', [], $invoice->locale)}} {{$client->name}}{{$client->tax?(', '.__('TAX ID', [], $invoice->locale).': '.$client->tax):''}}</td>
                        </tr>
                        <tr>
                            <td class="text-left">{{__('Address:', [], $invoice->locale)}} {{$client->address}}</td>
                        </tr>
                        <tr>
                            <td class="text-left">{{__('The amount of', [], $invoice->locale)}} {!! htmlentities($invoice->priceFormat($payment->amount), ENT_COMPAT, 'UTF-8') !!}, {{__('meaning', [], $invoice->locale)}} {{$invoice->priceSpellout($payment->amount)}}, </td>
                        </tr>
                        <tr>
                            <td class="text-left">{{__('representing a payment according to the invoice', [], $invoice->locale)}} {{ $invoice->number ? $invoice->number : Auth::user()->invoiceNumberFormat($invoice->increment) }} {{__('from', [], $invoice->locale)}} {{ $invoice->dateFormat($invoice->issue_date) }}</td>
                        </tr>
                        <tr>
                            <td class="text-left"></td>
                            <td class="text-right">{{__('Signature', [], $invoice->locale)}},</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>    
        @endif
    @endforeach
    {{--                    <div class="text-right">--}}
    {{--                        <button class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Print</button>--}}
    {{--                    </div>--}}
</div>