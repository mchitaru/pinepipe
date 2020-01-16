{{ Form::open(array('url' => 'expenses','enctype' => "multipart/form-data")) }}
<div class="modal-header">
    <h5 class="modal-title"></h5>
    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
    <i class="material-icons">close</i>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-6">
            {{ Form::label('category_id', __('Category')) }}
            {{ Form::select('category_id', $category,null, array('class' => 'form-control font-style selectric','required'=>'required')) }}
            @error('category_id')
            <span class="invalid-category_id" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('amount', __('Amount')) }}
            {{ Form::number('amount', '', array('class' => 'form-control','required'=>'required')) }}
            @error('amount')
            <span class="invalid-amount" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
            @enderror
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('date', __('Date')) }}
            {{ Form::text('date', '', array('class' => 'form-control datepicker','required'=>'required')) }}
            @error('date')
            <span class="invalid-date" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
            @enderror
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('project_id', __('Project')) }}
            {{ Form::select('project_id', $projects,null, array('class' => 'form-control font-style selectric','required'=>'required')) }}
            @error('project_id')
            <span class="invalid-project_id" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('user_id', __('User')) }}
            {{ Form::select('user_id', $users,null, array('class' => 'form-control font-style selectric','required'=>'required')) }}
            @error('user_id')
            <span class="invalid-user_id" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('attachment', __('Attachment')) }}
            {{ Form::file('attachment', array('class' => 'form-control','accept'=>'.jpeg,.jpg,.png,.doc,.pdf')) }}
            @error('attachment')
            <span class="invalid-attachment" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
            @enderror
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description')) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
            @error('terms')
            <span class="invalid-terms" role="alert">
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
