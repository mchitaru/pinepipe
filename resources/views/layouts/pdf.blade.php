<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.pdf.head')
<body>

    <div class="main-container">

        @include('partials.app.content')

    </div>

</body>

</html>
