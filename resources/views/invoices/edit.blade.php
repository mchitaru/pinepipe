{{ Form::model($invoice, array('route' => array('invoices.update', $invoice->id), 'method' => 'PUT')) }}
<div class="modal-header">
    <h5 class="modal-title"></h5>
    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
    <i class="material-icons">close</i>
    </button>
</div>
<div class="modal-body">
    <div class="row">
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
            {{ Form::label('issue_date', __('Issue Date')) }}
            {{ Form::text('issue_date', null, array('class' => 'form-control datepicker','required'=>'required')) }}
            @error('issue_date')
            <span class="invalid-issue_date" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
            @enderror
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('due_date', __('Due Date')) }}
            {{ Form::text('due_date', null, array('class' => 'form-control datepicker','required'=>'required')) }}
            @error('due_date')
            <span class="invalid-due_date" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
            @enderror
        </div>

        <div class="form-group  col-md-6">
            {{ Form::label('discount', __('Discount')) }}
            {{ Form::number('discount',null, array('class' => 'form-control','required'=>'required','min'=>"0")) }}
            @error('issue_date')
            <span class="invalid-issue_date" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
            @enderror
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('tax_id', __('Tax %')) }}
            {{ Form::select('tax_id', $taxes,null, array('class' => 'form-control font-style selectric')) }}
            @error('tax_id')
            <span class="invalid-tax_id" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('terms', __('Terms')) }}
            {!! Form::textarea('terms', null, ['class'=>'form-control font-style','rows'=>'2']) !!}
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
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}

