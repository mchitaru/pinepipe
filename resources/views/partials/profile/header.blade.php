
<div class="navbar navbar-expand-lg bg-dark navbar-dark sticky-top">
<a class="navbar-brand" href="{{ route('home') }}">
    <img alt="Pipeline" width=30 src="{{ asset('assets/img/logo.svg') }}" />
</a>

<div class="d-flex align-items-center">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
</div>
<div class="collapse navbar-collapse justify-content-between" id="navbar-collapse">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">{{__('Home')}}</a>
        </li>
    </ul>
</div>
</div>
