@extends('layouts.auth')

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-7">
        <div class="text-center">
            <h1 class="display-1 text-primary">5&#x1f635;3</h1>
            <p> {{__('We are performing some maintenance on the platform. Please come back later!')}} </p>
            <p>
                <a href="{{route('home')}}">{{__('Click here to go home')}}</a>
             {{__('or ')}}
             <a href="{{ route('home')}}">{{__('Contact Us')}}</a>
              {{__('if you think this might be an error.')}}
            </p>
        </div>
        </div>
    </div>
</div>
@endsection
