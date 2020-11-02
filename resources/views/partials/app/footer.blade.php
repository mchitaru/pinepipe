@php
use Carbon\Carbon;
$timesheet = $_user?$_user->timesheets->first():null;
@endphp

<!-- WARNING!! DO NOT LEAVE LINE COMMENTS IN SCRIPTS!! -->

<script type="text/javascript" src="{{ asset('js/manifest.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/vendor.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/scripts.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('js/remote.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/avatar.js') }}"></script> --}}

<!-- Required vendor scripts (Do not remove) -->
@livewireScripts

<!-- Optional Vendor Scripts (Remove the plugin script here and comment initializer script out of index.js if site does not use that feature) -->
<script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/easytimer.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('assets/js/pace.min.js') }}"></script> --}}
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-notify.min.js') }}"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ro.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/i18n/ro.js"></script>

<!-- Autosize - resizes textarea inputs as user types -->
{{-- <script type="text/javascript" src="{{ asset('assets/js/autosize.min.js') }}"></script>
<!-- Flatpickr (calendar/date/time picker UI) -->
<script type="text/javascript" src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
<!-- Prism - displays formatted code boxes -->
<script type="text/javascript" src="{{ asset('assets/js/prism.js') }}"></script>
<!-- Shopify Draggable - drag, drop and sort items on page -->
<script type="text/javascript" src="{{ asset('assets/js/draggable.bundle.legacy.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/swap-animation.js') }}"></script>
<!-- Dropzone - drag and drop files onto the page for uploading -->
<script type="text/javascript" src="{{ asset('assets/js/dropzone.min.js') }}"></script>
<!-- List.js - filter list elements -->
<script type="text/javascript" src="{{ asset('assets/js/list.min.js') }}"></script>

<!-- Required theme scripts (Do not remove) -->
<script type="text/javascript" src="{{ asset('assets/js/theme.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('assets/js/dropzone.min.js') }}"></script> --}}

{{-- <script src="{{ asset('assets/js/easytimer.min.js') }}"></script>
<script>
    var timerInstance = new easytimer.Timer();
</script> --}}

<script>

    var options = {
        url: function(phrase) {
            return "{{route('search')}}/" + phrase ;
        },
        categories: [
            {
                listLocation: "Projects",
                header: "<b>{{ __('PROJECTS') }}</b>"
            },
            {
                listLocation: "Tasks",
                header: "<b>{{ __('TASKS') }}</b>"
            },
            {
                listLocation: "Events",
                header: "<b>{{ __('EVENTS') }}</b>"
            },
            {
                listLocation: "Clients",
                header: "<b>{{ __('CLIENTS') }}</b>"
            },
            {
                listLocation: "Contacts",
                header: "<b>{{ __('CONTACTS') }}</b>"
            },
            {
                listLocation: "Leads",
                header: "<b>{{ __('LEADS') }}</b>"
            },
            {
                listLocation: "Invoices",
                header: "<b>{{ __('INVOICES') }}</b>"
            },
            // {
            //     listLocation: "Expenses",
            //     header: "<b>{{ __('EXPENSES') }}</b>"
            // }
        ],
        getValue: "text",
        requestDelay: 200,
        highlightPhrase: true,
        template: {
            type: "custom",
            method: function(value, item) {
                return '<a href="' + item.link + '"' + item.param + '>' + value + '</a>';
            }
        },
        list: {
            maxNumberOfElements: 100,
            match: {
                enabled: true
            }
        },
    };

    $('.search-element').each(function(){

        $(this).easyAutocomplete(options);
    });


    $.fn.select2.defaults.set( "theme", "bootstrap" );

    function toastrs(message, status) {
        $.notify({
                    message: message
                },{
                    type: status
                })
    };

    $("#notification-bell").click(function()
    {
        $(this).children('i').html('<i class="material-icons">notifications_none</i>');
    });


    $(function() {

        window.timerInstance = new easytimer.Timer();

        var id = {!! $timesheet ? $timesheet->id : 0 !!};
        var offset = {!! $timesheet&&$timesheet->isStarted() ? $timesheet->computeTime() : 0 !!};

        if(offset)
        {
            timer(window.timerInstance, offset, id);
        }
    });

</script>

@if ($message = Session::get('success'))
    <script>toastrs('{!! $message !!}', 'success')</script>
@endif

@if ($message = Session::get('error'))
    <script>toastrs('{!! $message !!}', 'danger')</script>
@endif

@if ($message = Session::get('info'))
    <script>toastrs('{!! $message !!}', 'info')</script>
@endif

@stack('scripts')
