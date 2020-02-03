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
        {{ Form::label('name', __('Project Name'), array('class'=>'col-3')) }}
        {{ Form::text('name', null, array('class' => 'form-control col', 'placeholder'=>'Project name', 'required'=>'required')) }}
    </div>
    <div class="form-group row required">
        {{ Form::label('client_id', __('Client'), array('class'=>'col-3')) }}
        {!! Form::select('client_id', $clients, null,array('class' => 'form-control col','required'=>'required')) !!}
    </div>
    <div class="form-group row">
        {{ Form::label('description', __('Description'), array('class'=>'col-3')) }}
        {!!Form::textarea('description', null, ['class'=>'form-control col','rows'=>'5', 'placeholder'=>'Project description']) !!}
    </div>
    <div class="form-group row">
        {{ Form::label('user_id', __('Assigned to'), array('class'=>'col-3')) }}
        {!! Form::select('user_id[]', $users, null, array('class' => 'form-control col', 'multiple'=>'multiple')) !!}
    </div>
    <hr>
    <h6>{{__('Timeline')}}</h6>
    <div class="form-group row align-items-center">
        {{ Form::label('start_date', __('Start Date'), array('class'=>'col-3')) }}
        {{ Form::date('start_date', '', array('class' => 'form-control col','required'=>'required', 'placeholder'=>'Select Date', 
                                            'data-flatpickr', 'data-default-date'=> date('Y-m-d'), 'data-alt-input')) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('due_date', __('Due Date'), array('class'=>'col-3')) }}
        {{ Form::date('due_date', '', array('class' => 'form-control col','required'=>'required', 'placeholder'=>'Select Date', 
                                            'data-flatpickr', 'data-default-date'=> date('Y-m-d'), 'data-alt-input')) }}
    </div>
    <div class="alert alert-warning text-small" role="alert">
    <span>{{__('You can change due dates at any time.')}}</span>
    </div>
    <hr>
    <h6>{{__('Visibility')}}</h6>
    <div class="row">
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-everyone" name="visibility" class="custom-control-input" disabled="true" checked>
        <label class="custom-control-label" for="visibility-everyone">Everyone</label>
        </div>
    </div>
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-members" name="visibility" class="custom-control-input" disabled="true">
        <label class="custom-control-label" for="visibility-members">Members</label>
        </div>
    </div>
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-me" name="visibility" class="custom-control-input" disabled="true">
        <label class="custom-control-label" for="visibility-me">Just me</label>
        </div>
    </div>
    </div>
</div>
@include('partials.errors')
@endsection

@section('footer')
{{Form::submit(__('Create'), array('class'=>'btn btn-primary', 'data-disable-with' => 'Saving...'))}}
@endsection

@section('form-end')
{{ Form::close() }}
@endsection
