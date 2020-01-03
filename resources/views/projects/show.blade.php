@extends('layouts.admin')
@php
    $profile=asset(Storage::url('avatar/'));
@endphp
@push('css-page')
    <link href="{{ asset('assets/default/render/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/default/render/dropzone/basic.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/module/css/custom_project.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@push('script-page')
    <script src="{{ asset('assets/default/render/dropzone/dropzone.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/pages/scripts/form-dropzone.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/default/render/morris/morris.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/default/render/morris/raphael-min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/pages/scripts/dashboard.min.js') }}" type="text/javascript"></script>

    <script>

        var count = document.getElementById('testID').innerHTML.split(' ').length;
        var html = $("#testID").html();
        var remain = html.substring(count);
        if (count > 100) {
            html = html.substring(0, 500) + '<a id="read-more-btn"  onclick="read_more()" >Read more</a> <p id="read_more">' + remain + '</p>';

        }
        $("#testID").html(html);
        $('#read_more').hide();

        function read_more() {

            var x = document.getElementById("read_more");
            if (x.style.display === "none") {
                x.style.display = "block";
                $('#read-more-btn').css('display', 'none');
            } else {
                x.style.display = "none";
                $('#read-more-btn').css('display', 'block');
            }
        }
    </script>

@endpush

@section('page-title')
    {{__('Project Detail')}}
@endsection

@section('breadcrumb')
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="{{ route('dashboard') }}">{{__('Home')}}</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('projects.index') }}">{{__('Project')}}</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>{{$project->name}}</span>
        </li>

    </ul>
@endsection
@section('content')
    @php
        $permissions=$project->client_project_permission();
        $perArr=(!empty($permissions)? explode(',',$permissions->permissions):[]);
        $project_last_stage = ($project->project_last_stage($project->id))? $project->project_last_stage($project->id)->id:'';

        $total_task = $project->project_total_task($project->id);
        $completed_task=$project->project_complete_task($project->id,$project_last_stage);

         $percentage=0;
            if($total_task!=0){
                $percentage = intval(($completed_task / $total_task) * 100);
            }


        $label='';
        if($percentage<=15){
            $label='bg-danger';
        }else if ($percentage > 15 && $percentage <= 33) {
            $label='bg-warning';
        } else if ($percentage > 33 && $percentage <= 70) {
            $label='bg-primary';
        } else {
            $label='bg-success';
        }
    @endphp

    <div class="custom_project">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="row" style="padding: 1.5rem !important">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <h4 class="font-weight-bold uppercase"><b>{{$project->name}}</b>

                                </h4>

                                <span class="custom-widget__desc font-style">
                                    <div id="testID"> {{ $project->description }}</div>
                                </span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div align="right">
                                    @can('manage task')
                                        @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show task',$perArr)))
                                            <a href="{{ route('project.taskboard',$project->id) }}" class="btn btn btn-outline btn-md blue-madison" data-ajax-popup="true" data-title="{{__('Task Kanban')}}" data-toggle="tooltip" data-original-title="{{__('Task Kanban')}}">
                                                &nbsp;<i class="fa fa-tasks"></i>
                                            </a>
                                        @endif
                                    @endcan
                                    @can('edit project')
                                        <a href="#" class="btn btn-outline btn-md blue-madison" data-url="{{ route('projects.edit',$project->id) }}" data-ajax-popup="true" data-title="{{__('Edit Project')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('delete task')
                                        <a href="#" class="btn btn-outline btn-md red" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$project->id}}').submit();">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['projects.destroy', $project->id],'id'=>'delete-form-'.$project->id]) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                </div>
                                <div>
                                    <div class="row" style="padding: 1.5rem !important">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <div class="custom-widget__item">
                                                <span class="custom-widget__date">
                                                   {{__('Start Date')}}
                                                </span>
                                                <div class="custom-widget__label">
                                                    <span class="btn btn-label-brand btn-md btn-bold btn-upper">{{ \Auth::user()->dateFormat($project->start_date) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <div class="custom-widget__item">
                                                <span class="custom-widget__date">
                                                    {{__('Due Date')}}
                                                </span>
                                                <div class="custom-widget__label">
                                                    <span class="btn btn-label-danger btn-md btn-bold btn-upper">{{ \Auth::user()->dateFormat($project->due_date) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <div class="custom-widget__item">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span class="custom-widget__date">
                                                            <b>{{__('Progress')}}</b>
                                                </span>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <span class="custom-widget__stat">
                                                            <b>{{$percentage}}%</b>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="custom-widget__label mt-10">
                                                    <div class="custom-widget__progress d-flex  align-items-center">
                                                        <div class="progress" style="height: 5px;width: 100%;">
                                                            <div class="progress-bar custom-bg-success {{$label}}" role="progressbar" style="width: {{$percentage}}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-footer">
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2" style="display: flex">
                                <span style="float: left;font-size: 30px;padding:0px 5px"><i class="fa fa-bank"></i></span>
                                <span style="padding: 2px">
                                    <span style="padding: 2px;"><b style="font-size: 12px">{{__('Budget')}}</b></span><br>
                                    <span style="padding: 2px;"><b style="font-size: 15px;">{{ \Auth::user()->priceFormat($project->price) }}</b></span>
                                </span>
                                <span style="clear: both"></span>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2" style="display: flex">
                                <span style="float: left;font-size: 30px;padding:0px 5px"><i class="fa fa-star"></i></span>
                                <span style="padding: 2px">
                                    <span style="padding: 2px;"><b style="font-size: 12px">{{__('Expense')}}</b></span><br>
                                    <span style="padding: 2px;"><b style="font-size: 15px;">{{ \Auth::user()->priceFormat($project->project_expenses()) }}</b></span>
                                </span>
                                <span style="clear: both"></span>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2" style="display: flex">
                                <span style="float: left;font-size: 30px;padding:0px 5px"><i class="fa fa-file-text-o"></i></span>
                                <span style="padding: 2px">
                                    <span style="padding: 2px;"><b style="font-size: 12px"> {{__('Tasks')}}</b></span><br>
                                    <span style="padding: 2px;"><b style="font-size: 15px;">{{$project->countTask()}}</b></span>
                                </span>
                                <span style="clear: both"></span>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2" style="display: flex">
                                <span style="float: left;font-size: 30px;padding:0px 5px"><i class="fa fa-comment"></i></span>
                                <span style="padding: 2px">
                                    <span style="padding: 2px;"><b style="font-size: 12px"> {{__('Comments')}}</b></span><br>
                                    <span style="padding: 2px;"><b style="font-size: 15px;">{{$project->countTaskComments()}}</b></span>
                                </span>
                                <span style="clear: both"></span>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2" style="display: flex">
                                <span style="float: left;font-size: 30px;padding:0px 5px"><i class="fa fa-users"></i></span>
                                <span style="padding: 2px">
                                    <span style="padding: 2px;"><b style="font-size: 12px">{{__('Members')}}</b></span><br>
                                    <span style="padding: 2px;"><b style="font-size: 15px;">{{$project->project_user()->count()}}</b></span>
                                </span>
                                <span style="clear: both"></span>
                            </div>
                            @php
                                $datetime1 = new DateTime($project->due_date);
                                $datetime2 = new DateTime(date('Y-m-d'));
                                $interval = $datetime1->diff($datetime2);
                                $days = $interval->format('%a')
                            @endphp
                            <div class=" col-lg-2 col-md-2" style="display: flex">
                                <span style="float: left;font-size: 30px;padding:0px 5px"><i class="fa fa-calendar"></i></span>
                                <span style="padding: 2px">
                                    <span style="padding: 2px;"><b style="font-size: 12px">{{__('Days Left')}}</b></span><br>
                                    <span style="padding: 2px;"><b style="font-size: 15px;">{{$days}}</b></span>
                                </span>
                                <span style="clear: both"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="col-lg-8 col-md-8">
                    <div class="row">
                        <div class="todo-ui">
                            <div class="portlet light ">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption">
                                        <i class=" icon-social-twitter font-dark hide"></i>
                                        <span class="caption-subject font-dark bold">{{__('Staff')}}</span>
                                    </div>
                                    @can('invite user project')
                                        <div class="actions">
                                            <div class="btn-group">
                                                <a href="#" class="btn btn btn-outline btn-md blue-madison" data-url="{{ route('project.invite',$project->id) }}" data-ajax-popup="true" data-title="{{__('Add User')}}">
                                                    <i class="fa fa-users"></i> {{__('Add User')}}
                                                </a>
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_actions_pending">
                                            <div class="mt-actions">
                                                <div class="mt-action">
                                                    <div class="mt-action-body">
                                                        <div class="mt-action-row">
                                                            <div class="mt-action-info ">
                                                                <div class="mt-action-details ">
                                                                    <a href="#" class="milestone-detail font-style">{{(!empty($project->client())?$project->client()->name:'')}}</a>
                                                                    <p class="mt-action-desc">{{(!empty($project->client())?$project->client()->email:'')}}</p>
                                                                </div>
                                                            </div>
                                                            <div class="mt-action-info ">
                                                                <div class="mt-action-details ">
                                                                    <span class="mt-action-author"></span>
                                                                    <p class="mt-action-desc"></p>
                                                                </div>
                                                            </div>
                                                            <div class="mt-action-datetime ">
                                                                <span class="mt=action-time"><div class="label label-soft-primary">{{__('Client')}}</div></span>
                                                            </div>
                                                            <div class="mt-action-buttons ">
                                                                @can('client permission project')
                                                                    <a href="#" class="btn btn btn-outline btn-md blue-madison" data-url="{{ route('client.permission',[$project->id,$project->client]) }}" data-ajax-popup="true" data-title="{{__('Client Permission')}}" data-toggle="tooltip" data-original-title="{{__('Client Permission')}}">
                                                                        <i class="fa fa-lock"></i>
                                                                    </a>
                                                                @endcan
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        @foreach($project->project_user() as $user)
                                                            @php $totalTask= $project->user_project_total_task($user->project_id,$user->user_id) @endphp
                                                            @php $completeTask= $project->user_project_complete_task($user->project_id,$user->user_id,($project->project_last_stage())?$project->project_last_stage()->id:'' ) @endphp
                                                            <div class="mt-action-row mb-10">
                                                                <div class="mt-action-info user-info">
                                                                    <div class="mt-action-details ">
                                                                        <a href="#" class="milestone-detail font-style">{{$user->name}}</a>
                                                                        <p class="mt-action-desc">{{$user->email}}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="mt-action-info ">
                                                                    <div class="mt-action-details ">
                                                                        <span class="mt-action-author">{{$completeTask.'/'.$totalTask}}</span>
                                                                        <p class="mt-action-desc"></p>
                                                                    </div>
                                                                </div>
                                                                <div class="mt-action-datetime ">
                                                                    <span class="mt=action-time font-style"><div class="label label-soft-primary">{{$user->type}}</div></span>
                                                                </div>
                                                                <div class="mt-action-buttons ">

                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show milestone',$perArr)))
                        <div class="row">
                            <div class="todo-ui">
                                <div class="portlet light ">
                                    <div class="portlet-title tabbable-line">
                                        <div class="caption">
                                            <i class=" icon-social-twitter font-dark hide"></i>
                                            <span class="caption-subject font-dark bold">{{__('Milestones')}} ({{count($project->milestones)}}) </span>
                                        </div>
                                        @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('create milestone',$perArr)))
                                            <div class="actions">
                                                <div class="btn-group">
                                                    <a href="#" data-url="{{ route('project.milestone',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New Milestone')}}" class="btn btn-outline btn-sm blue-madison">
                                                        <i class="fa fa-plus"></i> {{__('Create Milestone')}}
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="portlet-body font-style">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_actions_pending">
                                                @foreach($project->milestones as $milestone)
                                                    <div class="mt-actions">
                                                        <div class="mt-action">
                                                            <div class="mt-action-body">
                                                                <div class="mt-action-row">
                                                                    <div class="mt-action-info mile-title">
                                                                        <div class="mt-action-details ">
                                                                            <a href="#" class="milestone-detail" data-ajax-popup="true" data-title="{{ __('Milestones Details') }}" data-url="{{route('project.milestone.show',[$milestone->id])}}">{{$milestone->title}}</a>
                                                                            <p class="mt-action-desc">{{$milestone->created_at}}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-action-info">
                                                                        <div class="mt-action-details ">
                                                                            <span class="mt-action-author">{{__('Milestone Cost')}}</span>
                                                                            <p class="mt-action-desc">$ {{number_format($milestone->cost)}}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-action-datetime ">
                                                                        @if($milestone->status == 'complete')
                                                                            <span class="mt=action-time"><div class="label label-soft-success">{{$milestone->status}}</div></span>
                                                                        @else
                                                                            <span class="mt=action-time"> <div class="label label-soft-warning">{{$milestone->status}}</div></span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="mt-action-buttons ">
                                                                        @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('edit milestone',$perArr)))
                                                                            <a href="#" data-url="{{ route('project.milestone.edit',$milestone->id) }}" data-ajax-popup="true" data-title="Edit Milestone" data-toggle="tooltip" data-original-title="{{__('Edit')}}" class="btn btn-outline btn-sm blue-madison">
                                                                                <i class="fa fa-pencil"></i>
                                                                            </a>
                                                                        @endif
                                                                        @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('delete milestone',$perArr)))
                                                                            <a href="#" class="btn btn-outline btn-sm red" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$milestone->id}}').submit();">
                                                                                <i class="fa fa-trash"></i>
                                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['project.milestone.destroy', $milestone->id],'id'=>'delete-form-'.$milestone->id]) !!}
                                                                                {!! Form::close() !!}
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif

                    @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show uploading',$perArr)))
                        <div class="row">
                            <div class="todo-ui">
                                <div class="portlet light ">
                                    <div class="portlet-title cust-portlet-title">
                                        <div class="caption" data-toggle="collapse" data-target=".todo-project-list-content">
                                            <span class="caption-subject font-black-sharp lab-title"> {{__('Files')}} </span>
                                        </div>
                                        <form action="#" class="dropzone dropzone-file-area" id="my-dropzone" style="">
                                            <h3 class="sbold">Drop files here or click to upload</h3>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @if(\Auth::user()->type  == 'company')
                    <div class="col-lg-4 col-md-4">
                        <div class="todo-content">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <span class="caption-subject font-black-sharp lab-title"> {{__('Project Status')}} </span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row">
                                        {{ Form::model($project, array('route' => array('projects.update.status', $project->id), 'method' => 'PUT')) }}
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                            <div class="form-group">
                                                <select class="bs-select form-control" name="status" id="status">
                                                    <option value="">Select Project Status</option>
                                                    @foreach($project_status as $key => $value)
                                                        <option value="{{ $key }}" {{ ($project->status == $key) ? 'selected' : '' }}>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                            <div class="form-group">
                                                {{Form::submit(__('Change Status'),array('class'=>'btn blue form-control'))}}
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show activity',$perArr)))
                    <div class="col-lg-4 col-md-4">
                        <div class="todo-content">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption" data-toggle="collapse" data-target=".todo-project-list-content">
                                        <span class="caption-subject font-black-sharp lab-title"> {{__('Activity')}} </span>
                                    </div>
                                </div>
                                <div class="container-fluid activity-body">
                                    @foreach($project->activities as $activity)
                                        <div class="portlet-body">
                                            <div class="row todo-row">
                                                <div class="todo-tasklist">
                                                    <div class="todo-tasklist-item todo-tasklist-item-border-blue">
                                                        <div class="todo-tasklist-item-title">{{$activity->log_type}}</div>
                                                        <div class="todo-tasklist-item-text"> {!! $activity->remark !!}</div>
                                                        <div class="todo-tasklist-controls pull-left">
                                                <span class="todo-tasklist-date">
                                                    <i class="fa fa-calendar"></i> {{date('d M Y H:i', strtotime($activity->created_at))}} </span>
                                                            <span class="todo-tasklist-badge badge badge-roundless"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>



@endsection
@push('script-page')
    <script>
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#my-dropzone", {
            maxFiles: 20,
            maxFilesize: 2,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{route('project.file.upload',[$project->id])}}",
            success: function (file, response) {
                if (response.is_success) {
                    dropzoneBtn(file, response);
                } else {
                    myDropzone.removeFile(file);
                    toastrs('Error', response.error, 'error');
                }
            },
            error: function (file, response) {
                myDropzone.removeFile(file);
                if (response.error) {
                    toastrs('Error', response.error, 'error');
                } else {
                    toastrs('Error', response.error, 'error');
                }
            }
        });
        myDropzone.on("sending", function (file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("project_id", {{$project->id}});
        });

        function dropzoneBtn(file, response) {
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "btn btn-outline btn-sm blue-madison cust-btn");
            download.setAttribute('data-toggle', "tooltip");
            download.setAttribute('data-original-title', "{{__('Download')}}");
            download.innerHTML = "<i class='fa fa-download'></i>";

            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "btn btn-outline btn-sm red cust-btn");
            del.setAttribute('data-toggle', "tooltip");
            del.setAttribute('data-original-title', "{{__('Delete')}}");
            del.innerHTML = "<i class='fa fa-trash'></i>";

            del.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm("Are you sure ?")) {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('href'),
                        data: {_token: $('meta[name="csrf-token"]').attr('content')},
                        type: 'DELETE',
                        success: function (response) {
                            if (response.is_success) {
                                btn.closest('.dz-image-preview').remove();
                            } else {
                                toastrs('Error', response.error, 'error');
                            }
                        },
                        error: function (response) {
                            response = response.responseJSON;
                            if (response.is_success) {
                                toastrs('Error', response.error, 'error');
                            } else {
                                toastrs('Error', response.error, 'error');
                            }
                        }
                    })
                }
            });

            var html = document.createElement('div');
            html.appendChild(download);
            html.appendChild(del);

            file.previewTemplate.appendChild(html);
        }

            @php
                $files = $project->files;

            @endphp
            @foreach($files as $file)
        var mockFile = {name: "{{$file->file_name}}", size: {{filesize(storage_path('app/public/project_files/'.$file->file_path))}} };
        myDropzone.emit("addedfile", mockFile);
        myDropzone.emit("thumbnail", mockFile, "{{asset('storage/project_files/'.$file->file_path)}}");
        myDropzone.emit("complete", mockFile);
        dropzoneBtn(mockFile, {download: "{{route('projects.file.download',[$project->id,$file->id])}}", delete: "{{route('projects.file.delete',[$project->id,$file->id])}}"});
        @endforeach
    </script>
@endpush


