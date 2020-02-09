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
        <div class="tab-pane fade show active" id="users" role="tabpanel" data-filter-list="content-list-body">
            <div class="row content-list-head">
            <div class="col-auto">
                <h3>{{__('Plans')}}</h3>
                @can('create permission')
                <span class="create-btn">
                    <button class="btn btn-round" data-url="{{ route('plans.create') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Create New Plan')}}">
                        <i class="material-icons">add</i>
                    </button>
                </span>
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
            <div class="content-list-body">
            @foreach($plans as $plan)
            <div class="w-25 card text-center" style="max-width: 250px;">
                <div class="card-body">
                <div class="row">
                    <div class="col sm">
                    <div class="mb-4">
                        <h6>Free</h6>
                        <h5 class="display-4 d-block mb-2 font-weight-normal">{{$plan->price}}$</h5>
                        <span class="text-muted text-small">{{$plan->duration}}</span>
                    </div>
                    <ul class="list-unstyled">
                        <li class="row">
                            <div class="col text-right">
                                <i class="material-icons">folder</i>
                            </div>
                            <div class="col text-left">
                                {{$plan->max_projects}} {{__('Projects')}}
                            </div>
                        </li>
                        <li class="row">
                            <div class="col text-right">
                                <i class="material-icons">people</i>
                            </div>
                            <div class="col text-left">
                                {{$plan->max_users}} {{__('Users')}}
                            </div>
                        </li>
                        <li class="row">
                            <div class="col text-right">
                                <i class="material-icons">apartment</i>
                            </div>
                            <div class="col text-left">
                                {{$plan->max_clients}} {{__('Clients')}}
                            </div>
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
                        <a class="dropdown-item" href="#" data-url="{{ route('plans.edit',$plan->id)  }}" data-ajax-popup="true" data-title="{{__('Edit Plan')}}">
                            <span>{{__('Edit')}}</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        @endcan
                        <a class="dropdown-item {{ (($plan->price == 0.00) || !Gate::check('buy plan') || ($plan->id==\Auth::user()->plan))?'disabled':'' }}" href="{{route('stripe',\Illuminate\Support\Facades\Crypt::encrypt($plan->id))}}">
                            <span>{{__('Upgrade')}}</span>
                        </a>
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
