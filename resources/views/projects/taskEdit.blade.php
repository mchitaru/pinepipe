{{ Form::model($task, array('route' => array('task.update', $task->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group  col-md-6">
        {{ Form::label('title', __('Title')) }}
        {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
        @error('title')
        <span class="invalid-title" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
    </span>
        @enderror
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('priority', __('Priority')) }}
        {!! Form::select('priority', $priority, null,array('class' => 'form-control','required'=>'required')) !!}
        @error('priority')
        <span class="invalid-priority" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('start_date', __('Start Date')) }}
        {{ Form::date('start_date', null, array('class' => 'form-control','required'=>'required')) }}
        @error('start_date')
        <span class="invalid-start_date" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
    </span>
        @enderror
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('due_date', __('Due Date')) }}
        {{ Form::date('due_date', null, array('class' => 'form-control','required'=>'required')) }}
        @error('due_date')
        <span class="invalid-due_date" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
    </span>
        @enderror
    </div>
    @if(\Auth::user()->type == 'company')
    <div class="form-group  col-md-6">
        {{ Form::label('assign_to', __('Assign To')) }}
        {!! Form::select('assign_to', $users, null,array('class' => 'form-control','required'=>'required')) !!}
        @error('assign_to')
        <span class="invalid-assign_to" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
        @enderror
    </div>
    @endif
    <div class="form-group  col-md-6">
        {{ Form::label('milestone_id', __('Milestone')) }}
        {!! Form::select('milestone_id', $milestones, null,array('class' => 'form-control')) !!}
        @error('milestone')
        <span class="invalid-milestone" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
        @error('description')
        <span class="invalid-description" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
    </span>
        @enderror
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn blue'))}}
</div>
{{ Form::close() }}
