@extends('layouts.modal')

@section('form-start')
    {{Form::model($plan, array('route' => array('plans.update', $plan->id), 'method' => 'PUT')) }}
@endsection

@section('title')
    {{__('Edit Plan')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row required">
        {{Form::label('name',__('Name'), array('class'=>'col-3'))}}
        {{Form::text('name',null,array('class'=>'form-control font-style','placeholder'=>__('Enter Plan Name'),'required'=>'required'))}}
    </div>
    <div class="form-group row required">
        {{Form::label('paddle_id',__('Paddle ID'), array('class'=>'col-3'))}}
        {{Form::text('paddle_id',null,array('class'=>'form-control col','required'=>'required','placeholder'=>__('Enter Paddle ID')))}}
    </div> 
    <div class="form-group row required">
        {{Form::label('price',__('Price'), array('class'=>'col-3'))}}
        {{Form::number('price',null,array('class'=>'form-control col','placeholder'=>__('Enter Plan Price'),'required'=>'required'))}}
    </div>
    <div class="row p-1 align-items-center">
        <div class="col-9">
        </div>
        <div class="form-group col custom-control custom-checkbox custom-checkbox-switch">
            <input type="hidden" name="deal" value="0">
            {{Form::checkbox('deal', 1, null, ['class'=>'custom-control-input', 'id' =>'deal'])}}
            {{Form::label('deal', __('Deal'), ['class'=>'custom-control-label'])}}
        </div>
    </div>
    <div class="form-group row">
        {{ Form::label('duration', __('Duration'), array('class'=>'col-3')) }}
        {{ Form::number('duration', null, array('class' => 'form-control col', 'placeholder'=>__('Number of Months'))) }}
    </div>
    <div class="form-group row">
        {{Form::label('max_users',__('Maximum Users'), array('class'=>'col-3'))}}
        {{Form::number('max_users',null,array('class'=>'form-control col'))}}
    </div>
    <div class="form-group row">
        {{Form::label('max_clients',__('Maximum Clients'), array('class'=>'col-3'))}}
        {{Form::number('max_clients',null,array('class'=>'form-control col'))}}
    </div>
    <div class="form-group row">
        {{Form::label('max_projects',__('Maximum Projects'), array('class'=>'col-3'))}}
        {{Form::number('max_projects',null,array('class'=>'form-control col'))}}
    </div>
    <div class="form-group row">
        {{Form::label('max_space',__('Maximum Disk Space (GP)'), array('class'=>'col-3'))}}
        {{Form::number('max_space',null,array('class'=>'form-control col'))}}
    </div>
    <div class="form-group row">
        {{ Form::label('description', __('Description'), array('class'=>'col-3')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control col','rows'=>'2']) !!}
    </div>
    <div class="alert alert-warning text-small" role="alert">
        <span>{{__('Leave the box empty for Unlimited (duration, users, clients, projects or space)')}}</span>
    </div>
</div>
@include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Update'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@endsection

@section('form-end')
{{ Form::close() }}
@endsection


