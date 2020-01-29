@extends('layouts.app')

@push('stylesheets')
    <link href='assets/module/fullcalendar/css/core.min.css' rel='stylesheet' />
    <link href='assets/module/fullcalendar/css/daygrid.min.css' rel='stylesheet' />
    <link href='assets/module/fullcalendar/css/list.min.css' rel='stylesheet' />
    <link href='assets/module/fullcalendar/css/timegrid.min.css' rel='stylesheet' />
    <link href='assets/module/fullcalendar/css/bootstrap.min.css' rel='stylesheet' />
    <link href='assets/module/fullcalendar/css/bootstrap.min.css' rel='stylesheet' />
    <link href='assets/module/fullcalendar/css/all.min.css' rel='stylesheet' />


    {{-- <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'> --}}
@endpush

@push('scripts')
    <script src="{{asset('assets/module/fullcalendar/js/core.min.js')}}"></script>
    <script src="{{asset('assets/module/fullcalendar/js/daygrid.min.js')}}"></script>
    <script src="{{asset('assets/module/fullcalendar/js/list.min.js')}}"></script>
    <script src="{{asset('assets/module/fullcalendar/js/timegrid.min.js')}}"></script>
    <script src="{{asset('assets/module/fullcalendar/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/module/fullcalendar/js/all.min.js')}}"></script>

    <script type="module">
        var tasks = {!! ($due_tasks) !!};

        $(document).on('click', '.fc-day-grid-event', function (e) {
                e.preventDefault();
                var event = $(this);
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
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
        });

        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'dayGrid', 'timeGrid', 'list', 'bootstrap' ],
        timeZone: 'UTC',
        themeSystem: 'bootstrap',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        weekNumbers: true,
        eventLimit: true, // allow "more" link when too many events
        events: tasks
        });

        calendar.render();

    </script>
@endpush

@php
    $profile=asset(Storage::url('avatar/'));
@endphp

@section('page-title')
    {{__('Calendar')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Calendar</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#team-manage-modal">New Event</a>

        </div>
    </div>
</div>
@endsection


@section('content')
<div class="container">
    <div class="pt-3" id="calendar"></div>
</div>
@endsection
