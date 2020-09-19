<head>    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ 'Pinepipe — Small Business Management, made simple' }}</title>

    <meta name="description" content="A complete Business Management platform, for freelancers and small businesses.">

    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-165597316-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-165597316-1');
    </script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-NH8QWLV');</script>
    <!-- End Google Tag Manager -->

    @if ($message = Session::get('checkout'))
        <!-- Event snippet for Purchase conversion page --> 
        <script>gtag('event', 'conversion', { 'send_to': 'AW-591630102/_DB-CMTt794BEJaejpoC', 'transaction_id':'' });</script>
        <!-- Facebook Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '384281792741509');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none" 
            src="https://www.facebook.com/tr?id=384281792741509&ev=PageView&noscript=1"/></noscript>
        <!-- End Facebook Pixel Code -->
    @endif

    @livewireStyles

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- App css (keep last) -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" media="all" />

    <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet" type="text/css" media="all" />

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @stack('stylesheets')
</head>
