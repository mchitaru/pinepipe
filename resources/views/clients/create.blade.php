@extends('layouts.modal')

@section('form-start')
    {{Form::open(array('route' => array('clients.store'), 'data-remote' => 'true'))}}
@endsection

@section('title')
    {{__('Add New Client')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row align-items-center required">
        {{Form::label('name',__('Name'), array('class'=>'col-3')) }}
        {{Form::text('name',null,array('class'=>'form-control col','placeholder'=>__('Pinepipe'),'required'=>'required'))}}
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('email',__('Email'), array('class'=>'col-3'))}}
        {{Form::text('email',null,array('class'=>'form-control col','placeholder'=>__('team@pinepipe.com')))}}
    </div>
    <div class="alert alert-warning text-small" role="alert">
        <span>{{__('No email will be sent to the client by adding them to your list.')}}</span>
    </div>
    <div class="alert alert-warning text-small" role="alert">
        <span>{{__('You can fill in additional details later.')}}</span>
    </div>
</div>
@include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Create'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@endsection

@section('form-end')
{{ Form::close() }}
@endsection

