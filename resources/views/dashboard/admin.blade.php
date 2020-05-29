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
                label: "{{ __('Subscriptions') }}",
                data: {!! json_encode($chartData['data']) !!},
                fill: false,
                backgroundColor: "transparent",
                borderColor: "#f36a5a",
                borderWidth: 1
            }]
        },
        options: {
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
    {{__('Home')}}
@endsection

@section('content')
<div class="container">
    <div class="row pt-3">
        <div class="col-xs-6 col-sm-12">
            <div class="row">
                <div class="col">
                    <a class="card card-info" href="{{ route('users.index') }}">
                        <div class="card-body">
                            <div class="number">
                                <h3 class="card-title">{{$user['total_user']}}</h3>
                                <small class="card-text">{{__('TOTAL USERS')}}</small>
                            </div>
                            <div class="icon">
                                <i class="material-icons">people</i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a class="card card-info" href="#">
                        <div class="card-body">
                            <div class="number">
                                <h3 class="card-title">{{$user['total_orders']}}</h3>
                                <small class="card-text">{{__('TOTAL SUBSCRIPTIONS')}}</small>
                            </div>
                            <div class="icon">
                                <i class="material-icons">shopping_cart</i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a class="card card-info" href="{{ route('plans.index') }}">
                        <div class="card-body">
                            <div class="number">
                                <h3 class="card-title">{{$user['total_plan']}}</h3>
                                <small class="card-text">{{__('TOTAL PLANS')}}</small>
                            </div>
                            <div class="icon">
                                <i class="material-icons">card_membership</i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card card-info">
                        <div class="card-body">
                            <h5 class="card-title">{{__('Recent Subscriptions')}}</h5>
                            <canvas id="order-chart" width="800" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
