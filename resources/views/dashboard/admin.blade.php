@extends('layouts.admin')

@push('stylesheets')
@endpush

@push('scripts')
{!! $charts[0]->renderChartJsLibrary() !!}
{!! $charts[0]->renderJs() !!}
{!! $charts[1]->renderJs() !!}
{!! $charts[2]->renderJs() !!}
{!! $charts[3]->renderJs() !!}
{!! $charts[4]->renderJs() !!}
{!! $charts[5]->renderJs() !!}
{!! $charts[6]->renderJs() !!}
{!! $charts[7]->renderJs() !!}
{!! $charts[8]->renderJs() !!}
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
            @foreach($charts as $chart)
            <div class="row">
                <div class="col">
                    <div class="card card-info">
                        <div class="card-body">
                            <h1>{{ $chart->options['chart_title'] }}</h1>
                            {!! $chart->renderHtml() !!}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
