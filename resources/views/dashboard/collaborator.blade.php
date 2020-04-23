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
            <div class="row pt-3">
                <div class="col-xs-6 col-sm-12">
                    <h3> {{__('Hey')}} {{\Auth::user()->name}}! {{__('After')}} &#x2615;, {{__("here is what's coming up")}}:</h3>
                    <div class="row pb-3">
                        <x-upcoming title="TODAY" :tasks='$todayTasks' :events='$todayEvents'></x-upcoming>
                        <x-upcoming title="THIS WEEK" :tasks='$thisWeekTasks' :events='$thisWeekEvents'></x-upcoming>
                        <x-upcoming title="NEXT WEEK" :tasks='$nextWeekTasks' :events='$nextWeekEvents'></x-upcoming>
                    </div>
                    <h3> {{__("Let's take on the day!")}} </h3>
                    <div class="row pb-3">
                        <x-todo type="projects" icon="folder" text="projects in progress." :items='$projects'></x-todo>
                        <x-todo type="tasks" icon="playlist_add_check" text="things to do." :items='$tasks'></x-todo>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@php clock()->endEvent('dashboard.index'); @endphp
