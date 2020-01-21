@extends('layouts.modal')

@section('form-start')
{{ Form::open(array('route' => (!empty($project_id) ? array('projects.task.store', $project_id) : array('tasks.store')), 'data-remote' => 'true')) }}
@endsection

@section('title')
{{__('Add New Task')}}
@endsection

@section('content')
    <div class="tab-content">
        <h6>{{__('General Details')}}</h6>
        <div class="form-group row align-items-center">
            {{ Form::label('title', __('Title'), array('class'=>'col-3')) }}
            {{ Form::text('title', '', array('class' => 'form-control col', 'placeholder'=>'Task title', 'required'=>'required')) }}
        </div>
        <div class="form-group row">
            {{ Form::label('description', __('Description'), array('class'=>'col-3')) }}
            {!!Form::textarea('description', null, ['class'=>'form-control col','rows'=>'3', 'placeholder'=>'Task description']) !!}
        </div>
        <div class="form-group row align-items-center">
            {{ Form::label('priority', __('Priority'), array('class'=>'col-3')) }}
            {!! Form::select('priority', $priority, null,array('class' => 'form-control col','required'=>'required')) !!}
        </div>
        @if(empty($project_id))
        <div class="form-group row align-items-center">
            {{ Form::label('project_id', __('Project'), array('class'=>'col-3')) }}
            {!! Form::select('project_id', $projects, null, array('class' => 'form-control col')) !!}
        </div>
        @endif
        @if(\Auth::user()->type == 'company')
        <div class="form-group row align-items-center">
            {{ Form::label('assign_to', __('Assigned To'), array('class'=>'col-3')) }}
            {!! Form::select('assign_to', $users, null,array('class' => 'form-control col','required'=>'required')) !!}
        </div>
        @endif
        <hr>
        <h6>{{__('Timeline')}}</h6>
        <div class="form-group row align-items-center">
            {{ Form::label('start_date', __('Start Date'), array('class'=>'col-3')) }}
            {{ Form::date('start_date', '', array('class' => 'form-control col','required'=>'required', 'placeholder'=>'Select Date', 'data-flatpickr', 'data-default-date'=> date('Y-m-d'), 'data-alt-input')) }}
        </div>
        <div class="form-group row align-items-center">
            {{ Form::label('due_date', __('Due Date'), array('class'=>'col-3')) }}
            {{ Form::date('due_date', '', array('class' => 'form-control col','required'=>'required', 'placeholder'=>'Select Date', 'data-flatpickr', 'data-default-date'=> date('Y-m-d'), 'data-alt-input')) }}
        </div>
        <div class="alert alert-warning text-small" role="alert">
        <span>{{__('You can change due dates at any time')}}.</span>
        </div>
    </div>
    @include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary', 'data-disable-with' => 'Saving...'))}}
@endsection

@section('form-end')
{{ Form::close() }}
@endsection
