<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ 'BaseCRM.io — Online CRM and Project Management' }}</title>

    <meta name="description" content="A simple project management platform">
    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Gothic+A1" rel="stylesheet">

    <!-- Other stylesheets -->
    <link href="{{ asset('assets/css/easy-autocomplete.min.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link href="{{ asset('assets/css/easy-autocomplete.themes.min.css') }}" rel="stylesheet" type="text/css" media="all" />
    
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" media="all" />

    <!-- App css (keep last) -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" media="all" />

    <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet" type="text/css" media="all" />

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @stack('stylesheets')
</head>
