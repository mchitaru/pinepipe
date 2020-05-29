@extends('layouts.profile')

@php
use App\SubscriptionPlan;
@endphp

@push('stylesheets')
@endpush

@section('page-title')
    {{__('User')}}
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
