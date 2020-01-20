{{ Form::open(!empty($project_id) ? array('route' => array('projects.task.store',$project_id)) : array('route' => array('tasks.store'))) }}
<div class="modal-header">
    <h5 class="modal-title"></h5>
    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
    <i class="material-icons">close</i>
    </button>
</div>
<!--end of modal head-->
<div class="modal-body">
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
</div>
<!--end of modal body-->
<div class="modal-footer">
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}
