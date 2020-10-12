@component('mail::message')

<p>{{__('Hi')}},</p><br>

<p>{{__('You have been invited to collaborate on Pinepipe projects, by')}} {{$host->name}} ({{$host->email}}).</p><br>

<p>{{__('To get started, just click the link below and finish setting up your account.')}}</p>

@component('mail::button', ['url' => $url])
{{__('Accept invitation')}}
@endcomponent

<p>{{__('If you have any questions about Pinepipe, send us an')}} <a href="mailto:team@pinepipe.com">email</a> 
    {{__('or learn more on our website')}} <a href="https://pinepipe.com">pinepipe.com</a>.</p>

@endcomponent
