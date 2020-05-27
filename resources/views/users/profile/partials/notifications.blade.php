{{Form::model($user, array('url' => $url, 'method' => 'put'))}}
    <h6>{{__('Activity Notifications')}}</h6>
    <div class="form-group">
        <div class="custom-control custom-checkbox custom-checkbox-switch">
            <input type="hidden" name="notify_task_assign" value="0">
            {{Form::checkbox('notify_task_assign', 1, null, ['class'=>'custom-control-input', 'id' =>'notify_task_assign'])}}
            {{Form::label('notify_task_assign', __('Someone assigns me to a task'), ['class'=>'custom-control-label'])}}
        </div>
    </div>
    <div class="form-group">
        <div class="custom-control custom-checkbox custom-checkbox-switch">
            <input type="hidden" name="notify_project_assign" value="0">
            {{Form::checkbox('notify_project_assign', 1, null, ['class'=>'custom-control-input', 'id' =>'notify_project_assign'])}}
            {{Form::label('notify_project_assign', __('Someone assigns me to a project'), ['class'=>'custom-control-label'])}}
        </div>
    </div>
    <div class="form-group">
        <div class="custom-control custom-checkbox custom-checkbox-switch">
            <input type="hidden" name="notify_project_activity" value="0">
            {{Form::checkbox('notify_project_activity', 1, null, ['class'=>'custom-control-input', 'id' =>'notify_project_activity'])}}
            {{Form::label('notify_project_activity', __('Activity on a project I am a member of'), ['class'=>'custom-control-label'])}}
        </div>
    </div>
    <div class="form-group mb-md-4">
        <div class="custom-control custom-checkbox custom-checkbox-switch">
            <input type="hidden" name="notify_item_overdue" value="0">
            {{Form::checkbox('notify_item_overdue', 1, null, ['class'=>'custom-control-input', 'id' =>'notify_item_overdue'])}}
            {{Form::label('notify_item_overdue', __('My items (tasks, invoices ...) are overdue'), ['class'=>'custom-control-label'])}}
        </div>
    </div>
    <h6>{{__('Service Notifications')}}</h6>
    <div class="form-group">
        <div class="custom-control custom-checkbox custom-checkbox-switch">
            <input type="hidden" name="notify_newsletter" value="0">
            {{Form::checkbox('notify_newsletter', 1, null, ['class'=>'custom-control-input', 'id' =>'notify_newsletter'])}}
            {{Form::label('notify_newsletter', __('Monthly newsletter'), ['class'=>'custom-control-label'])}}
        </div>
    </div>
    <div class="form-group">
        <div class="custom-control custom-checkbox custom-checkbox-switch">
            <input type="hidden" name="notify_major_updates" value="0">
            {{Form::checkbox('notify_major_updates', 1, null, ['class'=>'custom-control-input', 'id' =>'notify_major_updates'])}}
            {{Form::label('notify_major_updates', __('Major feature enhancements'), ['class'=>'custom-control-label'])}}
        </div>
    </div>
    <div class="form-group">
        <div class="custom-control custom-checkbox custom-checkbox-switch">
            <input type="hidden" name="notify_minor_updates" value="0">
            {{Form::checkbox('notify_minor_updates', 1, null, ['class'=>'custom-control-input', 'id' =>'notify_minor_updates'])}}
            {{Form::label('notify_minor_updates', __('Minor updates and bug fixes'), ['class'=>'custom-control-label'])}}
        </div>
    </div>
    <div class="row justify-content-end">
        {{Form::submit('Save', array('class'=>'btn btn-primary'))}}
    </div>
{{Form::close()}}
