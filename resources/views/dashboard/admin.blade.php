@extends('layouts.admin')

@push('stylesheets')
@endpush

@push('scripts')
    <!-- Charting library -->
    <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
    <!-- Chartisan -->
    <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>
    <!-- Your application script -->
    <script>
        const newUsersChart = new Chartisan({
            el: '#new_users_chart',
            url: "@chart('new_users_chart')",
            hooks: new ChartisanHooks()
                .colors(['#007bff', '#4299E1', '#AAEE11'])
                .tooltip({tooltips: true})
                .datasets([{ type: 'bar', fill: false }, 'bar']),
            loader: {
                color: '#007bff',
                size: [30, 30],
                type: 'bar',
                textColor: '#007bff',
                text: '',
            }        
        });

        const dailyUsersChart = new Chartisan({
            el: '#daily_users_chart',
            url: "@chart('daily_users_chart')",
            hooks: new ChartisanHooks()
                .colors(['#007bff', '#4299E1', '#AAEE11'])
                .tooltip({tooltips: true})
                .datasets([{ type: 'bar', fill: false }, 'bar']),
            loader: {
                color: '#007bff',
                size: [30, 30],
                type: 'bar',
                textColor: '#007bff',
                text: '',
            }        
        });

        const activeUsersChart = new Chartisan({
            el: '#active_users_chart',
            url: "@chart('active_users_chart')",
            hooks: new ChartisanHooks()
                .colors(['#007bff', '#4299E1', '#AAEE11'])
                .tooltip({tooltips: true})
                .axis(false)
                .datasets([{ type: 'pie', fill: false }, 'pie']),
            loader: {
                color: '#007bff',
                size: [30, 30],
                type: 'bar',
                textColor: '#007bff',
                text: '',
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
                    <a class="card card-info" href="{{ route('subscribers') }}">
                        <div class="card-body">
                            <div class="number">
                                <h3 class="card-title">{{$user['total_paid_user']}}</h3>
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
                            <h4 >{{__('New users')}}</h4>
                            <div id="new_users_chart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card card-info">
                        <div class="card-body">
                            <h4 >{{__('Daily active users')}}</h4>
                            <div id="daily_users_chart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card card-info">
                        <div class="card-body">
                            <h4 >{{__('Most active users (last 30 days)')}}</h4>
                            <div id="active_users_chart" style="height: 800px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
