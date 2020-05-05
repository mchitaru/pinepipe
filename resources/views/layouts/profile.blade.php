<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.app.head')

<body>

    <div class="layout layout-nav-top">

        @include('partials.profile.header')

        <div class="main-container">

            @include('partials.app.content')

        </div>

    </div>

    @include('partials.app.footer')

</body>

</html>
