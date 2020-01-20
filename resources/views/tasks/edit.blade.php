{{ Form::model($task, array('route' => array('tasks.update', $task->id), 'method' => 'PUT')) }}
<div class="modal-header">
    <h5 class="modal-title"></h5>
    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
    <i class="material-icons">close</i>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-6">
            {{ Form::label('title', __('Title')) }}
            {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('priority', __('Priority')) }}
            {!! Form::select('priority', $priority, null,array('class' => 'form-control','required'=>'required')) !!}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('start_date', __('Start Date')) }}
            {{ Form::date('start_date', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('due_date', __('Due Date')) }}
            {{ Form::date('due_date', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('project_id', __('Project'), array('class'=>'col-3')) }}
            {!! Form::select('project_id', $projects, null, array('class' => 'form-control col')) !!}
        </div>
        @if(\Auth::user()->type == 'company')
        <div class="form-group  col-md-6">
            {{ Form::label('assign_to', __('Assign To')) }}
            {!! Form::select('assign_to', $users, null,array('class' => 'form-control','required'=>'required')) !!}
        </div>
        @endif
        <div class="form-group  col-md-6">
            {{ Form::label('milestone_id', __('Milestone')) }}
            {!! Form::select('milestone_id', $milestones, null,array('class' => 'form-control')) !!}
        </div>
    </div>
    <div class="row">
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description')) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']) !!}
        </div>
    </div>
    @include('partials.errors')
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}
