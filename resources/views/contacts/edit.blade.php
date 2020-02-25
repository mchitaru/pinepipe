@extends('layouts.modal')

@section('form-start')
    {{Form::model($contact, array('route' => array('contacts.update', $contact->id), 'method' => 'PUT', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Edit Contact')}}
@endsection

@section('content')
<div class="tab-content">
    <h6>{{__('General Details')}}</h6>
    <div class="form-group row align-items-center required">
        {{Form::label('name',__('Name'), array('class'=>'col-3')) }}
        {{Form::text('name',null,array('class'=>'form-control col','placeholder'=>__('Enter Contact Name'),'required'=>'required'))}}
    </div>
    <div class="form-group row">
        {{Form::label('email',__('Email'), array('class'=>'col-3'))}}
        {{Form::text('email',null,array('class'=>'form-control col','placeholder'=>__('Enter Contact Email')))}}
    </div>
    <div class="form-group row">
        {{Form::label('phone',__('Phone Number'), array('class'=>'col-3'))}}
        {{Form::text('phone',null,array('class'=>'form-control col','placeholder'=>__('Enter Contact Phone Number')))}}
    </div>
    <div class="form-group row">
        {{ Form::label('address', __('Address'), array('class'=>'col-3')) }}
        {!!Form::textarea('address', null, ['class'=>'form-control col','rows'=>'2', 'placeholder'=>'Enter Contact Address']) !!}
    </div>
    <div class="form-group row">
        {{Form::label('website',__('Website'), array('class'=>'col-3'))}}
        {{Form::text('website',null,array('class'=>'form-control col','placeholder'=>__('Enter Contact Website')))}}
    </div>
    <div class="form-group row">
        {{Form::label('job',__('Job Title'), array('class'=>'col-3'))}}
        {{Form::text('job',null,array('class'=>'form-control col','placeholder'=>__('Enter Contact Job Title')))}}
    </div>
    <div class="form-group row">
        {{ Form::label('birthday', __('Birthday'), array('class'=>'col-3')) }}
        {{ Form::date('birthday', '', array('class' => 'form-control col','required'=>'required', 'placeholder'=>'Select Date', 
                                            'data-flatpickr', 'data-default-date'=> date('Y-m-d'), 'data-alt-input')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('notes', __('Notes'), array('class'=>'col-3')) }}
        {!!Form::textarea('notes', null, ['class'=>'form-control col','rows'=>'2']) !!}
    </div>
    @if(\Auth::user()->type=='company')
        <div class="form-group row">
            {{ Form::label('user_id', __('Owner'), array('class'=>'col-3')) }}
            {!! Form::select('user_id', $owners, null,array('class' => 'form-control col font-style selectric','required'=>'required')) !!}
        </div>
    @endif
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
</div>
@include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Update'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@endsection

@section('form-end')
    {{ Form::close() }}
@endsection

