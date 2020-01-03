{{ Form::open(array('route' => array('invite',$project_id))) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('user', __('User')) }}
        {!! Form::select('user[]', $employee, null,array('class' => 'form-control','required'=>'required')) !!}
        @error('client')
        <span class="invalid-user" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Add'),array('class'=>'btn blue'))}}
</div>
{{ Form::close() }}
