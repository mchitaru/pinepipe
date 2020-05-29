<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.app.head')

<body>
    <div class="layout layout-nav-side">

    {{-- <div class="layout layout-nav-top"> --}}

        @include('partials.app.sidebar')
        {{-- @include('partials.app.header') --}}

        <div class="main-container">

            @include('partials.app.header')
            @include('partials.app.content')

        </div>

    </div>

    @include('partials.app.footer')

</body>

</html>
