@extends('layouts.app')

@push('stylesheets')
    <link href='assets/module/fullcalendar/core.min.css' rel='stylesheet' />
    <link href='assets/module/fullcalendar/daygrid.min.css' rel='stylesheet' />
    <link href='assets/module/fullcalendar/list.min.css' rel='stylesheet' />
    <link href='assets/module/fullcalendar/timegrid.min.css' rel='stylesheet' />
    <link href='assets/module/fullcalendar/bootstrap.min.css' rel='stylesheet' />
@endpush

@push('scripts')

    <script src="{{asset('assets/module/fullcalendar/core.min.js')}}"></script>
    <script src="{{asset('assets/module/fullcalendar/daygrid.min.js')}}"></script>
    <script src="{{asset('assets/module/fullcalendar/list.min.js')}}"></script>
    <script src="{{asset('assets/module/fullcalendar/timegrid.min.js')}}"></script>
    <script src="{{asset('assets/module/fullcalendar/interaction.min.js')}}"></script>
    <script src="{{asset('assets/module/fullcalendar/bootstrap.min.js')}}"></script>

    <script>
        var events = {!! ($events) !!};

        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'dayGrid', 'timeGrid', 'list', 'bootstrap', 'interaction' ],
        timeZone: 'UTC',
        themeSystem: 'bootstrap',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay listMonth'
        },
        buttonText: {
            prev: '<',
            next: '>'
        },
        defaultView: (localStorage.getItem("fcDefaultView") ? localStorage.getItem("fcDefaultView") : "listMonth"),
        weekNumbers: true,
        eventLimit: true,
        selectable: true,
        events: events,

        select: function(info)
        {
            $.ajax({
                url: '{{url("/events/create")}}',
                type: 'get',
                data: {start: info.startStr, end: info.endStr, "_token": $('meta[name="csrf-token"]').attr('content')},
                dataType: 'text',
                    success: function(data, status, xhr) {
                        $(document).trigger('ajax:success', [data, status, xhr]);
                    },
                    complete: function(xhr, status) {
                        $(document).trigger('ajax:complete', [xhr, status]);
                    },
                    error: function(xhr, status, error) {
                        $(document).trigger('ajax:error', [xhr, status, error]);
                    }
            });
        },
        eventClick: function(info) 
        {
            info.jsEvent.preventDefault();

            if (info.event.url) {

                $.ajax({
                    url: info.event.url,
                    type: 'get',
                    dataType: 'text',
                        success: function(data, status, xhr) {
                            $(document).trigger('ajax:success', [data, status, xhr]);
                        },
                        complete: function(xhr, status) {
                            $(document).trigger('ajax:complete', [xhr, status]);
                        },
                        error: function(xhr, status, error) {
                            $(document).trigger('ajax:error', [xhr, status, error]);
                        }
                });
            }
        },
        datesRender: function (info) 
        {
            localStorage.setItem("fcDefaultView", info.view.type);
        }        
        });

        calendar.render();

    </script>
@endpush

@section('page-title')
    {{__('Calendar')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Calendar')}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            <a class="dropdown-item" href="{{ route('events.create') }}" data-remote="true" data-type="text">{{__('New Event')}}</a>

        </div>
    </div>
</div>
@endsection


@section('content')
<div class="container">
    <div class="page-header">
    </div>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="events" role="tabpanel">
            <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Events')}}</h3>
                    @can('create event')
                    <a href="{{ route('events.create') }}" class="btn btn-round" data-remote="true" data-type="text">
                        <i class="material-icons">add</i>
                    </a>
                    @endcan
                </div>
            </div>
            <!--end of content list head-->
            <div class="content-list-body">
                <div class="pt-3" id="calendar"></div>
            </div>
            <!--end of content list body-->
        </div>
        <!--end of tab-->
    </div>
</div>
@endsection
