{{ Form::model($selected, array('route' => array('projects.client.permission.store', $project_id,$client_id), 'method' => 'PUT')) }}
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
                @if(!empty($permissions))
                    <table class="table table-striped mb-0" id="dataTable-1">
                        <thead>
                        <tr>
                            <th>{{__('Module')}} </th>
                            <th>{{__('Permissions')}} </th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $modules=['milestone','task','checklist','activity','uploading','bug report','timesheet'];
                        @endphp
                        @foreach($modules as $module)
                            <tr>
                                <td>{{ ucfirst($module) }}</td>
                                <td>
                                    <div class="row cust-checkbox-row">
                                        @if(in_array('create '.$module,(array) $permissions))
                                            @if($key = array_search('create '.$module,$permissions))
                                                <div class="col-3 custom-control custom-checkbox custom-checkbox-switch">
                                                    {{Form::checkbox('permissions[]','create '.$module,in_array('create '.$module,(array) $selected), ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                                    {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('edit '.$module,(array) $permissions))
                                            @if($key = array_search('edit '.$module,$permissions))
                                                <div class="col-3 custom-control custom-checkbox custom-checkbox-switch">
                                                    {{Form::checkbox('permissions[]','edit '.$module,in_array('edit '.$module,(array) $selected), ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                                    {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('delete '.$module,(array) $permissions))
                                            @if($key = array_search('delete '.$module,$permissions))
                                                <div class="col-3 custom-control custom-checkbox custom-checkbox-switch">
                                                    {{Form::checkbox('permissions[]','delete '.$module,in_array('delete '.$module,(array) $selected), ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                                    {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('show '.$module,(array) $permissions))
                                            @if($key = array_search('show '.$module,$permissions))
                                                <div class="col-3 custom-control custom-checkbox custom-checkbox-switch">
                                                    {{Form::checkbox('permissions[]','show '.$module,in_array('show '.$module,(array) $selected), ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                                    {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if(in_array('move '.$module,(array) $permissions))
                                            @if($key = array_search('move '.$module,$permissions))
                                                <div class="col-3 custom-control custom-checkbox custom-checkbox-switch">
                                                    {{Form::checkbox('permissions[]','move '.$module,in_array('move '.$module,(array) $selected), ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                                    {{Form::label('permission'.$key,'Move',['class'=>'custom-control-label'])}}<br>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}
