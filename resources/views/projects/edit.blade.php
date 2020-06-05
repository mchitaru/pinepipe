@extends('layouts.modal')

@section('form-start')
    {{ Form::model($project, array('route' => array('projects.update', $project->id), 'method' => 'PUT', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Edit Project')}}
@endsection

@section('content')

<ul class="nav nav-tabs nav-fill" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="project-add-details-tab" data-toggle="tab" href="#project-add-details" role="tab" aria-controls="project-add-details" aria-selected="true">{{__('Details')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="project-timeline-tab" data-toggle="tab" href="#project-timeline" role="tab" aria-controls="project-timeline" aria-selected="false">{{__('Timeline')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="project-visibility-tab" data-toggle="tab" href="#project-visibility" role="tab" aria-controls="project-visibility" aria-selected="false">{{__('Visibility')}}</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="project-add-details" role="tabpanel">
        <h6>{{__('General Details')}}</h6>
        <div class="form-group row align-items-center required">
            {{ Form::label('name', __('Name'), array('class'=>'col-4')) }}
            {{ Form::text('name', null, array('class' => 'form-control col', 'placeholder'=>__('Website Redesign'), 'required'=>'required')) }}
        </div>
        <div class="form-group row required">
            {{ Form::label('client_id', __('Client'), array('class'=>'col-4')) }}
            {!! Form::select('client_id', $clients, null,array('class' => (Gate::check('create client')?'tags':'').' form-control col','required'=>'required')) !!}
        </div>
        <div class="form-group row">
            {{ Form::label('description', __('Description'), array('class'=>'col-4')) }}
            {!!Form::textarea('description', null, ['class'=>'form-control col','rows'=>'5', 'placeholder'=>__('What this project is about')]) !!}
        </div>
        <div class="form-group row">
            {{ Form::label('users', __('Assign'), array('class'=>'col-4')) }}
            {!! Form::select('users[]', $users, $user_id, array('class' => 'form-control col', 'multiple'=>'multiple')) !!}
        </div>
        <div class="form-group row">
            {{ Form::label('price', __('Budget'), array('class'=>'col-4')) }}
            {{ Form::number('price', null, array('class' => 'form-control col', 'required'=>'required')) }}
        </div>
        <div class="form-group row">
            {{ Form::label('lead_id', __('Lead'), array('class'=>'col-4')) }}
            {!! Form::select('lead_id', $leads, null, array('class' => 'form-control col', 'placeholder' =>'...')) !!}
        </div>
    </div>
    <div class="tab-pane fade show" id="project-timeline" role="tabpanel">
        <h6>{{__('Timeline')}}</h6>
        <div class="form-group row align-items-center">
            {{ Form::label('start_date', __('Start Date'), array('class'=>'col-4')) }}
            {{ Form::date('start_date', '', array('class' => 'start form-control col', 'placeholder'=>'...',
                                                'data-flatpickr', 'data-default-date'=> $start_date, 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
        </div>
        <div class="form-group row align-items-center">
            {{ Form::label('due_date', __('Due Date'), array('class'=>'col-4')) }}
            {{ Form::date('due_date', '', array('class' => 'end form-control col', 'placeholder'=>'...',
                                                'data-flatpickr', 'data-default-date'=> $due_date, 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
        </div>
    </div>
    <div class="tab-pane fade show" id="project-visibility" role="tabpanel">
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
</div>
@include('partials.errors')
@endsection

@section('footer')
{{Form::submit(__('Update'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@endsection

@section('form-end')
{{ Form::close() }}
@endsection
