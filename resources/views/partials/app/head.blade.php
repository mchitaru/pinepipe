<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ 'Pinepipe — Client Relationship Management, made simple' }}</title>

    <meta name="description" content="A complete Client Relationship Management platform, for freelancers and small businesses.">
    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- App css (keep last) -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" media="all" />

    <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet" type="text/css" media="all" />

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @stack('stylesheets')
</head>
