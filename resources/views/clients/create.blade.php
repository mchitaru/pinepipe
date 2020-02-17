@extends('layouts.modal')

@section('form-start')
    {{Form::open(array('url'=>'clients','method'=>'post'))}}
@endsection

@section('title')
    {{__('Add New Client')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row align-items-center required">
        {{Form::label('name',__('Name'), array('class'=>'col-3')) }}
        {{Form::text('name',null,array('class'=>'form-control col','placeholder'=>__('Enter Client Name'),'required'=>'required'))}}
    </div>
    <div class="form-group row required">
        {{Form::label('email',__('Email'), array('class'=>'col-3'))}}
        {{Form::text('email',null,array('class'=>'form-control col','placeholder'=>__('Enter Client Email'),'required'=>'required'))}}
    </div>
    <div class="alert alert-warning text-small" role="alert">
        <span>{{__('No email will be sent to the client by adding them to your list.')}}</span>
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

