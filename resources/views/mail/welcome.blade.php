@component('mail::message')
<p>{{__('Hi', [], $user->locale)}} {{$user->name}},</p><br>
<p>{{__('You just took your first step towards organizing your business process.', [], $user->locale)}}</p>
<p>{{__('To get started with Pinepipe, you can watch a short', [], $user->locale)}} <a href="https://youtu.be/{{__('Bab_HvQbT9I', [], $user->locale)}}">{{__('Demo Video', [], $user->locale)}}</a></p>

@component('mail::button', ['url' => route('home')])
{{__('Take me to my account', [], $user->locale)}}
@endcomponent

{{ config('app.name') }}
@endcomponent
