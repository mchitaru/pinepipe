@extends('layouts.app')

@push('stylesheets')
    <link rel="stylesheet" href="assets/css/fullcalendar.min.css">
@endpush

@push('scripts')
    <script src="{{asset('assets/js/fullcalendar.min.js')}}"></script>
    <script>
        var tasks = {!! ($due_tasks) !!};

        $(document).on('click', '.fc-day-grid-event', function (e) {
            if (!$(this).hasClass('deal')) {
                e.preventDefault();
                var event = $(this);
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    success: function (data) {
                        $('#commonModal .modal-content').html(data);
                        $("#commonModal").modal('show');
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        toastr('Error', data.error, 'error')
                    }
                });
            }
        });

    $("#myEvent").fullCalendar({
    height: 'auto',
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listWeek'
    },
    editable: false,
    events: tasks,
    });

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

            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#team-manage-modal">Edit Team</a>
            <a class="dropdown-item" href="#">Share</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="#">Leave</a>

        </div>
    </div>
</div>
@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container-fluid">
            <div class="row pt-5">
                <div class="col">
                    <div class="fc-overflow">
                        <div id="myEvent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
