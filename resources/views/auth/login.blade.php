@extends('layouts.auth')

@push('scripts')
<script>
    var HW_config = {
      selector: "#headway-widget",
      account:  "xazm27",
      embed: 'true',
      callbacks: {
    onWidgetReady: function(widget) {
        $("#headway-spinner").hide();
    }
  }
}
</script>
<script async src="https://cdn.headwayapp.co/widget.js"></script>
@endpush

@section('content')

    <div class="container pt-4">
        <div class="row justify-content-center">
        <div class="col-lg-6 col-md-12 mb-5">
            <div class="row justify-content-center" id="headway-spinner">
                @include('partials.spinner')
            </div>
            <div class="row justify-content-center" id="headway-widget">
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="text-center">
            <h1 class="h2">{{__('Welcome Back')}} &#x1f44b;</h1>
            <p class="lead">{{__('Log in to your account to continue')}}</p>
            {{Form::open(array('route'=>'login','method'=>'post','id'=>'loginForm','class'=> 'login-form' ))}}
            <div class="form-group">
                {{Form::text('email',null,array('class'=>'form-control form-control-solid placeholder-no-fix','placeholder'=>__('Email Address')))}}
            </div>
            <div class="form-group">
                {{Form::password('password',array('class'=>'form-control form-control-solid placeholder-no-fix','placeholder'=>__('Password')))}}
                @if (Route::has('password.request'))
                    <div class="text-right">
                        <small><a href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}</a>
                        </small>
                    </div>
                @endif
            </div>
            <div class="form-group mb-3">
                <div class="form-check">
                    <label class="rememberme check mt-checkbox mt-checkbox-outline" for="remember">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" value="true" {{ (!old() || old('remember') == 'true') ?  'checked' : '' }}>
                        {{ __('Keep me logged in') }}
                        <span></span>
                    </label>
                </div>

            </div>
            @include('partials.errors')
            <div class="form-group row mb-0">
                {{Form::submit(__('Login'),array('class'=>'btn btn-lg btn-block btn-primary','id'=>'saveBtn'))}}
            </div>
            <small>{{ __('Don\'t have an account yet?') }} <a href="{{ route('register') }}">
                {{ __('Create one') }}</a>
            </small>
            {{Form::close()}}
            </div>
        </div>
        </div>
    </div>

@endsection
