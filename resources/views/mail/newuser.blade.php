@component('mail::message')
<p>Hello,</p><br>

<p>A new user has registered on <b>Pinepipe</b>:</p><br>

<p><b>Name:</b>   {{$name}}</p>
<p><b>Email:</b>  {{$email}}</p><br>

{{ config('app.name') }}
@endcomponent
