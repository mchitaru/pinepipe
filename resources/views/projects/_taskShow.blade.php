<div class="modal-header">
    <h5 class="modal-title"></h5>
    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
    <i class="material-icons">close</i>
    </button>
</div>
<div class="modal-body">
    <div class="p-2">
        <div class="row mb-4">
        <div class="col">
            <div class="row">
                <div class="col-md-4">
                    <div class="font-weight-bold lab-title">{{ __('Title')}} :</div>
                    <p class="mt-1 lab-val">{{$task->title}}</p>
                </div>
                <div class="col-md-4">
                    <div class="font-weight-bold lab-title">{{ __('Priority')}} :</div>
                    <p class="mt-1 lab-val">{{$task->priority}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="font-weight-bold lab-title">{{ __('Description')}} :</div>
                    <p class="mt-1 lab-val">{{$task->description}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="font-weight-bold lab-title">{{ __('Start Date')}} :</div>
                    <p class="mt-1 lab-val">{{$task->start_date}}</p>
                </div>
                <div class="col-md-4">
                    <div class="font-weight-bold lab-title">{{ __('Due Date')}} :</div>
                    <p class="mt-1 lab-val">{{$task->due_date}}</p>
                </div>
                <div class="col-md-4">
                    <div class="font-weight-bold lab-title">{{ __('Milestone')}} :</div>
                    <p class="mt-1 lab-val">{{!empty($task->milestone)?$task->milestone->title:''}}</p>
                </div>
            </div>
            <div class="row mt-10">
                <div class="col-md-12">
                    <div class="portlet-body">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            @can('create checklist')
                                @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show checklist',$perArr)))
                                    <li class="nav-item ">
                                        <a class="nav-link active" href="#tab_1_3" data-toggle="tab"> {{__('Checklist')}} </a>
                                    </li>
                                @endif
                            @endcan
                            <li class="nav-item">
                                <a class="nav-link" href="#tab_1_1" data-toggle="tab"> {{__('Comments')}} </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#tab_1_2" data-toggle="tab"> {{__('Files')}} </a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show" id="tab_1_1">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" id="form-comment" data-action="{{route('comment.store',[$task->project_id,$task->id])}}">
                                            <textarea class="form-control" name="comment" placeholder="{{ __('Write message')}}" id="example-textarea" rows="3" required></textarea>
                                            <div class="text-right mt-10">
                                                <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                                                    <button type="button" class="btn blue">{{ __('Submit')}}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="row">
                                    <ul class="col-md-12" id="comments">
                                        @foreach($task->comments as $comment)
                                            <li class="media">
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-1 font-weight-bold">{{$comment->user->name}}</h5>
                                                    {{$comment->comment}}
                                                    <div class="comment-trash" style="float: right">
                                                        <a href="#" class="btn btn-outline btn-sm red  delete-comment" data-url="{{route('comment.destroy',[$comment->id])}}"> <i class="fa fa-trash"></i></a>

                                                        </form>
                                                    </div>
                                                </div>
                                            </li>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="tab_1_2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" id="form-file" enctype="multipart/form-data" data-action="{{ route('comment.file.store',[$task->id]) }}">
                                            @csrf
                                            <input type="file" class="form-control mb-2" name="file" id="file">
                                            <span class="invalid-feedback" id="file-error" role="alert">
                                                <strong></strong>
                                            </span>
                                            <div class="text-right mt-10">
                                                <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                                                    <button type="submit" class="btn blue">{{ __('Upload')}}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <ul class="col-md-12" id="comments-file">
                                        @foreach($task->taskFiles as $file)
                                            <li class="media">
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-1 font-weight-bold"> {{$file->name}}</h5>
                                                    {{$file->file_size}}
                                                    <div class="comment-trash" style="float: right">
                                                        <a download href="{{asset('/storage/tasks/'.$file->file)}}" class="btn btn-outline btn-sm blue-madison">
                                                            <i class="fa fa-download"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-outline btn-sm red text-muted delete-comment-file" data-url="{{route('comment.file.destroy',[$file->id])}}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>

                                                    </div>
                                                </div>
                                            </li>
                                    @endforeach
                                </div>
                            </div>
                            @can('create checklist')
                                @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show checklist',$perArr)))
                                    <div class="tab-pane fade show active" id="tab_1_3">
                                        <div class="row">
                                            @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('create checklist',$perArr)))
                                                <div class="col-md-11">
                                                    <div class="row mb-10">
                                                        <div class="col-md-6">
                                                            <b>{{__('Progress')}}</b>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            <b>
                                                                <span class="progressbar-label custom-label" style="margin-top: -9px !important;margin-left: .7rem">
                                                                    0%
                                                                </span>
                                                            </b>
                                                        </div>
                                                    </div>
                                                    <div class="text-left">
                                                        <div class="custom-widget__item flex-fill">
                                                            <div class="custom-widget__progress d-flex  align-items-center">
                                                                <div class="progress" style="height: 5px;width: 100%;">
                                                                    <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" id="taskProgress"></div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="text-right mb-1">
                                                        <a href="#" class="btn btn-outline btn-sm blue-madison plus-btn" data-toggle="collapse" data-target="#form-checklist"><i class="fa fa-plus"></i></a>
                                                    </div>
                                                </div>
                                            @endif

                                            <form method="POST" id="form-checklist" class="collapse" data-action="{{ route('task.checklist.store',[$task->id]) }}">
                                                @csrf
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="container-fluid">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{__('Name')}}</label>
                                                                    <input type="text" name="name" class="form-control" required placeholder="{{__('Checklist Name')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="text-right container-fluid">
                                                        <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                                                            <button type="submit" class="btn blue">{{ __('Create')}}</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="row">
                                            <ul class="col-md-12" id="check-list">
                                                @foreach($task->taskCheckList as $checkList)
                                                    <li class="media">
                                                        <div class="media-body">
                                                            <h5 class="mt-0 mb-1 font-weight-bold"></h5>
                                                            <div class=" custom-control custom-checkbox checklist-checkbox">
                                                                @can('create checklist')
                                                                    @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('edit checklist',$perArr)))
                                                                        <input type="checkbox" id="checklist-{{$checkList->id}}" class="custom-control-input taskCheck" {{($checkList->status==1)?'checked':''}} value="{{$checkList->id}}" data-url="{{route('task.checklist.update',[$checkList->task_id,$checkList->id])}}">
                                                                        <label for="checklist-{{$checkList->id}}" class="custom-control-label"></label>
                                                                    @endif
                                                                @endcan
                                                                {{$checkList->name}}
                                                            </div>
                                                            <div class="comment-trash" style="float: right">
                                                                @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('delete checklist',$perArr)))
                                                                    <a href="#" class="btn btn-outline btn-sm red text-muted delete-checklist" data-url="{{route('task.checklist.destroy',[$checkList->task_id,$checkList->id])}}">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </li>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
<div class="modal-footer">
    <div class="text-right ">
        <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        </div>
    </div>
</div>


