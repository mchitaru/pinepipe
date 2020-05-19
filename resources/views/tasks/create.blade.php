@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('route' => array('tasks.store'), 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Add New Task')}}
@endsection

@section('content')
    <div class="tab-content">
        <h6>{{__('General Details')}}</h6>
        <div class="form-group row align-items-center required">
            {{ Form::label('title', __('Title'), array('class'=>'col-3')) }}
            {{ Form::text('title', null, array('class' => 'form-control col', 'placeholder'=>'Prepare Client Proposal', 'required'=>'required')) }}
        </div>
        <div class="form-group row">
            {{ Form::label('description', __('Description'), array('class'=>'col-3')) }}
            {!!Form::textarea('description', null, ['class'=>'form-control col','rows'=>'5', 'placeholder'=>'The proposal must contain the negociated discount.']) !!}
        </div>
        <div class="form-group row align-items-center">
            {{ Form::label('priority', __('Priority'), array('class'=>'col-3')) }}
            {!! Form::select('priority', $priorities, 1, array('class' => 'form-control col','required'=>'required')) !!}
        </div>
        <div class="form-group row align-items-center">
            {{ Form::label('project_id', __('Project'), array('class'=>'col-3')) }}
            {!! Form::select('project_id', $projects, $project_id, array('class' => 'form-control col', 'placeholder'=>'Select Project...',
                            'data-refresh'=>route('tasks.refresh','0'))) !!}
        </div>
        @if(\Auth::user()->type == 'company')
        <div class="form-group row align-items-center">
            {{ Form::label('users', __('Assign'), array('class'=>'col-3')) }}
            {!! Form::select('users[]', $users, $user_id, array('class' => 'form-control col', 'multiple'=>'multiple')) !!}
        </div>
        @else
        <div class="form-group row align-items-center required">
            {{ Form::label('users', __('Assign'), array('class'=>'col-3')) }}
            {!! Form::select('users[]', $users, $user_id, array('class' => 'form-control col', 'required'=>'true', 'multiple'=>'multiple')) !!}
        </div>
        @endif
        <div class="form-group row align-items-center">
            {{ Form::label('tags', __('Labels'), array('class'=>'col-3')) }}
            {!! Form::select('tags[]', $tags, null, array('class' => 'tags form-control col', 'multiple'=>'multiple')) !!}
        </div>
        <hr>
        <h6>{{__('Timeline')}}</h6>
        <div class="form-group row align-items-center">
            {{ Form::label('due_date', __('Due Date'), array('class'=>'col-3')) }}
            {{ Form::date('due_date', null, array('class' => 'form-control col', 'placeholder'=>'Select Date', 
                                                'data-flatpickr', 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
        </div>
        <div class="alert alert-warning text-small" role="alert">
        <span>{{__('You can change due dates at any time')}}.</span>
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
            <input type="radio" id="visibility-me" name="visibility" class="custom-control-input" disabled="true">
            <label class="custom-control-label" for="visibility-me">Just me</label>
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
