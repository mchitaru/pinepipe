@php
    $project_id= Request::segment(2)
@endphp
{{ Form::open(array('route' => array('task.timesheet.store',$project_id))) }}
<div class="modal-header">
    <h5 class="modal-title"></h5>
    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
    <i class="material-icons">close</i>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('task_id', __('Task')) }}
            {!! Form::select('task_id', $tasks, null,array('class' => 'form-control font-style selectric','required'=>'required')) !!}
            @error('task_id')
            <span class="invalid-task_id" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('date', __('Task Date')) }}
            {{ Form::text('date', '', array('class' => 'form-control datepicker','required'=>'required')) }}
            @error('date')
            <span class="invalid-date" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
            @enderror
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('hours', __('Task Hours')) }}
            {{ Form::number('hours', '', array('class' => 'form-control','required'=>'required')) }}
            @error('hours')
            <span class="invalid-hours" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
            @enderror
        </div>

    </div>
    <div class="row">
        <div class="form-group  col-md-12">
            {{ Form::label('remark', __('Remark')) }}
            {!! Form::textarea('remark', null, ['class'=>'form-control','rows'=>'2']) !!}
            @error('remark')
            <span class="invalid-remark" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
            @enderror
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}
