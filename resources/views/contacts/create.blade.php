@extends('layouts.modal')

@section('form-start')
    {{Form::open(array('url'=>'contacts', 'method'=>'post', 'data-remote' => 'true'))}}
@endsection

@section('title')
    {{__('Add New Contact')}}
@endsection

@section('content')
<div class="tab-content">
    <h6>{{__('General Details')}}</h6>
    <div class="form-group row align-items-center required">
        {{Form::label('name',__('Name'), array('class'=>'col-3')) }}
        {{Form::text('name',null,array('class'=>'form-control col','placeholder'=>__('John Smith'),'required'=>'required'))}}
    </div>
    <div class="form-group row">
        {{Form::label('email',__('Email'), array('class'=>'col-3'))}}
        {{Form::text('email',null,array('class'=>'form-control col','placeholder'=>__('john.smith@basecrm.io')))}}
    </div>
    <div class="form-group row">
        {{Form::label('phone',__('Phone Number'), array('class'=>'col-3'))}}
        {{Form::text('phone',null,array('class'=>'form-control col','placeholder'=>__('(800) 613-1303')))}}
    </div>
    <hr>
    <h6>{{__('Attach To')}}</h6>
    <div class="form-group row">
        {{ Form::label('client_id', __('Client'), array('class'=>'col-3')) }}
        {!! Form::select('client_id', $clients, null,array('class' => 'form-control col', 'placeholder'=>'Select Client')) !!}
    </div>
    <hr>
    <h6>{{__('Visibility')}}</h6>
    <div class="row">
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-everyone" name="visibility" class="custom-control-input" disabled="true" >
        <label class="custom-control-label" for="visibility-everyone">Everyone</label>
        </div>
    </div>
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-me" name="visibility" class="custom-control-input" disabled="true" checked>
        <label class="custom-control-label" for="visibility-me">Just me</label>
        </div>
    </div>
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

