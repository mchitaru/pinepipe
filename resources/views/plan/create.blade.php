@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('url' => 'plans')) }}
@endsection

@section('title')
    {{__('Add New Plan')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row">
        {{Form::label('name',__('Name'), array('class'=>'col-3'))}}
        {{Form::text('name',null,array('class'=>'form-control col','placeholder'=>__('Enter Plan Name'),'required'=>'required'))}}
    </div>
    <div class="form-group row">
        {{Form::label('price',__('Price'), array('class'=>'col-3'))}}
        {{Form::number('price',null,array('class'=>'form-control col','placeholder'=>__('Enter Plan Price')))}}
    </div>
    <div class="form-group row">
        {{ Form::label('duration', __('Duration'), array('class'=>'col-3')) }}
        {!! Form::select('duration', $arrDuration, null,array('class' => 'form-control col','required'=>'required')) !!}
    </div>
    <div class="form-group row">
        {{Form::label('max_users',__('Maximum Users'), array('class'=>'col-3'))}}
        {{Form::number('max_users',null,array('class'=>'form-control col','required'=>'required'))}}
    </div>
    <div class="form-group row">
        {{Form::label('max_clients',__('Mabimum Clients'), array('class'=>'col-3'))}}
        {{Form::number('max_clients',null,array('class'=>'form-control col','required'=>'required'))}}
    </div>
    <div class="form-group row">
        {{Form::label('max_projects',__('Maximum Projects'), array('class'=>'col-3'))}}
        {{Form::number('max_projects',null,array('class'=>'form-control col','required'=>'required'))}}
    </div>
    <div class="form-group row">
        {{ Form::label('description', __('Description'), array('class'=>'col-3')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control col','rows'=>'2']) !!}
    </div>
    <div class="alert alert-warning text-small" role="alert">
        <span>{{__('Leave the box empty for Unlimited (users, clients or projects)')}}</span>
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


