<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.app.head')

<body>
    <div class="main-container fullscreen">

        @include('partials.app.content')

    </div>

    @include('partials.app.footer')

</body>

</html>
