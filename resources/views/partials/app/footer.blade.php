<!-- WARNING!! DO NOT LEAVE LINE COMMENTS IN SCRIPTS!! -->

<script type="text/javascript" src="{{ asset('js/manifest.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/vendor.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/scripts.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('js/remote.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/avatar.js') }}"></script> --}}

<!-- Required vendor scripts (Do not remove) -->

<!-- Optional Vendor Scripts (Remove the plugin script here and comment initializer script out of index.js if site does not use that feature) -->
<script type="text/javascript" src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('assets/js/pace.min.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('assets/js/bootstrap-notify.min.js') }}"></script> --}}

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

    $("#search-element").easyAutocomplete(options);

    $.fn.select2.defaults.set( "theme", "bootstrap" ); 

    /* $.notify("Hello World"); */

    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };

    function toastrs(message, status) {
        toastr[status](message)
    };

    $("#notification-bell").click(function()
    {
        $(this).children('i').html('<i class="material-icons">notifications_none</i>');
    });


    $(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]', function () {
        var title = $(this).data('title');
        var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
        var url = $(this).data('url');
        $("#commonModal .modal-title").html(title);
        $("#commonModal .modal-dialog").addClass('modal-' + size);
        $.ajax({
            url: url,
            success: function (data) {
                $('#commonModal .modal-body').html(data);
                $("#commonModal").modal('show');
                common_bind("#commonModal");
                common_bind_select("#commonModal");
            },
            error: function (data) {
                data = data.responseJSON;
                toastrs('Error', data.error, 'error')
            }
        });

    });

</script>

@if ($message = Session::get('success'))
    <script>toastrs('{!! $message !!}', 'success')</script>
@endif

@if ($message = Session::get('error'))
    <script>toastrs('{!! $message !!}', 'error')</script>
@endif

@if ($message = Session::get('info'))
    <script>toastrs('{!! $message !!}', 'info')</script>
@endif

@stack('scripts')
