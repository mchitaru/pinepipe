@extends('layouts.app')

@php
use Carbon\Carbon;
use App\Http\Helpers;
@endphp

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('page-title')
    {{__('Lead Board')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Leads')}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            <a class="dropdown-item disabled" href="#">{{__('New Lead')}}</a>

            <div class="dropdown-divider"></div>
            <a class="dropdown-item disabled" href="#" data-remote="true" data-type="text">{{__('Import')}}</a>
            <a class="dropdown-item disabled" href="#" data-remote="true" data-type="text">{{__('Export')}}</a>
        </div>
    </div>
</div>
@endsection

@section('content')

@php clock()->startEvent('leads.board', "Display lead board"); @endphp

    <div class="container-kanban" data-filter-list="card-list-body">
        <div class="container-fluid page-header d-flex justify-content-between align-items-start">
            <div class="col">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>{{__('Leads')}}</h3>
                        @can('create lead')
                        <a href="{{ route('leads.create') }}" class="btn btn-round" data-remote="true" data-type="text">
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
                        <input type="search" class="form-control filter-list-input" placeholder="{{__('Filter Leads')}}" aria-label="{{__('Filter Leads')}}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="kanban-board container-fluid mt-lg-3">
                
            @foreach($stages as $stage)

            @php ($leads = $stage->leadsByUserType()->get()) @endphp

            <div class="kanban-col">
                <div class="card-list">
                <div class="card-list-header">
                    <h6>{{$stage->name}} ({{ $leads->count() }})</h6>
                    <div class="dropdown">
                    <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Edit</a>
                        <a class="dropdown-item text-danger" href="#">Archive List</a>
                    </div>
                    </div>
                </div>
                <div class="card-list-body">

                    @foreach($leads as $lead)

                    <div class="card card-kanban">

                    <div class="card-body">
                        <div class="dropdown card-options">
                        <button class="btn-options" type="button" id="kanban-dropdown-button-14" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Edit</a>
                            <a class="dropdown-item text-danger" href="#">Archive Card</a>
                        </div>
                        </div>
                        <div class="card-title">
                            @can('edit lead')
                            <a href="{{ route('leads.edit',$lead->id) }}" data-remote="true" data-type="text">
                            @endcan
                                <h6 data-filter-by="text">{{$lead->name}}</h6>
                            @can('edit lead')
                            </a>
                            @endcan
                            <p>
                                <span class="text-small">{{ $lead->client->name }}</span>
                            </p>
                        </div>

                        <div class="card-title">
                            <span class="text-small" data-filter-by="text">
                                {{ \Auth::user()->priceFormat($lead->price) }}
                            </span>
                            @if(!empty($lead->user))
                            <div class="float-right">
                                <a href="#" data-toggle="tooltip" title="{{$lead->user->name}}">
                                    {!!Helpers::buildAvatar($lead->user)!!}
                                </a>
                            </div>
                            @endif
                         </div>
                    </div>
                    </div>

                    @endforeach

                </div>
                </div>
            </div>

            @endforeach

            <div class="kanban-col">
                <div class="card-list">
                <button class="btn btn-link btn-sm text-small">{{__('Add Stage')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@php clock()->endEvent('leads.board'); @endphp
