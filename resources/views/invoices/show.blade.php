@extends('layouts.app')

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('page-title')
    {{__('Invoice Detail')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ route('invoices.index') }}">{{__('Invoices')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ Auth::user()->invoiceNumberFormat($invoice->id) }}</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="container-fluid">
        <div class="row pt-5">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-11">
                                <h6>{{ Auth::user()->invoiceNumberFormat($invoice->id) }}
                                    {!! $invoice->getStatusBadge() !!}
                                </h6>
                            </div>
                            <div class="col-1 dropdown card-options d-print-none">
                                <button class="btn-options float-right" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item disabled" href="#" data-remote="true" data-type="text">
                                        <span>{{__('Export PDF')}}</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    @can('create invoice item')
                                    <a class="dropdown-item" href="{{ route('invoices.items.create',$invoice->id) }}" data-remote="true" data-type="text">
                                        <span>{{__('Add Item')}}</span>
                                    </a>
                                    @endcan
                                    @can('create invoice payment')
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
                    <div class="card-body invoice">
                        <div class="invoice-print">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-12 text-md-center">
                                            <address>
                                                <span class="row align-items-center justify-content-center">
                                                    <img width="60" height="60" alt="{{$companyName}}" {!! !$companyLogo ? "avatar='".$companyName."'" : "" !!} class="rounded" src="{{$companyLogo?$companyLogo->getFullUrl():""}}" data-filter-by="alt"/>
                                                </span>
                                                <span class="row align-items-center justify-content-center">
                                                    <h5>{{$client->name}} invoice</h5>
                                                </span>
                                                <span class="row align-items-center justify-content-center">
                                                    <i class="material-icons" title="{{__('Project')}}">folder</i>
                                                    {{$invoice->project->name }}
                                                </span>
                                            </address>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6">
                                            <address>
                                                <strong>{{__('From')}} : </strong><br>
                                                @if($companySettings && $companyName)
                                                {{$companyName}}<br>
                                                {{$companySettings->address}}<br>
                                                {{$companySettings->city}}, {{$companySettings->state}}-{{$companySettings->zipcode}}<br>
                                                {{$companySettings->country}}
                                                @else
                                                <a href="{{ route('profile.show') }}#company">
                                                    <u>{{__('Edit Company Info')}}</u>
                                                </a>
                                                @endif
                                            </address>
                                        </div>
                                        <div class="col-xs-12 col-md-6 text-md-right">
                                            <address>
                                                <strong>{{__('To')}}:</strong><br>
                                                {{(!empty($client))?$client->name:''}}<br>
                                                {{(!empty($client))?$client->email:''}}<br>
                                            </address>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6 text-md-left">

                                            <strong>{{__('Issue Date')}}:</strong><br>
                                            {{ AUth::user()->dateFormat($invoice->issue_date) }}<br>

                                        </div>
                                        <div class="col-xs-12 col-md-6 text-md-right">

                                            <strong>{{__('Due Date')}}:</strong><br>
                                            {{ AUth::user()->dateFormat($invoice->due_date) }}<br><br>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row ">
                                <div class="col-md-12">
                                    <div class="section-title"><b>{{__('Order Summary')}}</b>
                                        @can('create invoice item')
                                        <div class="col-md-12 text-right d-print-none">
                                            <a href="{{ route('invoices.items.create',$invoice->id) }}" data-remote="true" data-type="text">
                                                <u>{{__('Add Item')}}</u>
                                            </a>
                                        </div>
                                        @endcan
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-md">
                                            <tr>
                                                <th data-width="40">#</th>
                                                <th class="text-center">{{__('Item')}}</th>
                                                <th class="text-center">{{__('Price')}}</th>
                                                <th class="text-right">{{__('Action')}}</th>

                                            </tr>
                                            @php $i=0; @endphp

                                            @foreach($invoice->items as $items)
                                                <tr>
                                                    <td>
                                                        {{++$i}}
                                                    </td>
                                                    <td class="text-center font-style">
                                                        {{$items->name}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{Auth::user()->priceFormat($items->price)}}
                                                    </td>
                                                    <td class="table-actions text-right">
                                                        @can('delete invoice item')

                                                        <div class="dropdown float-right">
                                                            <button class="btn-options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="material-icons">more_vert</i>
                                                            </button>

                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item text-danger" href="{{ route('invoices.items.delete', [$invoice->id, $items->id]) }}" data-method="delete" data-remote="true" data-type="text">
                                                                    <span>{{'Delete'}}</span>
                                                                </a>
                                                            </div>
                                                        </div>

                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </table>
                                    </div>

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                @php
                                    $subTotal = $invoice->getSubTotal();
                                $tax = $invoice->getTax();
                                @endphp
                                <div class="col-md-2">
                                    <div class="invoice-detail-name"><b>{{__('Subtotal')}}</b></div>
                                    <div class="invoice-detail-value"> {{Auth::user()->priceFormat($subTotal)}}</div>
                                </div>
                                @if($invoice->discount != 0)
                                <div class="col-md-2 text-md-center">
                                    <div class="invoice-detail-name"><b>{{__('Discount')}}</b></div>
                                    <div class="invoice-detail-value"> {{$invoice->discount}}%</div>
                                </div>
                                @endif
                                <div class="col-md-3 text-md-center">
                                    <div class="invoice-detail-name"><b>{{(!empty($invoice->tax)?$invoice->tax->name:'Tax')}} ({{(!empty($invoice->tax->rate)?$invoice->tax->rate:'0')}} %)</b></div>
                                    <div class="invoice-detail-value"> {{Auth::user()->priceFormat($tax)}}</div>
                                </div>
                                <div class="col-md-3 text-md-center">
                                    <div class="invoice-detail-name" style="color: #000000"><b>{{__('Total')}}</b></div>
                                    <div class="invoice-detail-value" style="color: #000000">{{Auth::user()->priceFormat($subTotal-$invoice->discount+$tax)}}</div>
                                </div>
                                <div class="col text-md-right">
                                    <div class="invoice-detail-name"><b>{{__('Due Amount')}}</b></div>
                                    <div class="invoice-detail-value"> {{Auth::user()->priceFormat($invoice->getDue())}}</div>
                                </div>
                            </div>
                            <hr>
                            <div class="row ">
                                <div class="col-md-12">
                                    <div class="section-title">{{__('Payment History')}}
                                        @can('create invoice payment')
                                        <div class="col-md-12 text-right d-print-none">
                                            <a href="{{ route('invoices.payments.create',$invoice->id) }}" data-remote="true" data-type="text">
                                                <span><i class="fas fa-plus"></i></span>
                                                <u>{{__('Add Payment')}}</u>
                                            </a>
                                        </div>
                                        @endcan
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-md">
                                            <tr>
                                                <th>{{__('Transaction ID')}}</th>
                                                <th class="text-center">{{__('Payment Date')}}</th>
                                                <th class="text-center">{{__('Payment Method')}}</th>
                                                <th class="text-center">{{__('Note')}}</th>
                                                <th class="text-right">{{__('Amount')}}</th>
                                            </tr>
                                            @php $i=0; @endphp
                                            @foreach($invoice->payments as $payment)
                                                <tr>
                                                    <td>
                                                        {{sprintf("%05d", $payment->transaction_id)}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ Auth::user()->dateFormat($payment->date) }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{(!$payment->categories->isEmpty()?$payment->categories->first()->name:'')}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{$payment->notes}}
                                                    </td>
                                                    <td class="text-right">
                                                        {{Auth::user()->priceFormat($payment->amount)}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>

                                </div>
                            </div>
                            {{--                    <div class="text-md-right">--}}
                            {{--                        <button class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Print</button>--}}
                            {{--                    </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
