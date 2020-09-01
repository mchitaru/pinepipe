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
            <h1 class="display-1 text-primary">419</h1>
            <h5> {{__('Because you were idle for some time, the page expired for security reasons.')}} </h5>
            <p>
                <a href="{{route('login')}}">{{__('Click here to go to the login page')}}</a>
             {{__('or ')}}
             <a href="{{ route('home')}}">{{__('Contact Us')}}</a>
              {{__('if you think this might be an error.')}}
            </p>
        </div>
        </div>
    </div>
</div>
@endsection
