<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.app.head')

<body>

    <div class="layout layout-nav-side">

    {{-- <div class="layout layout-nav-top"> --}}

        @include('partials.app.sidebar')

        <div class="main-container">

            @include('partials.app.content')

        </div>

    </div>

    <div id="commonModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modelCommanModelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content ">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelCommanModelLabel"></h4>

                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

    @include('partials.app.social')
    @include('partials.app.footer')

</body>

</html>
