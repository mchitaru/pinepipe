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

@section('breadcrumb')
@endsection

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
                        <x-upcoming title="TODAY" :tasks='$todayTasks' :events='$todayEvents'></x-upcoming>
                        <x-upcoming title="THIS WEEK" :tasks='$thisWeekTasks' :events='$thisWeekEvents'></x-upcoming>
                        <x-upcoming title="NEXT WEEK" :tasks='$nextWeekTasks' :events='$nextWeekEvents'></x-upcoming>
                    </div>
                    <h3> {{__("Let's take on the day!")}} </h3>
                    <div class="row pt-1 pb-3">
                        <x-todo type="projects" icon="folder" :text="__('project(s) in progress.')" :items='$projects'></x-todo>
                        <x-todo type="tasks" icon="playlist_add_check" :text="__('important thing(s) to do.')" :items='$tasks'></x-todo>
                        <x-todo type="invoices" icon="description" :text="__('unpaid invoice(s).')" :items='$invoices'></x-todo>
                        <x-todo type="leads" icon="phone" :text="__('lead(s) rotting.')" :items='$leads'></x-todo>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@php clock()->endEvent('dashboard.index'); @endphp
