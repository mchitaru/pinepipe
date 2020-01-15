{{ Form::model($invoice, array('route' => array('invoices.products.store', $invoice->id), 'method' => 'POST')) }}
<div class="modal-header">
    <h5 class="modal-title"></h5>
    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
    <i class="material-icons">close</i>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <input type="text" class="form-control font-style" value="{{$invoice->project->name}}" readonly>
            </div>
        </div>
        <div class="col-md-4 mb-10">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="customRadio5" name="type" value="milestone" checked="checked" onclick="hide_show(this)">
                <label class="custom-control-label" for="customRadio5">{{__('Milestone & Task')}}</label>
            </div>
        </div>
        <div class="col-md-4 mb-10">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="customRadio6" name="type" value="other" onclick="hide_show(this)">
                <label class="custom-control-label" for="customRadio6">{{__('Other')}}</label>
            </div>
        </div>
    </div>
    <div id="milestone">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="milestone_id">{{__('Milestone')}}</label>
                    <select class="form-control font-style custom-select" onchange="getTask(this,{{$invoice->project_id}})" id="milestone_id" name="milestone_id">
                        <option value="" selected="selected">{{__('Select Milestone')}}</option>
                        @foreach($milestones as  $milestone)
                            <option value="{{$milestone->id}}">{{$milestone->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="task_id">{{__('Task')}}</label>
                    <select class="form-control font-style custom-select" id="task_id" name="task_id">
                        <option value="" selected="selected">{{__('Select Task')}}</option>
                        @foreach($tasks as  $task)
                            <option value="{{$task->id}}">{{$task->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div id="other" style="display: none">
        <div id="milestone">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">{{__('Title')}}</label>
                        <input type="text" class="form-control font-style" name="title">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="price">{{__('Price')}}</label>
                <input type="number" class="form-control font-style" name="price" required>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    @if(isset($invoice))
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Save'),array('class'=>'btn btn-primary'))}}
    @else
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
    @endif
</div>
{{ Form::close() }}

