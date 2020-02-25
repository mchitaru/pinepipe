@extends('layouts.admin')
@push('css-page')
@endpush

@push('script-page')
@endpush

@section('page-title')
    {{__('Plan')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Plan')}}</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
@php
    $dir= asset(Storage::url('plan'));
@endphp
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="page-header">
        </div>
        <div class="tab-content">
        <div class="tab-pane fade show active" id="plans" role="tabpanel" data-filter-list="content-list-body">
            <div class="row content-list-head">
            <div class="col-auto">
                <h3>{{__('Plans')}}</h3>
                @can('create plan')
                <a class="btn btn-round" href="{{ route('plans.create') }}" data-remote="true" data-type="text">
                    <i class="material-icons">add</i>
                </a>
                @endcan
            </div>
            <form class="col-md-auto">
                <div class="input-group input-group-round">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                    <i class="material-icons">filter_list</i>
                    </span>
                </div>
                <input type="search" class="form-control filter-list-input" placeholder="Filter plans" aria-label="Filter Plans">
                </div>
            </form>
            </div>
            <!--end of content list head-->
            <div class="content-list-body row">
                @foreach($plans as $plan)
                    <div class="card text-center" style="max-width: 250px;">
                        <div class="card-body">
                        <div class="row">
                            <div class="col sm">
                            <div class="mb-4">
                                <h6>{{$plan->name}}</h6>

                                <h4 class="mb-2 font-weight-bold">{{str_replace('.00','',Auth::user()->priceFormat($plan->duration?$plan->price/$plan->duration:$plan->price))}}
                                    <span class="text-small">{{$plan->price?'/month':''}}</span>
                                </h4>                                    
    
                            </div>
                            <ul class="list-unstyled">
                                <li class="text-small">
                                    <b>{{!isset($plan->max_clients)?'Unlimited':$plan->max_clients}}</b> {{__('client(s)')}}
                                </li>
                                <li class="text-small">
                                    <b>{{!isset($plan->max_projects)?'Unlimited':$plan->max_projects}}</b> {{__('project(s)')}}
                                </li>
                                <li class="text-small">
                                    <b>{{!isset($plan->max_users)?'Unlimited':($plan->max_users==0?'No':$plan->max_users)}}</b> {{__('collaborator(s)')}}
                                </li>
                            </ul>
                        </div>
                        </div>
                        <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>

                            <div class="dropdown-menu dropdown-menu-right">
                                @can('edit plan')
                                <a class="dropdown-item" href="{{ route('plans.edit',$plan->id)  }}" data-remote="true" data-type="text">
                                    <span>{{__('Edit')}}</span>
                                </a>
                                @endcan
                            </div>
                        </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</div>
@endsection
