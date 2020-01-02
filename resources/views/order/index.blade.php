@extends('layouts.admin')

@push('css-page')
@endpush

@push('script-page')
@endpush

@section('page-title')
    {{__('Orders')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Orders')}}</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="page-header">
        </div>
        <div class="tab-content">
        <div class="tab-pane fade show active" id="users" role="tabpanel" data-filter-list="content-list-body">
            <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Orders')}}</h3>
                </div>
            </div>
            <!--end of content list head-->
            <div class="content-list-body">
                        <table class="table table-striped table-bordered table-hover" id="dataTable">
                        <thead>
                        <tr>
                            <th>{{__('Order Id')}}</th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Plan Name')}}</th>
                            <th>{{__('Price')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Invoice')}}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{$order->order_id}}</td>
                                <td>{{$order->user_name}}</td>
                                <td>{{$order->plan_name}}</td>
                                <td>${{number_format($order->price)}}</td>
                                <td>
                                    @if($order->payment_status == 'succeeded')
                                        <i class="mdi mdi-circle text-success"></i> {{ucfirst($order->payment_status)}}
                                    @else
                                        <i class="mdi mdi-circle text-danger"></i> {{ucfirst($order->payment_status)}}
                                    @endif
                                </td>
                                <td>{{$order->created_at->format('d M Y')}}</td>
                                <td>
                                    @if(!empty($order->receipt))
                                        <a href="{{$order->receipt}}" title="Invoice" target="_blank" class="btn btn-outline btn-sm blue-madison"><i class="icon-envelope"></i> </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
