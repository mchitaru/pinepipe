@extends('layouts.modal')

@section('form-start')
{{ Form::open(array('url' => Request::url(), 'method' => 'DELETE')) }}
@endsection

@section('title') 
    Are you sure? 
@endsection

@section('content')
    <p>This action can not be undone. Do you want to continue?</p>
    @include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Yes'),array('class'=>'btn btn-primary btn-danger'))}}
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('No')}}</button>
@endsection

@section('form-end')
{{ Form::close() }}
@endsection
