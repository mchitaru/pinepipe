@extends('layouts.app')

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('page-title')
    {{__('Invoice Detail')}}
@endsection

@section('content')
<div class="container">
    <div class="row pt-5 justify-content-center">
        <div class="col-12 col-lg-9">
            <div class="card">
                @include('invoices.invoice')
            </div>
        </div>
    </div>
</div>
@endsection
