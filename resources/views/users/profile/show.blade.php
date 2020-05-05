@extends('layouts.app')

@push('stylesheets')
@endpush

@section('page-title')
    {{__('User')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Profile')}}</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="container-fluid">
        <div class="col">
            <div class="card">
            <div class="container row" style="min-height: 67px;">
                <div class="pl-2 pt-3 position-absolute">
                    <a href="#" data-toggle="tooltip" title={{$user->name}}>
                        {!!Helpers::buildUserAvatar($user, 60, 'rounded')!!}
                    </a>
                </div>
                <div class="card-body pl-5">
                    <div class="card-title m-0 col-xs-12 col-sm-3">
                        <h4 data-filter-by="text">{{$user->name}}</h4>
                    </div>
                    @if(!empty($user->email))
                        <div class="card-title m-0 col-xs-12 col-sm-5">
                            <div class="container row align-items-center">
                                <i class="material-icons">email</i>
                                <a href="mailto:kenny.tran@example.com">
                                    <span data-filter-by="text" class="text-small">
                                        {{$user->email}}
                                    </span>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="card-title col-xs-12 col-sm-5">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
