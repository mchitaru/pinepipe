{{ Form::model($lead, array('route' => array('leads.update', $lead->id), 'method' => 'PUT')) }}
<div class="modal-header">
    <h5 class="modal-title"></h5>
    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
    <i class="material-icons">close</i>
    </button>
</div>
<div class="modal-body">
    <div class="row ">
        <div class="form-group col-md-6">
            {{ Form::label('name', __('Name')) }}
            {{ Form::text('name', null, array('class' => 'form-control font-style','required'=>'required')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('price', __('Price')) }}
            {{ Form::number('price', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('stage', __('Stage')) }}
            {{ Form::select('stage', $stages,null, array('class' => 'form-control font-style selectric','required'=>'required')) }}
        </div>
        @if(\Auth::user()->type=='company')
        <div class="form-group  col-md-6">
            {{ Form::label('owner', __('Lead User')) }}
            {!! Form::select('owner', $owners, null,array('class' => 'form-control font-style selectric','required'=>'required')) !!}
        </div>
        @endif

        <div class="form-group col-md-6">
            {{ Form::label('client', __('Client')) }}
            {!! Form::select('client', $clients, null,array('class' => 'form-control font-style selectric','required'=>'required')) !!}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('source', __('Source')) }}
            {!! Form::select('source', $sources, null,array('class' => 'form-control font-style selectric','required'=>'required')) !!}
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('notes', __('Notes')) }}
            {!! Form::textarea('notes', null,array('class' => 'form-control font-style','rows'=>'3')) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
