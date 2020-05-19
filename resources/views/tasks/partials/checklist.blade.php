@foreach($subtasks as $key=>$subtask)

<div class="row" data-id = "{{$subtask->id}}" tabindex="{{$key}}">
    <div class="form-group col">
    <span class="checklist-reorder">
        <i class="material-icons">reorder</i>
    </span>
    <div class="custom-control custom-checkbox col">
        <input type="checkbox" class="custom-control-input" name="status" id="checklist-{{$subtask->id}}" data-id="{{$task->id}}" {{($subtask->status==1)?'checked':''}} value="{{$subtask->id}}" data-url="{{route('tasks.subtask.update', [$task->id,$subtask->id])}}" data-remote="true" data-method="patch" data-type="text" data-disable="true">
        <label class="custom-control-label" for="checklist-{{$subtask->id}}"></label>
        <div class="col">
            <input class="col" type="text" name="title" id="title-{{$subtask->id}}" placeholder="{{__('Something to do...')}}" value="{{$subtask->title}}" data-filter-by="value" data-url="{{route('tasks.subtask.update', [$task->id,$subtask->id])}}" data-remote="true" data-method="patch" data-type="text" data-disable="true"/>
            <div class="checklist-strikethrough"></div>
        </div>
    </div>
    </div>
    <!--end of form group-->
</div>

@endforeach
