@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('url' => 'projects', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Add New Project')}}
@endsection

@section('content')
<div class="tab-content">
    <h6>{{__('General Details')}}</h6>
    <div class="form-group row align-items-center required">
        {{ Form::label('name', __('Name'), array('class'=>'col-3')) }}
        {{ Form::text('name', $name, array('class' => 'form-control col', 'placeholder'=>__('Website Redesign'), 'required'=>'required')) }}
    </div>
    <div class="form-group row required">
        {{ Form::label('client_id', __('Client'), array('class'=>'col-3')) }}
        {!! Form::select('client_id', $clients, $client_id, array('class' => (Gate::check('create client')?'tags':'').' form-control col', 'required'=>'required', 'placeholder'=>'...', 'lang'=>\Auth::user()->locale)) !!}
    </div>
    <div class="form-group row">
        {{ Form::label('users', __('Assign'), array('class'=>'col-3')) }}
        {!! Form::select('users[]', $users, $user_id, array('class' => 'form-control col', 'multiple'=>'multiple', 'lang'=>\Auth::user()->locale)) !!}
    </div>
    <hr>
    <h6>{{__('Attach')}}</h6>
    <div class="form-group row">
        {{ Form::label('lead_id', __('Lead'), array('class'=>'col-3')) }}
        {!! Form::select('lead_id', $leads, $lead_id, array('class' => 'form-control col', 'placeholder' =>'...', 'lang'=>\Auth::user()->locale)) !!}
    </div>
    <hr>
    <h6>{{__('Timeline')}}</h6>
    <div class="form-group row align-items-center">
        {{ Form::label('start_date', __('Start Date'), array('class'=>'col-3')) }}
        {{ Form::date('start_date', null, array('class' => 'start form-control col', 'placeholder'=>'...',
                                            'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('due_date', __('Due Date'), array('class'=>'col-3')) }}
        {{ Form::date('due_date', null, array('class' => 'end form-control col', 'placeholder'=>'...',
                                            'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
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
