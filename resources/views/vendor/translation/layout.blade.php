<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<link rel="stylesheet" href="/vendor/translation/css/main.css">

@include('partials.app.head')

<body>
    <div class="layout layout-nav-side">

    {{-- <div class="layout layout-nav-top"> --}}
    
        @include('partials.app.sidebar')
        {{-- @include('partials.app.header') --}}

        <div class="main-container">

        <div id="app">
            
            @include('translation::nav')
            @include('translation::notifications')
            
            @yield('body')
            
        </div>
    </div>

</div>

@include('partials.app.footer')
<script src="/vendor/translation/js/app.js"></script>

</body>

</html>
