@extends('layouts.app')

@php clock()->startEvent('dashboard.index', "Display dash"); @endphp

@php
use App\Project;
use Carbon\Carbon;
@endphp

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('content')
@php
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="page-header">
                    </div>
                    <h3> {{__('Hey')}} {{\Auth::user()->name}}! {{__('After')}} &#x2615;, {{__("here is what's coming up")}}:</h3>
                    <div class="row pt-1 pb-3">
                        @livewire('upcoming', ['title'=>__('Today'), 'tasks' => $todayTasks, 'events' => $todayEvents])
                        @livewire('upcoming', ['title'=>__('This week'), 'tasks' => $thisWeekTasks, 'events' => $thisWeekEvents])
                        @livewire('upcoming', ['title'=>__('Next week'), 'tasks' => $nextWeekTasks, 'events' => $nextWeekEvents])
                    </div>
                    <h3> {{__("Let's take on the day!")}} </h3>
                    <div class="row pt-1 pb-3">
                        @livewire('projects', ['icon'=>'folder', 'text' => __('project(s) in progress.'), 'items' => $projects])
                        @livewire('tasks', ['icon'=>'playlist_add_check', 'text' => __('important thing(s) to do.'), 'items' => $tasks])
                        @livewire('invoices', ['icon'=>'description', 'text' => __('unpaid invoice(s).'), 'items' => $invoices])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@php clock()->endEvent('dashboard.index'); @endphp
