@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('route' => array('events.store'), 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Add New Event')}}
@endsection

@section('content')
    <div class="tab-content">
        <h6>{{__('General Details')}}</h6>
        <div class="form-group row align-items-center required">
            {{ Form::label('name', __('Title'), array('class'=>'col-3')) }}
            {{ Form::text('name', null, array('class' => 'form-control col', 'placeholder'=>__('Call with Client'), 'required'=>'required')) }}
        </div>
        <div class="form-group row  align-items-center required">
            {{ Form::label('users', __('Atendees'), array('class'=>'col-3')) }}
            {!! Form::select('users[]', $users, \Auth::user()->id, array('class' => 'form-control col', 'multiple'=>'multiple', 'required'=>'required', 'lang'=>\Auth::user()->locale)) !!}
        </div>
        <div class="form-group row">
            {{ Form::label('description', __('Description'), array('class'=>'col-3')) }}
            {!!Form::textarea('description', null, ['class'=>'form-control col','rows'=>'3', 'placeholder'=>__('Some notes about the call')]) !!}
        </div>
        <hr>
        <h6>{{__('Timeline')}}</h6>
        <div class="form-group row align-items-center">
            {{ Form::label('start', __('Start'), array('class'=>'col-3')) }}
            {{ Form::date('start', null, array('class' => 'start form-control col bg-white', 'required'=>'required', 'placeholder'=>'...',
                                                'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-enable-time'=>'true', 'data-default-date'=> \Helpers::utcToLocal($start), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
        </div>
        <div class="form-group row align-items-center">
            {{ Form::label('end', __('End'), array('class'=>'col-3')) }}
            {{ Form::date('end', null, array('class' => 'end form-control col bg-white','required'=>'required', 'placeholder'=>'...',
                                                'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-enable-time'=>'true', 'data-default-date'=> \Helpers::utcToLocal($end), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
        </div>
        <div class="row p-1 align-items-center">
            <div class="form-group col custom-control custom-checkbox custom-checkbox-switch d-flex justify-content-end">
                <input type="hidden" name="allday" value="0">
                {{Form::checkbox('allday', 1, 0, ['class'=>'custom-control-input', 'id' =>'allday'])}}
                {{Form::label('allday', __('All day'), ['class'=>'custom-control-label'])}}
            </div>
        </div>
        <hr>
        <h6>{{__('Attach')}}</h6>
        <div class="form-group row align-items-center">
            {{ Form::label('lead_id', __('Lead'), array('class'=>'col-3')) }}
            {!! Form::select('lead_id', $leads, $lead_id, array('class' => 'form-control col font-style selectric',
                            'placeholder'=>'...', 'lang'=>\Auth::user()->locale)) !!}
        </div>
        {{-- <hr>
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
        </div> --}}
    </div>
    @include('partials.errors')
@endsection

@section('footer')
{{Form::submit(__('Create'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@endsection

@section('form-end')
{{ Form::close() }}
@endsection
