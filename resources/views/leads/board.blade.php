@extends('layouts.app')

@php
use Carbon\Carbon;
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
            <li class="breadcrumb-item"><a href="{{ route('leads.index') }}">{{__('Clients')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Lead Board')}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            <a class="dropdown-item disabled" href="#">{{__('New Lead')}}</a>

        </div>
    </div>
</div>
@endsection

@section('content')
    <div class="container-kanban">
        <div class="container-fluid page-header d-flex justify-content-between align-items-start">
            <div class="row align-items-center">
                <h3>Lead Board</h3>
                <span class="badge badge-secondary">demo</span>
            </div>
        </div>
        <div class="kanban-board container-fluid mt-lg-3">
                
            @foreach($stages as $stage)

            @if(\Auth::user()->type == 'company')
                @php($leads = $stage->leads)
            @else
                @php($leads = $stage->user_leads())
            @endif

            <div class="kanban-col">
                <div class="card-list">
                <div class="card-list-header">
                    <h6>{{$stage->name}} ({{ count($leads) }})</h6>
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
                            <a href="#" data-toggle="modal" data-target="#task-modal">
                                <h6>{{$lead->name}}</h6>
                            </a>
                            <p>
                                <span class="text-small">{{ $lead->client->name }}</span>
                            </p>
                        </div>

                        <div class="card-title">
                            <span class="text-small" data-filter-by="text">
                                {{ \Auth::user()->priceFormat($lead->price) }}
                            </span>
                            <div class="float-right">
                                <a href="#" data-toggle="tooltip" title="Ravi">
                                    <img alt="{{$lead->client->name}}" {!! empty($lead->client->avatar) ? "avatar='".$lead->client->name."'" : "" !!} class="avatar" src="{{Storage::url($lead->client->avatar)}}" data-filter-by="alt"/>
                                </a>
                            </div>
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
