@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('route' => array('projects.timesheet.store', $project_id))) }}
@endsection

@section('title')
    {{__('Create Timesheet')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row required">
        {{ Form::label('hours', __('Hours'), array('class'=>'col-3')) }}
        {{ Form::number('hours', null, array('class' => 'form-control col', 'required'=>'required', 'placeholder'=>'Logged time')) }}
    </div>
    <div class="form-group row required">
        {{ Form::label('date', __('Date'), array('class'=>'col-3')) }}
        {{ Form::date('date', null, array('class' => 'form-control col','required'=>'required', 'placeholder'=>'Select Date',
                                        'data-flatpickr', 'data-default-date'=> date('Y-m-d'), 'data-alt-input')) }}
    </div>
    <div class="form-group row required">
        {{ Form::label('rate', __('Hourly Rate'), array('class'=>'col-3')) }}
        {{ Form::number('rate', '0', array('class' => 'form-control col', 'required'=>'required')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('task_id', __('Task'), array('class'=>'col-3')) }}
        {!! Form::select('task_id', $tasks, null,array('class' => 'form-control col', 'placeholder'=>'Select Task...')) !!}
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