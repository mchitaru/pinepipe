@extends('layouts.modal')

@section('form-start')
    {{ Form::model($event, array()) }}
@endsection

@section('title')
    {{__('View Event')}}
@endsection

@section('content')

<div class="tab-content">
    <h6>{{__('General Details')}}</h6>
    <div class="form-group row align-items-center">
        {{ Form::label('name', __('Title'), array('class'=>'col-3')) }}
        {{ Form::text('name', null, array('class' => 'form-control col', 'readonly')) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('users', __('Atendees'), array('class'=>'col-3')) }}
        {!! Form::select('users[]', $users, $user_id, array('class' => 'form-control col', 'multiple'=>'multiple', 'readonly', 'lang'=>\Auth::user()->locale)) !!}
    </div>
    <div class="form-group row">
        {{ Form::label('description', __('Description'), array('class'=>'col-3')) }}
        {!!Form::textarea('description', null, ['class'=>'form-control col', 'rows'=>'3', 'readonly']) !!}
    </div>
    <hr>
    <h6>{{__('Timeline')}}</h6>
    <div class="form-group row align-items-center">
        {{ Form::label('start', __('Start'), array('class'=>'col-3')) }}
        {{ Form::date('start', null, array('class' => 'start form-control col', 'disabled', 'placeholder'=>'...',
                                            'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-enable-time'=>'true', 'data-default-date'=> \Helpers::utcToLocal($event->start), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('end', __('End'), array('class'=>'col-3')) }}
        {{ Form::date('end', null, array('class' => 'end form-control col', 'disabled', 'placeholder'=>'...',
                                            'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-enable-time'=>'true', 'data-default-date'=> \Helpers::utcToLocal($event->end), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="row p-1 align-items-center">
        <div class="form-group col custom-control custom-checkbox custom-checkbox-switch d-flex justify-content-end">
            <input type="hidden" name="allday" value="0">
            {{Form::checkbox('allday', 1, null, ['class'=>'custom-control-input', 'disabled', 'id' =>'allday'])}}
            {{Form::label('allday', __('All day'), ['class'=>'custom-control-label'])}}
        </div>
    </div>
    <hr>
    <h6>{{__('Attach')}}</h6>
    <div class="form-group row align-items-center">
        {{ Form::label('lead_id', __('Lead'), array('class'=>'col-3')) }}
        {!! Form::select('lead_id', $leads, $lead_id, array('class' => 'form-control col font-style selectric', 'readonly', 'placeholder'=>'...', 'lang'=>\Auth::user()->locale)) !!}
    </div>
</div>
@include('partials.errors')
@endsection

@section('form-end')
{{ Form::close() }}
@endsection
