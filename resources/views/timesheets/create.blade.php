@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('route' => array('timesheets.store'), 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Create Timesheet')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row">
        <div class="form-group col required">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text form-control">
                        {{__('HR')}}
                    </span>
                </div>
                {{ Form::number('hours', '0', array('class' => 'form-control col', 'required'=>'required','min'=>'0')) }}
            </div>        
        </div>
        <div class="form-group col required">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text form-control">
                        {{__('MIN')}}
                    </span>
                </div>
                {{ Form::number('minutes', '0', array('class' => 'form-control col', 'required'=>'required','min'=>'0')) }}
            </div>
        </div>
        <div class="form-group col required">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text form-control">
                        {{__('SEC')}}
                    </span>
                </div>
                {{ Form::number('seconds', '0', array('class' => 'form-control col', 'required'=>'required','min'=>'0')) }}
            </div>
        </div>
    </div>
    <div class="form-group row required">
        {{ Form::label('date', __('Date'), array('class'=>'col-3')) }}
        {{ Form::date('date', null, array('class' => 'form-control col','required'=>'required', 'placeholder'=>__('Select Date...'),
                                        'data-flatpickr', 'data-default-date'=> date('Y-m-d'), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('rate', __('Hourly Rate'), array('class'=>'col-3')) }}
        {{ Form::number('rate', '', array('class' => 'form-control col','min'=>'0',"step"=>"0.01", 'placeholder'=>\Auth::user()->priceFormat(50))) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('project_id', __('Project'), array('class'=>'col-3')) }}
        {!! Form::select('project_id', $projects, $project_id, array('class' => 'form-control col', 'placeholder'=>__('Select Project...'),
                        'data-refresh'=>route('timesheets.refresh','0'))) !!}
    </div>
    <div class="form-group row">
        {{ Form::label('task_id', __('Task'), array('class'=>'col-3')) }}
        {!! Form::select('task_id', $tasks, null,array('class' => array('class' => (Gate::check('create task')?'tags':'').' form-control col', 'placeholder'=>__('Select Task...'))) !!}
    </div>

    <div class="form-group row">
        {{ Form::label('remark', __('Remark'), array('class'=>'col-3')) }}
        {!! Form::textarea('remark', null, ['class'=>'form-control col','rows'=>'3']) !!}
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