@php
use Carbon\Carbon;
$timesheet = $_timesheets->first();
@endphp

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-165597316-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-165597316-1');
</script>

<!-- WARNING!! DO NOT LEAVE LINE COMMENTS IN SCRIPTS!! -->

<script type="text/javascript" src="{{ asset('js/manifest.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/vendor.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/scripts.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('js/remote.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/avatar.js') }}"></script> --}}

<!-- Required vendor scripts (Do not remove) -->

<!-- Optional Vendor Scripts (Remove the plugin script here and comment initializer script out of index.js if site does not use that feature) -->
<script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('assets/js/pace.min.js') }}"></script> --}}
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-notify.min.js') }}"></script>

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
            }
        ],
        getValue: "text",
        template: {
            type: "links",
            fields: {
                link: "link"
            }
        },
        adjustWidth: false,
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

        var offset = {!! $timesheet&&$timesheet->isStarted() ? $timesheet->computeTime() : 0 !!};

        if(offset)
        {
            timer(window.timerInstance, offset);
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
