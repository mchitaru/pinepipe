@extends('layouts.profile')

@php
use App\SubscriptionPlan;
@endphp

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
    <div class="col-xl-8 col-lg-10">
        <div class="card">
        <div class="card-body">
                @include('users.profile.partials.notifications')
        </div>
        </div>
    </div>
    </div>
</div>
@endsection
