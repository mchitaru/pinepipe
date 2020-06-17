@extends('layouts.modal')

@section('form-start')
    {{ Form::model($event, array('route' => array('events.update', $event->id), 'method' => 'PUT', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Edit Event')}}
@endsection

@section('content')

<div class="tab-content">
    <h6>{{__('General Details')}}</h6>
    <div class="form-group row align-items-center required">
        {{ Form::label('name', __('Title'), array('class'=>'col-3')) }}
        {{ Form::text('name', null, array('class' => 'form-control col', 'required'=>'required')) }}
    </div>
    <div class="form-group row required">
        {{ Form::label('users', __('Atendees'), array('class'=>'col-3')) }}
        {!! Form::select('users[]', $users, $user_id, array('class' => 'form-control col', 'multiple'=>'multiple', 'required'=>'required', 'lang'=>\Auth::user()->locale)) !!}
    </div>
    <div class="form-group row">
        {{ Form::label('description', __('Description'), array('class'=>'col-3')) }}
        {!!Form::textarea('description', null, ['class'=>'form-control col','rows'=>'3']) !!}
    </div>
    <hr>
    <h6>{{__('Timeline')}}</h6>
    <div class="form-group row align-items-center">
        {{ Form::label('start', __('Start'), array('class'=>'col-3')) }}
        {{ Form::date('start', null, array('class' => 'start form-control col', 'required'=>'required', 'placeholder'=>'...',
                                            'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-enable-time'=>'true', 'data-default-date'=> \Helpers::utcToLocal($event->start), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('end', __('End'), array('class'=>'col-3')) }}
        {{ Form::date('end', null, array('class' => 'end form-control col','required'=>'required', 'placeholder'=>'...',
                                            'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-enable-time'=>'true', 'data-default-date'=> \Helpers::utcToLocal($event->end), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="row p-1 align-items-center">
        <div class="col-9">
        </div>
        <div class="form-group col custom-control custom-checkbox custom-checkbox-switch">
            <input type="hidden" name="allday" value="1">
            {{Form::checkbox('allday', 1, null, ['class'=>'custom-control-input', 'id' =>'allday'])}}
            {{Form::label('allday', __('All day'), ['class'=>'custom-control-label'])}}
        </div>
    </div>
    <div class="alert alert-warning text-small" role="alert">
    <span>{{__('You can change due dates at any time')}}.</span>
    </div>
    <hr>
    <h6>{{__('Attach')}}</h6>
    <div class="form-group row align-items-center">
        {{ Form::label('lead_id', __('Lead'), array('class'=>'col-3')) }}
        {!! Form::select('lead_id', $leads, $lead_id, array('class' => 'form-control col font-style selectric', 'placeholder'=>'...', 'lang'=>\Auth::user()->locale)) !!}
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
        <input type="radio" id="visibility-me" name="visibility" class="custom-control-input" disabled="true">
        <label class="custom-control-label" for="visibility-me">{{__('Just me')}}</label>
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
