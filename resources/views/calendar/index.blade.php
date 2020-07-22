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
    <script src="{{asset('assets/module/fullcalendar/locales-all.min.js')}}"></script>

    <script>
        $(function() {

            var events = {!! ($events) !!};

            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'dayGrid', 'timeGrid', 'list', 'bootstrap', 'interaction' ],
            timeZone: 'local',
            locale: '{{\Auth::user()->locale}}',
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
            eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'narrow'
                },
            events: events,

            select: function(info)
            {
                $(".context-menu > a").each(function() {
                    $(this).attr("data-start", info.startStr);
                    $(this).attr("data-end", info.endStr);
                });

                $(".context-menu").finish().toggle().

                css({
                    top: info.jsEvent.pageY + "px",
                    left: info.jsEvent.pageX + "px"
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

            $('.fc-new-button').addClass('dropdown-toggle');
        });

    </script>

    <script>
        // If the document is clicked somewhere
        $(document).bind("mousedown", function (e) {

            // If the clicked element is not the menu
            if (!$(e.target).parents(".context-menu").length > 0) {

                // Hide it
                $(".context-menu").hide();
            }
        });

        $(".context-menu a").click(function(e){

            e.preventDefault();

            startStr = $(this).attr("data-start");
            endStr = $(this).attr("data-end");

            $.ajax({
                url: $(this).attr("href"),
                type: 'get',
                data: {start: startStr, end: endStr, "_token": $('meta[name="csrf-token"]').attr('content')},
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

            // Hide it AFTER the action was triggered
            $(".custom-menu").hide();
        });
    </script>
@endpush

@section('page-title')
    {{__('Calendar')}}
@endsection

@section('content')
<div class="dropdown-menu context-menu">
    <a class="dropdown-item" href="{{ route('events.create') }}" >{{__('New Event')}}</a>
    <a class="dropdown-item" href="{{ route('tasks.create') }}" >{{__('New Task')}}</a>
</div>

<div class="container">
    <div class="row page-header">
    </div>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="events" role="tabpanel">
            <div class="content-list">
                <div class="row content-list-head">
                    <div class="col-12 col-md-auto">
                        <h3>{{__('Calendar')}}</h3>
                        <div class="dropdown ml-2">
                            <button class="btn btn-round btn-primary" role="button" data-toggle="dropdown" aria-expanded="false">
                                <i class="material-icons">add</i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('events.create') }}" data-remote="true" data-type="text" >{{__('New Event')}}</a>
                                <a class="dropdown-item" href="{{ route('tasks.create') }}" data-remote="true" data-type="text" >{{__('New Task')}}</a>
                            </div>
                        </div>
                    </div>
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
