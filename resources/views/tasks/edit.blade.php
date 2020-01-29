@extends('layouts.modal')

@section('form-start')
    {{ Form::model($task, array('route' => array('tasks.update', $task->id), 'method' => 'PUT', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Edit Task')}}
@endsection

@section('content')

<ul class="nav nav-tabs nav-fill" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="task-add-details-tab" data-toggle="tab" href="#task-add-details" role="tab" aria-controls="task-add-details" aria-selected="true">{{__('General Details')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="task-timeline-tab" data-toggle="tab" href="#task-timeline" role="tab" aria-controls="task-timeline" aria-selected="false">{{__('Timeline')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="task-visibility-tab" data-toggle="tab" href="#task-visibility" role="tab" aria-controls="task-visibility" aria-selected="false">{{__('Visibility')}}</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="task-add-details" role="tabpanel">
        <h6>{{__('General Details')}}</h6>
        <div class="form-group row align-items-center required">
            {{ Form::label('title', __('Title'), array('class'=>'col-3')) }}
            {{ Form::text('title', null, array('class' => 'form-control col', 'placeholder'=>'Task title', 'required'=>'required')) }}
        </div>
        <div class="form-group row">
            {{ Form::label('description', __('Description'), array('class'=>'col-3')) }}
            {!!Form::textarea('description', null, ['class'=>'form-control col','rows'=>'5', 'placeholder'=>'Task description']) !!}
        </div>
        <div class="form-group row align-items-center">
            {{ Form::label('priority', __('Priority'), array('class'=>'col-3')) }}
            {!! Form::select('priority', $priority, null,array('class' => 'form-control col','required'=>'required')) !!}
        </div>
        <div class="form-group row align-items-center">
            {{ Form::label('project_id', __('Project'), array('class'=>'col-3')) }}
            {!! Form::select('project_id', $projects, null, ['placeholder' => __('Select a project...')], array('class' => 'form-control col')) !!}
        </div>
        <div class="form-group row align-items-center">
            {{ Form::label('user_id', __('Assigned To'), array('class'=>'col-3')) }}
            {!! Form::select('user_id[]', $users, $user_id, array('class' => 'form-control col', 'multiple'=>'multiple')) !!}
        </div>
        <div class="form-group row align-items-center">
            {{ Form::label('milestone_id', __('Milestone'), array('class'=>'col-3')) }}
            {!! Form::select('milestone_id', $milestones, null,array('class' => 'form-control col')) !!}
        </div>
    </div>
    <div class="tab-pane fade show" id="task-timeline" role="tabpanel">
        <h6>{{__('Timeline')}}</h6>
        <div class="form-group row align-items-center">
            {{ Form::label('start_date', __('Start Date'), array('class'=>'col-3')) }}
            {{ Form::date('start_date', null, array('class' => 'form-control col','required'=>'required', 'placeholder'=>'Select Date', 
                                                    'data-flatpickr', 'data-default-date'=> $start_date, 'data-alt-input')) }}
        </div>
        <div class="form-group row align-items-center">
            {{ Form::label('due_date', __('Due Date'), array('class'=>'col-3')) }}
            {{ Form::date('due_date', null, array('class' => 'form-control col','required'=>'required', 'placeholder'=>'Select Date', 
                                                'data-flatpickr', 'data-default-date'=> $due_date, 'data-alt-input')) }}
        </div>
        <div class="alert alert-warning text-small" role="alert">
        <span>{{__('You can change due dates at any time')}}.</span>
        </div>
    </div>
    <div class="tab-pane fade show" id="task-visibility" role="tabpanel">
        <h6>{{__('Visibility')}}</h6>
        <div class="row">
        <div class="col">
            <div class="custom-control custom-radio">
            <input type="radio" id="visibility-everyone" name="visibility" class="custom-control-input" checked>
            <label class="custom-control-label" for="visibility-everyone">Everyone</label>
            </div>
        </div>
        <div class="col">
            <div class="custom-control custom-radio">
            <input type="radio" id="visibility-me" name="visibility" class="custom-control-input">
            <label class="custom-control-label" for="visibility-me">Just me</label>
            </div>
        </div>
        </div>    
    </div>    
</div>
@include('partials.errors')
@endsection

@section('footer')
{{Form::submit(__('Update'), array('class'=>'btn btn-primary', 'data-disable-with' => 'Saving...'))}}
@endsection

@section('form-end')
{{ Form::close() }}
@endsection
