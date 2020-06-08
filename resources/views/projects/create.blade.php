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
        {{ Form::text('name', null, array('class' => 'form-control col', 'placeholder'=>__('Website Redesign'), 'required'=>'required')) }}
    </div>
    <div class="form-group row required">
        {{ Form::label('client_id', __('Client'), array('class'=>'col-3')) }}
        {!! Form::select('client_id', $clients, $client_id, array('class' => (Gate::check('create client')?'tags':'').' form-control col', 'required'=>'required', 'placeholder'=>'...')) !!}
    </div>
    <div class="form-group row">
        {{ Form::label('users', __('Assign'), array('class'=>'col-3')) }}
        {!! Form::select('users[]', $users, null, array('class' => 'form-control col', 'multiple'=>'multiple')) !!}
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
    <div class="alert alert-warning text-small" role="alert">
    <span>{{__('You can change due dates at a later time.')}}</span>
    </div>
    <hr>
    <h6>{{__('Visibility')}}</h6>
    <div class="row">
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-everyone" name="visibility" class="custom-control-input" disabled="true" checked>
        <label class="custom-control-label" for="visibility-everyone">{{__('Everyone')}}</label>
        </div>
    </div>
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-members" name="visibility" class="custom-control-input" disabled="true">
        <label class="custom-control-label" for="visibility-members">{{__('Members')}}</label>
        </div>
    </div>
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-me" name="visibility" class="custom-control-input" disabled="true">
        <label class="custom-control-label" for="visibility-me">{{__('Just me')}}</label>
        </div>
    </div>
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
