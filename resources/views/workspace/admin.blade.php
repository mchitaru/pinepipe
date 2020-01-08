@extends('layouts.admin')

@push('stylesheets')
@endpush

@push('scripts')
<script>
    var ctx = document.getElementById('order-chart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['label']) !!},
            datasets: [{
                label: "{{ __('Orders') }}",
                data: {!! json_encode($chartData['data']) !!},
                fill: false,
                backgroundColor: "transparent",
                borderColor: "#f36a5a",
                borderWidth: 1
            }]
        },
        options: {
                // maintainAspectRatio: false,
                scales: {
                    xAxes: [{reverse: !0, gridLines: {color: "rgba(0,0,0,0.05)"}}],
                    yAxes: [{
                        ticks: {stepSize: 10, display: !1},
                        min: 10,
                        max: 100,
                        display: !0,
                        borderDash: [5, 5],
                        gridLines: {color: "rgba(0,0,0,0)", fontColor: "#fff"}
                    }]
                },
                responsive: true,
                title: {
                    display: false,
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                legend: {
                    display: false
                }
            }
    });
</script>

@endpush

@section('page-title')
    {{__('Workspace')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Workspace</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row pt-5">
        <div class="col">
            <div class="row">
                <div class="col">
                    <div class="card card-info">
                        <div class="card-body">
                            <div class="number">
                                <h3 class="card-title">{{$user['total_user']}}</h3>
                                <small class="card-text">{{__('TOTAL USERS')}}</small>
                            </div>
                            <div class="icon">
                                <i class="material-icons">people</i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-info">
                        <div class="card-body">
                            <div class="number">
                                <h3 class="card-title">{{$user['total_orders']}}</h3>
                                <small class="card-text">{{__('TOTAL ORDERS')}}</small>
                            </div>
                            <div class="icon">
                                <i class="material-icons">shopping_cart</i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-info">
                        <div class="card-body">
                            <div class="number">
                                <h3 class="card-title">{{$user['total_plan']}}</h3>
                                <small class="card-text">{{__('TOTAL PLANS')}}</small>
                            </div>
                            <div class="icon">
                                <i class="material-icons">card_membership</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card card-info">
                        <div class="card-body">
                            <h5 class="card-title">{{__('Recent Orders')}}</h5>
                            <canvas id="order-chart" width="800" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="row">
                <div class="col">
                    <div class="card card-info">
                        <div class="card-body">
                            <h5 class="card-title">{{__('Latest Activity')}} </h5>
                                <ol class="timeline small">
                                @foreach($activities as $activity)
                                <li>
                                    <div class="media align-items-center">
                                        <div class="media-body">
                                            <div>
                                                <span data-filter-by="text">{!! $activity->remark !!}</span>
                                            </div>
                                            <span class="text-small" data-filter-by="text">{{date('d M Y H:i', strtotime($activity->created_at))}}</span>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
