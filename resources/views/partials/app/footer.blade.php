<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

<!-- Required vendor scripts (Do not remove) -->

<!-- Optional Vendor Scripts (Remove the plugin script here and comment initializer script out of index.js if site does not use that feature) -->
<script type="text/javascript" src="{{ asset('assets/js/jquery.easy-autocomplete.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>

<!-- Autosize - resizes textarea inputs as user types -->
<script type="text/javascript" src="{{ asset('assets/js/autosize.min.js') }}"></script>
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
<script type="text/javascript" src="{{ asset('assets/js/theme.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/js/avatar.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/custom.js') }}"></script>

<script>
    var options = {
        url: function(phrase) {
            return "{{route('search.json')}}/" + phrase ;
        },
        categories: [
            {
                listLocation: "Projects",
                header: "{{ __('PROJECTS') }}"
            },
            {
                listLocation: "Tasks",
                header: "{{ __('TASKS') }}"
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
</script>

@if ($message = Session::get('success'))
    <script>toastrs('Success', '{!! $message !!}', 'success')</script>
@endif

@if ($message = Session::get('error'))
    <script>toastrs('Error', '{!! $message !!}', 'error')</script>
@endif

@if ($message = Session::get('info'))
    <script>toastrs('Info', '{!! $message !!}', 'info')</script>
@endif

@stack('scripts')
