<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.app.head')

<body>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NH8QWLV"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    @if ($message = Session::get('checkout'))
        <!-- Event snippet for Purchase conversion page --> 
        <script>fbq('trackCustom', 'Purchase', {subscription: 'purchase'});</script>
    @endif

    <div class="layout layout-nav-side">

    {{-- <div class="layout layout-nav-top"> --}}

        {{-- @include('partials.app.headbar') --}}
        @include('partials.app.sidebar')

        <div class="main-container">

            @include('partials.app.header')
            @include('partials.app.content')

        </div>

    </div>

        {{-- @include('partials.app.social') --}}
    @include('partials.app.tawk')
    @include('partials.app.footer')

</body>

</html>
