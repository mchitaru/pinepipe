@extends('layouts.app')

@php
    use Carbon\Carbon;

    $profile = asset(Storage::url('avatar/'));

@endphp

@push('stylesheets')
@endpush

@push('scripts')

<script>

    // keep active tab
    // $(document).ready(function() {
    
    //     $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    //         window.location.hash = $(e.target).attr('href');
    //         $(window).scrollTop(0);
    //     });
    
    //     if(window.location.hash)
    //     {
    //         var hash = window.location.hash ? window.location.hash : '#invoices';
            
    //         $('.nav-tabs a[href="' + hash + '"]').tab('show');        
    //     }
    // });
        
</script>
    
@endpush

@section('page-title')
    {{__('Finance')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Finance')}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            <a class="dropdown-item" href="#">{{__('New Contract')}}</a>
            <a class="dropdown-item" href="#">{{__('New Invoice')}}</a>

        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="page-header">
        </div>
        <ul class="nav nav-tabs nav-fill" role="tablist">
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#proposals" role="tab" aria-controls="proposals" aria-selected="true">{{__('Proposals')}}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#contracts" role="tab" aria-controls="contracts" aria-selected="false">{{__('Contracts')}}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{(Request::segment(1)=='invoices')?'active':''}}" data-toggle="tab" href="#invoices" role="tab" aria-controls="invoices" aria-selected="false">{{__('Invoices')}}
                <span class="badge badge-secondary">{{ count($invoices) }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{(Request::segment(1)=='expenses')?'active':''}}" data-toggle="tab" href="#expenses" role="tab" aria-controls="expenses" aria-selected="false">{{__('Expenses')}}
                <span class="badge badge-secondary">{{ count($expenses) }}</span>
            </a>
        </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show {{(Request::segment(1)=='invoices')?'active':''}}" id="invoices" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>{{__('Invoices')}}</h3>
                        @can('create invoice')
                        <button class="btn btn-round" data-url="{{ route('invoices.create') }}" data-ajax-popup="true" data-title="{{__('Create New Invoice')}}" class="btn btn-circle btn-outline btn-sm blue-madison">
                            <i class="material-icons">add</i>
                        </button>
                        @endcan
                    </div>
                    <form class="col-md-auto">
                        <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                            </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="{{__('Filter Invoices')}}" aria-label="{{__('Filter Invoices')}}">
                        </div>
                    </form>
                    </div>
                    <!--end of content list head-->
                    <div class="content-list-body">
                        @foreach ($invoices as $invoice)
                        <div class="card card-task mb-1" style="min-height: 77px;">
                            <div class="container row align-items-center">
                                <div class="pl-2 position-absolute">
                                </div>
                                <div class="card-body p-2">
                                    <div class="card-title col-sm-3">
                                        @can('show invoice')
                                        <a href="{{ route('invoices.show',$invoice->id) }}">
                                        @endcan
                                            <h6 data-filter-by="text">{{ Auth::user()->dateFormat($invoice->issue_date) }}
                                                @if($invoice->status == 0)
                                                <span class="badge badge-primary">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                            @elseif($invoice->status == 1)
                                                <span class="badge badge-danger">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                            @elseif($invoice->status == 2)
                                                <span class="badge badge-warning">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                            @elseif($invoice->status == 3)
                                                <span class="badge badge-success">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                            @elseif($invoice->status == 4)
                                                <span class="badge badge-info">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                            @endif
                                            </h6>
                                        @can('show invoice')
                                        </a>
                                        @endcan
                                        <p>
                                            <span class="text-small">{{__('Due')}} {{ Carbon::parse($invoice->due_date)->diffForHumans() }}</span>
                                        </p>

                                    </div>
                                    <div class="card-title col-sm-2">
                                        <div class="container row align-items-center">
                                            <span data-filter-by="text" class="text-small">{{ Auth::user()->invoiceNumberFormat($invoice->id) }}</span>
                                        </div>
                                    </div>
                                    <div class="card-title col-sm-2">
                                        <div class="container row align-items-center">
                                            <i class="material-icons">folder</i>
                                            <span data-filter-by="text" class="text-small">{{ $invoice->project->name }}</span>
                                        </div>
                                    </div>
                                    <div class="card-title col-sm-2">
                                        <div class="container row align-items-center">
                                            <i class="material-icons">person</i>
                                            <span data-filter-by="text" class="text-small">
                                                <a href="{{ route('clients.index',$invoice->project->client()->id) }}" data-toggle="tooltip" data-original-title="{{__('Client')}}" data-filter-by="text">{{(!empty($invoice->project->client())?$invoice->project->client()->name:'')}}</a>
                                            </span>
                                        </div>
                                    </div>    
                                    <div class="card-meta col">
                                        <div class="container row align-items-center">
                                            <span data-filter-by="text" class="text-small">{{ Auth::user()->priceFormat($invoice->getTotal()) }}</span>
                                        </div>
                                    </div>    
                                    @if(Gate::check('edit invoice') || Gate::check('delete invoice'))
                                    <div class="dropdown card-options">
                                        <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </button>
    
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @can('edit invoice')
                                            <a class="dropdown-item" href="#" data-url="{{ route('invoices.edit',$invoice->id) }}" data-ajax-popup="true" data-title="{{__('Edit Invoice')}}">
                                                <span>{{__('Edit')}}</span>
                                            </a>
                                            @endcan
                                            <div class="dropdown-divider"></div>
                                            @can('delete invoice')
                                                <a class="dropdown-item text-danger" href="#" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$invoice->id}}').submit();">
                                                    <span>{{'Delete'}}</span>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['invoices.destroy', $invoice->id],'id'=>'delete-form-'.$invoice->id]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!--end of content list body-->
                </div>
            <!--end of tab-->
            <div class="tab-pane fade show {{Request::segment(1)=='expenses'?'active':''}}" id="expenses" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>{{__('Expenses')}}</h3>
                        @can('create invoice')
                        <button class="btn btn-round" data-url="{{ route('expenses.create') }}" data-ajax-popup="true" data-title="{{__('Add New Expense')}}" class="btn btn-circle btn-outline btn-sm blue-madison">
                            <i class="material-icons">add</i>
                        </button>
                        @endcan
                    </div>
                    <form class="col-md-auto">
                        <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                            </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="{{__('Filter Expenses')}}" aria-label="{{__('Filter Expenses')}}">
                        </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">
                    @foreach ($expenses as $expense)
                    <div class="card card-task mb-1" style="min-height: 77px;">
                        <div class="container row align-items-center">
                            <div class="pl-2 position-absolute">
                            </div>
                            <div class="card-body p-2">
                                <div class="card-title col-sm-3">
                                    @can('edit expense')
                                        <a href="#" data-url="{{ route('expenses.edit',$expense->id) }}" data-ajax-popup="true" data-title="{{__('Edit Expense')}}">
                                    @endcan
                                        <h6 data-filter-by="text">{{  (!empty($expense->category)?$expense->category->name:'')}}
                                        </h6>
                                    @can('edit expense')
                                    </a>
                                    @endcan
                                    <p>
                                        <span class="text-small">{{ Carbon::parse($expense->date)->diffForHumans() }}</span>
                                    </p>

                                </div>
                                <div class="card-title col-sm-2">
                                    <div class="container row align-items-center">
                                        <i class="material-icons">folder</i>
                                        <span data-filter-by="text" class="text-small">{{ $expense->projects->name }}</span>
                                    </div>
                                </div>
                                <div class="card-title col-sm-2">
                                    <div class="container row align-items-center">
                                        <span data-filter-by="text" class="text-small">{{ Auth::user()->priceFormat($expense->amount) }}</span>
                                    </div>
                                </div>    
                                <div class="card-title col-sm-2">
                                    <div class="container row align-items-center">
                                        <span data-filter-by="text" title="{{ $expense->description }}" class="text-small text-truncate" style="max-width: 150px;">{{ $expense->description }}</span>
                                    </div>
                                </div>
                                @if(!empty($expense->user))
                                <div class="card-meta col">
                                    @if($expense->attachment)
                                        <a href="{{asset(Storage::url('app/public/attachment/'. $expense->attachment))}}" download="" class="mr-2" data-toggle="tooltip" data-original-title="{{__('Download')}}">
                                            <i class="material-icons" title="Projects">attachment</i>
                                        </a>
                                    @endif
                                    <a href="#" data-toggle="tooltip" title={{$expense->user->name}}>
                                        @if(empty($expense->user->avatar))
                                            <img width="32" height="32" alt="{{$expense->user->name}}" avatar="{{$expense->user->name}}" class="round" />
                                        @else
                                            <img alt={{$expense->user->name}} class="avatar" src="{{ asset(Storage::url("avatar/".$expense->user->avatar))}}" />
                                        @endif
                                    </a>        
                                </div>    
                                @endif
                                @if(Gate::check('edit expense') || Gate::check('delete expense'))
                                <div class="dropdown card-options">
                                    <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        @can('edit expense')
                                        <a class="dropdown-item" href="#" data-url="{{ route('expenses.edit',$expense->id) }}" data-ajax-popup="true" data-title="{{__('Edit Expense')}}">
                                            <span>{{__('Edit')}}</span>
                                        </a>
                                        @endcan
                                        <div class="dropdown-divider"></div>
                                        @can('delete expense')
                                            <a class="dropdown-item text-danger" href="#" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('expense-delete-form-{{$expense->id}}').submit();">
                                                <span>{{'Delete'}}</span>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['expenses.destroy', $expense->id],'id'=>'expense-delete-form-'.$expense->id]) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!--end of content list body-->
            </div>
            <!--end of tab-->
        </div>
    </div>
</div>
@endsection
