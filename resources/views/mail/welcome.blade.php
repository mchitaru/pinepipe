@component('mail::message')
<p>{{__('Hi')}} {{$user->name}},</p><br>
<p>{{__('You just took your first step towards organizing your business process.')}}</p>
<p>{{__('To get started with Pinepipe, you can watch a short')}} <a href="https://youtu.be/{{__('Bab_HvQbT9I')}}">{{__('Demo Video')}}</a></p>

@component('mail::button', ['url' => route('home')])
{{__('Take me to my account')}}
@endcomponent

{{ config('app.name') }}
@endcomponent
