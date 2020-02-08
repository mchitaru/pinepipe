@extends('layouts.modal')

@section('form-start')
{{ Form::open(array('url' => Request::url(), 'method' => 'PATCH')) }}
@endsection

@section('title') 
    {{__('Are you sure?')}}
@endsection

@section('content')
    <p>{{__('You can always restore it in the future, but it will not appear in your dashboard by default. Do you want to continue?')}}</p>
    {!! Form::hidden('archived', 1) !!}
    @include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Yes'),array('class'=>'btn btn-primary btn-danger'))}}
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('No')}}</button>
@endsection

@section('form-end')
{{ Form::close() }}
@endsection
